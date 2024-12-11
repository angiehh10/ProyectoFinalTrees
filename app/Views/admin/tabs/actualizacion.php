<div class="container mt-4">
    <h2 class="text-center mb-4">Registrar Actualización de Árbol</h2>
    <form action="<?= base_url('/admin/registrarActualizacion'); ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="arbol_id" class="form-label text-start d-block">Seleccionar Árbol</label>
            <select name="arbol_id" id="arbol_id" class="form-control" required>
                <option value="">-- Seleccionar un árbol --</option>
                <?php foreach ($arboles as $arbol): ?>
                    <option value="<?= $arbol['id']; ?>">ID: <?= $arbol['id']; ?> - <?= esc($arbol['ubicacion_geografica']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="tamano" class="form-label text-start d-block">Tamaño</label>
            <input type="text" name="tamano" id="tamano" class="form-control" required>
        </div>
        <div class="mb-3">
                    <label for="estado" class="form-label text-start d-block">Estado</label>
                    <select name="estado" class="form-control" required>
                        <option value="Vendido">Vendido</option>
                        <option value="Disponible">Disponible</option>
                    </select>
                </div>
        <div class="mb-3">
            <label for="foto" class="form-label text-start d-block">Foto del Árbol</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Registrar Actualización</button>
    </form>
</div>

