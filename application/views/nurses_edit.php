<div class="container">
    <h1>Edit Nurse</h1>
    <?php
        if (isset($success) && $success != null):
    ?>
        <p><?php echo $success; ?></p>
    <?php
        endif;
    ?>
    <p></p>
    <div id="addNurse">
        <form action="<?php echo base_url("nurses/edit/$id"); ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Name" value="<?php echo $formErrors != null ? set_value('name') : $nurse['name']; ?>"><br>
            <input type="text" name="nib" placeholder="Nib" value="<?php echo $formErrors != null ? set_value('nib'): $nurse['nib']; ?>"><br>
            <input type="text" name="nif" placeholder="Nif" value="<?php echo $formErrors != null ? set_value('nif'): $nurse['nif']; ?>"><br>
            <input type="text" name="specialty" placeholder="Specialty" value="<?php echo $formErrors != null ? set_value('specialty'): $nurse['specialty']; ?>"><br>
            <input type="text" name="address" placeholder="Address" value="<?php echo $formErrors != null ? set_value('address'): $addr['name']; ?>"><br>
            <input type="text" name="city" placeholder="City" value="<?php echo $formErrors != null ? set_value('city'): $addr['city']; ?>"><br>
            <label for="">Birthdate: <input type="date" name="birthday" placeholder="Birthday" value="<?php echo $formErrors != null ? set_value('birthday'): $nurse['birthday']; ?>"></label><br>
            <label for="">Image: <input type="file" name="image"></label><br>
            <input type="submit" value="Save">
        </form>
    </div>
    <?php echo $formErrors; ?>
</div>