<?php
require_once(__DIR__ . '/../db.php');

if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}

if (!$_POST['thread_question']) {
    header('Location: /forum/You need to write something to send it...');
    exit();
}
/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header('Location: /forum/You are not validated to send the message. Please log in again.');
    exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
    header('Location: /forum/You are not validated to send the message. Please log in again.');
    exit();
}

$thread_id = bin2hex(random_bytes(16));
//encrypting with CBC
$alg = 'AES-128-CBC';
//$iv=bin2hex(openssl_random_pseudo_bytes(openssl_cipher_iv_length(($alg))));
$iv_len = openssl_cipher_iv_length($alg);

//bin2hex => remember to use it to revert to a string
//$iv=bin2hex(openssl_random_pseudo_bytes($iv_len));
$comment_iv = bin2hex(openssl_random_pseudo_bytes(8));
//echo $iv;
$plaintext = $_POST['thread_question'];
$key = "47062c85f9b1a4d27f50717951f58fa0";

try {
    $q = $db->prepare("INSERT INTO thread (thread_id, thread_name, thread_owner_id) values (:thread_id, :thread_name, :thread_owner_id)");
    $q->bindValue(':thread_id', $thread_id);
    $q->bindValue(':thread_name', $_POST['thread_question']);
    $q->bindValue(':thread_owner_id', $_SESSION['uuid']);
    $q->execute();

    $q2 = $db->prepare("INSERT INTO comments (comment_text, comment_iv, user_id, thread_id) VALUES (:comment_text, :comment_iv, :uuid, :thread_id)");
    $q2->bindValue(':uuid', $_SESSION['uuid']);
    $q2->bindValue(':comment_iv', $comment_iv);
    $q2->bindValue(':thread_id', $thread_id);
    $q2->bindValue(':comment_text', openssl_encrypt($plaintext, $alg, $key, 0, $comment_iv));
    $q2->execute();

    header('Location: /forum/Your question is saved!');
} catch (PDOException $ex) {
    echo $ex;
}