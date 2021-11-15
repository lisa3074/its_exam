<?php
/* import db */
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . './../cookie_config.php');

//###########
//VALIDATION
//###########

$radiovalue = $_POST['account'];

/* ACCOUNT TYPE */
if (!isset($radiovalue) || !$radiovalue) {
    header('Location: /signup/You need to chose an account type');
    exit();
}

/* FIRSTNAME */
if (!isset($_POST['firstname'])) {
    header('Location: /signup/You need to provide a name');
    exit();
}

if (
    strlen($_POST['firstname']) < 2 ||
    strlen($_POST['firstname']) > 30
) {
    header('Location: /signup/Your first name needs to be between 2 - 30 characters');
    exit();
}

/* LASTNAME */ //not organizer
if ($radiovalue == 3 || $radiovalue == 1) {
    if (!isset($_POST['lastname']) || !$_POST['lastname']) {
        header('Location: /signup/You need to provide a last name.');
        exit();
    }
    if (
        strlen($_POST['lastname']) < 2 ||
        strlen($_POST['lastname']) > 30
    ) {
        header('Location: /signup/Your last name needs to be between 2 - 30 characters');
        exit();
    }
}

/* EMAIL */
if (!isset($_POST['user_email'])) {
    header('Location: /signup/You need to provide a valid email.');
    exit();
}

if (strlen($_POST['user_email']) < 1) {
    header('Location: /signup/Put in an email');
    exit();
}

if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: /signup/The email you entered was not an email (example: a@a.com)');
    exit();
}

/* PASSWORD */
if (!isset($_POST['password'])) {
    header('Location: /signup/You need to provide a password.');
    exit();
}
if (
    strlen($_POST['password']) < 8 ||
    strlen($_POST['password']) > 32
) {
    header('Location: /signup/Your password should be more than 8 and less than 33 characters');
    exit();
}

$pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s]).{8,32}$/';
if (!preg_match($pattern, $_POST['password'])) {
    header('Location: /signup/You need a password between 8 - 32 charachters, at least one uppercase, one lowercase, one number and one special character.');
    exit();
}

/* PASSWORD MATCH */
if (!isset($_POST['password_match'])) {
    header('Location: /signup/You need to provide a matching password.');
    exit();
}

if (
    strlen($_POST['password_match']) < 8 ||
    strlen($_POST['password_match']) > 32
) {
    header('Location: /signup/Your match password needs to be between 8 - 32 characters');
    exit();
}

if ($_POST['password'] != $_POST['password_match']) {
    header('Location: /signup/The two passwords does not match.');
    exit();
}

//Check if email has been used
$q1 = $db->prepare('SELECT * FROM user WHERE email = :email');
$q1->bindValue(':email', strtolower($_POST['user_email']));
$q1->execute();
if ($q1->rowCount()) {
    header('Location: /signup/You have chosen an email that already exists. Sign up with another one.');
    exit();
}

//check if password has been used
$q2 = $db->prepare('SELECT * FROM user WHERE password = :password');
$q2->bindValue(':password', hash("sha256", $_POST['password']));
$q2->execute();
if ($q2->rowCount()) {
    header('Location: /signup/An error occured. Try choosing a different password.');
    exit();
}

/* If admin account is chosen, check if the admin key is a match */
if ($radiovalue == 3 && $_POST['key'] != 'cQxIQtxEycwGq1RWzpcZUQ@') {
    header('Location: /signup/You\'ve entered a wrong admin key. Try again or choose another account type.');
    exit();
}
require_once(__DIR__ . '/../apis/api_signup.php');
