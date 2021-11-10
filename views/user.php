<?php

require_once(__DIR__ . '/../db.php');

if (!isset($_SESSION)) {
  require_once(__DIR__ . './../cookie_config.php');
  session_start();
}
try {
  $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
  $q->bindValue(':uuid', $uuid);
  $q->execute();
  $user = $q->fetch();
} catch (PDOException $ex) {
  echo $ex;
}

?>


<main>
    <h3><?= out($user['firstname']) ?> <?= out($user['lastname']) ?></h3>
    <p><?= out($user['email']) ?></p>
    <p><?= out($user['user_description']) ?></p>
    <p><?= out($user['user_link']) ?></p>

    <button onclick="history.back()">Back</button>
</main>