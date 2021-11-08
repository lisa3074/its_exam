<?php

    session_start();
    session_destroy();
    header('Location: /login/You are now logged out');
    exit();