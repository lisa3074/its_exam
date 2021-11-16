<?php
/* import modules */
require_once(__DIR__ . '/../db.php');
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

try {
  /* get comments from view */
  $q = $db->prepare('SELECT * FROM display_comments
                    WHERE user_id = :uuid
                    ORDER BY comment_ts desc');
  $q->bindValue(':uuid', $_SESSION['uuid']);
  $q->execute();
  $comments = $q->fetchAll();
  //encrypting with CBC
  $alg = 'AES-128-CBC';
  //secret key
  $key = "47062c85f9b1a4d27f50717951f58fa0";
  $decrypted_comments = [];
  for ($i = 0; $i < count($comments); $i++) {
    $comment = openssl_decrypt($comments[$i]['comment_text'], $alg, $key, 0, $comments[$i]['comment_iv']);
    array_push($decrypted_comments, $comment);
  }
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}