<div class="container">
    <h1>Nurses</h1>
    <?php
    if (isset($success) && $success != null):
        ?>
        <p><?php echo $success; ?></p>
    <?php
    endif;
    ?>
    <div id="medicos">
        <?php foreach ($nurses as $nurse):?>
            <ul class="medico">
                <li>Name: <?php echo $nurse->name;?></li>
                <li>Specialty: <?php echo $nurse->specialty;?></li>
                <?php if($isLoggedIn):?>
                    <li>Nib: <?php echo $nurse->nib;?></li>
                    <li>Nif: <?php echo $nurse->nif;?></li>
                    <li>Address: <?php echo $nurse->address;?></li>
                    <li>Birthdate: <?php echo $nurse->birthday;?></li>
                    <?php if ($hasAdmin): ?>
                        <li>
                            <a href="<?php echo base_url('nurses/edit/'.$nurse->id); ?>">Edit</a>
                            ||
                            <a href="<?php echo base_url('nurses/remove/'.$nurse->id); ?>">Remove</a>
                            ||
                            <a href="<?php echo base_url('nurses/'.$nurse->id); ?>">See More</a>
                        </li>
                    <?php endif; ?>
                <?php endif;?>
            </ul>
        <?php endforeach; ?>
    </div>
</div>