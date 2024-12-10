<?php echo view('header'); ?>

<div class="container mt-5">
    <h2>Actualizaciones del Árbol</h2>
    <p>Visualiza las actualizaciones del árbol seleccionado.</p>

    <?php if (!empty($actualizaciones)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha de Actualización</th>
                    <th>Tamaño</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actualizaciones as $actualizacion): ?>
                    <tr>
                        <td><?= esc($actualizacion['fecha_actualizacion']); ?></td>
                        <td><?= esc($actualizacion['tamano']); ?></td>
                        <td><?= esc($actualizacion['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay actualizaciones disponibles para este árbol.</p>
    <?php endif; ?>

    <a href="<?= base_url('amigo'); ?>" class="btn btn-secondary mt-3">Regresar</a>
</div>

<?php echo view('footer'); ?>
