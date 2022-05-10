<div class="container">
    <h1>Add Doctor</h1>
    <div id="addMedico">
        <form action="<?php echo base_url("doctors/add"); ?>" method="post">
            <input type="text" name="name" placeholder="Name"><br>
            <input type="text" name="nib" placeholder="Nib"><br>
            <input type="text" name="nif" placeholder="Nif"><br>
            <input type="text" name="specialty" placeholder="Specialty"><br>
            <input type="text" name="address" placeholder="Address"><br>
            <input type="text" name="city" placeholder="City"><br>
            <label for="">Birthdate: <input type="date" name="birthday" placeholder="Birthday"></label><br>
            <label for="">Image: <input type="file" name="image" placeholder="Image"></label><br>
            <input type="submit" name="Store">
        </form>
    </div>
</div>