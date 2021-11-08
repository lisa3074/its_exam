<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__.'./../cookie_config.php');
require_once(__DIR__.'/../apis/api_display_threads.php');


?>
<main class="threads_wrapper">

    <section class="threads">
        <h3>Threads</h3>
        <article>
            <?php
    foreach ($threads as $thread) {
        
        ?>
            <article class="thread"
                onclick="setComments(<?= $thread['thread_id']  ?>)">
                <p class="bold question"><?=$thread['thread_name']?></p>
                <p class="asked_by">Asked by: <?=$thread['firstname']?> <?=$thread['lastname']?></p>
            </article>
            <?php
    }
    ?>
        </article>
    </section>
</main>

<script>
function setComments(threadId) {
    console.log(threadId)
    window.location.href = `/posts/${threadId}`;
}
</script>

<?php
require_once(__DIR__ . '/bottom.php');