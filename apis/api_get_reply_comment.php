<?php
/* import modules */
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . './../cookie_config.php');
/* Get reply comments / view from db */
try {
    $q = $db->prepare('SELECT * FROM display_reply_comment;');
    $q->execute();
    if (!$q->rowCount()) {
        exit();
    }
    $reply_comment = $q->fetchAll();
} catch (PDOException $ex) {
    echo $ex;
    http_response_code(400);
    exit();
}
