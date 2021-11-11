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
if ($user_id != $_SESSION['uuid'] || $_SESSION['privilige'] == '2') {
  if (!isset($thread_id)) {
    header("Location: /posts/$thread_id/You do not have permission to delete this event");
  } else {
    header("Location: /admin/You do not have permission to delete this event");
  }
  exit();
}


try {
  $q = $db->prepare('DELETE FROM comments
	                WHERE comment_id = :comment_id
                    ');
  $q->bindValue(':comment_id', $comment_id);
  $q->execute();
  if (!isset($thread_id)) {
    header("Location: /admin/Your comment has been deleted.");
  } else {
    header("Location: /posts/$thread_id/Your comment has been deleted.");
  }
} catch (PDOException $ex) {
  echo $ex;
}