<div class="container">
    <h1>Medicos</h1>
    <div id="medicos">
        <?php foreach ($medicos as $medico):?>
            <ul class="medico">
                <li>Nome: <?php echo $medico->name;?></li>
                <li>Especialidade: <?php echo $medico->specialty;?></li>
            </ul>
        <?php endforeach; ?>
    </div>
</div>