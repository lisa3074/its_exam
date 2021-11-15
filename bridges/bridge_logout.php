<?php
/* destroy session on logout */
session_start();
session_destroy();
header('Location: /login/You are now logged out');
exit();
