<?php
require_once(__DIR__ . '/../apis/api_display_threads.php');
?>
<section class="threads">
    <h3>Topics</h3>
    <article>
        <?php
        foreach ($threads as $thread) {

        ?>
        <article class="thread"
            onclick="setComments('<?= $thread['thread_id'] ?>')">
            <p class="bold question"><?= out($thread['thread_name']) ?></p>

            <p class="asked_by">Asked by: <?= out($thread['firstname']) ?>
                <?= out($thread['lastname']) ?><?= $thread['thread_done'] ? ' <span class="green">ANSWERED</span>' : '' ?></p>
            </p>
            <p class="asked_by done">
        </article>
        <?php
        }
        ?>
    </article>
</section>