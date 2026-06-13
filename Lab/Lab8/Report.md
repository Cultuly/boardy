## Установка MySQL
![alt text](screenshots/01-mysql-status.png)

## База данных и пользователь
![alt text](screenshots/02-db-charset.png)
utf8mb4 поддерживает все символы Unicode, включая эмодзи, также это надмножество utf8, поэтому при переходе данные не потеряются
utf8mb4_unicode_ci-наиболее точная сортировка 

## phpMyAdmin
![alt text](screenshots/03-phpmyadmin.png)

## Три таблицы
![alt text](screenshots/04-tables-cli.png)

![alt text](screenshots/05-tables-pma.png)
FOREIGN KEY - это связь между таблицами, которая обеспечивает целостность данных
ON DELETE CASCADE - это автоматическое удаление зависимых записей при удалении главной
Мы используем InnoDB т.к. он поддерживает FOREIGN KEY и является современным стандартом

## SQL - скрипт
![alt text](screenshots/06-schema-sql.png)

## INSERT
![alt text](screenshots/07-data-cli.png)

![alt text](screenshots/08-data-pma.png)

## SELECT + JOIN
![alt text](screenshots/09-join.png)
зачем JOIN? Как получить имя автора без него?

## Foreign Key — защита целостности
![alt text](screenshots/10-fk-error.png)

## CASCADE
![alt text](screenshots/11-cascade.png)

## SQL-инъекция
![alt text](screenshots/12-injection.png)
SQL-инъекция работает путем внедрения вредоносного SQL-кода в пользовательский ввод, который база данных ошибочно выполняет как часть запроса.
Prepared Statements защищают, разделяя логику запроса и данные: база данных сначала компилирует шаблон, а затем подставляет пользовательский ввод строго как значения, делая код неисполнимым.

## PHP + MySQL
![alt text](screenshots/13-db-php.png)

## submit.php через MySQL
![alt text](screenshots/14-submit.png)

![alt text](screenshots/15-submit-pma.png)

## messages.php через MySQL
![alt text](screenshots/16-messages.png)

## FastAPI + MySQL
![alt text](screenshots/17-api-messages.png)


![alt text](screenshots/18-api-users.png)
aiomysql-это асинхронный драйвер, специально разработанный для работы с asyncio. Он позволяет выполнять неблокирующие операции с базой данных, используя await.
Event loop блокируется, во время выполнения запроса к БД event loop останавливается и не может обрабатывать другие задачи,следовательно будет сильная потеря производительности

## Pull Request
![alt text](screenshots/19-pull-request.png)
