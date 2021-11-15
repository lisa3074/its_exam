<!-- EDIT PROFiLE MODULE -->
<!-- the data-validate attribute is a condition in validator.js that is called on submit for frontend validation -->
<form action="/admin"
    method="POST"
    onsubmit="return validate()"
    class="edit">

    <!-- Check for cross site request forgery -->
    <input type="hidden"
        name="csrf"
        value=<?= $_SESSION['csrf'] ?>>

    <!-- all data is wrapped in out() before being set as input value, to make sure all html special characters are escaped -->
    <div class="flex_inputs">
        <!-- FIRST NAME -->
        <div class="firstname">
            <label>
                <p>First name</p>
                <input type="text"
                    placeholder="Ex. John"
                    name="firstname"
                    id="name"
                    value="<?= out($user['firstname']) ?>"
                    data-validate="str"
                    data-min="2"
                    data-max="30"
                    onkeyup="clear_parent_error(this)" />
            </label>
            <div class="invalid-feedback">
                Characters: min 2, max 30.
            </div>
        </div>

        <!-- LAST NAME -->
        <!-- Only show if user is a person (admin or regular user) -->
        <?= $_SESSION['privilige'] == '1' || $_SESSION['privilige'] == '3' ?
            "<div class='lastname'>
            <label><p>Last name</p>
                  <input type='text'
                    placeholder='Ex. Smith'
                    name='lastname'
                    id='lastname'
                    value='{$user['lastname']}'
                    data-validate='str'
                    data-min='2'
                    data-max='30'
                    onkeyup='clear_parent_error(this)' />
            </label>
            <div class='invalid-feedback'>
                Characters: min 2, max 30.
            </div>
        </div>"
            : ''  ?>

        <!-- EMAIL -->
        <div class="email">
            <label>
                <p>Email</p>
                <input type="text"
                    placeholder="example@mail.com"
                    name="user_email"
                    data-validate="email"
                    id="email"
                    value="<?= out($user['email']) ?>"
                    onkeyup="clear_parent_error(this)" />
            </label>
            <div class="invalid-feedback">
                Enter a valid email.
            </div>
        </div>

        <!-- LINK -->
        <div class="link">
            <label>
                <p>Link</p>
                <input type="text"
                    placeholder="Ex. www.example.com"
                    name="link"
                    id="link"
                    data-validate="url"
                    value="<?= out($user['user_link']) ?>"
                    onkeyup="clear_parent_error(this)" />
            </label>
            <div class="invalid-feedback">
                Only www, http, https or ftp.
            </div>
        </div>
    </div>

    <!-- USER DESCRIPTION -->
    <div class="description">
        <label>
            <p>Description</p>
            <textarea placeholder="Add a description to mae your profile stand out"
                name="description"
                id="description"
                onkeyup="clear_parent_error(this)"><?= out($user['user_description']) ?></textarea>
        </label>
    </div>
    <button onclick="showProfile()">Save changes</button>
    <button class="close_cancel_btn"
        onclick="showProfile('cancel')">Cancel</button>
</form>