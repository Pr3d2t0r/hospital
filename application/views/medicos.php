<div class="container">
    <h1>Doctors</h1>
    <?php
    if (isset($success) && $success != null):
        ?>
        <p><?php echo $success; ?></p>
    <?php
    endif;
    ?>
    <div id="medicos">
        <?php foreach ($medicos as $medico):?>
            <ul class="medico">
                <li>Name: <?php echo $medico->name;?></li>
                <li>Specialty: <?php echo $medico->specialty;?></li>
                <?php if($isLoggedIn):?>
                    <li>Nib: <?php echo $medico->nib;?></li>
                    <li>Nif: <?php echo $medico->nif;?></li>
                    <li>Address: <?php echo $medico->address;?></li>
                    <li>Birthdate: <?php echo $medico->birthday;?></li>
                    <?php if ($hasAdmin): ?>
                        <li>
                            <a href="<?php echo base_url('doctors/edit/'.$medico->id); ?>">Edit</a>
                            ||
                            <a href="<?php echo base_url('doctors/remove/'.$medico->id); ?>">Remove</a>
                            ||
                            <a href="<?php echo base_url('doctors/'.$medico->id); ?>">See More</a>
                        </li>
                    <?php endif; ?>
                <?php endif;?>
            </ul>
            <hr>
        <?php endforeach; ?>
    </div>
</div>