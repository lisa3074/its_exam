<?php
/* import db */
require_once(__DIR__ . './../cookie_config.php');

/* EMAIL */
if (!isset($_POST['user_email'])) {
    header('Location: /login/Put in an email');
    exit();
}

if (strlen($_POST['user_email']) < 1) {
    header('Location: /signup/Put in an email');
    exit();
}

if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: /login/The email you entered was not an email (example: a@a.com)');
    exit();
}

/* PASSWORD */
if (!isset($_POST['password'])) {
    header('Location: /login/Put in a password');
    exit();
}

if (
    strlen($_POST['password']) < 8 ||
    strlen($_POST['password']) > 32
) {
    header('Location: /login/Your password should be more than 8 and less than 33 characters');
    exit();
}

$pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s]).{8,32}$/';
if (!preg_match($pattern, $_POST['password'])) {
    header('Location: /login/You need a password between 8 - 32 charachters, at least one uppercase, one lowercase, one number and one special character.');
    exit();
}


require_once(__DIR__ . '/../apis/api_login.php');
