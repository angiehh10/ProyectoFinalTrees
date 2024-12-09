<?php echo view('header'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h5>Regístrate en MyTrees</h5>
                </div>
                <div class="card-body">
                    <!-- Mensajes de éxito o error -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
                    <?php elseif (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <!-- Formulario -->
                    <form action="<?= base_url('/register/store') ?>" method="POST">
                        <div class="mb-3">
                            <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" name="nombre_usuario" placeholder="Ingresa tu nombre de usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="email" placeholder="Ingresa tu correo electrónico" required>
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" name="contrasena" placeholder="Ingresa tu contraseña" required>
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ingresa tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" placeholder="Ingresa tus apellidos" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Número de Teléfono</label>
                            <input type="text" class="form-control" name="telefono" placeholder="Ingresa tu número de teléfono" required>
                        </div>
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Ingresa tu dirección" required>
                        </div>
                        <div class="mb-3">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control" name="pais" placeholder="Ingresa tu país" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registrar</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">¿Ya tienes una cuenta? <a href="<?= base_url('/login') ?>" class="text-primary">Inicia Sesión</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('footer'); ?>
