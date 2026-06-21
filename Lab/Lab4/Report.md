# 1) Директория проекта
![alt text](screenshots/01-directory.png)

# 2) Конфиг виртуального хоста
![alt text](screenshots/02-vhost-config.png)
**server_name** указывает имя домена, по которому nginx выбирает соответствующий виртуальный сервер для обработки запроса.

**root** задает корневую директорию на файловой системе, относительно которой располагаются файлы сайта.

**access_log** определяет путь к файлу, в который записывается лог всех обработанных клиентских запросов.

**error_log** задает путь к файлу, куда сохраняются сообщения об ошибках и предупреждениях работы сервера.

**try_files** последовательно проверяет существование указанных файлов или директорий и возвращает первый найденный ресурс или код ответа.

**error_page** настраивает отображение пользовательских страниц при возникновении указанных кодов ошибок HTTP.

# 3) Лендинг
![alt text](screenshots/03-landing.png)

# 4) Форма обратной связи
![alt text](screenshots/04-form.png)

# 5) Стили и 404
![alt text](screenshots/05-404.png)

# 6) DNS-запись для поддомена
![alt text](screenshots/06-dns-api.png)

# 7) Проверка DNS
![alt text](screenshots/07-dig-api.png)

# 8) Конфиг и заглушка API
![alt text](screenshots/08-api-config.png)

# 9)
![alt text](screenshots/09-api-browser.png)

# 10) GET-запрос через curl -v
![alt text](screenshots/10-curl-v.png)
**Стартовая строка** GET / HTTP/1.1
**Заголовок** Host: boardy-api.cultuly.ai-info.ru
**Стартовая строка ответа** HTTP/1.1 200 OK
**Content-Type** text/html
**Content-Length** 68

# 11) Виртуальные хосты в действии
![alt text](screenshots/11-vhosts.png)
Веб-сервер nginx получает запрос на один IP-адрес, но смотрит на значение заголовка, сравнивает значение Host с директивами server_name в своей конфигурации и выбирает соответствующий блок, третий запрос вернул default_server, потому что не нашёл указаного Host, у меня это boardy-api,
т.к. я его указал таким

# 12) POST-запрос
![alt text](screenshots/12-post-405.png)
POST /feedback.html HTTP/1.1
Content-Type application/x-www-form-urlencoded
тело запроса name=Ivanov&message=Hello
Потому что Nginx не обрабатывает данные, поэтому выдаёт 405

GET возвращает заголовки и тело ответа, а HEAD только заголовки.
HEAD можно использовать для проверки на существования страницы,получение метаданных, проверка ссылок

# 13) Раздельные логи
![alt text](screenshots/13-logs.png)

# 14) Фильтрация логов
![alt text](screenshots/14-log-stats.png)

# 15) Скриншот Pull Request
![alt text](screenshots/15-pull-request.png)
