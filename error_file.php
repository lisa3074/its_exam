<?php
if (!isset($_SESSION)) {
    session_start();
}

//User is not logged in
if (!isset($_SESSION['uuid'])) {
    http_response_code(400);
    exit();
}

if (isset($_FILES['fileToUpload'])) {
    //Is session id the same as profile user id?
    if ($user_id != $_SESSION['uuid']) {
        http_response_code(400);
        header('Location: /admin/You do not have permission to upload a picture on another user\'s profile.');
        exit();
    }
}

if (isset($_FILES['fileToUpload'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
}


$uploadOk = 1;
if (isset($_FILES['fileToUpload'])) {
    $image_type = mime_content_type($_FILES['fileToUpload']['tmp_name']);
}

$extension = strrchr($image_type, '/');
$extension = strtolower(ltrim($extension, '/'));
$valid_extensions = ['png', 'jpg', 'jpeg', 'gif'];
$random_number = bin2hex(random_bytes(16));

// Check if image file is a actual image or fake image
if (isset($_FILES["fileToUpload"])) {
    $random_image_name = 'uploads/' . $random_number . '.' . $extension;
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $message = 'File is not an image. The file was not uploaded.';
            redirect(400, 'admin', $message);
            $uploadOk = 0;
        }
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    $uploadOk = 0;
    $message = 'Sorry, file already exists. The file was not uploaded.';
    redirect(400, 'admin', $message);
}

// Check file size
if (isset($_FILES["fileToUpload"])) {
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $uploadOk = 0;
        $message = 'Sorry, your file is too large. The file was not uploaded.';
        redirect(400, 'admin', $message);
    }
}

// Allow certain file formats
if (!in_array($extension, $valid_extensions)) {
    //  $message = 'This is not a valid extension. The file was not uploaded.';
    $message = 'extension: ' . $extension . ' Valid extentions ' . $valid_extensions . ' File: ' . $_FILES['fileToUpload'];
    $uploadOk = 0;

    redirect(400, 'admin', $message);
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    http_response_code(400);
    // if everything is ok, try to upload file
} else {
    if (isset($_FILES["fileToUpload"])) {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $random_image_name)) {
            //echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            //header("Location: /admin/upload/$random_number/$extension/$user_id");
            if (!isset($_SESSION)) {
                session_start();
            }

            //if not signed in
            if (!isset($_SESSION['uuid'])) {
                http_response_code(400);
                exit();
            }
            //If logged in user is not user of this profile
            if ($user_id != $_SESSION['uuid']) {
                http_response_code(400);
                exit();
            }

            try {
                require_once(__DIR__ . '/../db.php');
                $q = $db->prepare("UPDATE user SET user_image=:user_image WHERE uuid = :uuid");
                $q->bindValue(':uuid', $_SESSION['uuid']);
                $q->bindValue(':user_image', $random_number . '.' . $extension);
                $q->execute();

                /*    if (isset($img)) {
                    header('Location: /admin/Your picture was updated!');
                    exit();
                } */
            } catch (PDOException $ex) {
                http_response_code(400);
                echo $ex;
            }
            exit();
        } else {
            http_response_code(400);
        }
    }
}
exit();

function redirect($error, $page, $message)
{
    http_response_code($error);
    header("Location: /$page/$message");
    exit();
}
?>
<!-- 
<script>
document.querySelector("#file_name").textContent = <?= $file ?>;
</script> -->