CREATE TABLE users(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
email VARCHAR(100) NOT NULL,
password CHAR(60) NOT NULL,
permissions INT(3) NOT NULL
)

CREATE TABLE databases(
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(150) NOT NULL,
created_at DATE NOT NULL,
created_by INT(6) NOT NULL
)

CREATE TABLE users_to_databases(
user_id INT(6) NOT NULL,
database_id INT(6) NOT NULL,
PRIMARY KEY(user_id,database_id),
FOREIGN KEY(user_id) 
   REFERENCES users(id),
FOREIGN KEY(database_id) 
   REFERENCES databases(id)
)
