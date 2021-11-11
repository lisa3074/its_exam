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
            <div class="thread_info">
                <p class="asked_by"><?= out($thread['firstname']) ?> <?= out($thread['lastname']) ?></p>

                <p class="asked_by status">
                    <?= $thread['thread_done'] ? '<span class="green">done</span>' : '<span class="green yellow">open</span>' ?>
                </p>
            </div>
        </article>
        <?php
        }
        ?>
    </article>
</section>