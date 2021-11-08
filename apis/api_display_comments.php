<?php
if(!isset($_SESSION)){
  require_once(__DIR__.'./../cookie_config.php');
    session_start();
}
require_once(__DIR__.'/../db.php');


if(!isset($_SESSION['uuid'])){
    header('Location: /You need to log in');
     exit();
}
try{
  $q = $db->prepare('SELECT firstname, lastname, comments.comment_ts, comments.comment_text, comments.comment_iv, comments.user_id, comments.comment_id
                    FROM user
	                LEFT JOIN comments ON user.uuid = comments.user_id
	                WHERE comment_text != :nothing 
                    ORDER BY comments.comment_ts asc;
                    ');
$q->bindValue(':nothing', '');
  $q->execute();
  $comments = $q->fetchAll();

 

 //encrypting with CBC
$alg='AES-128-CBC';
//$ciphertext = $comments['comment_text'];
$key="47062c85f9b1a4d27f50717951f58fa0";
$decrypted_comments = [];
for($i = 0; $i < count($comments); $i++){
 
    $comment=openssl_decrypt($comments[$i]['comment_text'], $alg, $key, 0, $comments[$i]['comment_iv']);
  

    array_push($decrypted_comments, $comment);
} 


/* if(!$comments){
   echo "There's no comments yet. Go write the first one...";
}  */

// exit(); 
}catch(PDOException $ex){
  echo $ex;
}