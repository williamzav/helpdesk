<?php
// ==========================================
// CONTROLLER: ReporteController.php
// ==========================================
session_start();
require_once __DIR__ . '/../models/ReporteModel.php';
require_once __DIR__ . '/../models/DispositivoModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'msg' => 'Sesión no iniciada']);
    exit;
}

$model      = new ReporteModel();
$modelDisp  = new DispositivoModel();
$action     = $_POST['action'] ?? $_GET['action'] ?? '';
$rol        = $_SESSION['rol'];

switch ($action) {

    // ── LISTAR TODOS (Admin/Técnico) ──────────────────────────
    case 'listar':
        if ($rol === 'Admin' || $rol === 'Tecnico') {
            $data = $model->getAll();
        } else {
            $data = $model->getByUsuario($_SESSION['id_usuario']);
        }
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    // ── MIS REPORTES (Admin/Cliente) ──────────────────────────
    case 'mis_reportes':
        $data = $model->getByUsuario($_SESSION['id_usuario']);
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    // ── OBTENER POR ID ────────────────────────────────────────
    case 'get':
        $id   = $_POST['id_reporte'] ?? 0;
        $data = $model->getById($id);
        echo json_encode(['success' => (bool)$data, 'data' => $data]);
        break;

    // ── CREAR ─────────────────────────────────────────────────
    case 'crear':
        $data = [
            'id_usuario'     => $_SESSION['id_usuario'],
            'id_dispositivo' => $_POST['id_dispositivo'] ?? null,
            'descripcion'    => trim($_POST['descripcion'] ?? '')
        ];
        if (empty($data['descripcion'])) {
            echo json_encode(['success' => false, 'msg' => 'La descripción es obligatoria']);
            exit;
        }
        $ok = $model->crear($data);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Reporte creado correctamente' : 'Error al crear el reporte'
        ]);
        break;

    // ── ACTUALIZAR (estado y solución) ────────────────────────
    case 'actualizar':
        if ($rol !== 'Admin' && $rol !== 'Tecnico') {
            echo json_encode(['success' => false, 'msg' => 'Sin permisos']);
            exit;
        }
        $data = [
            'id_reporte' => $_POST['id_reporte'] ?? 0,
            'estado'     => trim($_POST['estado']   ?? 'Abierto'),
            'solucion'   => trim($_POST['solucion'] ?? '')
        ];
        $ok = $model->actualizar($data);
        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Reporte actualizado correctamente' : 'Error al actualizar'
        ]);
        break;

    // ── ELIMINAR ──────────────────────────────────────────────
    case 'eliminar':
        $id = $_POST['id_reporte'] ?? 0;
        $validar = $model->puedeEliminar($id);
        if (!$validar['puede']) {
            echo json_encode(['success' => false, 'msg' => $validar['msg']]);
            exit;
        }
        $ok = $model->eliminar($id);
        echo json_encode([
            'success'    => $ok,
            'msg'        => $ok ? 'Reporte eliminado correctamente' : 'Error al eliminar',
            'valoracion' => $validar['valoracion'] ?? ''
        ]);
        break;


    // ── VALORAR REPORTE ───────────────────────────────────────
    case 'valorar':
        $id_reporte = $_POST['id_reporte'] ?? 0;
        $valoracion = trim($_POST['valoracion'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!in_array($valoracion, ['resuelto', 'no_resuelto'])) {
            echo json_encode(['success' => false, 'msg' => 'Valoración inválida']);
            exit;
        }

        if ($valoracion === 'no_resuelto') {
            // Reabrir reporte con nueva descripción
            if (empty($descripcion)) {
                echo json_encode(['success' => false, 'msg' => 'La nueva descripción es requerida']);
                exit;
            }
            $ok = $model->reabrir($id_reporte, $descripcion);
        } else {
            // Marcar como resuelto
            $ok = $model->valorar($id_reporte, $valoracion);
        }

        echo json_encode([
            'success' => $ok,
            'msg'     => $ok ? 'Valoración registrada' : 'Error al valorar'
        ]);
        break;

    // ── LISTAR DISPOSITIVOS DEL USUARIO (para select al crear) ─
    case 'mis_dispositivos':
        $data = $modelDisp->getByUsuario($_SESSION['id_usuario']);
        echo json_encode(['success' => true, 'data' => $data]);
        break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Acción no reconocida']);
        break;
}
?>