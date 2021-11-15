<?php
/* import modules */
require_once(__DIR__ . '/../db.php');

//start session if not started already
require_once(__DIR__ . '/../cookie_config.php');

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
  if ($radiovalue == 3 && $_POST['key'] == 'cQxIQtxEycwGq1RWzpcZUQ@') {
    $q->bindValue(':privilige', 3);
  } else if ($radiovalue == 2) {
    $q->bindValue(':privilige', 2);
  } else if ($radiovalue == 1) {
    $q->bindValue(':privilige', 1);
  }
  $q->execute();
  if (!$q->rowCount()) {
    header('Location: /signup/An error happend and you weren\'t signed up. Try again.');
    exit();
  }
  header('Location: /login/You have succesfully signed up, now go log in with the chosen credentials!');
  exit();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
}
