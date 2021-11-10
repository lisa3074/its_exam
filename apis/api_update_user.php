<?php
require_once(__DIR__ . '/../db.php');
if (!isset($_SESSION)) {
    session_start();
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

//Sæt meget mere validering ind!!!

if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}

//VALIDATION
if (!$_POST['firstname']) {
    header('Location: /admin/Fill out your first name');
    exit();
}

if (!$_POST['user_email']) {
    header('Location: /admin/Put in an email');
    exit();
}

if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
    header('Location: /admin/The email you entered was not an email (example: a@a.com)');
    exit();
}

if (!isset($_POST['firstname'])) {
    header('Location: /signup/You need to provide a name');
    exit();
}
if (!isset($_POST['user_email'])) {
    header('Location: /signup/You need to provide a valid email.');
    exit();
}
$link_pattern = '/^(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i';
$subject = $_POST['link'];
if (!preg_match($link_pattern, $subject)) {
    header('Location: /admin/Enter a correct url.');
}



try {
    if ($_SESSION['privilige'] != '2') {
        $q = $db->prepare("UPDATE user 
        SET 
        firstname = :firstname, 
        lastname = :lastname, 
        email = :email, 
        user_description = :user_description, 
        user_link = :user_link 
        WHERE uuid = :uuid");
        $q->bindValue(':lastname', $_POST['lastname']);
    } else {

        $q = $db->prepare("UPDATE user SET 
        firstname = :firstname, 
        email = :email, 
        user_description = :user_description, 
        user_link = :user_link 
        WHERE uuid = :uuid");
        $q->bindValue(':firstname', $_POST['firstname']);
        $q->bindValue(':email', strtolower($_POST['user_email']));
        $q->bindValue(':user_description', $_POST['description']);
        $q->bindValue(':user_link', $_POST['link']);
        $q->bindValue(':uuid', $_SESSION['uuid']);
        $q->execute();
    }

    header('Location: /admin/You have edited your profile!');
    exit();
} catch (PDOException $ex) {
    echo $ex;
}