<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="container">
    <h1>Contact-Us</h1>
    <?php
    if (isset($success) && $success != null):
        ?>
        <p><?php echo $success; ?></p>
    <?php
    endif;
    ?>
    <form action="<?php echo base_url('contactus/')?>" method="post">
        <input type="text" name="name" placeholder="Name" value="<?php echo $formErrors != null ? set_value('name'): ""; ?>"><br>
        <input type="text" name="email" placeholder="E-mail" value="<?php echo $formErrors != null ? set_value('email'): ""; ?>"><br>
        <input type="text" name="subject" placeholder="Subject" value="<?php echo $formErrors != null ? set_value('subject'): ""; ?>"><br>
        <textarea name="content" id="" cols="30" rows="10" placeholder="Body"><?php echo $formErrors != null ? set_value('content'): ""; ?></textarea><br>
        <div class="g-recaptcha" data-theme="dark" data-sitekey="<?php echo $googleKey; ?>"></div>
        <input type="submit">
    </form>
    <?php echo $formErrors; ?>
</div>