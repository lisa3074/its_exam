 <?php
    /* import modules */
    require_once(__DIR__ . '/../db.php');
    require_once(__DIR__ . '/../apis/api_display_comments.php');
    /* Get reply comments from view */
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
    /* Put the comment and user replyed to in the posted comment */
    foreach ($reply_comment as $reply) { ?>
 <?= $comment['comment_reply_uuid'] != '' ? '<p>Replied to ' : '' ?>
 <?= $comment['comment_reply_uuid'] == $reply['comment_reply_uuid'] && $comment_id == $reply['comment_id'] ?  out($reply['firstname']) . ' ' . out($reply['lastname']) : '' ?>
 <?= $comment['comment_reply_uuid'] != '' ? '</p>' : '' ?>
 <?php }

/*     foreach ($reply_comment as $reply) {
        if ($comment['comment_reply_uuid'] == $reply['comment_reply_uuid'] && $comment_id == $reply['comment_id']) {
            echo '<p>Replied to ' . out($reply['firstname']) . ' ' . out($reply['lastname']) . '</p>';
        }
    } */