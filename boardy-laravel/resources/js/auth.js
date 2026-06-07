import { generateVerifier, generateChallenge, generateState } from './pkce.js';

const CLIENT_ID = document.querySelector('meta[name="oauth-client-id"]')?.content;
const REDIRECT_URI = document.querySelector('meta[name="oauth-redirect-uri"]')?.content
    || (window.location.origin + '/oauth/callback');

export async function startLogin() {
    const verifier = generateVerifier();
    const challenge = await generateChallenge(verifier);
    const state = generateState();

    sessionStorage.setItem('pkce_verifier', verifier);
    sessionStorage.setItem('oauth_state', state);

    const params = new URLSearchParams({
        client_id: CLIENT_ID,
        response_type: 'code',
        redirect_uri: REDIRECT_URI,
        code_challenge: challenge,
        code_challenge_method: 'S256',
        state: state,
        scope: '*',
    });

    window.location = '/oauth/authorize?' + params.toString();
}

export async function handleCallback() {
    const params = new URLSearchParams(window.location.search);
    const code = params.get('code');
    const state = params.get('state');

    if (!code) return null;

    const savedState = sessionStorage.getItem('oauth_state');
    if (state !== savedState) {
        throw new Error('Invalid state — возможна CSRF атака');
    }

    const verifier = sessionStorage.getItem('pkce_verifier');
    if (!verifier) throw new Error('Отсутствует code_verifier');

    const res = await fetch('/oauth/token', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        credentials: 'include',
        body: new URLSearchParams({
            grant_type: 'authorization_code',
            client_id: CLIENT_ID,
            code,
            code_verifier: verifier,
            redirect_uri: REDIRECT_URI,
        }),
    });

    if (!res.ok) throw new Error('Ошибка обмена кода на токен');

    const data = await res.json();
    sessionStorage.removeItem('pkce_verifier');
    sessionStorage.removeItem('oauth_state');

    window.history.replaceState({}, document.title, window.location.pathname);

    return data.access_token;
}

export async function refreshToken() {
    const res = await fetch('/oauth/token', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            grant_type: 'refresh_token',
            client_id: CLIENT_ID,
        }),
    });

    if (!res.ok) {
        startLogin();
        return null;
    }

    const data = await res.json();
    return data.access_token;
}

document.addEventListener('DOMContentLoaded', () => {
    handleCallback().then(token => {
        if (token) {
            sessionStorage.setItem('access_token', token);
            document.getElementById('oauth-login-box')?.style.setProperty('display', 'none');
        }
    }).catch(err => console.error('OAuth callback error:', err));

    if (!sessionStorage.getItem('access_token')) {
        document.getElementById('oauth-login-box')?.style.setProperty('display', 'block');
    }

    document.getElementById('login-btn')?.addEventListener('click', () => {
        startLogin();
    });
});