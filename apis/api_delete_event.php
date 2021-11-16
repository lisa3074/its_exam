<?php
/* import module */
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . '/../db.php');
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* if user is not logged in as admin or event organizer */
if ($_SESSION['privilige'] != '3' || $_SESSION['privilige'] != '2') {
  header('Location: /events/You do not have permission to delete this event');
}

//If logged in user is not the topic owner but is an event organizer
if ($user_id != $_SESSION['uuid'] && $_SESSION['privilige'] == '2') {
  header('Location: /events/You do not have permission to delete this event');
  exit();
}

try {
  $q = $db->prepare('DELETE FROM events WHERE event_id = :event_id ');
  $q->bindValue(':event_id', $event_id);
  $q->execute();
  header('Location: /events/succes/The event was deleted.');
  exit();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}