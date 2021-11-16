<?php
/* Set cookie and start session if not started already + make sure user is logged in */
require_once(__DIR__ . '/../bridges/bridge_go_to_start.php');

/* Compare if the session cookie is the same as the value of the hidden input field */
if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
    header('Location: /admin/You are not validated to edit the profile. Please log in again.');
    exit();
}
if (!$_POST['csrf'] == $_SESSION['csrf']) {
    header('Location: /admin/You are not validated to edit the profile. Please log in again.');
    exit();
}

/* check if fileupload exixts */
if (!isset($_FILES['fileToUpload'])) {
    echo 'there\'s no file set';
    echo 'File: ' . $_FILES['fileToUpload'];
    exit();
}
/* Is this image not being uploaded for an event? */
if (!isset($event) || !$event) {
    //Is session id the same as profile user id?
    if ($user_id != $_SESSION['uuid']) {
        http_response_code(400);
        header('Location: /admin/You do not have permission to upload a picture on another user\'s profile.');
        exit();
    }
    if (!isset($_POST['submit'])) {
        echo '$_POST submit is not set';
        exit();
    }
}

/* Variables */
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$image_type = mime_content_type($_FILES['fileToUpload']['tmp_name']);
$extension = strrchr($image_type, '/');
$extension = strtolower(ltrim($extension, '/'));
$valid_extensions = ['png', 'jpg', 'jpeg', 'gif'];
$random_number = bin2hex(random_bytes(16));
$random_image_name = 'uploads/' . $random_number . '.' . $extension;


// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    $message = 'File is not an image. The file was not uploaded.';
    redirect(400, 'admin', $message);
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    $message = 'Sorry, file already exists. The file was not uploaded.';
    redirect(400, 'admin', $message);
    exit();
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $uploadOk = 0;
    $message = 'Sorry, your file is too large. The file was not uploaded.';
    redirect(400, 'admin', $message);
    exit();
}

// Allow certain file formats
if (!in_array($extension, $valid_extensions)) {
    $message = 'This is not a valid extension. The file was not uploaded.';
    $uploadOk = 0;
    redirect(400, 'admin', $message);
    exit();
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    http_response_code(400);
} else {
    // if everything is ok, try to upload file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $random_image_name)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        if (!isset($event) || !$event) {
            require_once(__DIR__ . '/../apis/api_post_image.php');
            exit();
        }
        /* Set session variables, so we can pass the inputs to api_add_event.php */
        $_SESSION['event_title'] = $_POST['event_title'];
        $_SESSION['event_time'] = $_POST['event_time'];
        $_SESSION['event_desc'] = $_POST['event_desc'];
        $_SESSION['event_ticket_link'] = $_POST['event_ticket_link'];
        $_SESSION['event_category'] = $_POST['event_category'];
        $_SESSION['event_genre'] = $_POST['event_genre'];
        $_SESSION['event_image_credits'] = $_POST['event_image_credits'];
        header("Location: /event/upload/$random_number/$extension");
        exit();
    } else {
        http_response_code(400);
    }
}
exit();

/* FUnction that redirects user and gives an http reponse code */
function redirect($error, $page, $message)
{
    http_response_code($error);
    header("Location: /$page/$message");
    exit();
}