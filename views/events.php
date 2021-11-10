<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . './../apis/api_display_events.php');



?>
<main class="admin_main">
    <?php
        if (isset($display_message)) { ?>
    <p class="url_decode admin">
        <?php
            echo urldecode($display_message);
        } ?>
    </p>

    <section class="add_event_wrapper hide">
        <?php 
        require_once(__DIR__.'/add_event.php');
        ?>
    </section>
    <section class="events_wrapper">
        <nav class="nav_wrapper">
            <nav>
                <select onchange="setCategory()">
                    <option value=""
                        default>Choose category</option>
                    <option value="">All</option>
                    <option value="concert">Concert</option>
                    <option value="talk">Talk</option>
                </select>
            </nav>
            <?= isset($_SESSION['privilige']) ? ($_SESSION['privilige'] == '2' ?
        '<button onclick="addEvent()" class="add_event">Add event</button>' : '' ) : ''?>
        </nav>
        <section class="events_wrapper">
            <?php
        foreach ($events as $event) {
            ?>
            <article class="event">
                <div class="event_info">
                    <h4><?= out($event['event_title']) ?></h4>
                    <p class="event_desc"><?= out($event['event_desc']) ?></p>
                    <div class="event_facts">

                        <p><?= out($event['event_time']) ?></p>
                        <p><?= out($event['event_category']) ?></p>
                        <p><?= out($event['event_genre']) ?></p>
                        <p><a href="
                    <?= out($event['event_ticket_link']) ?>"><?= out($event['event_ticket_link']) ?></a></p>
                        <p><a href="/user/<?= $event['uuid'] ?>"><?= out($event['firstname']) ?></a></p>
                        <!-- <a href="/events/event/<?=$event['event_id']?>">See event</a> -->
                    </div>
                </div>
                <img src="/uploads/<?=out($event['event_image'])?>"
                    class="bgImg" />

            </article>

            <?php
        }
        ?>
        </section>
    </section>
</main>

<script>
function setCategory() {
    console.log('change')
    const category = document.querySelector("select").value;
    window.location.href = `/events/${category}`;
}

function addEvent() {
    document.querySelector(".add_event_wrapper").classList.remove("hide");
    document.querySelector(".events_wrapper").classList.add("hide");
}

function showEvents() {
    event.preventDefault();
    location.reload();
}
</script>

<?php
require_once(__DIR__ . '/bottom.php');