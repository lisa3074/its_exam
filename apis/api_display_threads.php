<?php
/* import modules */
require_once(__DIR__ . '/../cookie_config.php');
require_once(__DIR__ . '/../db.php');

/* Get threads from view */
try {
  $q = $db->prepare('SELECT * FROM display_thread ORDER BY thread_time asc;');
  $q->execute();
  if (!$q->rowCount()) {
    exit();
  }
  $threads = $q->fetchAll();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}
