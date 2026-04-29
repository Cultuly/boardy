# 1. Ответ по запросу без авторизации:
[!](screenshots/01-no-token.png)
## Ответ на вопрос:
    Bearer это тип схемы заголовка (анотация типа схемы) Authorization в формате: 
```http
Authorization: Bearer <token>
```
**Почему не просто токен? :**
Потому что HTTP по стандарту ожидает получить и тип схемы и сами данные.

```http
Authorization: <schema> <credentials>
```

# 2. Запрос на /api/me.php с кукой
[!](screenshots/02-me-php.png)
## Ответ на вопрос:
    me.php использует session_start() вместо логина и пароля, потому что пользователь уже залогинился через форму логина или oauth аутентификацию и его личность сохранена в серверной сессии а клиент идентифицируется кукой PHPSESSID
**Какую роль играет кука? : Без куки сервер просто не поймёт чью сессию открывать**

# 3. React получает JWT
[!](screenshots/03-console-jwt.png)

# 4. Authorization: Bearer в fetch
[!](screenshots/04-bearer-header.png)

# 5. Комментарий создан, автор указан правильный
[!](screenshots/05-comment-created.png)

# 6. Декодирование JWT токена на jwt.io
[!](screenshots/06-jwt-io.png)
## Ответ на вопрос:
    payload закодирован в формате Base64URL, но не зашифрован.

    Получив JWT токен злоумышленник получит доступ ко всем указанным полям в JWT токене.

    Это не проблема так как JWT токен и не используется для сокрытия данных, он используется для подтверждения подлинности этих данных. 
    
    Плюс если трафик идёт по HTTPS, то перехватить токен на лету не получится, практически невозможно, но даже если получится, у JWT токена обычно небольшое время жизни.

# 7. 401 - Token Expired
[!](screenshots/07-expired.png)

# 8. Невалидный токен
[!](screenshots/08-invalid.png)

# 9. Github oauth app
[!](screenshots/09-github-app.png)

# 10. Добавление столбца github_id в таблицу users
[!](screenshots/10-describe.png)

# 11. Кнопка «Войти через GitHub»
[!](screenshots/11-login-button.png)

# 12. Oauth flow
[!](screenshots/12-github-authorize.png)
