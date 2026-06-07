-- Создание двух баз при первой инициализации MySQL-контейнера
CREATE DATABASE IF NOT EXISTS boardy_main
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS boardy_api
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Права пользователю boardy на обе базы
GRANT ALL ON boardy_main.* TO 'boardy'@'%';
GRANT ALL ON boardy_api.*  TO 'boardy'@'%';
FLUSH PRIVILEGES;

USE boardy_api;
CREATE TABLE IF NOT EXISTS comments (
  id          bigint NOT NULL AUTO_INCREMENT,
  post_id     bigint NOT NULL,
  author_id   bigint NOT NULL,
  author_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  body        text COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at  timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_post_id (post_id),
  KEY idx_author_id (author_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
