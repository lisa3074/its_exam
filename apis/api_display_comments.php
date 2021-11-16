<?php
/* import modules */
require_once(__DIR__ . '/../db.php');
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

try {
  /* get comments from view that is in a certain thread/topic */
  $q = $db->prepare('SELECT * FROM display_comments
                    WHERE thread_id = :thread_id
                    ORDER BY comment_ts asc');
  $q->bindValue(':thread_id', $thread_id);
  $q->execute();
  if (!$q->rowCount()) {
    header('Location: /forum/No comments related to this topic');
    exit();
  }
  $comments = $q->fetchAll();

  //encrypting with CBC
  $alg = 'AES-128-CBC';
  //Secret key
  $key = "47062c85f9b1a4d27f50717951f58fa0";
  $decrypted_comments = [];
  /* Loop throung the comments, decrypt the and push into array */
  for ($i = 0; $i < count($comments); $i++) {
    $comment = openssl_decrypt($comments[$i]['comment_text'], $alg, $key, 0, $comments[$i]['comment_iv']);
    array_push($decrypted_comments, $comment);
  }
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}