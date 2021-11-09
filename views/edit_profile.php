<form action="/admin"
    method="POST"
    onsubmit="return validate()"
    class="edit">

    <!-- Check for client side request forgery -->
    <input type="hidden"
        name="csrf"
        value=<?= $_SESSION['csrf'] ?>>


    <!-- IMAGE -->
    <div class="user_container">
        <div class="header_profile">
            <!--    <input type="file"
                class="hide"
                name="fileToUpload"
                id="fileToUpload"
                onchange="preview()"> -->

            <!-- HIDDEN FOR FILE UPLOAD -->


            <!-- 
            <input type="file"
                class="hide"
                name="fileToUpload"
                id="fileToUpload"
                onchange="preview()"> -->



            <label for="fileToUpload"
                data-bs-toggle="tooltip"
                data-bs-placement="right"
                title="Click to edit your profile picture">
                <img class="previewImg"
                    data-src="/uploads/<?= $user['user_image'] ?>"
                    src="/uploads/<?= $user['user_image'] ?>"></label>
            <div class="submit_image hide"
                data-type="cancel">
                <img src="/static/images/close_black_24dp.svg">
            </div>
        </div>
    </div>

    <!-- FIRST NAME -->
    <div class="firstname">
        <label>First name
            <input type="text"
                placeholder="First name"
                name="firstname"
                id="name"
                value="<?= $user['firstname'] ?>"
                data-validate="str"
                data-min="2"
                data-max="30"
                onkeyup="clear_parent_error(this)" />
        </label>
        <div class="invalid-feedback">
            You need at least 2 charachters in your name and no more than 30.
        </div>
    </div>
    <!-- LAST NAME -->
    <?= $_SESSION['privilige'] != '2' ?

        "<div class='lastname'>
        <label>Last name
            <input type='text'
                placeholder='last name'
                name='lastname'
                id='lastname'
                value={$user['lastname']}
                data-validate='str'
                data-min='2'
                data-max='30'
                onkeyup='clear_parent_error(this)' />
        </label>
        <div class='invalid-feedback'>
            You need at least 2 charachters in your name and no more than 30.
        </div>
    </div>"
        : ''
    ?>
    <!-- EMAIL -->
    <div class="email">
        <label>Email
            <input type="text"
                placeholder="Email"
                name="user_email"
                data-validate="email"
                id="email"
                value="<?= $user['email'] ?>"
                onkeyup="clear_parent_error(this)" />
        </label>
        <div class="invalid-feedback">
            You need at to enter a valid email.
        </div>
    </div>

    <!-- LINK -->
    <div class="link">
        <label>Link
            <input type="text"
                placeholder="Ex. www.example.com"
                name="link"
                id="link"
                data-validate="url"
                value="<?= $user['user_link'] ?>"
                onkeyup="clear_parent_error(this)" />
        </label>
        <div class="invalid-feedback">
            You need to provide a valid link starting with www, http, https or ftp
        </div>
    </div>

    <!-- USER DESCRIPTION -->
    <div class="description">
        <label>Description
            <textarea placeholder="Add a description to mae your profile stand out"
                name="description"
                id="description"><?= $user['user_description'] ?></textarea>
        </label>
    </div>
    <!--  <button>Save changes</button> -->
    <!-- 
    <input type="submit"
        name="submit"
        id="submit_image">Save Changes</input> -->

    <form action="/admin"
        method="post"
        enctype="multipart/form-data">
        Select image to upload:
        <input type="file"
            name="fileToUpload"
            id="fileToUpload"
            onchange="preview()">
        <input type="submit"
            value="Upload Image"
            name="submit">


    </form>
</form>