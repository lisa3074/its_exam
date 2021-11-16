<?php
/* import modules */
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . '/../db.php');
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* if user is not logged in as admin or regular user */
if ($_SESSION['privilige'] != '3' || $_SESSION['privilige'] != '1') {
    header('Location: /forum/You do not have permission to delete this topic');
}
//If logged in user is not the topic owner but is a regulat user
if ($user_id != $_SESSION['uuid'] && $_SESSION['privilige'] == '1') {
    header('Location: /forum/You do not have permission to delete this topic');
    exit();
}

try {
    /* Use transaction to make sure both statements are successful or they will both roll back */
    $db->beginTransaction();
    $q2 = $db->prepare('DELETE FROM comments WHERE thread_id = :thread_id');
    $q2->bindValue(':thread_id', $thread_id);
    $q2->execute();

    $q = $db->prepare('DELETE FROM thread WHERE thread_id = :thread_id ');
    $q->bindValue(':thread_id', $thread_id);
    $q->execute();
    $db->commit();
    header('Location: /forum');
    exit();
} catch (PDOException $ex) {
    echo $ex;
    $db->rollback();
    http_response_code(400);
    exit();
}