<?php echo view('header'); ?>

<div class="container my-5 flex-grow-1">
    <div class="row">
        <div class="col-md-8 mx-auto position-relative">
            <div class="text-center p-5 bg-white rounded shadow" style="position: relative; z-index: 1;">
                <h1 class="display-4">Bienvenido a MyTrees</h1>
                <p class="lead">Una plataforma para apoyar la reforestación y cuidar el medio ambiente.</p>
                <img src="<?= base_url('img/arbol1.png') ?>" alt="Árbol" class="tree-image">
            </div>

            <div class="text-center mt-4">
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar Sesión</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Inicio de Sesión -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (session()->get('error')): ?>
                    <div class="alert alert-danger"><?= esc(session()->get('error')) ?></div>
                <?php endif; ?>
                <form action="<?= base_url('/login') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="email" placeholder="Ingresa tu correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="contrasena" placeholder="Ingresa tu contraseña" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php echo view('footer'); ?>
