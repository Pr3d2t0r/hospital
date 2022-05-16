<div class="container">
    <h1>Login</h1>
    <div id="addUser">
        <form action="<?php echo base_url("login/"); ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Username"><br>
            <input type="password" name="password" placeholder="Password"><br>
            <input type="submit" value="Login">
        </form>
    </div>
    <?php echo $formErrors; ?>
</div>