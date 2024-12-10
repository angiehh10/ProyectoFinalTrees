<div class="tab-pane fade <?= $tab === 'amigos' ? 'show active' : ''; ?>" id="amigos">
    <div class="card my-4">
        <div class="card-body">
            <h4 class="mb-4 text-start">Lista de Amigos</h4>

            <!-- Formulario para seleccionar amigo -->
            <form action="<?= base_url('/admin/verAmigos'); ?>" method="post">
                <div class="mb-3">
                    <label for="amigo_id" class="form-label">Seleccionar Amigo</label>
                    <select name="amigo_id" id="amigo_id" class="form-control" required>
                        <option value="">-- Seleccionar un amigo --</option>
                        <?php foreach ($amigos as $amigo): ?>
                            <option value="<?= $amigo['id']; ?>" <?= isset($amigoSeleccionado) && $amigoSeleccionado == $amigo['id'] ? 'selected' : ''; ?>>
                                <?= esc($amigo['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Ver Árboles</button>
            </form>
        </div>
    </div>

    <!-- Mostrar árboles del amigo seleccionado -->
    <div id="arboles-container" class="mt-4">
        <?php if (!empty($arboles)): ?>
            <h5>Árboles del Amigo Seleccionado:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Especie</th>
                        <th>Ubicación</th>
                        <th>Tamaño</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arboles as $arbol): ?>
                        <tr>
                            <td><?= $arbol['id']; ?></td>
                            <td><?= esc($arbol['especie']); ?></td>
                            <td><?= esc($arbol['ubicacion_geografica']); ?></td>
                            <td><?= esc($arbol['tamano']); ?></td>
                            <td><?= esc($arbol['estado']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($amigoSeleccionado)): ?>
            <p>No hay árboles asociados a este amigo.</p>
        <?php else: ?>
            <p>Selecciona un amigo para ver sus árboles.</p>
        <?php endif; ?>
    </div>
</div>



