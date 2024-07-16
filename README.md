# modul-183
💡Note: This app was a school project (modul-183) from more than a year ago. This is only a reupload from the GitHub Classroom repo so that it also is publicly accessible.

This repository demonstrates a basic web-app solely for authentification purposes.
To get started, you'll need to install (or check if you already have) some dependencies.

## Dependencies
- PHP 8.2
- PHP Webserver (Docker, XAMPP, PhpStorm Built-In Webserver, ...) or just `php -S localhost:80` inside a terminal if you have `php.exe` in your environment variable
- MySQL (Docker, MySQL Community Server, ...)
- A functional web browser, preferably Google Chrome

## config.php
This config file has to be created and put inside the root directory of the repository.
To see what you need to exactly do, see below.

```php
<?php
const SMTP_HOST = 'smtp.example.com'; // Set the SMTP server to send through
const SMTP_USERNAME = 'user@example.com'; // SMTP username
const SMTP_PASSWORD = 'secret'; // SMTP password
const SMTP_ADDRESS = 'from@example.com'; // Address from the sender
const SMTP_NAME = 'John Doe'; // Name from the sender which the recipient will see
const DB_HOST = 'localhost'; // specifies the server where the database is hosted
const DB_USER = 'root'; // specifies the username used to access the database
const DB_PASS = 'secret'; // specifies the password used to authenticate the user
const DB_NAME = 'nameOfDatabase'; // specifies the name of the database being accessed
```

For more help, visit [PHPMailer](https://github.com/PHPMailer/PHPMailer)

## Thank you for visiting !
Contributor: Alperen "[hnypot](https://github.com/hnypot)" Yilmaz

[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-8d59dc4de5201274e310e4c54b9627a8934c3b88527886e3b421487c677d23eb.svg)](https://classroom.github.com/a/dygNXH4X)
