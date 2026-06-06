import os, functools
import jwt
from fastapi import Header, HTTPException

@functools.lru_cache
def _public_key() -> str:
    path = os.environ.get("OAUTH_PUBLIC_KEY", "oauth-public.key")
    with open(path) as f:
        return f.read()

async def get_current_user(authorization: str = Header(None)):
    if not authorization or not authorization.startswith('Bearer '):
        raise HTTPException(401, detail='Token required')
    token = authorization.split(' ')[1]
    try:
        payload = jwt.decode(
            token, _public_key(),
            algorithms=['RS256'],
            options={'verify_aud': False}
        )
        return payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(401, detail='Token expired')
    except jwt.InvalidTokenError as e:
        raise HTTPException(401, detail=f'Invalid token: {e}')