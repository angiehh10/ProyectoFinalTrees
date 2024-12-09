<div class="card my-4">
    <div class="card-body">
        <h4>Árboles de <?= esc($amigo['nombre']); ?></h4>
        <?php if (!empty($arboles)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arboles as $arbol): ?>
                        <tr>
                            <td><?= esc($arbol['nombre']); ?></td>
                            <td><?= esc($arbol['tamano']); ?></td>
                            <td><?= esc($arbol['ubicacion_geografica']); ?></td>
                            <td><?= esc($arbol['estado']); ?></td>
                            <td>
                                <a href="<?= base_url('admin/updateTree/' . $arbol['id']); ?>" class="btn btn-primary btn-sm">Actualizar</a>
                                <a href="<?= base_url('admin/deleteTree/' . $arbol['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este árbol?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay árboles registrados para este amigo.</p>
        <?php endif; ?>
    </div>
</div>
