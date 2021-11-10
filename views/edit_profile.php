<form action="/admin"
    method="POST"
    onsubmit="return validate()"
    class="edit">

    <!-- Check for client side request forgery -->
    <input type="hidden"
        name="csrf"
        value=<?= $_SESSION['csrf'] ?>>

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
        <?= $_SESSION['privilige'] != '2' ?

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
            : ''
        ?>
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
    <!-- Organizers can put as long description they want, users and admins only up to 300 ch -->
    <div class="description">
        <label>
            <p>Description <?= $user['privilige'] != '2' ? '(max 3500 characters)' : '' ?></p>
            <textarea placeholder="Add a description to mae your profile stand out"
                data-validate="<?= $user['privilige'] != '2' ? 'str' : '' ?>"
                data-max="3500"
                data-min="0"
                name="description"
                id="description"
                onkeyup="clear_parent_error(this)"><?= out($user['user_description']) ?></textarea>
        </label>
        <div class="invalid-feedback">
            Max 3500 characters
        </div>
    </div>
    <button onclick="showProfile()">Save changes</button>

    <button class="close_cancel_btn"
        onclick="showProfile('cancel')">Cancel</button>
</form>