<?php
if(!isset($_SESSION)){
    session_start();
}
require_once(__DIR__.'/../db.php');


try{
    if(! isset($category)){
        $q = $db->prepare('SELECT *
                    FROM display_event
                    ORDER BY event_time asc; '); 
    }else{
        $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_category = :category 
                        ORDER BY event_time asc;'); 
        $q->bindValue(':category', $category);
    }

  $q->execute();
  $events = $q->fetchAll();

}catch(PDOException $ex){
  echo $ex;
}