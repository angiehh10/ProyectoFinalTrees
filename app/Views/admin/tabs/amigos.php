<div class="tab-pane fade <?= $tab === 'amigos' ? 'show active' : ''; ?>" id="amigos">
    <div class="card my-4">
        <div class="card-body">
            <h4 class="mb-4 text-start">Lista de Amigos</h4>
            <?php if (!empty($amigos)): ?>
                <ul class="list-group">
                    <?php foreach ($amigos as $amigo): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= esc($amigo['nombre']); ?>
                            <button class="btn btn-info btn-sm ver-arboles" data-amigo-id="<?= $amigo['id']; ?>">Ver Árboles</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">No hay amigos registrados.</p>
            <?php endif; ?>
        </div>
    </div> 

    <!-- Contenedor dinámico para mostrar los árboles -->
    <div id="arboles-container" class="mt-4">
        <p>Selecciona un amigo para ver sus árboles.</p>
    </div>
</div>

