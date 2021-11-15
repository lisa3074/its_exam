<?php
/* import modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../apis/api_display_comments.php');
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . '/../apis/api_get_reply_comment.php');
?>

<main class="admin_main">

    <?php if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php echo urldecode($display_message);
    }
    $thread_done = false; ?>
    </p>

    <button onclick="history.back()">Back</button>
    <!-- COMMENTS LIST -->
    <section class="comment_wrapper">
        <article>
            <?php foreach ($comments as $key => $comment) {
                    /* Variables */
                    $isMe = false;
                    $isAdmin = false;
                    $question = $key == 0;
                    $comment_id = $comment['comment_id'];
                    $thread_id = $comment['thread_id'];
                    $thread_done = $comment['thread_done'];
                    $user_id = $comment['user_id'];
                    $thread_done_text = $thread_done ? 'Open topic' : 'Mark as answered';
                    /* if user is logged in */
                    if (isset($_SESSION['uuid'])) {
                        /* If logged in user is the same as comment writer, set to true */
                        $isMe = $comment['user_id'] == $_SESSION['uuid'];
                        /* If logged in user is admin */
                        $isAdmin = $_SESSION['privilige'] == '3';
                    } ?>

            <h1 class="heading"><?= $key === 0 ? 'Question:' : '' ?><?= $key === 1 ? 'Answers:' : '' ?> </h1>
            <div class="comment <?= $isMe ? 'me' : '' ?> <?= $key === 0 ? 'question' : '' ?>">
                <div class="flex">
                    <!-- HEADING -->
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
                                $relational_comment;
                                foreach ($comments as $reply_to_comment) {
                                    $relational_comment = $reply['comment_reply_comment_id'];
                                    if ($reply['comment_reply_comment_id'] == $reply_to_comment['comment_id']) {
                                        $e1 = '<p class="quote"><span class="quote_signs">“ </span>';
                                        $e2 = openssl_decrypt($reply_to_comment['comment_text'], $alg, $decryptKey, 0, $reply_to_comment['comment_iv']);
                                        $e3 =  '<span class="quote_signs"> ”</span></p>';
                                    }
                                }
                                /* If user is not logged in or the user is an event organizer, don't make clickable */
                                if (!isset($_SESSION['privilige']) || $_SESSION['privilige'] == '2') {
                                    echo "<p class='italic' data-comment_id='$relational_comment''>Replied to ";
                                } else {
                                    echo "<p class='italic' data-comment_id='$relational_comment' onclick='scrollToPost()''>Replied to ";
                                }
                                echo out($reply['firstname']) . ' ';
                                echo out($reply['lastname']);
                                echo ':</p>';
                                echo $e1, out($e2), $e3;
                            }
                        } ?>

                <!-- Use out to sanitize the read from the db, to prevent XXS -->
                <p class="comment_text <?= $key === 0 ? 'bold' : '' ?>"><?= out($decrypted_comments[$key]); ?></p>
                <div class="flex no_pad <?= $key == 0 ? 'question' : '' ?>">
                    <!-- Show reply link if: it's not the first question, it's not the logged in users comment, the user is logged in and not an organizer -->
                    <?= $key == 0 || $isMe || !isset($_SESSION['privilige']) || $_SESSION['privilige'] == '2' ? '' : "<div><button data-id='$user_id' data-comment_id='$comment_id'
                    onclick='reply()'>Reply</button></div>" ?>
                    <!-- DELETE BUTTON -> If it's not a question (topic) and user is an admin or comment owner, show delete button -->
                    <?= !$question && $isAdmin || !$question && $isMe ?
                                "<form action='/post/delete/$comment_id/$user_id/$thread_id' method='POST'>
                                     <input type='hidden'
                                    name='csrf'
                                    value='{$_SESSION['csrf']}'>
                                    <button data-comment_id='$comment_id'>Delete</button>
                                </form>" : '' ?>
                    <!-- ANSWERED BUTTON -> If it's a question (topic) and user is comment owner, show marked as answered button -->
                    <?= $question && $isMe ?
                                "<form action='/topic/update/$thread_id/$user_id/$thread_done' method='POST'> 
                                    <input type='hidden'
                                    name='csrf'
                                    value='{$_SESSION['csrf']}'>
                                    <button>$thread_done_text</button>
                                 </form>" : '' ?>
                    <!-- DELETE BUTTON -> If it's a question and user is an admin or comment owner, show delete button -->
                    <?= $question && $isAdmin || $question && $isMe ?
                                "<form action='/topic/delete/$thread_id/$user_id' method='POST'>
                                     <input type='hidden'
                                    name='csrf'
                                    value='{$_SESSION['csrf']}'>
                                    <button>Delete topic</button>
                                </form>" : '' ?>
                </div>
            </div>
            <?php } ?>
        </article>
    </section>

    <!-- only show form if user is logged in -->
    <?php if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2' && !$thread_done) { ?>
    <!-- the data-validate attribute is a condition in validator.js that is called on submit for frontend validation -->
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
            <p><span class="answering">Write comment</span> <span class="green red close hide">⤫</span></p>
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
        <button>Send</button>
    </form>
    <?php }
        echo !$thread_done ? '' : '<p>As the topic has been marked as answered by the topic starter, it is closed for further comments.</p>'; ?>

</main>

<script>
/* reply to another comment => set up the form with the right values*/
function reply() {
    event.preventDefault();
    const name = event.target.parentNode.parentNode.parentNode.querySelector('h4>a').textContent;
    /* Set hiden input fields */
    document.querySelector("input[name='reply_uuid']").value = event.target.dataset.id;
    document.querySelector("input[name='reply_comment']").value = event.target.dataset.comment_id;
    /* Set name if user that is being answered */
    document.querySelector(".comment_form .answering").textContent = 'Answering' + name;
    document.querySelector(".comment_form .answering").classList.add("att");
    document.querySelector(".comment_form .close").classList.remove("hide");
    /* Reset form to not answer a user */
    document.querySelector(".comment_form .close").addEventListener("click", () => {
        document.querySelector(".comment_form .close").classList.add("hide");
        document.querySelector(".comment_form .answering").textContent = "Write comment";
        document.querySelector(".comment_form .answering").classList.remove("att");
        document.querySelector("input[name='reply_comment']").value = "";
        document.querySelector("input[name='reply_uuid']").value = "";
    })
    /* Scroll to form */
    document.querySelector(".comment_form").scrollIntoView({
        behavior: "smooth",
        block: 'end',
    })
}

function scrollToPost() {
    /* scroll to the commment that is related to clicked button */
    if (document.querySelector(`button[data-comment_id="${event.target.dataset.comment_id}"]`)) {
        const relation = document.querySelector(`button[data-comment_id="${event.target.dataset.comment_id}"]`).parentNode.parentNode.parentNode;
        relation.scrollIntoView({
            behavior: "smooth",
            block: 'center',
        })
        /* add animation to the comment */
        relation.classList.add('bg_color_animation');
        setTimeout(() => {
            relation.classList.remove('bg_color_animation');

        }, 4000);
    } else {
        alert('Post was deleted.')
    }
}
</script>

<?php require_once(__DIR__ . '/bottom.php');