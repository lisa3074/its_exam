<?php
/* import modules */
require_once(__DIR__ . '/../cookie_config.php');
require_once(__DIR__ . '/../db.php');

/* Get events that has the same category and genre the user has chosen to filter by */
try {
  if (!isset($category) && !isset($genre)) {
    $q = $db->prepare('SELECT *
                    FROM display_event
                    ORDER BY event_time asc; ');
  } else if ($category == 'all' && $genre == 'all') {
    $q = $db->prepare('SELECT *
                    FROM display_event
                    ORDER BY event_time asc; ');
  } else if ($genre == 'all') {
    $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_category = :category
                        ORDER BY event_time asc;');
    $q->bindValue(':category', $category);
  } else if ($category == 'all') {
    $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_genre = :genre
                        ORDER BY event_time asc;');
    $q->bindValue(':genre', $genre);
  } else {
    $q = $db->prepare('SELECT *
                        FROM display_event
	                    WHERE event_category = :category AND event_genre = :genre
                        ORDER BY event_time asc;');
    $q->bindValue(':category', $category);
    $q->bindValue(':genre', $genre);
  }

  $q->execute();
  if (!$q->rowCount()) {
    header('Location: /events/No event mached your search');
    exit();
  }
  $events = $q->fetchAll();
} catch (PDOException $ex) {
  echo $ex;
  http_response_code(400);
  exit();
}
