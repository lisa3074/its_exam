<?php
/* import modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
?>

<main class="signup">
    <!-- the data-validate attribute is a condition in validator.js that is called on submit for frontend validation -->
    <form action="/signup" method="POST" onsubmit="return validate()" class="signup_form">
        <!-- Set message from url if any -->
        <?php if (isset($display_error)) {
            echo '<p class="url_decode sign_in_up">' . urldecode($display_error) . '</p>';
        } ?>

        <!-- ACCOUNT TYPE -->
        <div class="form_wrapper first">
            <p>What kind of account do you want to create?</p>

            <!-- REGULAR USER -->
            <div class="form-check">
                <input type="radio" name="account" id="regular" value=1 onchange="checkAccount()" checked>
                <label for="regular">
                    Regular user
                </label>
            </div>

            <!-- EVENT ORGANIZER -->
            <div class="form-check">
                <input type="radio" name="account" id="organizer" value=2 onchange="checkAccount()">
                <label for="orgaziner">
                    Event organizer
                </label>
            </div>

            <!-- ADMINISTRATOR -->
            <div class="form-check">
                <input type="radio" name="account" id="admin" value=3 onchange="checkAccount()">
                <label for="admin">
                    Administrator
                </label>
            </div>

            <!-- ADMINISTRATOR KEY -->
            <div class="admin_account hide">
                <div>
                    <input type="text" class="form-control" placeholder="Administrator key" name="key" id="key" onkeyup="clear_parent_error(this)" data-validate="admin_key" />
                    <label for="key">Admin key</label>
                    <div class="invalid-feedback">
                        The key was not correct.
                    </div>
                </div>
                <p class="subnote"><img class="admin_info" src="/static/images/info_black_24dp.svg" alt="info"> If you have an admin key, you can sign up as an admin.</p>
            </div>

            <!-- FIRSTNAME -->
            <div class="firstname">
                <label>First name
                    <input type="text" placeholder="First name" name="firstname" id="name" data-validate="str" data-min="2" data-max="30" onkeyup="clear_parent_error(this)" />
                </label>
                <div class="invalid-feedback">
                    You need at least 2 charachters in your name and no more than 30.
                </div>
            </div>

            <!-- LASTNAME -->
            <div class="lastname">
                <label>Last name
                    <input type="text" placeholder="last name" name="lastname" id="lastname" data-validate="str" data-min="2" data-max="30" onkeyup="clear_parent_error(this)" />
                </label>
                <div class="invalid-feedback">
                    You need at least 2 charachters in your name and no more than 30.
                </div>
            </div>

            <!-- EMAIL -->
            <div class="email">
                <label>Email
                    <input type="text" placeholder="Email" name="user_email" data-validate="email" id="email" onkeyup="clear_parent_error(this)" />
                </label>
                <div class="invalid-feedback">
                    You need at to enter a valid email.
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="password">
                <label>Password
                    <input type="text" placeholder="Password" name="password" id="password" data-match-name="password_match" data-validate="match" onkeyup="clear_parent_error(this)" />
                </label>
                <div class="invalid-feedback">
                    The passwords doesn't match.
                </div>

                <!-- PASSWORD MATCH -->
                <label>Match password
                    <input type="text" placeholder="Match password" name="password_match" id="password_match" onkeyup="clear_sibling_error(this)" />
                </label>
            </div>
            <button>Sign up</button>
            <p>Go to <a href="/login">log in</a></p>
    </form>
</main>

<?php require_once(__DIR__ . '/bottom.php');
