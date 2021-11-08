<?php

require_once(__DIR__ . '/../db.php');

if(!isset($_SESSION)) { 
       require_once(__DIR__.'./../cookie_config.php');
        session_start(); 
    } 
try{
  $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
  $q->bindValue(':uuid', $uuid);
  $q->execute();
  $user = $q->fetch();
  
}catch(PDOException $ex){
  echo $ex;
}

?>


<main>
    <h3><?= $user['firstname']?> <?= $user['lastname']?></h3>
    <p><?= $user['email']?></p>
    <p><?= $user['user_description']?></p>
    <p><?= $user['user_link']?></p>

    <button onclick="history.back()">Back</button>
</main>