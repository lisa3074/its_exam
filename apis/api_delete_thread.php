<?php
if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}
require_once(__DIR__ . '/../db.php');


if (!isset($_SESSION['uuid'])) {
    header('Location: /login/You need to log in');
    exit();
}
if ($_SESSION['privilige'] != '3' || $_SESSION['privilige'] != '2') {
    header('Location: /events/You do not have permission to delete this event');
}
if ($user_id != $_SESSION['uuid'] && $_SESSION['privilige'] == '2') {
    header('Location: /admin/You do not have permission to delete this comment');
    exit();
}

try {
    $db->beginTransaction();

    $q2 = $db->prepare('DELETE FROM comments
	                WHERE thread_id = :thread_id
                    ');
    $q2->bindValue(':thread_id', $thread_id);
    $q2->execute();

    $q = $db->prepare('DELETE FROM thread
	                WHERE thread_id = :thread_id
                    ');
    $q->bindValue(':thread_id', $thread_id);
    $q->execute();
    $db->commit();
    header('Location: /forum');
} catch (PDOException $ex) {
    echo $ex;
    $db->rollback();
    http_response_code(400);
}