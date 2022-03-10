# About this project
A API in PHP and PostgreSQL 

# Router
- POST /login.php({"name": "admin", "password": "admin"}): returns a token valid for 1 hour, if the username and password are correct and previously registered in the database.
- POST /logout.php(bearer token): invalidates the token for future requests.
- POST /create.php(bearer token, {"name": "IVAN","age": "22"}): inserts a person into the database and returns their identifier, if the token is valid.
- DELETE /delete.php(bearer token, {"id": 9}): removes a person with identifier id from the database, if the token is valid.
- GET /age.php(bearer token, {"id": 9}):returns the age of a person with identifier id in the database, if the token is valid.

# Install Dependencies
`composer require firebase/php-jwt`
- the SQL is in the sql.sql file



