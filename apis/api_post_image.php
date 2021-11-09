<?php

require_once(__DIR__ . '/../db.php');

try {
    $q = $db->prepare("UPDATE user SET user_image=:user_image WHERE uuid = :uuid");
    $q->bindValue(':uuid', $_SESSION['uuid']);
    $q->bindValue(':user_image', $random_number . '.' . $extension);
    $q->execute();

    /*       if (isset($img)) {
                    header('Location: /admin/Your picture was updated!');
                    exit();
                } */
} catch (PDOException $ex) {
    http_response_code(400);
    echo $ex;
}
exit();