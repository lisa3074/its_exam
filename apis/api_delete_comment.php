<?php
if (!isset($_SESSION)) {
  require_once(__DIR__ . './../cookie_config.php');
  session_start();
}
require_once(__DIR__ . '/../db.php');


if (!isset($_SESSION['uuid'])) {
  header('Location: /login/You need to log in');
  exit();
}
if ($_SESSION['privilige'] != '3' || $_SESSION['privilige'] != '2') {
  header('Location: /events/You do not have permission to delete this event');
  exit();
}
if ($user_id != $_SESSION['uuid'] && $_SESSION['privilige'] == '2') {
  header('Location: /admin/You do not have permission to delete this comment');
  exit();
}

try {
  $q = $db->prepare('DELETE FROM comments
	                WHERE comment_id = :comment_id
                    ');
  $q->bindValue(':comment_id', $comment_id);
  $q->execute();

  header('Location: /admin');
} catch (PDOException $ex) {
  echo $ex;
}