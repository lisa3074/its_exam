<?php
/* import module */
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* if logged in user is not the comment owner or if the user is an event organizer */
if ($user_id != $_SESSION['uuid'] || $_SESSION['privilige'] == '2') {
  if (!isset($thread_id)) {
    /* if this api is called from the forum */
    header("Location: /posts/$thread_id/You do not have permission to delete this event");
  } else {
    /* if this api is called from the admin page */
    header("Location: /admin/You do not have permission to delete this event");
  }
  exit();
}

try {
  $q = $db->prepare('DELETE FROM comments WHERE comment_id = :comment_id');
  $q->bindValue(':comment_id', $comment_id);
  $q->execute();
  if (!isset($thread_id)) {
    /* if this api is called from the forum */
    header("Location: /admin/Your comment has been deleted.");
  } else {
    /* if this api is called from the admin page */
    header("Location: /posts/$thread_id/Your comment has been deleted.");
  }
  exit();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}
