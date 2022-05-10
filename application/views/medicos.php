<div class="container">
    <h1>Doctors</h1>
    <div id="medicos">
        <?php foreach ($medicos as $medico):?>
            <ul class="medico">
                <li>Name: <?php echo $medico->name;?></li>
                <li>Specialty: <?php echo $medico->specialty;?></li>
            </ul>
        <?php endforeach; ?>
    </div>
</div>