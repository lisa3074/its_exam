<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../apis/api_display_comments.php');

if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}

require_once(__DIR__ . '/../db.php');

try {
    $q = $db->prepare('SELECT * FROM display_reply_comment;');
    $q->execute();
    $reply_comment = $q->fetchAll();
} catch (PDOException $ex) {
    echo $ex;
}
?>

<main class="admin_main">

    <?php if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php
        // Use out to sanitize the read from the db, to prevent XXS 
        echo urldecode($display_message);
    }
    $thread_done;


        ?>
    </p>
    <button onclick="history.back()">Back</button>
    <section class="comment_wrapper">
        <article>
            <?php foreach ($comments as $key => $comment) {
                    $isMe = false;
                    $isAdmin = false;
                    if (isset($_SESSION['uuid'])) {
                        $isMe = $comment['user_id'] == $_SESSION['uuid'];
                        $isAdmin = $_SESSION['privilige'] == '3';
                    }
                    $question = $key == 0;
                    $comment_id = $comment['comment_id'];
                    $thread_id = $comment['thread_id'];
                    $thread_done = $comment['thread_done'];
                    $user_id = $comment['user_id'];
                    $thread_done_text = $thread_done ? 'Open topic' : 'Mark as answered'; ?>

            <h1 class="heading"><?= $key === 0 ? 'Question:' : '' ?><?= $key === 1 ? 'Answers:' : '' ?> </h1>
            <div class="comment <?= $isMe ? 'me' : '' ?> <?= $key === 0 ? 'question' : '' ?>">
                <div class="flex">
                    <h4>
                        <!-- Make only logged in users able to visit other profiles (not event organizers) -->
                        <?= isset($_SESSION['uuid']) ? ($_SESSION['privilige'] != '2' ? "<a href='/user/$user_id'>" : '<a>') : '<a>' ?>
                        <!-- Is the comment from the logged in user or not -->
                        <?php if ($isMe) {
                                    echo 'You';
                                } else {
                                    echo out($comment['firstname']) . " ";
                                    echo out($comment['lastname']) . " ";
                                } ?>
                        </a>
                    </h4>
                    <p><?= $comment['comment_ts'] ?></p>
                </div>
                <!-- Loop through each entry in the array (a view) -->
                <?php foreach ($reply_comment as $reply) {
                            //If the comment_reply_uuid is the same as a user and the comment id is the same as the read comment
                            if ($comment['comment_reply_uuid'] == $reply['comment_reply_uuid'] && $comment_id == $reply['comment_id']) {
                                $alg = 'AES-128-CBC';
                                $decryptKey = "47062c85f9b1a4d27f50717951f58fa0";

                                echo '<p class="italic">Replied to ';
                                echo out($reply['firstname']) . ' ';
                                echo out($reply['lastname']);
                                echo ':</p>';
                                foreach ($comments as $reply_to_comment) {

                                    if ($reply['comment_reply_comment_id'] == $reply_to_comment['comment_id']) {
                                        echo '<p class="quote">';
                                        echo out(openssl_decrypt($reply_to_comment['comment_text'], $alg, $decryptKey, 0, $reply_to_comment['comment_iv']));
                                        echo '</p>';
                                    }
                                }
                            }
                        } ?>
                <?php require_once(__DIR__ . '/../apis/api_display_comment_reply.php'); ?>
                <!-- Use out to sanitize the read from the db, to prevent XXS -->
                <p class="<?= $key === 0 ? 'bold' : '' ?>"><?= out($decrypted_comments[$key]); ?></p>
                <div class="flex no_pad <?= $key == 0 ? 'question' : '' ?>">
                    <!-- Show reply link if: not first question, not the logged in users comment, user is logged in and user is not an organizer -->
                    <?= $key == 0 || $isMe || !isset($_SESSION['privilige']) || $_SESSION['privilige'] == '2' ? '' : "<button data-id='$user_id' data-comment_id='$comment_id'
                    onclick='reply()'>Reply</button>" ?>
                    <!-- DELETE BUTTON -> If it's not a question and user is an admin or comment owner, show delete button -->
                    <?= !$question && $isAdmin || !$question && $isMe ? "<form action='/post/delete/$comment_id/$user_id/$thread_id' method='POST'><button>Delete</button></form>" : '' ?>
                    <!-- ANSWERED BUTTON -> If it's a question and user is comment owner, show marked as answered button -->
                    <?= $question && $isMe ? "<form action='/topic/update/$thread_id/$user_id/$thread_done' method='POST'><button>$thread_done_text</button></form>" : '' ?>
                    <!-- DELETE BUTTON -> If it's a question and user is an admin or comment owner, show delete button -->
                    <?= $question && $isAdmin || $question && $isMe ? "<form action='/topic/delete/$thread_id/$user_id' method='POST'><button>Delete topic</button></form>" : '' ?>
                </div>
            </div>
            <?php } ?>
        </article>
    </section>

    <!-- only show form if user is logged in -->
    <?php if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2' && !$thread_done) { ?>
    <form action="/post/comment/<?= $thread_id ?>"
        method="POST"
        class="comment_form"
        onsubmit="return validate()">
        <!-- Hidden input field to prevent CSRF with value that coresponds to the session['csrf] -->
        <input type="hidden"
            name="csrf"
            value="<?= $_SESSION['csrf'] ?>">
        <input type="hidden"
            name="reply_uuid">
        <input type="hidden"
            name="reply_comment">
        <label for="comment">
            <p>Write comment</p>
            <textarea name="comment_text"
                data-validate="str"
                data-min="1"
                data-max="10000"
                onkeyup="clear_parent_error(this)"
                id="comment"
                placeholder="Write a comment"></textarea>
        </label>
        <div class="invalid-feedback">
            Your answer needs to be at least 1 characters
        </div>
        </div>
        <button>Send</button>
    </form>
    <?php }
        echo !$thread_done ? '' : '<p>As the topic has been marked as answered by the topic starter, it is closed for further comments.</p>';
        ?>

</main>

<script>
function reply(val) {
    event.preventDefault();
    console.log(event.target.dataset.id);
    console.log(event.target.dataset.comment_id);
    console.log(val);
    document.querySelector("input[name='reply_uuid']").value = event.target.dataset.id;
    document.querySelector("input[name='reply_comment']").value = event.target.dataset.comment_id;
}
</script>

<?php
require_once(__DIR__ . '/bottom.php');