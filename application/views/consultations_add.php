<div class="container">
    <h1>Add Consultation</h1>
    <?php
        if (isset($success) && $success != null):
    ?>
        <p><?php echo $success; ?></p>
    <?php
        endif;
    ?>
    <p></p>
    <div id="addNurse">
        <form action="<?php echo base_url("consultations/add"); ?>" method="post" enctype="multipart/form-data">
            <input type="text" name="n_utente" placeholder="Nº de utente" value="<?php echo $formErrors !== null ? set_value('n_utente'): ""; ?>"><br>
            <?php if ($isSuperAdmin): ?>
                <select name="doctor_id">
                    <option>Select an Doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor->id ?>"><?php echo $doctor->name ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <label for="">Date: <input type="date" name="date" placeholder="Birthday" value="<?php echo $formErrors !== null ? set_value('birthday'): ""; ?>"></label><br>
            <input type="submit" value="Add">
        </form>
    </div>
    <?php echo $formErrors; ?>
</div>