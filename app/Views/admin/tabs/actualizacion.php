<div class="card my-4">
    <div class="card-body">
        <h4>Registrar Actualización de Árbol</h4>
        <form action="<?= base_url('admin/registrarActualizacion'); ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>
            <div class="mb-3">
                <label for="arbol_id" class="form-label text-start d-block">Seleccionar Árbol</label>
                <select name="arbol_id" class="form-control" required>
                    <option value="">-- Seleccione un árbol --</option>
                    <?php foreach ($arboles as $arbol): ?>
                        <option value="<?= $arbol['id']; ?>">ID: <?= $arbol['id']; ?> - <?= $arbol['ubicacion_geografica']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tamano" class="form-label text-start d-block">Tamaño</label>
                <input type="text" name="tamano" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label text-start d-block">Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="Crecimiento">Crecimiento</option>
                    <option value="Maduro">Maduro</option>
                    <option value="Podado">Podado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label text-start d-block">Foto del Árbol</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm">Registrar Actualización</button>
        </form>
    </div>
</div>
