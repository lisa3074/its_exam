<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . '/../apis/api_display_threads.php');


?>
<main class="threads_wrapper">
    <?php
    if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2') {
    ?>
    <button onclick="goToBottom()">Add new question</button>
    <?php }

    if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php
        // Use out to sanitize the read from the db, to prevent XXS 
        echo out(urldecode($display_message));
    } ?>
    </p>
    <section class="threads">
        <h3>Threads</h3>
        <article>
            <?php
                foreach ($threads as $thread) {

                ?>
            <article class="thread"
                onclick="setComments('<?= $thread['thread_id'] ?>')">
                <p class="bold question"><?= $thread['thread_name'] ?></p>
                <p class="asked_by">Asked by: <?= $thread['firstname'] ?> <?= $thread['lastname'] ?></p>
            </article>
            <?php
                }
                ?>
        </article>
    </section>
    <?php
        if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2') {
        ?>
    <form onsubmit="return validate()"
        method="POST"
        action="/forum">
        <div class="thread_question">
            <!-- Hidden input field to prevent CSRF with value that coresponds to the session['csrf] -->
            <input type="hidden"
                name="csrf"
                value="<?= $_SESSION['csrf'] ?>">
            <label for="thread_question">
                <textarea placeholder="Add your question to create a thread..."
                    id="thread_question"
                    name="thread_question"
                    data-validate="str"
                    data-min="20"
                    data-max="10000"
                    onkeyup="clear_parent_error(this)"></textarea>
            </label>
            <div class="invalid-feedback">
                Your question needs to be at least 20 characters
            </div>
        </div>
        <button>Add question</button>
    </form>
    <?php }
        ?>
</main>

<script>
function setComments(threadId) {
    console.log(threadId)
    window.location.href = `/posts/${threadId}`;
}

function goToBottom() {
    const question = document.querySelector('#thread_question');
    question.scrollIntoView({
        behavior: "smooth"
    })
}
</script>

<?php
require_once(__DIR__ . '/bottom.php');