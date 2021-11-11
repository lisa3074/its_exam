<?php
require_once(__DIR__ . '/../db.php');


if (!isset($_SESSION)) {
  require_once(__DIR__ . './../cookie_config.php');
  session_start();
}

//VALIDATION
if (!$_POST['firstname']) {
  header('Location: /signup/Fill out your first name');
  exit();
}

//not organizer
if ($radiovalue == 3 || $radiovalue == 1) {
  if (!isset($_POST['lastname']) || !$_POST['lastname']) {
    header('Location: /signup/You need to provide a last name.');
    exit();
  }
}
if (strlen($_POST['user_email']) < 1) {
  header('Location: /signup/Put in an email');
  exit();
}

if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
  header('Location: /signup/The email you entered was not an email (example: a@a.com)');
  exit();
}

if (!$_POST['password']) {
  header('Location: /signup/Put in a password');
  exit();
}

if (!$_POST['password_match']) {
  header('Location: /signup/Put in a matching password');
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

if (!isset($_POST['password'])) {
  header('Location: /signup/You need to provide a password.');
  exit();
}

if (!isset($_POST['password_match'])) {
  header('Location: /signup/You need to provide a matching password.');
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
  header('Location: /signup/You can not use this password. Choose a different one');
  exit();
}

$password_length = strlen($_POST['password']);
if ($password_length < 8 or $password_length > 32) {
  header('Location: /signup/Your password should be more than 8 and less than 33 characters');
  exit();
}

$radiovalue = $_POST['account'];
//Admin
if ($radiovalue == 3 && $_POST['key'] != 12345) {
  header('Location: /signup/You\'ve entered a wrong admin key. Try again or choose another account type.');
  exit();
}


if (!$_POST['password']) {
  header('Location: /signup/Put in a password');
  exit();
}
$pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s]).{8,32}$/';
$subject = $_POST['password'];
if (!preg_match($pattern, $subject)) {
  header('Location: /signup/You need a password between 8 - 32 charachters, at least one uppercase, one lowercase, one number and one special character.');
  exit();
}
if ($_POST['password'] != $_POST['password_match']) {
  header('Location: /signup/The two passwords does not match.');
  exit();
}

//declare a salt that is hashed and store it in the database. This salt is unique for this user
$salt = hash("sha256", base64_encode(openssl_random_pseudo_bytes(10)));
$currentTime = time(); //timestamp
try {
  $q = $db->prepare("INSERT INTO user (firstname, lastname, email, password, salt, uuid, logger, logged_time, privilige, user_blocked, user_image) values (:firstname, :lastname, :email, :password, :salt, :uuid, 0, $currentTime, :privilige, :user_blocked, :user_image)");
  $q->bindValue(':firstname', $_POST['firstname']);
  $q->bindValue(':lastname', $_POST['lastname']);
  $q->bindValue(':email', strtolower($_POST['user_email']));
  $q->bindValue(':password', hash("sha256", $_POST['password']));
  $q->bindValue(':salt', hash("sha256", $salt));
  $q->bindValue(':uuid', bin2hex(random_bytes(16)));
  $q->bindValue(':user_blocked', 0);
  $q->bindValue(':user_image', 'user_placeholder.png');
  if ($radiovalue == 3 && $_POST['key'] == 12345) {
    $q->bindValue(':privilige', 3);
  } else if ($radiovalue == 2) {
    $q->bindValue(':privilige', 2);
  } else if ($radiovalue == 1) {
    $q->bindValue(':privilige', 1);
  }
  $q->execute();


  header('Location: /login/You have succesfully signed up, now  go log in with the chosen credentials!');
  exit();
} catch (PDOException $ex) {
  echo $ex;
}