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
                    <th>Foto</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($actualizaciones as $actualizacion): ?>
                    <tr>
                        <td><?= esc($actualizacion['fecha_actualizacion']); ?></td>
                        <td><?= esc($actualizacion['tamano']); ?></td>
                        <td><?= esc($actualizacion['estado']); ?></td>
                        <td>
                            <?php if (!empty($actualizacion['foto'])): ?>
                                <img src="<?= base_url('uploads/' . esc($actualizacion['foto'])); ?>" 
                                     alt="Foto de actualización" 
                                     style="width: 100px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay actualizaciones disponibles para este árbol.</p>
    <?php endif; ?>

    <a href="<?= base_url('amigo'); ?>" class="btn btn-primary">Regresar</a>
</div>

<?php echo view('footer'); ?>
