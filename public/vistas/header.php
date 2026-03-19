<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../../index.html');
    exit;
}

$rol     = $_SESSION['rol']     ?? '';
$nombre  = $_SESSION['nombre']  ?? '';
$usuario = $_SESSION['usuario'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <title>Help-Desk</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light static-top mb-5 shadow">
    <div class="container">

        <a class="navbar-brand fw-bold" href="inicio.php">
            <i class="fas fa-headset me-2"></i>Help-Desk
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto align-items-center">

                <!-- Inicio: todos -->
                <li class="nav-item">
                    <a class="nav-link" href="inicio.php">
                        <i class="fas fa-home me-1"></i>Inicio
                    </a>
                </li>

                <?php if ($rol === 'Admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php">
                        <i class="fas fa-users me-1"></i>Usuarios
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($rol === 'Admin' || $rol === 'Tecnico'): ?>
               
                <li class="nav-item">
                    <a class="nav-link" href="reportes.php">
                        <i class="fas fa-file-alt me-1"></i>Reportes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dispositivos.php">
                        <i class="fas fa-laptop me-1"></i>Dispositivos
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($rol === 'Admin' || $rol === 'Cliente'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="misreportes.php">
                        <i class="fas fa-file-invoice me-1"></i>Mis Reportes
                    </a>
                </li>
                <?php endif; ?>

                <!-- Dropdown usuario: todos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <?= htmlspecialchars($nombre) ?>
                        <span class="badge bg-secondary ms-1" style="font-size:10px;">
                            <?= htmlspecialchars($rol) ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="editar.php">
                                <i class="fas fa-user-edit me-2"></i>Editar perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="cerrarSesion()">
                                <i class="fas fa-sign-out-alt me-2"></i>Salir
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>