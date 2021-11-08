<?php
require_once(__DIR__.'/top.php');

ini_set('session.cookie_httponly', 1);
?>

<main class="signin">




    <form action="/login"
        method="POST"
        class="login_form">
        <?php
  if( isset($display_error) ){
       echo '<p class="url_decode sign_in_up">'.urldecode($display_error).'</p>';
    }
?>
        <label>Email
            <input type="text"
                placeholder="Email"
                name="user_email" />
        </label>
        <label>Password
            <input type="text"
                placeholder="Password"
                name="password" />
        </label>
        <button>Log in</button>
        <p>Go to <a href="/signup">sign up</a></p>
    </form>

</main>
<?php 
require_once(__DIR__.'/bottom.php');