<?php

require_once(__DIR__ . '/../db.php');

if (!isset($_SESSION)) {
  require_once(__DIR__ . './../cookie_config.php');
  session_start();
}
if (
  strlen($_POST['comment_text']) < 1 ||
  strlen($_POST['comment_text']) > 3500
) {
  header("Location: /posts/$thread_id/You need to write at least 1 characters, max 3500");
  exit();
}
/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
  echo 'Hacker alert!!!!';
  exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
  echo 'Hacker alert!!!!';
  exit();
}
echo 'The comment is saved';


//encrypting with CBC
$alg = 'AES-128-CBC';
//$iv=bin2hex(openssl_random_pseudo_bytes(openssl_cipher_iv_length(($alg))));
$iv_len = openssl_cipher_iv_length($alg);

//bin2hex => remember to use it to revert to a string
//$iv=bin2hex(openssl_random_pseudo_bytes($iv_len));
$comment_iv = bin2hex(openssl_random_pseudo_bytes(8));
//echo $iv;
$plaintext = $_POST['comment_text'];
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

  header("Location: /posts/$thread_id/Your comment was saved!");
} catch (PDOException $ex) {
  echo $ex;
}