<form action="/events"
    method="POST"
    onsubmit="return validate()"
    class="new_event">

    <!-- Check for client side request forgery -->
    <input type="hidden"
        name="csrf"
        value=<?= $_SESSION['csrf'] ?>>
    <!-- EVENT_TITLE -->


    <!-- FIRST NAME -->
    <div class="event_title">
        <label>
            <p>Event title</p>
            <input type="text"
                placeholder="Ex. Metallica concert @ Forum"
                name="event_title"
                id="event_title"
                data-validate="str"
                data-min="5"
                data-max="100"
                onkeyup="clear_parent_error(this)" />
        </label>
        <div class="invalid-feedback">
            Characters: min 5, max 100.
        </div>
    </div>
    <!-- EVENT_TICKET_LINK -->
    <div class='event_ticket_link'>
        <label>
            <p>Link to ticket</p>
            <input type='text'
                placeholder='Ex. www.ticketlink.com'
                name='event_ticket_link'
                id='event_ticket_link'
                data-validate='url'
                onkeyup='clear_parent_error(this)' />
        </label>
        <div class='invalid-feedback'>
            Only www, http, https or ftp.
        </div>
    </div>
    <!-- EVENT_TIME -->
    <div class="event_time">
        <label>
            <p>Date and time</p>
            <input type="datetime-local"
                placeholder="add the date"
                name="event_time"
                id="event_time"
                onkeyup="clear_parent_error(this)" />
        </label>
        <div class="invalid-feedback">
            Enter a valid date.
        </div>
    </div>
    <!-- EVENT_IMAGE -->

    <!-- EVENT_CATEGORY -->
    <div class="event_category">
        <label>
            <p>Category</p>
            <select name="event_category">
                <option default>Choose a category</option>
                <option>Upcoming</option>
                <option>Commercial</option>
                <option>Underground</option>

            </select>
        </label>
        <div class="invalid-feedback">
            Please choose a category
        </div>
    </div>
    <!-- EVENT_CATEGORY -->
    <div class="event_genre">
        <label>
            <p>Genre</p>
            <select name="event_genre">
                <option default>Choose a genre</option>
                <option>Rock</option>
                <option>Pop</option>
                <option>Country</option>
                <option>Jazz</option>
                <option>Folk</option>
                <option>Classic</option>
                <option>Singer/songwriter</option>
                <option>Blues</option>
                <option>Punk</option>
                <option>Techno</option>
                <option>Dance</option>
                <option>Hip hop</option>
                <option>R'n'B</option>
                <option>House</option>
                <option>Reggae</option>
                <option>Hardcore</option>
                <option>Funk</option>
                <option>Middle Eastern</option>
                <option>Disco</option>
                <option>Electronic</option>
                <option>Latin</option>
                <option>Children music</option>
                <option>New age</option>
                <option>Accapella</option>
                <option>Christian</option>
                <option>Ska</option>
                <option>Heavy metal</option>
                <option>Death metal</option>
            </select>
        </label>
        <div class="invalid-feedback">
            Please choose a genre
        </div>
    </div>



    <!-- EVENT_DESC -->
    <div class="new_event_desc">
        <label>
            <p>Description <?= $_SESSION['privilige'] != '2' ? '(max 3500 characters)' : '' ?></p>
            <textarea placeholder="Add a description of the event..."
                name="event_desc"
                id="event_desc"
                onkeyup="clear_parent_error(this)"></textarea>
        </label>

        <button>Save event</button>
        <button onclick="showEvents()">Cancel</button>
</form>