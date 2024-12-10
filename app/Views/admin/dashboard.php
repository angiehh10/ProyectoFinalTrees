<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MyTrees</title>
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/estilos.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php echo view('header'); ?>

    <div class="container my-5">
        <!-- Título del Panel -->
        <h1 class="text-center mb-4">Dashboard de Administración</h1>

        <!-- Estadísticas -->
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Amigos Registrados</h5>
                        <p class="card-text display-4"><?= $totalAmigos ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Árboles Disponibles</h5>
                        <p class="card-text display-4"><?= $totalArbolesDisponibles ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Árboles Vendidos</h5>
                        <p class="card-text display-4"><?= $totalArbolesVendidos ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú de Pestañas -->
        <ul class="nav nav-tabs" id="adminTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'especies' ? 'active' : '' ?>" href="<?= base_url('/admin?tab=especies') ?>">Administrar Especies</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'arboles' ? 'active' : '' ?>" href="<?= base_url('/admin?tab=arboles') ?>">Administrar Árboles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'actualizacion' ? 'active' : '' ?>" href="<?= base_url('/admin?tab=actualizacion') ?>">Registrar Actualización</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'historial' ? 'active' : '' ?>" href="<?= base_url('/admin?tab=historial') ?>">Historial</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'amigos' ? 'active' : '' ?>" href="<?= base_url('/admin?tab=amigos') ?>">Ver Amigos</a>
            </li>
        </ul>

        <!-- Contenido de Pestañas -->
        <div class="tab-content mt-4" id="adminTabContent">
            <?php 
            // Renderizar la vista de la pestaña activa
            switch ($tab) {
                case 'especies':
                    echo view('admin/tabs/especies', ['especies' => $especies]);
                    break;
                case 'arboles':
                    echo view('admin/tabs/arboles', ['arboles' => $arboles, 'especies' => $especies]);
                    break;
                case 'actualizacion':
                    echo view('admin/tabs/actualizacion', ['arboles' => $arboles]);
                    break;
                case 'historial':
                    echo view('admin/tabs/historial', ['arboles' => $arboles, 'historial' => $historial, 'arbol' => $arbol]);
                    break;
                case 'amigos':
                    echo view('admin/tabs/amigos', []);
                    break;
                default:
                    echo "<p class='text-center'>Seleccione una pestaña para comenzar.</p>";
                    break;
            }
            ?>
        </div>
    <!-- Footer -->
    <?php echo view('footer'); ?>

    <!-- Archivo de JavaScript -->
    <script src="<?= base_url('js/admin.js'); ?>"></script>
</body>
</html>

