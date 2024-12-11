<div class="container mt-4">

    <!-- Mensajes de éxito/error -->
    <?php if (session('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>
    <?php if (session('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>

    <!-- Tabla de Usuarios -->
    <h3>Usuarios Registrados</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= esc($usuario['id']) ?></td>
                    <td><?= esc($usuario['email']) ?></td>
                    <td><?= esc($usuario['rol']) ?></td>
                    <td><?= esc($usuario['estado']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulario para Crear Usuarios -->
    <h3 class="mt-4">Agregar Nuevo Usuario</h3>
    <form method="post" action="<?= base_url('admin/crearUsuario') ?>">
        <div class="mb-3">
            <label for="email" class="form-label text-start d-block">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label text-start d-block">Contraseña</label>
            <input type="password" class="form-control" id="contrasena" name="contrasena" required>
        </div>
        <div class="mb-3">
            <label for="rol" class="form-label text-start d-block">Rol</label>
            <select class="form-control" id="rol" name="rol" required>
                <option value="Administrador">Administrador</option>
                <option value="Operador">Operador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-info btn-sm ver-arboles">Crear Usuario</button>
    </form>
</div>
