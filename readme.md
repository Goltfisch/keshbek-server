# keshbek-server

## Requirements
- php 7.1.3 or higher
- docker

## Installation
1. move into the `keshbek-server/` directory
2. run `docker-compose up -d` to start the database in a docker container
3. create a file namend `.env` in the project directory with demo configuration:
```
APP_ENV=dev
APP_SECRET=XXX
#TRUSTED_PROXIES=127.0.0.1,127.0.0.2
#TRUSTED_HOSTS=localhost,example.com
DATABASE_URL=mysql://root:123456@127.0.0.1:3306/keshbek
```
4. open `localhost:8001` in your browser to open phpMyAdmin
5. create the database `keshbek`
6. run this command `php -S 127.0.0.1:8000 -t public` to run the symfony server
7. now you can open the symfony projekt on `localhost:8000`

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
