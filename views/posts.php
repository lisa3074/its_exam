<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../apis/api_display_comments.php');

if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}
?>


<main class="admin_main">

    <?php
    if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php
        // Use out to sanitize the read from the db, to prevent XXS 
        echo urldecode($display_message);
    } ?>
    </p>
    <button onclick="history.back()">Back</button>
    <section class="comment_wrapper">
        <article>
            <?php
                foreach ($comments as $key => $comment) {
                    $isMe = false;
                    if (isset($_SESSION['uuid'])) {
                        $isMe = $comment['user_id'] == $_SESSION['uuid'];
                    }
                    $comment_id = $comment['comment_id'];
                    $user_id = $comment['user_id'];
                ?>
            <h1 class="heading"><?= $key === 0 ? 'Question:' : '' ?><?= $key === 1 ? 'Answers:' : '' ?> </h1>
            <div class="comment <?= $isMe ? 'me' : '' ?> <?= $key === 0 ? 'question' : '' ?>">
                <div class="flex">
                    <h4>
                        <!-- Make only logged in users able to visit other profiles (not event organizers) -->
                        <?= isset($_SESSION['uuid']) ? ($_SESSION['privilige'] != '2' ? "<a href='/user/$user_id'>" : '<a>') : '<a>' ?>
                        <?= $isMe ? 'You' : out($comment['firstname']) . ' ' . out($comment['lastname']); ?>
                        </a>

                    </h4>
                    <p><?= $comment['comment_ts'] ?></p>
                </div>
                <!-- Use out to sanitize the read from the db, to prevent XXS -->
                <p class="<?= $key === 0 ? 'bold' : '' ?>"><?= out($decrypted_comments[$key]); ?></p>
                <?= $isMe ? "<form action='/admin/delete/$comment_id/$user_id' method='POST'><button>Delete</button></form>" : '' ?>
            </div>
            <?php
                }
                ?>
        </article>
    </section>

    <!-- only show form if user is logged in -->
    <?php
        if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2') {
        ?>
    <form action="/posts/comment/<?= $thread_id ?>"
        method="POST">
        <!-- Hidden input field to prevent CSRF with value that coresponds to the session['csrf] -->
        <input type="hidden"
            name="csrf"
            value="<?= $_SESSION['csrf'] ?>">
        <textarea name="comment_text"
            id="comment"
            placeholder="Write a comment"></textarea>
        <button>Send</button>
    </form>
    <?php }
        ?>

</main>


<?php
require_once(__DIR__ . '/bottom.php');