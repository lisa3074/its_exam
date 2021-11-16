<?php
/* import db */
require_once(__DIR__ . '/../db.php');

//encrypting with CBC, the algoritm of the encryption
$alg = 'AES-128-CBC';
//The iv
$comment_iv = bin2hex(openssl_random_pseudo_bytes(8));
//The comment
$plaintext = $_POST['comment_text'];
//the secret key
$key = "47062c85f9b1a4d27f50717951f58fa0";

try {

  $q = $db->prepare("INSERT INTO comments (comment_text, comment_iv, user_id, thread_id, comment_reply_uuid, comment_reply_comment_id) VALUES (:comment_text, :comment_iv, :uuid, :thread_id, :comment_reply_uuid, :comment_reply_comment_id)");
  $q->bindValue(':uuid', $_SESSION['uuid']);
  $q->bindValue(':comment_iv', $comment_iv);
  $q->bindValue(':thread_id', $thread_id);
  $q->bindValue(':comment_reply_uuid', $_POST['reply_uuid']);
  $q->bindValue(':comment_reply_comment_id', $_POST['reply_comment']);
  $q->bindValue(':comment_text', openssl_encrypt($plaintext, $alg, $key, 0, $comment_iv));
  $q->execute();
  if (!$q->rowCount()) {
    header('Location: /posts/$thread_id/An error occured and your comment was not saved. Try again');
    exit();
  }


  header("Location: /posts/$thread_id/Your comment was saved!");
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
}