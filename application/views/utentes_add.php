<div class="container">
    <h1>Add Nurse</h1>
    <?php
        if (isset($success) && $success != null):
    ?>
        <p><?php echo $success; ?></p>
    <?php
        endif;
    ?>
    <p></p>
    <div id="addNurse">
        <form action="<?php echo base_url("patients/add"); ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" value="<?php echo $formErrors !== null ? set_value('name'): ""; ?>"><br>
            <input type="text" name="nib" placeholder="Nib" value="<?php echo $formErrors !== null ? set_value('nib'): ""; ?>"><br>
            <input type="text" name="n_utente" placeholder="Nº de Utente" value="<?php echo $formErrors !== null ? set_value('n_utente'): ""; ?>"><br>
            <input type="text" name="address" placeholder="Address" value="<?php echo $formErrors !== null ? set_value('address'): ""; ?>"><br>
            <input type="text" name="city" placeholder="City" value="<?php echo $formErrors !== null ? set_value('city'): ""; ?>"><br>
            <label for="">Birthdate: <input type="date" name="birthday" placeholder="Birthday" value="<?php echo $formErrors !== null ? set_value('birthday'): ""; ?>"></label><br>
            <label for="">Image: <input type="file" name="image"></label><br>
            <label for="">Username: <input type="text" name="username" placeholder="Username" value="<?php echo $formErrors !== null ? set_value('username'): ""; ?>"></label><br>
            <input type="submit" value="Add">
        </form>
    </div>
    <?php echo $formErrors; ?>
</div>