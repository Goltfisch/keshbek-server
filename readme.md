# keshbek-server

## Requirements
- php 7.2.0 or higher
- docker
- composer

## Installation

### Install dependencies
1. Navigate to the project's root directory.
1. Run `composer install`
2. In the `.env`-file, adjust DATABASE_URL to:
```
DATABASE_URL=mysql://root:123456@127.0.0.1:3306/keshbek
```

### Generate keys for JWT
1. Navigate to the project's root directory.
2. run `mkdir config/jwt`.
3. Run `openssl genrsa -out config/jwt/private.pem -aes256 4096`. Here, you will be asked for a pass phrase two times. Both times, copy and paste the value of the `JWT_PASSPHRASE`-setting in your `.env`-file (f.ex. 64e0b28d693886fc0c3ce975d6c2fb26).
4. Run `openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem`. Here you also need to enter the `JWT_PASSPHRASE`-setting when asked.

### Start database-server and create database
1. Navigate to the project's root directory.
2. Run `docker-compose up -d` to start the database in a docker container.
3. Open `localhost:8001` in your browser to open phpMyAdmin and create the database `keshbek`.
9. Back in the terminal, run `php bin/console doctrine:migrations:migrate` and enter `y` when asked.
10. Run `php -S 127.0.0.1:8000 -t public` to run the symfony server
11. Now you can open the symfony projekt on `localhost:8000`

## Register new user
For development you can create an user with postman (or other tools).

POST Request to http://localhost:8000/register
with form-data

```
_username
_email
_password
_firstname
_lastname
```

## Known errors

### Database changes

When someone implements changes to database structure (f.ex. to the 'transaction'-table), the tables should be emptied before running new migrations to prevent errors, which could eventuelly lead to recreating the database.