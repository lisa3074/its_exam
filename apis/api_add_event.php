<?php

 require_once(__DIR__.'/../db.php');
if(!isset($_SESSION)){
    session_start();
}


 try{
  $q = $db->prepare("INSERT INTO events (event_title, event_time, event_desc, event_image, event_ticket_link, event_category, event_genre, event_owner_id)
  values (:event_title, :event_time, :event_desc, :event_image, :event_ticket_link, :event_category, :event_genre, :event_owner_id)");

  
  $q->bindValue(':event_title', $_POST['event_title']);
  $q->bindValue(':event_time', $_POST['event_time']);
  $q->bindValue(':event_desc', $_POST['event_desc']);
  $q->bindValue(':event_image', 'testImg.png');
  $q->bindValue(':event_ticket_link', $_POST['event_ticket_link']);
  $q->bindValue(':event_category', $_POST['event_category']);
  $q->bindValue(':event_genre', $_POST['event_genre']);
  $q->bindValue(':event_owner_id', $_SESSION['uuid']);
  $q->execute();

 
   header('Location: /events/succes/You have succesfully added your event!'); 
   exit();

}catch(PDOException $ex){
  echo $ex;
} 