<?php
if(!isset($_SESSION)){
    session_start();
}
require_once(__DIR__.'/../db.php');


try{
    if(!isset($category) && !isset($genre)){
        $q = $db->prepare('SELECT *
                    FROM display_event
                    ORDER BY event_time asc; '); 
}   else if($category == 'all' && $genre == 'all'){
        $q = $db->prepare('SELECT *
                    FROM display_event
                    ORDER BY event_time asc; '); 
    }else if($genre == 'all'){
        $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_category = :category
                        ORDER BY event_time asc;'); 
        $q->bindValue(':category', $category);
    }else if($category == 'all'){
       $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_genre = :genre
                        ORDER BY event_time asc;'); 
        $q->bindValue(':genre', $genre);
    }else{
       $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_category = :category AND event_genre = :genre
                        ORDER BY event_time asc;'); 
        $q->bindValue(':category', $category);
        $q->bindValue(':genre', $genre);
    }

  $q->execute();
  $events = $q->fetchAll();

}catch(PDOException $ex){
  echo $ex;
}