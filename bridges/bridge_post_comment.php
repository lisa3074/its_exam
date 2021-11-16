<?php
/* import db */
require_once(__DIR__ . '/../db.php');
/* Make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header("Location: /posts/$thread_id/You do not have permission to write a comment");
    exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
    header("Location: /posts/$thread_id/You do not have permission to write a comment");
    exit();
}

/* COMMENT */
if (!isset($_POST['comment_text'])) {
    header("Location: /posts/$thread_id/You need to write at least 1 characters, max 3500");
    exit();
}
if (
    strlen($_POST['comment_text']) < 1 ||
    strlen($_POST['comment_text']) > 3500
) {
    header("Location: /posts/$thread_id/You need to write at least 1 characters, max 3500");
    exit();
}

// post to db
require_once(__DIR__ . '/../apis/api_post_comment.php');