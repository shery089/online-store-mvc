<?php ob_start(); ?>
    <?php if(!empty($halqas)): ?>
        <?php foreach ($halqas as $halqa): ?>
            <option value="<?= $halqa['id'] ?>"><?= strtoupper($halqa['name']) ?></option>        
        <?php endforeach ?>
    <?php endif; ?>
<?= ob_get_clean(); ?>