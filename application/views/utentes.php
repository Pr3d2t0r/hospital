<div class="container">
    <h1>Utentes</h1>
    <div id="utentes">
        <?php foreach ($utentes as $utente):?>
            <ul class="utente">
                <li>Nome: <?php echo $utente->name;?></li>
                <li>Cidade: <?php /*echo $utente->city;*/?></li>
            </ul>
        <?php endforeach; ?>
    </div>
</div>