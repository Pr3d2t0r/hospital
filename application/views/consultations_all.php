<div class="container">
    <h1>All Consultations</h1>
    <?php
    if (isset($success) && $success != null):
        ?>
        <p><?php echo $success; ?></p>
    <?php
    endif;
    ?>
    <div id="medicos">
        <?php if (count($consultas) == 0) : ?>
            <h3>No Consultations To Show!</h3>
        <?php else:
            foreach ($consultas as $consulta):?>
                <ul class="medico">
                    <li>Doctor: <?php echo $consulta->doctor_name;?></li>
                    <li>Patient: <?php echo $consulta->patient_name;?></li>
                    <li>Date: <?php echo $consulta->date;?></li>
                    <?php if($isLoggedIn):?>

                        <li><?php echo $consulta->recipe_id != null ? "Has Prescription": "Doesn't have prescription"?></li>
                        <?php if ($hasAdmin && $consulta->recipe_id != null): ?>
                            <li>
                                <a href="<?php echo base_url('consultations/'.$consulta->id); ?>">See More</a>
                            </li>
                        <?php endif; ?>
                    <?php endif;?>
                </ul>
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>