<?php
/* importing modules */
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . './../apis/api_display_events.php');
?>

<main class="admin_main">
    <!-- display message, if any -->
    <?php if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php echo urldecode($display_message);
    } ?>
    </p>
    <?php
        //Show add event only if a user is logged in and has the right privilege
        if (isset($_SESSION['privilege']) && ($_SESSION['privilege'] == '2')) { ?>

    <!-- ADD EVENT MODULE-->
    <section class='add_event_wrapper hide'>
        <?php require_once(__DIR__ . '/add_event.php'); ?>
    </section>
    <?php } ?>

    <!-- FILTERING EVENTS -->
    <section class="events_wrapper">
        <nav class="nav_wrapper">
            <nav>
                <select class="cat">
                    <option value="all"
                        default>All</option>
                    <option value="Upcoming">Upcoming</option>
                    <option value="Commercial">Commercial</option>
                    <option value="Underground">Underground</option>
                </select>
                <select class="gen">
                    <option value="all"
                        default>All</option>
                    <option>Rock</option>
                    <option value="Pop">Pop</option>
                    <option value="Country">Country</option>
                    <option value="Jazz">Jazz</option>
                    <option value="Folk">Folk</option>
                    <option value="Classic">Classic</option>
                    <option value="Singer-songwriter">Singer/songwriter</option>
                    <option value="Blues">Blues</option>
                    <option value="Punk">Punk</option>
                    <option value="Techno">Techno</option>
                    <option value="Dance">Dance</option>
                    <option value="Hip hop">Hip hop</option>
                    <option value="R'n'B">R'n'B</option>
                    <option value="House">House</option>
                    <option value="Reggae">Reggae</option>
                    <option value="Hardcore">Hardcore</option>
                    <option value="Funk">Funk</option>
                    <option value="Middle Eastern">Middle Eastern</option>
                    <option value="Disco">Disco</option>
                    <option value="Electronic">Electronic</option>
                    <option value="Latin">Latin</option>
                    <option value="Children music">Children music</option>
                    <option value="New age">New age</option>
                    <option value="Accapella">Accapella</option>
                    <option value="Indie">Indie</option>
                    <option value="Christian">Christian</option>
                    <option value="Ska">Ska</option>
                    <option value="Heavy metal">Heavy metal</option>
                    <option value="Death metal">Death metal</option>
                    <option value="Other">Other</option>
                </select>
                <button class="iconBtn"
                    title="Filter"
                    onclick="setFilter()">✓</button>
                <button class="iconBtn"
                    title="Reset filter"
                    onclick="resetFilter()">𝗫</button>
            </nav>
            <!-- Show only add event button if user is logged in and has event organizer privilege -->
            <?= isset($_SESSION['privilege']) ? ($_SESSION['privilege'] == '2' ?
                    '<button onclick="addEvent()" class="add_event">Add event</button>' : '') : '' ?>
        </nav>

        <!-- EVENT LIST -->
        <section class="events_wrapper">
            <!-- print out all events -->
            <?php foreach ($events as $event) {
                    //Make sure only event owner and admins can delete events.
                    $hasPrivilege = false;
                    if (isset($_SESSION['uuid'])) {
                        $hasPrivilege = $event['uuid'] == $_SESSION['uuid'];
                        if ($_SESSION['privilege'] == '3') {
                            $hasPrivilege = true;
                        }
                    } ?>
            <!-- all data is wrapped in out() before being displayed, to make sure all html special characters are escaped (avoid XSS)-->
            <article class="event">
                <div class="event_info">
                    <h4 class="event_heading"><?= out($event['event_title']) ?></h4>
                    <h4 class="at"><a href="/user/<?= $event['uuid'] ?>"><?= out($event['firstname']) ?></a></h4>
                    <p class="event_desc"><?= out($event['event_desc']) ?></p>
                    <div class="event_facts">
                        <p><?= out($event['event_time']) ?></p>
                        <p class="italic"><?= out($event['event_category']) ?> | <?= out($event['event_genre']) ?></p>
                        <p><a href="https://<?= out($event['event_ticket_link']) ?>"
                                target="_blank">Buy tickets</a></p>
                    </div>
                </div>

                <!-- Delete button for owner and admins, avoid CSRF with a hidden input -->
                <?= $hasPrivilege ? "<form class='delete_event' method='POST' action='/events/delete/{$event['event_id']}/{$event['uuid']}'>
                                        <input type='hidden' name='csrf' value='{$_SESSION['csrf']}'>
                                        <button>Delete</button>
                                    </form>" : '' ?>
                <img src="/uploads/<?= out($event['event_image']) ?>"
                    class="bgImg"
                    title="<?= out($event['event_image_credits']) ?>" />
            </article>
            <?php } ?>
        </section>
    </section>
</main>

<script>
//filter after set category and genre
function setFilter() {
    const category = document.querySelector(".cat").value;
    const genre = document.querySelector(".gen").value;
    window.location.href = `/events/${category}/${genre}`;
}

function resetFilter() {
    window.location.href = `/events/all/all`;
}
//Show add event module and hide events
function addEvent() {
    document.querySelector(".add_event_wrapper").classList.remove("hide");
    document.querySelector(".events_wrapper").classList.add("hide");
}
//Show events by reloading page
function showEvents() {
    event.preventDefault();
    location.reload();
}
</script>

<?php require_once(__DIR__ . '/bottom.php');