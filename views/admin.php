<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . '/../db.php');
require_once(__DIR__ . '/../apis/api_display_my_comments.php');

if(!isset($_SESSION)) { 
       require_once(__DIR__.'./../cookie_config.php');
        session_start(); 
    } 
try{
  $q = $db->prepare('SELECT * FROM user WHERE uuid = :uuid LIMIT 1');
  $q->bindValue(':uuid', $_SESSION['uuid']);
  $q->execute();
  $user = $q->fetch();


/*    $q2 = $db->prepare('SELECT * FROM display_comments
                    WHERE user_id = :uuid
                    ORDER BY comment_ts asc');
  $q2->bindValue(':uuid', $_SESSION['uuid']);
  $q2->execute();
  $comments = $q2->fetchAll(); */


  $q3 = $db->prepare('SELECT * FROM thread WHERE thread_owner_id = :uuid LIMIT 1');
  $q3->bindValue(':uuid', $_SESSION['uuid']);
  $q3->execute();
  $threads = $q3->fetchAll();

  
}catch(PDOException $ex){
  echo $ex;
}

?>

<main class="admin_main">

    <?php
if (isset($display_message)) {?>
    <p class="url_decode admin">
        <?php
    // Use out to sanitize the read from the db, to prevent XXS 
  echo out(urldecode($display_message));
}?>
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
        <p>Name: <?= $user['firstname']?> <?= $user['lastname']?></p>
        <p>Email: <?= $user['email']?></p>
        <p>Image: <?= $user['user_image']?></p>
    </article>


    <article class="user_questions">
        <h3>Active questions in the forum:</h3>
        <?php
foreach($threads as $thread){
    ?>
        <article class="question_profile"
            onclick="setComments(<?= $thread['thread_id']  ?>)">
            <div>
                <h4><?=$thread['thread_name']?></h4>
                <p>Asked <?=$thread['thread_time']?></p>
            </div>
            <button>Mark as answered</button>
        </article>
        <?php
}
?>
    </article>


    <article class="latest_activity">

        <h3>Latest commments:</h3>
        <?php
    foreach ($comments as $key => $comment) {
        if(isset($_SESSION['uuid'])){
            $isMe = $comment['user_id'] == $_SESSION['uuid'];
        }
        ?>
        <article onclick="goToComment(<?= $comment['thread_id']  ?>)"
            class="activity">
            <div>
                <p class="bold"><?= out($decrypted_comments[$key]); ?></p>
                <p>Asked <?=$comment['comment_ts']?></p>
            </div>
            <?= $isMe ? "<form action='/admin/delete/{$comment['comment_id']}/{$comment['user_id']}' method='POST'><button>Delete</button></form>" : ''?>
        </article>
        <?php
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
</script>


<?php
  require_once(__DIR__ . '/bottom.php');