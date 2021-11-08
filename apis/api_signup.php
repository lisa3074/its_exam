<?php
require_once(__DIR__.'/../db.php');


if(!isset($_SESSION)){
  require_once(__DIR__.'./../cookie_config.php');
  session_start();
}

 if( !$_POST['firstname'] ){
  header('Location: /signup/Fill out your first name');
  exit();  
}
 if( !$_POST['lastname'] ){
  header('Location: /signup/Fill out your last name');
  exit();  
}
 if( !$_POST['user_email'] ){
  header('Location: /signup/Put in an email');
  exit();  
}

if( ! filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) ){
  header('Location: /signup/The email you entered was not an email (example: a@a.com)');
  exit();  
}


 //Check if email has been used
$q1 = $db->prepare('SELECT * FROM user WHERE email = :email');
$q1->bindValue(':email', strtolower($_POST['user_email']));
$q1->execute();
if($q1->rowCount()){
  header('Location: /signup/You have chosen an email that already exists. Sign up with another one.');
  exit();
}

//check if password has been used
$q2 = $db->prepare('SELECT * FROM user WHERE password = :password');
  $q2->bindValue(':password', hash("sha256", $_POST['password']));
$q2->execute();
if($q2->rowCount()){
  header('Location: /signup/You can not use this password. Choose a different one');
  exit();
} 

$password_length = strlen($_POST['password']);
if( $password_length < 8 or $password_length > 32 ){
  header('Location: /signup/Your password should be more than 8 and less than 33 characters');
  exit();  
} 

if( !$_POST['password'] ){
  header('Location: /signup/Put in a password');
  exit();  
}
$pattern = '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*[\`|\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\+|\=|\[|\{|\]|\}|\||\\|\'|\<|\,|\.|\>|\?|\/|\""|\;|\:|\s]).{8,32}$/';
$subject = $_POST['password'];
if(!preg_match($pattern, $subject)){
header('Location: /signup/You need a password between 8 - 32 charachters, at least one uppercase, one lowercase, one number and one special character.');
exit();
}

    //declare a salt that is hashed and store it in the database. This salt is unique for this user
    $salt = hash("sha256",base64_encode(openssl_random_pseudo_bytes(10)));
    $currentTime = time(); //timestamp
 try{
  $q = $db->prepare("INSERT INTO user (firstname, lastname, email, password, salt, uuid, logger, logged_time) values (:firstname, :lastname, :email, :password, :salt, :uuid, 0, $currentTime)");
  $q->bindValue(':firstname', $_POST['firstname']);
  $q->bindValue(':lastname', $_POST['lastname']);
  $q->bindValue(':email', strtolower($_POST['user_email']));
  $q->bindValue(':password', hash("sha256", $_POST['password']));
  $q->bindValue(':salt',hash("sha256", $salt));
  $q->bindValue(':uuid', bin2hex(random_bytes(16)));
  $q->execute();

 
   header('Location: /login/You have succesfully signed up, now  go log in with the chosen credentials!'); 
   exit();

}catch(PDOException $ex){
  echo $ex;
} 