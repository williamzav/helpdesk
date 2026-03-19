<?php
// ==========================================
// CONTROLLER: UsuarioController.php
// Recibe peticiones AJAX, responde JSON
// ==========================================

session_start();
require_once __DIR__ . '/../models/UsuarioModel.php';

header('Content-Type: application/json');

$model  = new UsuarioModel();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ── LOGIN ─────────────────────────────────────────────────────
    case 'login':
        $usuario  = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($usuario) || empty($password)) {
            echo json_encode(['success' => false, 'msg' => 'Usuario y contraseña son requeridos']);
            exit;
        }

        $user = $model->login($usuario);

        if (!$user || !password_verify($password, $user['password'])) {
            echo json_encode(['success' => false, 'msg' => 'Usuario o contraseña incorrectos']);
            exit;
        }

        // Guardar sesión
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nombre']     = $user['nombre'];
        $_SESSION['paterno']    = $user['paterno'];
        $_SESSION['rol']        = $user['rol'];
        $_SESSION['usuario']    = $user['usuario'];
        $_SESSION['correo']     = $user['correo'];

        // Redirect según rol
        $redirect = match($user['rol']) {
            'Admin'   => 'public/vistas/inicio.php',
            'Tecnico' => 'public/vistas/inicio.php',
            'Cliente' => 'public/vistas/inicio.php',
            default   => 'index.html'
        };

        echo json_encode([
            'success'  => true,
            'redirect' => $redirect,
            'nombre'   => $user['nombre'],
            'rol'      => $user['rol']
        ]);
        break;

    // ── CONTRASEÑA TEMPORAL ───────────────────────────────────────
    case 'password_temporal':
        $usuario = trim($_POST['usuario'] ?? '');
        $correo  = trim($_POST['correo'] ?? '');

        if (empty($usuario) || empty($correo)) {
            echo json_encode(['success' => false, 'msg' => 'Usuario y correo son requeridos']);
            exit;
        }

        $user = $model->verificarUsuarioCorreo($usuario, $correo);

        if (!$user) {
            echo json_encode(['success' => false, 'msg' => 'No se encontró ninguna cuenta con esos datos']);
            exit;
        }

        // Generar contraseña temporal de 8 caracteres
        $caracteres   = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789@#$!';
        $tempPassword = '';
        for ($i = 0; $i < 8; $i++) {
            $tempPassword .= $caracteres[random_int(0, strlen($caracteres) - 1)];
        }

        $hash = password_hash($tempPassword, PASSWORD_DEFAULT);
        $ok   = $model->actualizarPassword($user['id_usuario'], $hash);

        if ($ok) {
            echo json_encode([
                'success'  => true,
                'msg'      => 'Contraseña temporal generada',
                'password' => $tempPassword,
                'nombre'   => $user['nombre']
            ]);
        } else {
            echo json_encode(['success' => false, 'msg' => 'Error al actualizar la contraseña']);
        }
        break;

    // ── OBTENER TODOS LOS USUARIOS (Admin) ────────────────────────
    case 'listar':
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Admin') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $usuarios = $model->getAll();
        echo json_encode(['success' => true, 'data' => $usuarios]);
        break;

    // ── CREAR USUARIO (Admin) ─────────────────────────────────────
    case 'crear':
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Admin') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }

        $campos = ['paterno','materno','nombre','fecha_nac','sexo',
                   'telefono','correo','usuario','password','rol','ubicacion'];
        $data = [];
        foreach ($campos as $campo) {
            $data[$campo] = trim($_POST[$campo] ?? '');
        }

        if (empty($data['usuario']) || empty($data['password'])) {
            echo json_encode(['success' => false, 'msg' => 'Usuario y contraseña son obligatorios']);
            exit;
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $ok = $model->crear($data);

        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Usuario creado correctamente' : 'Error al crear el usuario'
        ]);
        break;

    // ── EDITAR USUARIO (Admin) ────────────────────────────────────
    case 'editar':
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Admin') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }

        $data = [
            'id_usuario' => $_POST['id_usuario'] ?? 0,
            'paterno'    => trim($_POST['paterno'] ?? ''),
            'materno'    => trim($_POST['materno'] ?? ''),
            'nombre'     => trim($_POST['nombre'] ?? ''),
            'fecha_nac'  => trim($_POST['fecha_nac'] ?? ''),
            'sexo'       => trim($_POST['sexo'] ?? ''),
            'telefono'   => trim($_POST['telefono'] ?? ''),
            'correo'     => trim($_POST['correo'] ?? ''),
            'usuario'    => trim($_POST['usuario'] ?? ''),
            'rol'        => trim($_POST['rol'] ?? ''),
            'ubicacion'  => trim($_POST['ubicacion'] ?? '')
        ];

        $ok = $model->editar($data);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Usuario actualizado' : 'Error al actualizar'
        ]);
        break;

    // ── CAMBIAR ESTADO ────────────────────────────────────────────
    case 'cambiar_estado':
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Admin') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }

        $id_usuario = $_POST['id_usuario'] ?? 0;
        $estado     = $_POST['estado'] ?? 1;
        $ok         = $model->cambiarEstado($id_usuario, $estado);

        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Estado actualizado' : 'Error al cambiar estado'
        ]);
        break;

    // ── EDITAR PERFIL PROPIO ──────────────────────────────────────
    case 'editar_perfil':
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Sesión no iniciada']);
            exit;
        }

        $data = [
            'id_usuario' => $_SESSION['id_usuario'],
            'paterno'    => trim($_POST['paterno']   ?? ''),
            'materno'    => trim($_POST['materno']   ?? ''),
            'nombre'     => trim($_POST['nombre']    ?? ''),
            'fecha_nac'  => trim($_POST['fecha_nac'] ?? ''),
            'sexo'       => trim($_POST['sexo']      ?? ''),
            'telefono'   => trim($_POST['telefono']  ?? ''),
            'correo'     => trim($_POST['correo']    ?? ''),
            'usuario'    => trim($_POST['usuario']   ?? ''),
            'ubicacion'  => trim($_POST['ubicacion'] ?? '')
        ];

        $ok = $model->editarPerfil($data);

        if ($ok) {
            $_SESSION['nombre']  = $data['nombre'];
            $_SESSION['correo']  = $data['correo'];
            $_SESSION['usuario'] = $data['usuario'];
        }

        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Perfil actualizado correctamente' : 'Error al actualizar perfil'
        ]);
        break;

    // ── CAMBIAR CONTRASEÑA PERFIL ─────────────────────────────────
    case 'cambiar_password':
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Sesión no iniciada']);
            exit;
        }

        $actual   = trim($_POST['password_actual'] ?? '');
        $nueva    = trim($_POST['password_nueva'] ?? '');
        $confirma = trim($_POST['password_confirma'] ?? '');

        if (empty($actual) || empty($nueva) || empty($confirma)) {
            echo json_encode(['success' => false, 'msg' => 'Todos los campos son requeridos']);
            exit;
        }

        if ($nueva !== $confirma) {
            echo json_encode(['success' => false, 'msg' => 'Las contraseñas no coinciden']);
            exit;
        }

        // Verificar contraseña actual
        $user = $model->login($_SESSION['usuario']);
        if (!$user || !password_verify($actual, $user['password'])) {
            echo json_encode(['success' => false, 'msg' => 'La contraseña actual es incorrecta']);
            exit;
        }

        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $ok   = $model->cambiarPasswordPerfil($_SESSION['id_usuario'], $hash);

        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Contraseña actualizada correctamente' : 'Error al cambiar la contraseña'
        ]);
        break;

    // ── OBTENER DATOS DE SESIÓN ───────────────────────────────────
    case 'get_sesion':
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Sin sesión']);
            exit;
        }
        $user = $model->getById($_SESSION['id_usuario']);
        echo json_encode(['success' => true, 'data' => $user]);
        break;


    // ── BUSCAR USUARIOS ───────────────────────────────────────
    case 'buscar':
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['success' => false, 'msg' => 'Sin sesión']);
            exit;
        }
        $buscar = trim($_POST['buscar'] ?? '');
        if (empty($buscar)) {
            echo json_encode(['success' => true, 'data' => []]);
            exit;
        }
        $data = $model->buscar($buscar);
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    // ── REGISTRO PÚBLICO ─────────────────────────────────────────
    case 'registro':
        $campos_req = ['nombre', 'paterno', 'correo', 'usuario', 'password'];
        foreach ($campos_req as $c) {
            if (empty(trim($_POST[$c] ?? ''))) {
                echo json_encode(['success' => false, 'msg' => 'Campos obligatorios incompletos']);
                exit;
            }
        }

        $rol_reg     = trim($_POST['rol'] ?? 'Cliente');
        $clave_sec   = trim($_POST['clave_secreta'] ?? '');
        $CLAVE_ROLES = '12345';

        // Validar clave secreta para Técnico y Admin
        if (($rol_reg === 'Tecnico' || $rol_reg === 'Admin') && $clave_sec !== $CLAVE_ROLES) {
            echo json_encode(['success' => false, 'msg' => 'Clave secreta incorrecta para ese rol']);
            exit;
        }

        $data_reg = [
            'paterno'   => trim($_POST['paterno']   ?? ''),
            'materno'   => trim($_POST['materno']   ?? ''),
            'nombre'    => trim($_POST['nombre']    ?? ''),
            'fecha_nac' => trim($_POST['fecha_nac'] ?? ''),
            'sexo'      => trim($_POST['sexo']      ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'correo'    => trim($_POST['correo']    ?? ''),
            'usuario'   => trim($_POST['usuario']   ?? ''),
            'password'  => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
            'rol'       => $rol_reg,
            'ubicacion' => trim($_POST['ubicacion'] ?? '')
        ];

        try {
            $ok_reg = $model->crear($data_reg);
            echo json_encode([
                'success' => $ok_reg,
                'msg'     => $ok_reg ? 'Usuario registrado correctamente' : 'Error al registrar'
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                echo json_encode(['success' => false, 'msg' => 'El usuario o correo ya está registrado']);
            } else {
                echo json_encode(['success' => false, 'msg' => 'Error del servidor']);
            }
        }
        break;

    // ── LOGOUT ────────────────────────────────────────────────────
    case 'logout':
        session_destroy();
        echo json_encode(['success' => true, 'redirect' => '../../index.html']);
        break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Acción no reconocida']);
        break;
}
?>