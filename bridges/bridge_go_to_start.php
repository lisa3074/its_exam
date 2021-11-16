<?php
/* MODULE TO CHECK FOR IF USER IS LOGGED IN OR NOT */
require_once(__DIR__ . '/../cookie_config.php');

/* if user is not logged in, go to start page  */
if (!isset($_SESSION['uuid'])) {
    http_response_code(400);
    header('Location: /login/You need to login to view that.');
    exit();
}
if (strlen($_SESSION['uuid']) < 2) {
    http_response_code(400);
    header('Location: /login/You need to login to view that.');
    exit();
}
if (!isset($_SESSION)) {
    http_response_code(400);
    header('Location: /login/You need to login to view that.');
    exit();
}