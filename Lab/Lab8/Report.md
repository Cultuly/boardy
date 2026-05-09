## Установка MySQL
![alt text](screenshots/01-mysql-status.png)

## База данных и пользователь
![alt text](screenshots/02-db-charset.png)
В utf8mb4 поддерживаются все символы Unicode, также это своего рода настройка над utf8, поэтому при переходе данные не потеряются
utf8mb4_unicode_ci- самая подходящая нам сортировка 

## phpMyAdmin
![alt text](screenshots/03-phpmyadmin.png)

## Три таблицы
![alt text](screenshots/04-tables-cli.png)

![alt text](screenshots/05-tables-pma.png)
FOREIGN KEY - это указатель на связь между разыми таблицами, которая обеспечивает целостность данных
ON DELETE CASCADE - автоматически удалит все зависимые от удалённой записи
Используем InnoDB т.к. он поддерживает FOREIGN KEY и является современным стандартом (до него был MyISAM)

## SQL - скрипт
![alt text](screenshots/06-schema-sql.png)

## INSERT
![alt text](screenshots/07-data-cli.png)

![alt text](screenshots/08-data-pma.png)

## SELECT + JOIN
![alt text](screenshots/09-join.png)

## Foreign Key — защита целостности
![alt text](screenshots/10-fk-error.png)

## CASCADE
![alt text](screenshots/11-cascade.png)

## SQL-инъекция
![alt text](screenshots/12-injection.png)
SQL-инъекция возникает, когда злоумышленник вставляет вредоносный SQL-код в пользовательский ввод, а база данных воспринимает его как часть обычного запроса и выполняет. Prepared Statements защищают от этого, потому что запрос и пользовательские данные обрабатываются отдельно. Сначала база данных подготавливает сам SQL-запрос, а затем подставляет введённые данные только как обычные значения, а не как исполняемый код.

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
aiomysql — это асинхронный драйвер для работы с MySQL через asyncio. Он позволяет отправлять запросы к базе данных с помощью await, не останавливая выполнение остальных задач
Если использовать обычный (синхронный) драйвер, то во время выполнения запроса event loop будет блокироваться. Пока база данных отвечает, цикл событий не сможет обрабатывать другие задачи, из-за чего производительность приложения может сильно снизится

## Pull Request
![alt text](screenshots/19-pull-request.png)
