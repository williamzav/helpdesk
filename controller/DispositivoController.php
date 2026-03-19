<?php
// ==========================================
// CONTROLLER: DispositivoController.php
// ==========================================
session_start();
require_once __DIR__ . '/../models/DispositivoModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'msg' => 'Sesión no iniciada']);
    exit;
}

$model  = new DispositivoModel();
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$rol    = $_SESSION['rol'];

switch ($action) {

    // ── LISTAR ────────────────────────────────────────────────
    case 'listar':
        if ($rol === 'Admin' || $rol === 'Tecnico') {
            $data = $model->getAll();
        } else {
            $data = $model->getByUsuario($_SESSION['id_usuario']);
        }
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    // ── OBTENER POR ID ────────────────────────────────────────
    case 'get':
        $id   = $_POST['id_dispositivo'] ?? 0;
        $data = $model->getById($id);
        echo json_encode(['success' => (bool)$data, 'data' => $data]);
        break;

    // ── CREAR ─────────────────────────────────────────────────
    case 'crear':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $data = [
            'id_usuario'   => null,
            'tipo'         => trim($_POST['tipo']         ?? ''),
            'marca'        => trim($_POST['marca']        ?? ''),
            'modelo'       => trim($_POST['modelo']       ?? ''),
            'color'        => trim($_POST['color']        ?? ''),
            'descripcion'  => trim($_POST['descripcion']  ?? ''),
            'memoria'      => trim($_POST['memoria']      ?? ''),
            'disco_duro'   => trim($_POST['disco_duro']   ?? ''),
            'procesador'   => trim($_POST['procesador']   ?? ''),
            'imagen'       => trim($_POST['imagen']       ?? '')
        ];
        $ok = $model->crear($data);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Dispositivo registrado correctamente' : 'Error al registrar'
        ]);
        break;

    // ── EDITAR ────────────────────────────────────────────────
    case 'editar':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $data = [
            'id_dispositivo' => $_POST['id_dispositivo'] ?? 0,
            'tipo'           => trim($_POST['tipo']         ?? ''),
            'marca'          => trim($_POST['marca']        ?? ''),
            'modelo'         => trim($_POST['modelo']       ?? ''),
            'color'          => trim($_POST['color']        ?? ''),
            'descripcion'    => trim($_POST['descripcion']  ?? ''),
            'memoria'        => trim($_POST['memoria']      ?? ''),
            'disco_duro'     => trim($_POST['disco_duro']   ?? ''),
            'procesador'     => trim($_POST['procesador']   ?? ''),
            'imagen'         => trim($_POST['imagen']       ?? '')
        ];
        $ok = $model->editar($data);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Dispositivo actualizado correctamente' : 'Error al actualizar'
        ]);
        break;

    // ── ELIMINAR ──────────────────────────────────────────────
    case 'eliminar':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $id       = $_POST['id_dispositivo'] ?? 0;
        $validar  = $model->puedeEliminar($id);
        if (!$validar['puede']) {
            echo json_encode(['success' => false, 'msg' => $validar['msg']]);
            exit;
        }
        $ok = $model->eliminar($id);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Dispositivo eliminado correctamente' : 'Error al eliminar'
        ]);
        break;


    // ── ASIGNAR USUARIO ───────────────────────────────────────
    case 'asignar_usuario':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $id_disp = $_POST['id_dispositivo'] ?? 0;
        $id_usu  = $_POST['id_usuario'] ?? 0;
        if (!$id_disp || !$id_usu) {
            echo json_encode(['success' => false, 'msg' => 'Datos incompletos']);
            exit;
        }
        $ok = $model->asignarUsuario($id_disp, $id_usu);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Usuario asignado correctamente' : 'Error al asignar'
        ]);
        break;

    // ── QUITAR USUARIO ────────────────────────────────────────
    case 'quitar_usuario':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $id_disp = $_POST['id_dispositivo'] ?? 0;
        $ok = $model->quitarUsuario($id_disp);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Usuario quitado correctamente' : 'Error al quitar usuario'
        ]);
        break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Acción no reconocida']);
        break;
}
?>