<?php
if(!isset($_SESSION)){
    session_start();
}

if(!isset($_SESSION['uuid'])){
    header('Location: /events');
    exit();
}