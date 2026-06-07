import os
import aiomysql


DB_CONFIG = {
    'host': os.getenv('DB_HOST', 'mysql'),
    'port': int(os.getenv('DB_PORT', '3306')),
    'user': os.getenv('DB_USER', 'boardy'),
    'password': os.getenv('DB_PASSWORD', 'boardy-password'),
    'db': os.getenv('DB_DATABASE', 'boardy_api'),
    'charset': 'utf8mb4',
}

async def get_db():
    conn = await aiomysql.connect(**DB_CONFIG)
    try:
        async with conn.cursor(aiomysql.DictCursor) as cur:
            yield cur
    finally:
        conn.close()

async def db_query(query, *args):
    async with aiomysql.connect(**DB_CONFIG) as conn:
        async with conn.cursor(aiomysql.DictCursor) as cur:
            await cur.execute(query, args)
            return await cur.fetchall()

async def db_query_one(query, *args):
    res = await db_query(query, *args)
    return res[0] if res else None

async def db_insert(query, *args):
    async with aiomysql.connect(**DB_CONFIG) as conn:
        async with conn.cursor() as cur:
            await cur.execute(query, args)
            await conn.commit()
            return cur.lastrowid

async def db_execute(query, *args):
    async with aiomysql.connect(**DB_CONFIG) as conn:
        async with conn.cursor() as cur:
            await cur.execute(query, args)
            await conn.commit()
