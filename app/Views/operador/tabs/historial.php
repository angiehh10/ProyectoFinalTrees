<div class="tab-pane fade <?= $tab === 'historial' ? 'show active' : '' ?>" id="historial">
    <div class="card my-4">
        <div class="card-body">
            <h4>Historial de Actualizaciones del Árbol</h4>
            
            <!-- Selección de árbol -->
            <form action="<?= base_url('/operador') ?>" method="GET" class="mb-4">
                <input type="hidden" name="tab" value="historial">
                <div class="mb-3">
                    <label for="arbol_id" class="form-label">Seleccionar Árbol</label>
                    <select name="arbol_id" id="arbol_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Seleccionar un árbol --</option>
                        <?php foreach ($arboles as $arbol): ?>
                            <option value="<?= $arbol['id'] ?>" <?= isset($arbolSeleccionado) && $arbolSeleccionado['id'] === $arbol['id'] ? 'selected' : '' ?>>
                                ID <?= $arbol['id'] ?> - <?= $arbol['ubicacion_geografica'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- Tabla de historial -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Árbol</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                        <th>Estado</th>
                        <th>Foto</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $item): ?>
                        <tr>
                            <td><?= esc($item['arbol_id']); ?></td>
                            <td><?= esc($item['fecha_actualizacion']); ?></td>
                            <td><?= esc($item['tamano']); ?></td>
                            <td><?= esc($item['estado']); ?></td>
                            <td>
                                <?php if (!empty($item['foto'])): ?>
                                    <img src="<?= base_url($item['foto']); ?>" alt="Foto del árbol" width="100">
                                <?php else: ?>
                                    Sin foto
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

