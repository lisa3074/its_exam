<?php
/* import modules */
require_once(__DIR__ . '/../db.php');
/* Make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');


/* Check if the chosen email already is in the system */
$q = $db->prepare('SELECT email FROM user WHERE email = :user_email');
$q->bindValue(':user_email', strtolower($_POST['user_email']));
$q->execute();
$user = $q->fetch();

/* Check if the chosen email already is in the system, and if so that it is the logged in users email */
if ($q->rowCount()) {
    if ($user['email'] != $_SESSION['email']) {
        header('Location: /admin/You have chosen an email that already exists. Sign up with another one.');
        exit();
    }
}

/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header('Location: /admin/You are not validated to edit the profile. Please log in again.');
    exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
    header('Location: /admin/You are not validated to edit the profile. Please log in again.');
    exit();
}

//VALIDATION

if (!isset($_POST['firstname'])) {
    header('Location: /admin/You need to provide your first name');
    exit();
}

if (
    strlen($_POST['firstname']) < 2 ||
    strlen($_POST['firstname']) > 30
) {
    header('Location: /admin/Fill out your first name');
    exit();
}

/* If user is not an event organizer */
if ($_SESSION['privilege'] == '1' || $_SESSION['privilege'] == '2') {
    if (!isset($_POST['lastname'])) {
        header('Location: /admin/You need to provide your last name');
        exit();
    }
    if (
        strlen($_POST['lastname']) < 2 ||
        strlen($_POST['lastname']) > 30
    ) {
        header('Location: /admin/Fill out your last name');
        exit();
    }
}

if (!isset($_POST['user_email'])) {
    header('Location: /admin/You need to provide a valid email.');
    exit();
}
if (
    strlen($_POST['user_email']) < 1
) {
    header('Location: /admin/Put in an email');
    exit();
}

if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: /admin/The email you entered was not an email (example: a@a.com)');
    exit();
}


//make sure it is a url
$link_pattern = '/^(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i';
if (!preg_match($link_pattern, $_POST['link']) && strlen($_POST['link']) > 0) {
    header('Location: /admin/Enter a correct url.');
}


require_once(__DIR__ . '/../apis/api_update_user.php');