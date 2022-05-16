<div class="container">
    <h1>Register</h1>
    <div id="addUser">
        <form action="<?php echo base_url("register/"); ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="password" name="password_repeat" placeholder="Repeat password"><br>
            <input type="submit" value="Register">
        </form>
    </div>
    <?php echo $formErrors; ?>
</div>