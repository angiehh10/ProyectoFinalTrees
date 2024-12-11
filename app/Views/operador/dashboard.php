<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Operador - MyTrees</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('css/estilos.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <?php echo view('header'); ?>
    <div class="container my-5">
    <?php if (session()->getFlashdata('mensaje')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('mensaje'); ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>
        <!-- Título del Panel -->
        <h1 class="text-center mb-4">Dashboard del Operador</h1>

        <!-- Estadísticas -->
        <div class="row text-center mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Amigos Registrados</h5>
                        <p class="card-text display-4"><?= $totalAmigos ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Árboles Disponibles</h5>
                        <p class="card-text display-4"><?= $totalArbolesDisponibles ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú de Pestañas -->
        <ul class="nav nav-tabs" id="operatorTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'actualizacion' ? 'active' : '' ?>" href="<?= base_url('/operador?tab=actualizacion') ?>">Registrar Actualización</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tab === 'historial' ? 'active' : '' ?>" href="<?= base_url('/operador?tab=historial') ?>">Historial</a>
            </li>
        </ul>

        <!-- Contenido de Pestañas -->
        <div class="tab-content mt-4" id="operatorTabContent">
            <?php 
            switch ($tab) {
                case 'actualizacion':
                    echo view('operador/tabs/actualizacion', ['arboles' => $arboles]);
                    break;
                case 'historial':
                    echo view('operador/tabs/historial', ['historial' => $historial, 'arbol' => $arbol]);
                    break;
                default:
                    echo "<p class='text-center'>Seleccione una pestaña para comenzar.</p>";
                    break;
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <?php echo view('footer'); ?>

    <script src="<?= base_url('js/operador.js'); ?>"></script>
</body>
</html>
