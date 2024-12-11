<?php echo view('header'); ?>

<div class="container mt-5">
    <h2>Comprar Árbol</h2>
    <p>Nombre comercial: <?= esc($arbol['nombre_comercial']); ?></p>
    <p>Nombre científico: <?= esc($arbol['nombre_cientifico']); ?></p>
    <p>Ubicación: <?= esc($arbol['ubicacion_geografica']); ?></p>
    <p>Precio: <?= number_format($arbol['precio'], 2); ?></p>
    <?php if (!empty($arbol['foto'])): ?>
            <img src="<?= base_url('uploads/' . $arbol['foto']); ?>" alt="<?= esc($arbol['nombre_comercial']); ?>" style="max-width: 300px; height: auto;">
        <?php endif; ?>

        <form action="<?= base_url('amigo/comprar'); ?>" method="post">
        <input type="hidden" name="arbol_id" value="<?= $arbol['id']; ?>">
        <button type="submit" class="btn btn-success">Confirmar Compra</button>
        <a href="<?= base_url('amigo'); ?>" class="btn btn-danger">Cancelar Compra</a>
    </form>
</div>

<?php echo view('footer'); ?>
