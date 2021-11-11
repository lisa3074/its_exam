<?php

require_once(__DIR__ . '/../db.php');
if (!isset($_SESSION)) {
    session_start();
}

if ($user_id != $_SESSION['uuid'] || $_SESSION['privilige'] == '2') {
    header("Location: /posts/$thread_id/You do not have permission to mark this topic");
    exit();
}

try {
    $mark = $thread_done ? 'opened' : 'closed';
    $q1 = $db->prepare("UPDATE thread SET thread_done=:thread_done WHERE thread_id = :thread_id");
    $q1->bindValue(':thread_id', $thread_id);
    //toggle thread_done
    if ($thread_done) {
        $q1->bindValue(':thread_done', 0);
    } else {
        $q1->bindValue(':thread_done', 1);
    }
    $q1->execute();

    header("Location: /posts/$thread_id/You've $mark the topic.");
    exit();
} catch (PDOException $ex) {
    echo $ex;
    http_response_code(400);
}