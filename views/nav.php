    <?php
    require_once(__DIR__ . '/../cookie_config.php');

    $loggedIn = 'top_nav';

    /* If user is logged in */
    if (isset($_SESSION['uuid'])) {
        $loggedIn = 'top_nav loggedIn';
        /* Use htmlspecialchars() to escape special characters (avoid XSS) */
        $name = htmlspecialchars($_SESSION['firstname']);
    }
    ?>

    <nav class="<?= $loggedIn ?>">
        <?php //Only show greeting if user is logged in.
        if (isset($name)) {
            echo "<p><a href='/admin'>$name | PROFILE</a></p>";
        }  ?>

        <div class="navigation">
            <ul>
                <li><a href="/events/all/all">Events</a></li>
                <li><a href="/forum">Forum</a></li>
                <!-- Only show if user is not logged in -->
                <?php if (!isset($_SESSION['uuid'])) { ?>
                <li><a href="/signup">Signup</a></li>
                <li><a href="/login">Login</li></a>
                <?php } ?>
            </ul>
            <?php //Only show logout if user is logged in.
            if (isset($_SESSION['uuid'])) {
                echo '<form action="/logout"><button>Logout</button></form>';
            } ?>
        </div>
    </nav>