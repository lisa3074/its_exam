<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../apis/api_display_my_comments.php');
require_once(__DIR__ . '/../db.php');

if (!isset($_SESSION)) {
    require_once(__DIR__ . './../cookie_config.php');
    session_start();
}
try {
    $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
    $q->bindValue(':uuid', $_SESSION['uuid']);
    $q->execute();
    $user = $q->fetch();

    $q3 = $db->prepare('SELECT * FROM thread WHERE thread_owner_id = :uuid LIMIT 1');
    $q3->bindValue(':uuid', $_SESSION['uuid']);
    $q3->execute();
    $threads = $q3->fetchAll();
} catch (PDOException $ex) {
    echo $ex;
}

?>

<main class="admin_main">

    <?php
    if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php
        // Use out to sanitize the read from the db, to prevent XXS 
        echo out(urldecode($display_message));
    } ?>
    </p>
    <?php

        if (isset($note)) {
        ?>
    <p>Saved note:
        <?php
            echo urldecode($note);
        } ?>
    </p>

    <article class="profile_info">
        <h3>Profile information:</h3>
        <p class="display-6">Name: <?= $user['firstname'] ?> <?= $user['lastname'] ?> </p>
        <p class="text-muted links">
            <a href="mailto:<?= $user['email'] ?>"><?= $user['email'] ?></a>
            <?php if ($user['user_link']) { ?> |
            <a href="http://<?= $user['user_link'] ?>"
                target="_blank"><?= $user['user_link'] ?></a><?php } ?>
        </p>

        <p>Image: <?= $user['user_image'] ?></p>
        <p>Description: <?= $user['user_description'] ?></p>

    </article>



    <button class="toggle_profile_edit"
        onclick="toggleProfileEdit()">Edit profile</button>

    <article class="edit_profile">
        <?php require_once(__DIR__ . '/edit_profile.php') ?>
    </article>

    <?php
            if (isset($_SESSION['uuid']) && $_SESSION['privilige'] != '2') {
            ?>
    <article class="user_questions">
        <h3>Active questions in the forum</h3>
        <?php
                    if (!$threads) {
                        echo '<article class="question_profile">
            <p>You have no questions yet.</p>
            </article>';
                    }
                    foreach ($threads as $thread) {
                    ?>
        <article class="question_profile"
            onclick="setComments(<?= $thread['thread_id']  ?>)">
            <div>
                <p class="bold"><?= $thread['thread_name'] ?></p>
                <p>Asked <?= $thread['thread_time'] ?></p>
            </div>
            <button>Mark as answered</button>
        </article>
        <?php
                    }
                    ?>
    </article>


    <article class="latest_activity">
        <h3>Latest commments</h3>
        <?php
                    if (!$comments) {
                        echo '<article class="activity">
                                <p>You have no comments yet.</p>
                                </article>';
                    }
                    foreach ($comments as $key => $comment) {
                        if (isset($_SESSION['uuid'])) {
                            $isMe = $comment['user_id'] == $_SESSION['uuid'];
                        }
                    ?>
        <article onclick="goToComment(<?= $comment['thread_id'] ?>)"
            class="activity">
            <div>
                <p class="bold"><?= out($decrypted_comments[$key]); ?></p>
                <p>Asked <?= $comment['comment_ts'] ?></p>
            </div>
            <?= $isMe ? "<form action='/admin/delete/{$comment['comment_id']}/{$comment['user_id']}' method='POST'><button>Delete</button></form>" : '' ?>
        </article>
        <?php
                    }
                }

                ?>
    </article>

</main>

<script>
function setComments(threadId) {
    console.log(threadId)
    window.location.href = `/posts/${threadId}`;
}

function goToComment(threadId) {
    console.log(threadId)
    window.location.href = `/posts/${threadId}`;
}

function toggleProfileEdit() {
    console.log("toggleProfileEdit")
    const button = document.querySelector(".toggle_profile_edit");
    if (button.textContent === "Edit profile") {
        button.textContent = "Close Edit profile";
    } else {
        button.textContent = "Edit profile";
    }
}
</script>


<?php
require_once(__DIR__ . '/bottom.php');