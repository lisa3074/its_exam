<?php
require_once(__DIR__.'/default_top.php');
require_once(__DIR__.'/nav.php');

?>

<main class="signup">



    <form action="/signup"
        method="POST"
        class="signup_form">
        <?php
  if( isset($display_error) ){
   echo '<p class="url_decode sign_in_up">'.urldecode($display_error).'</p>';
    }

?>
        <label>First name
            <input type="text"
                placeholder="First name"
                name="firstname" />
        </label>
        <label>Last name
            <input type="text"
                placeholder="last name"
                name="lastname" />
        </label>
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
        <button>Sign up</button>
        <p>Go to <a href="/login">log in</a></p>
    </form>

</main>
<?php
require_once(__DIR__.'/bottom.php');