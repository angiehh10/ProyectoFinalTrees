<div class="tab-pane fade <?= $tab === 'arboles' ? 'show active' : ''; ?>" id="arboles">
    <div class="card my-4">
        <div class="card-body">
            <h4>Crear Nuevo Árbol</h4>
            <form action="<?= base_url('admin/createTree'); ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="especie_id" class="form-label text-start d-block">Especie</label> <!-- Alineado a la izquierda -->
                    <select name="especie_id" id="especie_id" class="form-control" required>
                        <?php foreach ($especies as $especie): ?>
                            <option value="<?= $especie['id']; ?>"><?= esc($especie['nombre_comercial']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ubicacion_geografica" class="form-label text-start d-block">Ubicación Geográfica</label> <!-- Alineado a la izquierda -->
                    <input type="text" name="ubicacion_geografica" id="ubicacion_geografica" class="form-control" placeholder="Ingrese la ubicación geográfica" required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label text-start d-block">Estado</label> <!-- Alineado a la izquierda -->
                    <select name="estado" id="estado" class="form-control" required>
                        <option value="Disponible">Disponible</option>
                        <option value="Vendido">Vendido</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label text-start d-block">Precio</label> <!-- Alineado a la izquierda -->
                    <input type="number" step="0.01" name="precio" id="precio" class="form-control" placeholder="Ingrese el precio" required>
                </div>
                <div class="mb-3">
                    <label for="tamano" class="form-label text-start d-block">Tamaño</label> <!-- Alineado a la izquierda -->
                    <input type="text" name="tamano" id="tamano" class="form-control" placeholder="Ingrese el tamaño" required>
                </div>
                <div class="mb-3">
                    <label for="foto_upload" class="form-label text-start d-block">Foto del Árbol</label> <!-- Alineado a la izquierda -->
                    <input type="file" name="foto_upload" id="foto_upload" class="form-control mt-2" accept="image/*">
                </div>
                <button type="submit" class="btn btn-success btn-sm" style="float: left;">Crear Árbol</button>
            </form>
        </div>
    </div>
</div>
