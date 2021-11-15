<?php
/* import modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
?>

<main class="signin">
    <!-- the data-validate attribute is a condition in validator.js that is called on submit for frontend validation -->
    <form action="/login" method="POST" onsubmit="return validate()" class="login_form">
        <!-- display message from url if any  -->
        <?php if (isset($display_error)) {
            echo '<p class="url_decode sign_in_up">' . urldecode($display_error) . '</p>';
        } ?>

        <!-- EMAIL -->
        <div class="email">
            <label>Email
                <input type="text" placeholder="name@example.com" name="user_email" id="email" data-validate="email" onkeyup="clear_parent_error(this)" />
            </label>
            <div class="invalid-feedback">
                You need at to enter a valid email.
            </div>
        </div>

        <!-- PASSWORD -->
        <div class="password">
            <label>Password
                <input type="text" placeholder="Password" name="password" data-validate="match" onkeyup="clear_parent_error(this)" />
            </label>
            <div class="invalid-feedback">
                You need at least 8 characters, 1 uppercase, 1 lowercase, 1 digit and max 50 characters.
            </div>
        </div>

        <button>Log in</button>
        <p>Go to <a href="/signup">sign up</a></p>
    </form>
</main>

<?php require_once(__DIR__ . '/bottom.php');
