CREATE TABLE users(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) UNIQUE NOT NULL,
email VARCHAR(100) UNIQUE NOT NULL,
password CHAR(60) NOT NULL,
permissions INT(3) NOT NULL
);

CREATE TABLE dbs(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(150) UNIQUE NOT NULL,
created_at DATE NOT NULL,
created_by INT(6) NOT NULL
);

CREATE TABLE users_to_databases(
user_id INT(6) NOT NULL,
database_id INT(6) NOT NULL,
PRIMARY KEY(user_id,database_id),
FOREIGN KEY(user_id) 
   REFERENCES users(id),
FOREIGN KEY(database_id) 
   REFERENCES dbs(id)
);

INSERT INTO users (name, email, password, permissions) VALUES ('root', 'kontakt@kchroscinski.pl', '$2y$10$09ynAJkeIQErL8E5qLKkE.e4ggCvekghSzIfq6IP0RM5KALzI3SAG', 15);
INSERT INTO users (name, email, password, permissions) VALUES ('nieroot', 'uczelnia@kchroscinski.pl', '$2y$10$H2wGPm3O3HOGcytbaXGfseCQkDTVui1JcFSrUD1Y5.cDwTQqrVczS', 15);
INSERT INTO dbs (name, created_at, created_by) VALUES ('szkola', '2015-05-15 05:05:51', 1);
INSERT INTO dbs (name, created_at, created_by) VALUES ('fabryka', '2017-03-07 12:08:23', 2);
INSERT INTO users_to_databases (user_id, database_id) VALUES (1, 1);
INSERT INTO users_to_databases (user_id, database_id) VALUES (2, 2);

