    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $loggedIn = 'top_nav';
    if (isset($_SESSION['uuid'])) {
        $loggedIn = 'top_nav loggedIn';
    }
    ?>
    <nav class="<?= $loggedIn ?>">

        <?php
        //Only show greeting if user is logged in.
        if (isset($_SESSION['uuid'])) {
            echo "<p><a href='/admin'>Welcome back {$_SESSION['firstname']}</a></p>";
        }
        ?>
        <div class="navigation">
            <ul>
                <li><a href="/events">Events</a></li>
                <li><a href="/forum">Forum</a></li>
                <?php
                if (!isset($_SESSION['uuid'])) {
                ?>
                <li><a href="/signup">Signup</a></li>
                <li><a href="/login">Login</li></a>
                <?php
                }
                ?>
            </ul>
            <?php
            //Only show logout if user is logged in.
            if (isset($_SESSION['uuid'])) {
                echo '<form action="/logout"><button>Logout</button></form>';
            }
            ?>

        </div>
    </nav>