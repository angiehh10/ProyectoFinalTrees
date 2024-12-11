<div class="card my-4">
    <div class="card-body">
        <h4>Crear Nueva Especie</h4>
        <form action="<?= base_url('/admin/saveEspecie') ?>" method="POST">
            <div class="mb-3">
                <label for="nombre_comercial" class="form-label text-start d-block">Nombre Comercial</label> <!-- Alineado a la izquierda -->
                <input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control" placeholder="Ingrese el nombre comercial" required>
            </div>
            <div class="mb-3">
                <label for="nombre_cientifico" class="form-label text-start d-block">Nombre Científico</label> <!-- Alineado a la izquierda -->
                <input type="text" name="nombre_cientifico" id="nombre_cientifico" class="form-control" placeholder="Ingrese el nombre científico" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm" style="float: left;">Crear Especie</button>
        </form>

        <h5 class="mt-5 text-start">Especies Existentes</h5> <!-- Alineado a la izquierda -->
        <?php if (!empty($especies)): ?>
            <ul class="list-group mt-3">
                <?php foreach ($especies as $especie): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong><?= esc($especie['nombre_comercial']) ?></strong> 
                            (<?= esc($especie['nombre_cientifico']) ?>)
                        </span>
                        <div class="d-flex">
                            <!-- Botón de editar -->
                            <button 
                                class="btn btn-primary btn-sm me-2" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editEspecieModal<?= $especie['id'] ?>">
                                Editar
                            </button>

                            <!-- Botón de eliminar -->
                            <form action="<?= base_url('/admin/deleteEspecie/' . $especie['id']) ?>" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta especie?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </li>

                    <!-- Modal para editar especie -->
                    <div class="modal fade" id="editEspecieModal<?= $especie['id'] ?>" tabindex="-1" aria-labelledby="editEspecieModalLabel<?= $especie['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editEspecieModalLabel<?= $especie['id'] ?>">Editar Especie</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <form action="<?= base_url('/admin/updateEspecie/' . $especie['id']) ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="especie_id" value="<?= $especie['id'] ?>">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="nombre_comercial_<?= $especie['id'] ?>" class="form-label text-start d-block">Nombre Comercial</label> <!-- Alineado a la izquierda -->
                                            <input type="text" name="nombre_comercial" id="nombre_comercial_<?= $especie['id'] ?>" class="form-control" value="<?= esc($especie['nombre_comercial']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nombre_cientifico_<?= $especie['id'] ?>" class="form-label text-start d-block">Nombre Científico</label> <!-- Alineado a la izquierda -->
                                            <input type="text" name="nombre_cientifico" id="nombre_cientifico_<?= $especie['id'] ?>" class="form-control" value="<?= esc($especie['nombre_cientifico']) ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted mt-3">No hay especies registradas.</p>
        <?php endif; ?>
    </div>
</div>
