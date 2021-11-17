<?php
/* import db */
require_once(__DIR__ . '/../db.php');
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* QUESTION */
if (!isset($_POST['thread_question'])) {
    header('Location: /forum/You need to write something to send it...');
    exit();
}
if (
    strlen($_POST['thread_question']) < 20 ||
    strlen($_POST['thread_question']) > 3500
) {
    header('Location: /forum/Your question needs to be between 20 and 3500 characters');
    exit();
}
/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header('Location: /forum/You are not validated to send the message. Please log in again.');
    exit();
}
if ($_POST['csrf'] != $_SESSION['csrf']) {
    header('Location: /forum/You are not validated to send the message. Please log in again.');
    exit();
}

$thread_id = bin2hex(random_bytes(16));

//encrypting with CBC, the algoritm of the encryption
$alg = 'AES-128-CBC';
//The iv
$comment_iv = bin2hex(openssl_random_pseudo_bytes(8));
//the comment
$plaintext = $_POST['thread_question'];
//The secret key
$key = "47062c85f9b1a4d27f50717951f58fa0";

try {
    //Set transaction to make sure both or none come through - prepared statement and bindValue to escape characters
    $db->beginTransaction();
    //post as thread
    $q = $db->prepare("INSERT INTO thread (thread_id, thread_name, thread_owner_id) values (:thread_id, :thread_name, :thread_owner_id)");
    $q->bindValue(':thread_id', $thread_id);
    $q->bindValue(':thread_name', $_POST['thread_question']);
    $q->bindValue(':thread_owner_id', $_SESSION['uuid']);
    $q->execute();
    if (!$q->rowCount()) {
        header('Location: /forum/An error occured and your question was not saved. Try again');
        exit();
    }
    //post as comment
    $q2 = $db->prepare("INSERT INTO comments (comment_text, comment_iv, user_id, thread_id) VALUES (:comment_text, :comment_iv, :uuid, :thread_id)");
    $q2->bindValue(':uuid', $_SESSION['uuid']);
    $q2->bindValue(':comment_iv', $comment_iv);
    $q2->bindValue(':thread_id', $thread_id);
    $q2->bindValue(':comment_text', openssl_encrypt($plaintext, $alg, $key, 0, $comment_iv));
    $q2->execute();
    if (!$q2->rowCount()) {
        header('Location: /forum/An error occured and your question was not saved. Try again');
        exit();
    }
    //commit if both statements are successful
    $db->commit();
    header('Location: /forum/Your question is saved!');
} catch (PDOException $ex) {
    echo $ex;
    //roll back if one or both statements fail
    $db->rollback();
    http_response_code(400);
}