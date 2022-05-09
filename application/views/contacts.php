<div class="container">
    <h1>Contact-Us</h1>
    <form action="<?php echo base_url('contactus/')?>" method="post">
        <input type="text" name="name" value="<?php echo $formErrors !== null ? set_value('name'): ""; ?>">
        <input type="text" name="email" value="<?php echo $formErrors !== null ? set_value('email'): ""; ?>">
        <input type="text" name="subject" value="<?php echo $formErrors !== null ? set_value('subject'): ""; ?>">
        <textarea name="content" id="" cols="30" rows="10"><?php echo $formErrors !== null ? set_value('content'): ""; ?></textarea>
        <input type="submit">
    </form>
    <?php echo $formErrors; ?>
</div>