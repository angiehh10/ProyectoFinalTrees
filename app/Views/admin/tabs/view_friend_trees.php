<?php echo view('header'); ?>

<div class="card my-4">
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success'); ?>
                </div>
            <?php endif; ?>
        <div class="text-end mb-3">
            <a href="<?= base_url('admin?tab=amigos'); ?>" class="btn btn-success btn-sm">Atrás</a>
        </div>
        <h4>Árboles de <?= esc($amigo['nombre']); ?></h4>
        <?php if (!empty($arboles)): ?>
            <form action="<?= base_url('admin/updateTree'); ?>" method="post" enctype="multipart/form-data">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tamaño</th>
                            <th>Ubicación</th>
                            <th>Estado</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arboles as $arbol): ?>
                            <tr>
                                <!-- Campo editable: Nombre -->
                                <td>
                                    <input type="text" name="nombre_comercial[<?= $arbol['id']; ?>]" value="<?= esc($arbol['nombre_comercial']); ?>" class="form-control">
                                </td>
                                <!-- Campo editable: Tamaño -->
                                <td>
                                    <input type="text" name="tamano[<?= $arbol['id']; ?>]" value="<?= esc($arbol['tamano']); ?>" class="form-control">
                                </td>
                                <!-- Campo editable: Ubicación -->
                                <td>
                                    <input type="text" name="ubicacion_geografica[<?= $arbol['id']; ?>]" value="<?= esc($arbol['ubicacion_geografica']); ?>" class="form-control">
                                </td>
                                <!-- Campo editable: Estado (select con opciones predeterminadas) -->
                                <td>
                                    <select name="estado[<?= $arbol['id']; ?>]" class="form-control">
                                        <option value="Disponible" <?= $arbol['estado'] === 'Disponible' ? 'selected' : ''; ?>>Disponible</option>
                                        <option value="Vendido" <?= $arbol['estado'] === 'Vendido' ? 'selected' : ''; ?>>Vendido</option>
                                    </select>
                                </td>
                                <!-- Campo editable: Imagen -->
                                <td>
                                    <input type="file" name="imagen[<?= $arbol['id']; ?>]" class="form-control">
                                    <small><a href="<?= base_url('uploads/' . $arbol['foto']); ?>" target="_blank">Ver Imagen Actual</a></small>
                                </td>
                                <!-- Botón de Actualizar -->
                                <td>
                                    <button type="submit" name="update[<?= $arbol['id']; ?>]" class="btn btn-primary btn-sm">Actualizar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <p>No hay árboles registrados para este amigo.</p>
        <?php endif; ?>
    </div>
</div>
