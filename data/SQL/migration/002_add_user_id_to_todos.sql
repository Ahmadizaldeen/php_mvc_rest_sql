USE phpd_mvc_rest;

ALTER TABLE todos ADD COLUMN user_id INT UNSIGNED NOT NULL;

ALTER TABLE todos
ADD CONSTRAINT fk_todos_user
FOREIGN KEY (user_id) REFERENCES users(id);