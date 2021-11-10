<?php
require_once(__DIR__ . '/default_top.php');
require_once(__DIR__ . '/nav.php');
require_once(__DIR__ . './../cookie_config.php');
require_once(__DIR__ . './../apis/api_display_events.php');


?>
<nav>
    <select onchange="setCategory()">
        <option value=""
            default>Choose category</option>
        <option value="">All</option>
        <option value="concert">Concert</option>
        <option value="talk">Talk</option>
    </select>
</nav>
<section class="events_wrapper">
    <article>
        <?php
        foreach ($events as $event) {
        ?>
        <h4><?= out($event['event_title']) ?></h4>
        <p><?= out($event['event_desc']) ?></p>
        <p><?= out($event['event_image']) ?></p>
        <p><?= out($event['event_time']) ?></p>
        <p><a href="<?= out($event['event_ticket_link']) ?>"><?= out($event['event_ticket_link']) ?></a></p>
        <p><?= out($event['event_category']) ?></p>
        <p><a href="/user/<?= $event['uuid'] ?>"><?= out($event['firstname']) ?></a></p>


        <?php
        }
        ?>
    </article>
</section>

<script>
function setCategory() {
    console.log('change')
    const category = document.querySelector("select").value;
    window.location.href = `/events/${category}`;
}
</script>

<?php
require_once(__DIR__ . '/bottom.php');