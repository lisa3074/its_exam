<?php
/* import db */
require_once(__DIR__ . '/../db.php');

//Set cookie and start session if not started already
require_once(__DIR__ . './../cookie_config.php');

/* Is logged in user the author of the topic */
if ($user_id != $_SESSION['uuid'] || $_SESSION['privilige'] == '2') {
    header("Location: /posts/$thread_id/You do not have permission to mark this topic");
    exit();
}

/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header("Location: /posts/$thread_id/You do not have permission to mark this topic");
    exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
    header("Location: /posts/$thread_id/You do not have permission to mark this topic");
    exit();
}
$mark = $thread_done ? 'opened' : 'closed';

/* Update thread to be marked done/undone */
try {
    $q1 = $db->prepare("UPDATE thread SET thread_done=:thread_done WHERE thread_id = :thread_id");
    $q1->bindValue(':thread_id', $thread_id);

    /* toggle thread_done */
    if ($thread_done) {
        $q1->bindValue(':thread_done', 0);
    } else {
        $q1->bindValue(':thread_done', 1);
    }
    $q1->execute();
    if (!$q1->rowCount()) {
        header("Location: /posts/$thread_id/Something went wrong. Try again");
        exit();
    }
    header("Location: /posts/$thread_id/You've $mark the topic.");
    exit();
} catch (PDOException $ex) {
    echo $ex;
    http_response_code(400);
}
