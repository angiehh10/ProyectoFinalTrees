<div class="tab-pane fade <?= $tab === 'actualizacion' ? 'show active' : ''; ?>" id="actualizacion">
    <div class="card my-4">
        <div class="card-body">
            <h4>Registrar Actualización de Árbol</h4>
            <form action="<?= base_url('/operador/registrarActualizacion'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="mb-3">
                    <label for="arbol_id" class="form-label text-start d-block">Seleccionar Árbol</label>
                    <select name="arbol_id" class="form-control" required>
                        <option value="">-- Seleccionar un árbol --</option>
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
                        <option value="Vendido">Vendido</option>
                        <option value="Disponible">Disponible</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label text-start d-block">Foto del Árbol</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-success">Registrar Actualización</button>
            </form>
        </div>
    </div>
</div>