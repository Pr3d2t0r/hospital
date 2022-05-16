<div class="container">
    <h1>Patients</h1>
    <?php
    if (isset($success) && $success != null):
        ?>
        <p><?php echo $success; ?></p>
    <?php
    endif;
    ?>
    <div id="utentes">
        <?php foreach ($utentes as $utente):?>
            <ul class="utente">
                <li>Name: <?php echo $utente->name;?></li>
                <li>City: <?php echo $utente->city;?></li>
                <?php if($isLoggedIn):?>
                    <li>Address: <?php echo $utente->address;?></li>
                    <li>Nib: <?php echo $utente->nib;?></li>
                    <li>Nº de utente: <?php echo $utente->n_utente;?></li>
                    <li>Birthdate: <?php echo $utente->birthday;?></li>
                    <?php if ($hasAdmin): ?>
                        <li>
                            <a href="<?php echo base_url('patients/edit/'.$utente->id); ?>">Edit</a>
                            ||
                            <a href="<?php echo base_url('patients/remove/'.$utente->id); ?>">Remove</a>
                            ||
                            <a href="<?php echo base_url('patients/'.$utente->id); ?>">See More</a>
                        </li>
                    <?php endif; ?>
                <?php endif;?>
            </ul>
            <hr>
        <?php endforeach; ?>
    </div>
</div>