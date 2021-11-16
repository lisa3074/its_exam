<?php
/* import module */
require_once(__DIR__ . '/../db.php');

try {
    /* if loggin in user is a regular user or an admin */
    if ($_SESSION['privilege'] == '1' || $_SESSION['privilege'] == '3') {
        $q1 = $db->prepare("UPDATE user 
        SET 
        firstname = :firstname, 
        lastname = :lastname, 
        email = :email, 
        user_description = :user_description, 
        user_link = :user_link 
        WHERE uuid = :uuid");
        $q1->bindValue(':lastname', $_POST['lastname']);
    } else {
        $q1 = $db->prepare("UPDATE user SET 
        firstname = :firstname, 
        email = :email, 
        user_description = :user_description, 
        user_link = :user_link 
        WHERE uuid = :uuid");
    }
    $q1->bindValue(':firstname', $_POST['firstname']);
    $q1->bindValue(':email', strtolower($_POST['user_email']));
    $q1->bindValue(':user_description', $_POST['description']);
    $q1->bindValue(':user_link', $_POST['link']);
    $q1->bindValue(':uuid', $_SESSION['uuid']);
    $q1->execute();
    if (!$q->rowCount()) {
        header('Location: /admin/User was not found.');
        exit();
    }

    /* Set new session variables */
    $_SESSION['email'] = $_POST['user_email'];
    $_SESSION['firstname'] = $_POST['firstname'];
    $_SESSION['lastname'] = $_POST['lastname'];

    header('Location: /admin/You have edited your profile!');
    exit();
} catch (PDOException $ex) {
    echo $ex;
    http_response_code(400);
}