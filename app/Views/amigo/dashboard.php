<?php echo view('header'); ?>

<div class="container mt-5">
    <h1>Bienvenido Amigo</h1>
    <p>Esta es la página de amigo donde solo los usuarios con permisos de amigo pueden acceder.</p>

    <h2>Mis Árboles</h2>
    <?php if (!empty($arbolesAmigo)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Especie</th>
                    <th>Ubicación</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arbolesAmigo as $arbol): ?>
                    <tr>
                        <td><?= esc($arbol['especie']); ?></td>
                        <td><?= esc($arbol['ubicacion_geografica']); ?></td>
                        <td><?= esc($arbol['precio']); ?></td>
                        <td><?= esc($arbol['estado']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tienes árboles registrados.</p>
    <?php endif; ?>

    <h2>Árboles Disponibles</h2>
    <?php if (!empty($arbolesDisponibles)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Especie</th>
                    <th>Ubicación</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arbolesDisponibles as $arbol): ?>
                    <tr>
                        <td><?= esc($arbol['especie']); ?></td>
                        <td><?= esc($arbol['ubicacion_geografica']); ?></td>
                        <td><?= esc($arbol['precio']); ?></td>
                        <td>
                            <a href="<?= base_url('compra/' . $arbol['id']); ?>" class="btn btn-primary btn-sm">Solicitar Compra</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No hay árboles disponibles.</p>
    <?php endif; ?>
</div>

<?php echo view('footer'); ?>
