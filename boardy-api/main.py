import os
from fastapi import FastAPI, Request
from datetime import datetime
import aiomysql, json, asyncio
import redis.asyncio as aioredis 
from routers import comments, ws
from fastapi.middleware.cors import CORSMiddleware
from routers.ws import manager
from contextlib import asynccontextmanager 
from database import get_db, db_query, db_query_one, db_insert, db_execute


async def redis_subscriber():
    REDIS_HOST = os.getenv("REDIS_HOST", "redis")
    REDIS_PORT = os.getenv("REDIS_PORT", "6379")
    redis = await aioredis.from_url(f"redis://{REDIS_HOST}:{REDIS_PORT}")
    pubsub = redis.pubsub()
    await pubsub.subscribe('new_post', 'post_updated', 'post_deleted', 'user.renamed')
    print("✅ Redis subscribed to channels")

    async for message in pubsub.listen():
        if message['type'] != 'message':
            continue
        print(f"📡 Redis message received: {message['channel']}")
        channel = message['channel'].decode()
        data = json.loads(message['data'])
        
        if channel == 'new_post':
            print(f"⚡️ Broadcasting new_post")
            await manager.broadcast({'type': 'new_post', 'post': data})
        elif channel == 'user.renamed':
            await db_execute(
                'UPDATE comments SET author_name=%s WHERE author_id=%s',
                data['new_name'], data['id']
            )
            await manager.broadcast({
                'type': 'user_renamed',
                'user_id': data['id'],
                'new_name': data['new_name']
            })
        elif channel == 'post_updated':
            print(f"📝 Broadcasting post_updated")
            await manager.broadcast({'type': 'post_updated', 'post': data})
        elif channel == 'post_deleted':
            print(f"🗑 Broadcasting post_deleted")
            await manager.broadcast({'type': 'post_deleted', 'post_id': data['id']})

@asynccontextmanager
async def lifespan(app: FastAPI):
    task = asyncio.create_task(redis_subscriber())
    try:
        yield
    finally:
        task.cancel()
        try:
            await task
        except asyncio.CancelledError:
            pass

app = FastAPI(title='Boardy API', version='0.2.0', lifespan=lifespan)

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(comments.router)
app.include_router(ws.router)

@app.get('/api/health')
def health():
    return {"ok": True}

@app.get('/api/status')
async def status():
	return {'status': 'ok', 'time': str(datetime.now())}
