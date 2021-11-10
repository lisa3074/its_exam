<?php
if(!isset($_SESSION)){
  require_once(__DIR__.'./../cookie_config.php');
    session_start();
}
require_once(__DIR__.'/../db.php');


if(!isset($_SESSION['uuid'])){
    header('Location: /login/You need to log in');
     exit();
}
if($user_id != $_SESSION['uuid']){
     header('Location: /events/You do not have permission to delete this event');
    exit();
}

try{
  $q = $db->prepare('DELETE FROM events
	                WHERE event_id = :event_id
                    ');
$q->bindValue(':event_id', $event_id);
  $q->execute();
 header('Location: /events/succes/The event was deleted.');
}catch(PDOException $ex){
  echo $ex;
}