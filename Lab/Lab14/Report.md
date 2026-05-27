# 1-2. Установка PKCE и SPA-клиент
![](screenshots/01-passport-install.png)
![](screenshots/02-spa-client.png)

## Пояснение

В Laravel Passport был установлен OAuth 2.1 сервер и создан публичный SPA-клиент для React-приложения с использованием Authorization Code Flow + PKCE.

## Ответ на вопросы

### Почему публичный клиент без secret?

SPA-приложение выполняется в браузере пользователя, и весь JavaScript-код доступен конечному пользователю. Если поместить `client_secret` в frontend — его можно легко извлечь через DevTools. Поэтому SPA использует публичный OAuth-клиент без секрета.

### Чем PKCE заменяет client_secret и от какой атаки защищает?

PKCE использует связку:

- `code_verifier` — случайная строка
- `code_challenge = SHA-256(code_verifier)`

Клиент отправляет `code_challenge` при авторизации, а затем `code_verifier` при обмене кода на токен.

Сервер проверяет соответствие `code_verifier` и `code_challenge`.

Это защищает от **authorization code interception attack** — даже если злоумышленник перехватит `code`, он не сможет обменять его на токен без `code_verifier`.

---

# 3. Token TTL
![](screenshots/03-token-ttl.png)

## Пояснение

В `AuthServiceProvider` настроены:

- access_token — 15 минут
- refresh_token — 30 дней

## Ответ на вопросы

### Почему access короткий, а refresh длинный?

Access token используется для каждого API-запроса, поэтому его компрометация критична. Он живёт недолго, чтобы минимизировать ущерб.

Refresh token используется только для обновления access token и хранится в HttpOnly cookie, поэтому его можно делать долгоживущим.

### Что будет, если access token будет жить 24 часа?

В случае кражи токена злоумышленник сможет пользоваться API целые сутки, что резко увеличивает риск атаки.

---

# 4. PKCE OAuth flow
![](screenshots/04-pkce-curl.png)

## Ответ на вопросы

OAuth flow включает:

1. генерацию `code_verifier`
2. вычисление `code_challenge`
3. редирект на `/oauth/authorize`
4. аутентификацию пользователя
5. получение `code`
6. обмен `code → access_token`
7. проверку `code_verifier` на сервере
8. выдачу `access_token + refresh_token`

---

# 5-6. Базы данных
![](screenshots/05-databases.png)
![](screenshots/06-comments-schema.png)

## Ответ на вопросы

### Почему нет FK между comments и users/posts?

Потому что comments находятся в отдельной базе `boardy_api`, принадлежащей FastAPI-сервису. Между микросервисами не используют внешние ключи.

### Как обеспечивается целостность?

Целостность реализуется через:
- денормализацию (`author_name`)
- события Redis (`user.renamed`)
- eventual consistency

---

# 7. FastAPI подключение к новой БД
![](screenshots/07-fastapi-db.png)

FastAPI переключён на `boardy_api`, что обеспечивает изоляцию микросервиса комментариев.

---

# 8-9. RS256
![](screenshots/08-rs256-success.png)
![](screenshots/09-rs256-fail.png)

## Ответ на вопросы

RS256 использует асимметричную криптографию:

- Laravel Passport подписывает токены приватным ключом
- FastAPI проверяет публичным ключом

Это безопаснее HS256, потому что сервисы не знают общий секрет и не могут выпускать токены.

---

# 9. CORS настройка
![](screenshots/12-cors-config.png)

## Ответ на вопросы

### Почему нельзя allow_origins="*"? + credentials=true?

Браузер блокирует такие запросы из соображений безопасности. При credentials=true обязательно указывать конкретный origin.

---

# 10. Полный CRUD
![](screenshots/10-crud-all.png)

## Ответ на вопросы

### Почему author_name передаётся в payload?

Это бизнес-данные конкретного комментария, а не данные аутентификации. JWT должен оставаться минимальным.

### Что если добавить author_name в JWT?

- токены станут неактуальными при смене имени
- потребуется перевыпуск токенов
- увеличится размер JWT

---

# 11. Owner check:
![](screenshots/11-owner-check.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 12. CORS:
![](screenshots/12-cors-config.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 13. PKCE Utils:
![](screenshots/13-pkce-utils.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 14-15. Login flow:
![](screenshots/14-login-redirect.png)
![](screenshots/15-login-callback.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 16. Code exchange:
![](screenshots/16-token-exchange.pn)

## Пояснение:

## Ответ на вопросы:
** **:

# 17. Refresh token в HttpOnly Cookie:
![](screenshots/17-refresh-cookie.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 18. Silent Refresh:
![](screenshots/18-silent-refresh.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 19. Redis:
![](screenshots/19-redis-ping.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 20. Laravel publish:
![](screenshots/20-laravel-publish.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 21-22. FasAPI Subscriber:
![](screenshots/21-subscriber-running.png)
![](screenshots/22-broadcast-flow.png)
## Пояснение:

## Ответ на вопросы:
** **:

# 23. User renamed:
![](screenshots/23-user-renamed.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 24-25. Денормализация имени:
![](screenshots/24-denorm-before.png)
![](screenshots/25-denorm-after.png)
## Пояснение:

## Ответ на вопросы:
** **:

# 26. Посты в realtime:
![](screenshots/26-two-browsers-post.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 27. Комментарии в realtime:
![](screenshots/27-two-browsers-comment.png)

## Пояснение:

## Ответ на вопросы:
** **:

# 28-29. Никаких HTTP-вызовов:
![](screenshots/28-no-http-callback.png)
![](screenshots/29-nginx-no-internal.png)
## Пояснение:

## Ответ на вопросы:
** **:
