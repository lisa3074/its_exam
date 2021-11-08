<?php

require_once(__DIR__.'/../db.php');

if(!isset($_SESSION)){
  require_once(__DIR__.'./../cookie_config.php');
  session_start();
}

 if( !$_POST['note'] ){
  header('Location: /admin/You need to write something to save it...');
  exit();  
}
if( ! isset($_SESSION['csrf']) || ! isset($_POST['csrf_secret'])){
    echo 'Hacker alert!!!!';
exit();
}
if( !$_POST['csrf_secret'] == $_SESSION['csrf']){
  echo 'Hacker alert!!!!';
exit();
}
    echo 'The note is saved';



//encrypting with CBC
$alg='AES-128-CBC';
//$iv=bin2hex(openssl_random_pseudo_bytes(openssl_cipher_iv_length(($alg))));
 $iv_len=openssl_cipher_iv_length($alg);
 //bin2hex => remember to use it to revert to a string
$iv=bin2hex(openssl_random_pseudo_bytes($iv_len));
//echo $iv;
$plaintext = $_POST['note'];
$key="secretKey";

var_dump($iv);
 try{
  $db->beginTransaction();
  
  $q = $db->prepare('UPDATE user 
  SET note=:note  WHERE uuid = :uuid');
  $q2 = $db->prepare('UPDATE user 
  SET iv=:iv  WHERE uuid = :uuid');

  $q->bindValue(':uuid', $_SESSION['uuid']);
  $q->bindValue(':note', openssl_encrypt($plaintext, $alg, $key, 0, $iv));
  $q->execute();
  
  $q2->bindValue(':uuid', $_SESSION['uuid']);
  $q2->bindValue(':iv', $iv);
  $q2->execute();

  $db->commit();
 header('Location: /admin/The note was saved! Click the below button to retrieve it'); 

}catch(PDOException $ex){
  echo $ex;
} 