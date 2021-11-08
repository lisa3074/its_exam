<?php
if(!isset($_SESSION)){
    session_start();
}
require_once(__DIR__.'/../db.php');

try{
        $q = $db->prepare('SELECT *
                        FROM display_thread
                        ORDER BY thread_time asc;'); 

  $q->execute();
  $threads = $q->fetchAll();

}catch(PDOException $ex){
  echo $ex;
}