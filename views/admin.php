<?php
/* Require modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../apis/api_display_my_comments.php');
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . './../cookie_config.php');

/* if user is not logged in */
if (!isset($_SESSION['uuid'])) {
    header('Location: /event/error/You are not logged in. Please login to view your profile.');
    exit();
}

try {
    /* get logged in user */
    $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
    $q->bindValue(':uuid', $_SESSION['uuid']);
    $q->execute();
    $user = $q->fetch();
    /* get the topics this user has created in forum */
    $q1 = $db->prepare('SELECT * FROM thread WHERE thread_owner_id = :uuid LIMIT 1');
    $q1->bindValue(':uuid', $_SESSION['uuid']);
    $q1->execute();
    $threads = $q1->fetchAll();
} catch (PDOException $ex) {
    echo $ex;
} ?>

<!-- MAIN PAGE -->
<main class="admin_main <?= $user['privilige'] != '2' ? 'user_admin' : '' ?>">

    <!-- IMAGE PREVIEW AND LABEL FOR FILE INPUT-->
    <div class="profile_picture <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>">
        <label for="fileToUpload" title="Click to edit your profile picture">
            <img class="previewImg" data-src="/uploads/<?= $user['user_image'] ?>" src="/uploads/<?= $user['user_image'] ?>"></label>
        <div class="icon_wrapper <?= $user['privilige'] == '2' ? '' : 'user_admin' ?>">
            <label class="submit_image hide <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>" for="submit_image">
                <div class="save"><img src="/static/images/save_black_24dp.svg"></div>
            </label>
            <div class="submit_image cancel hide <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>" data-type="cancel">
                <img src="/static/images/close_black_24dp.svg">
            </div>
        </div>
    </div>


    <!-- HIDDEN FILE INPUT FOR IMAGE -->
    <form action="/admin/image/<?= $user['uuid'] ?>" method="post" class="hide" enctype="multipart/form-data">

        <!-- Check for cross site request forgery -->
        <input type="hidden" name="csrf" value=<?= $_SESSION['csrf'] ?>>

        <label for="fileToUpload"></label>
        <input type="file" name="fileToUpload" id="fileToUpload" onchange="preview()">

        <input type="submit" name="submit" class="hide" id="submit_image"></input>
    </form>

    <!-- EDIT PROFILE MODULE-->
    <article class="edit_profile hide">
        <?php require_once(__DIR__ . '/edit_profile.php') ?>
    </article>

    <!-- THE USER PROFILE-->
    <article class="user_container">

        <!-- Display message if one is sent along -->
        <?php if (isset($display_message)) { ?>
            <p class="url_decode admin">
            <?php
            echo urldecode($display_message);
        } ?>

            </p>
            <!-- USER INFO -->
            <div class="user_info <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>">
                <div class="flex_heading">
                    <p class="profile_name"><?= out($user['firstname']) ?> <?= out($user['lastname']) ?> </p>
                    <button class="toggle_profile_edit" onclick="editProfile()">Edit profile</button>
                </div>
                <p class="text-muted links">
                    <a href="mailto:<?= out($user['email']) ?>"><?= out($user['email']) ?></a>
                    <?php if ($user['user_link']) { ?> |
                        <a href="http://<?= out($user['user_link']) ?>" target="_blank"><?= out($user['user_link']) ?></a><?php } ?>
                </p>
                <p class="profile_desc"><?= $user['user_description'] == '' ? 'Write a description others can read...' : out($user['user_description']) ?>
                </p>
            </div>
    </article>

    <!-- if user is logeed in and user is admin or regular user -->
    <?php if (isset($_SESSION['uuid']) && ($_SESSION['privilige'] == '1' || $_SESSION['privilige'] == '3')) { ?>
        <!-- TOPICS LIST -->
        <article class="user_questions">
            <h3>Active questions in the forum</h3>
            <?php  /* if user have not posted a topic */
            if (!$threads) {
                echo '<article class="question_profile">
            <p>You have no questions yet.</p>
            </article>';
            }
            /* print out the topics this user has posted */
            foreach ($threads as $thread) {  ?>
                <article data-event_id="<?= $thread['thread_id'] ?>" onclick="goToComment(this)" class="question_profile">
                    <div class="no_pointer">
                        <p class="bold"><?= out($thread['thread_name']) ?></p>
                        <p>Asked <?= $thread['thread_time'] ?></p>
                    </div>
                    <!--  <button>Mark as answered</button> -->
                </article>
            <?php  }  ?>
        </article>

        <!-- COMMENTS LIST -->
        <article class="latest_activity">
            <h3>Latest commments</h3>
            <?php if (!$comments) { /* if the user has not written any comments */
                echo '<article class="activity">
                                <p>You have no comments yet.</p>
                                </article>';
            }
            /* print out the comments this user has made */
            foreach ($comments as $key => $comment) {
                if (isset($_SESSION['uuid'])) {
                    $isMe = $comment['user_id'] == $_SESSION['uuid'];
                } ?>
                <article data-event_id="<?= $comment['thread_id'] ?>" onclick="goToComment(this)" class="activity">
                    <div class="no_pointer">
                        <p class="bold"><?= out($decrypted_comments[$key]); ?></p>
                        <p>Sent <?= $comment['comment_ts'] ?></p>
                    </div>
                    <!-- check if logged in user has also written the comment. If yes make dele button visible -->
                    <?= $isMe ? "<form action='/admin/delete/{$comment['comment_id']}/{$comment['user_id']}' method='POST'><button>Delete</button></form>" : '' ?>
                </article>
        <?php }
        } ?>
        </article>
</main>

<script>
    //Go to the thread that was clicked (or the comment that was clicked)
    function goToComment() {
        const threadId = event.target.dataset.event_id;
        window.location.href = `/posts/${threadId}`;
    }

    //Show edit form, hide profile info
    function editProfile() {
        document.querySelector(".edit_profile").classList.remove("hide");
        document.querySelector(".user_container").classList.add("hide");
        document.querySelector(".close_cancel_btn").textContent = 'Cancel';
    }

    //If use cancels edit, prevent from posting and just reload page
    function showProfile(type) {
        if (type === 'cancel') {
            event.preventDefault();
        }
        setTimeout(() => {
            location.reload()
        }, 500);
    }
</script>

<?php require_once(__DIR__ . '/bottom.php');
