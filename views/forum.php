<?php
/* import modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
?>

<main class="threads_wrapper admin_main">
    <!-- Check if user is logged in and has the right privilege to see button -->
    <?php if (isset($_SESSION['privilege']) && (($_SESSION['privilege'] == '1') || ($_SESSION['privilege'] == '3'))) { ?>
    <button onclick="goToBottom()">Add new question</button>
    <?php }

    /* display message from url if any */
    if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php echo urldecode($display_message);
    } ?>
    </p>


    <!-- TOPICS MODULE -->
    <?php require_once(__DIR__ . '/topics.php');
        /* Check if user is logged in and has the right privileges to see the form*/
        if (isset($_SESSION['privilege']) && ($_SESSION['privilege'] == '1' || $_SESSION['privilege'] == '3')) {  ?>
    <!-- the data-validate attribute is a condition in validator.js that is called on submit for frontend validation -->
    <form onsubmit="return validate()"
        method="POST"
        action="/forum">

        <!-- CSRF - Hidden input field to prevent CSRF with value that coresponds to the session['csrf] -->
        <input type="hidden"
            name="csrf"
            value="<?= $_SESSION['csrf'] ?>">

        <!-- QUESTION -->
        <div class="thread_question">
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
    <?php } ?>
</main>

<script>
//Go to the topic that was clicked (from the topics module)
function setComments() {
    const threadId = event.target.dataset.id;
    console.log(event.target)
    window.location.href = `/posts/${threadId}`;
}
//Scroll form to write a queston
function goToBottom() {
    const question = document.querySelector('#thread_question');
    question.scrollIntoView({
        behavior: "smooth"
    })
}
</script>

<?php require_once(__DIR__ . '/bottom.php');