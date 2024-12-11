<?php echo view('header'); ?>

<div class="container mt-5">
    <h1>Detalles del Árbol</h1>
    
    <?php if ($arbol): ?>
        <h2><?= esc($arbol['nombre_comercial']); ?></h2>
        <p>Nombre científico: <?= esc($arbol['nombre_cientifico']); ?></p>
        <p>Ubicación: <?= esc($arbol['ubicacion_geografica']); ?></p>
        <p>Precio: <?= esc($arbol['precio']); ?></p>
        <p>Estado: <?= esc($arbol['estado']); ?></p>
        
        <?php if (!empty($arbol['foto'])): ?>
            <div class="text-center">
                <img src="<?= base_url('uploads/' . $arbol['foto']); ?>" alt="<?= esc($arbol['nombre_comercial']); ?>" style="max-width: 300px; height: auto;">
            </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="<?= base_url('amigo'); ?>" class="btn btn-primary">Volver</a>
        </div>
        
    <?php else: ?>
        <p>No se encontró información del árbol.</p>
    <?php endif; ?>
</div>

<?php echo view('footer'); ?>
