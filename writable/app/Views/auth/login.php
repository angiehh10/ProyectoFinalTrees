<?php echo view('header'); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white text-center">
                    <h5>Iniciar Sesión</h5>
                </div>
                <div class="card-body">
                    <!-- Mostrar mensaje de error si existe -->
                    <?php if (session()->get('error')): ?>
                        <div class="alert alert-danger text-center">
                            <?= esc(session()->get('error')) ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Formulario de inicio de sesión -->
                    <form action="<?= base_url('/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input 
                                type="email" 
                                class="form-control" 
                                name="email" 
                                placeholder="Ingresa tu correo electrónico" 
                                required
                                value="<?= old('email') ?>"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input 
                                type="password" 
                                class="form-control" 
                                name="contrasena" 
                                placeholder="Ingresa tu contraseña" 
                                required
                            >
                        </div>
                        <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">¿No tienes cuenta? 
                        <a href="<?= base_url('/register') ?>" class="text-success">Regístrate</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo view('footer'); ?>
