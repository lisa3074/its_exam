 <?php
    /* import modules */
    require_once(__DIR__ . '/../db.php');
    require_once(__DIR__ . '/../apis/api_display_comments.php');
    /* Set cookie and start session if not started already + make sure user is logged in */
    require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

    /* Get reply comments from view */
    try {
        $q = $db->prepare('SELECT * FROM display_reply_comment;');
        $q->execute();
        $reply_comment = $q->fetchAll();
    } catch (PDOException $ex) {
        echo $ex;
        http_response_code(400);
        exit();
    }
    /* Put the comment and user replyed to in the posted comment */
    foreach ($reply_comment as $reply) { ?>
 <?= $comment['comment_reply_uuid'] != '' ? '<p>Replied to ' : '' ?>
 <?= $comment['comment_reply_uuid'] == $reply['comment_reply_uuid'] && $comment_id == $reply['comment_id'] ?  out($reply['firstname']) . ' ' . out($reply['lastname']) : '' ?>
 <?= $comment['comment_reply_uuid'] != '' ? '</p>' : '' ?>
 <?php }