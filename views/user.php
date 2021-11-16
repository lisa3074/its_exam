<?php
/* import module */
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../apis/api_get_user.php');
?>

<!-- SHOW USER PROFILE -->
<!-- Use out() to escape special characters (avoid XSS)-->
<main>
    <h3><?= out($user['firstname']) ?> <?= out($user['lastname']) ?></h3>
    <p><?= out($user['email']) ?></p>
    <p><?= out($user['user_description']) ?></p>
    <p><?= out($user['user_link']) ?></p>

    <button onclick="history.back()">Back</button>
</main>