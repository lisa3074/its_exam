<?php 
try{
    $q1 = $db->prepare("UPDATE user SET logger=$set_new_logger WHERE email = :user_email");
    $q1->bindValue(':user_email', $_POST['user_email']);
    $q1->execute();
    $q2 = $db->prepare("UPDATE user SET logged_time = $currentTime WHERE email = :user_email");
    $q2->bindValue(':user_email', $_POST['user_email']);
    $q2->execute();

}catch(PDOException $ex){
    echo $ex;
}