<?php
/* import module */
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . './../cookie_config.php');
/* Get the user that was clicked on */
try {
    $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
    $q->bindValue(':uuid', $uuid);
    $q->execute();
    if (!$q->rowCount()) {
        header('Location: /Events/You can\'t visit this profile');
        exit();
    }
    $user = $q->fetch();
} catch (PDOException $ex) {
    echo $ex;
    http_response_code(400);
    exit();
}
