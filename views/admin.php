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

<!-- MAIN PAGE -->
<main class="admin_main <?= $user['privilige'] != '2' ? 'user_admin' : '' ?>">



    <!-- IMAGE -->
    <div class="profile_picture <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>">
        <label for="fileToUpload"
            title="Click to edit your profile picture">
            <img class="previewImg"
                data-src="/uploads/<?= $user['user_image'] ?>"
                src="/uploads/<?= $user['user_image'] ?>"></label>
        <div class="icon_wrapper <?= $user['privilige'] == '2' ? '' : 'user_admin' ?>">
            <label class="submit_image hide <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>"
                for="submit_image">
                <div class="save"><img src="/static/images/save_black_24dp.svg"></div>
            </label>
            <div class="submit_image cancel hide <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>"
                data-type="cancel">
                <img src="/static/images/close_black_24dp.svg">
            </div>
        </div>
    </div>


    <!-- HIDDEN FILE INPUT IMAGE -->
    <form action="/admin/image/<?= $user['uuid'] ?>"
        method="post"
        class="hide"
        enctype="multipart/form-data">

        <!-- Check for client side request forgery -->
        <input type="hidden"
            name="csrf"
            value=<?= $_SESSION['csrf'] ?>>

        <label for="fileToUpload"></label>
        <input type="file"
            name="fileToUpload"
            id="fileToUpload"
            onchange="preview()">

        <input type="submit"
            name="submit"
            class="hide"
            id="submit_image"></input>
    </form>

    <!-- EDIT PROFILE -->
    <article class="edit_profile hide">
        <?php require_once(__DIR__ . '/edit_profile.php') ?>
    </article>

    <article class="user_container">

        <?php
        if (isset($display_message)) { ?>
        <p class="url_decode admin">
            <?php
            echo urldecode($display_message);
        } ?>
        </p>
        <!-- PROFILE INFO -->
        <div class="user_info <?= $user['privilige'] == '2' ? 'organizer' : 'user_admin' ?>">
            <div class="flex_heading">
                <p class="profile_name"><?= out($user['firstname']) ?> <?= out($user['lastname']) ?> </p>
                <button class="toggle_profile_edit"
                    onclick="editProfile()">Edit profile</button>
            </div>
            <p class="text-muted links">
                <a href="mailto:<?= out($user['email']) ?>"><?= out($user['email']) ?></a>
                <?php if ($user['user_link']) { ?> |
                <a href="http://<?= out($user['user_link']) ?>"
                    target="_blank"><?= out($user['user_link']) ?></a><?php } ?>
            </p>
            <p class="profile_desc"><?= $user['user_description'] == '' ? 'Write a description others can read...' : out($user['user_description']) ?>
            </p>
        </div>

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
                <p class="bold"><?= out($thread['thread_name']) ?></p>
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

function editProfile() {
    console.log("editProfile")
    document.querySelector(".edit_profile").classList.remove("hide");
    document.querySelector(".user_info").classList.add("hide");
    document.querySelector(".close_cancel_btn").textContent = 'Cancel';
}

function showProfile() {
    location.reload()
}

function changeBtnText() {
    document.querySelector("#description").textContent = 'Close edit';
}
</script>


<?php
require_once(__DIR__ . '/bottom.php');