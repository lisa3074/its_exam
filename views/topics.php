<?php require_once(__DIR__ . '/../apis/api_display_threads.php'); ?>
<section class="threads">
    <h3>Topics</h3>
    <article>
        <!-- TOPIC LIST -->
        <?php foreach ($threads as $thread) { ?>
        <!-- If user is not logged in, topics are not clickable -->
        <!-- Use out() to escape special characters (avoid XSS)-->
        <article class="thread"
            title="<?= !isset($_SESSION['uuid']) ? 'Log in to view the thread' : '' ?>"
            data-id="<?= out($thread['thread_id']) ?>"
            onclick="<?= !isset($_SESSION['uuid']) ? '' : 'setComments(this)' ?>">
            <p class="bold question no_pointer2"><?= out($thread['thread_name']) ?></p>
            <div class="thread_info no_pointer2">
                <p class="asked_by no_pointer2"><?= out($thread['firstname']) ?> <?= out($thread['lastname']) ?></p>
                <p class="asked_by status no_pointer2">
                    <?= $thread['thread_done'] ? '<span class="green no_pointer2">done</span>' : '<span class="green no_pointer2 yellow">open</span>' ?>
                </p>
            </div>
        </article>
        <?php } ?>
    </article>
</section>