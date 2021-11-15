<?php
/* import module */
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../cookie_config.php');
/* Add event to db */
try {
  $q = $db->prepare("INSERT INTO events (event_title, event_time, event_desc, event_image, event_ticket_link, event_category, event_genre, event_owner_id, event_image_credits)
  values (:event_title, :event_time, :event_desc, :event_image, :event_ticket_link, :event_category, :event_genre, :event_owner_id, :event_image_credits)");
  $q->bindValue(':event_title', $_SESSION['event_title']);
  $q->bindValue(':event_time', $_SESSION['event_time']);
  $q->bindValue(':event_desc', $_SESSION['event_desc']);
  $q->bindValue(':event_image', $image);
  $q->bindValue(':event_ticket_link', $_SESSION['event_ticket_link']);
  $q->bindValue(':event_category', $_SESSION['event_category']);
  $q->bindValue(':event_genre', $_SESSION['event_genre']);
  $q->bindValue(':event_owner_id', $_SESSION['uuid']);
  $q->bindValue(':event_image_credits', $_SESSION['event_image_credits']);
  $q->execute();
  if (!$q->rowCount()) {
    header("Location: /events/Something went wrong. Your event was not saved.");
    exit();
  }
  header('Location: /events/succes/You have succesfully added your event!');
  exit();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}
