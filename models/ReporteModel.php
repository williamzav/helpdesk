<?php
// ==========================================
// MODEL: ReporteModel.php
// ==========================================
require_once __DIR__ . '/../config/conexion.php';

class ReporteModel {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // ── Listar todos (Admin/Técnico) ──────────────────────────
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT r.*, r.valoracion,
                   u.nombre AS nombre_usuario, u.paterno AS paterno_usuario,
                   d.tipo, d.marca, d.modelo
            FROM T_Reportes r
            LEFT JOIN T_Usuario u ON r.id_usuario = u.id_usuario
            LEFT JOIN T_Dispositivos d ON r.id_dispositivo = d.id_dispositivo
            ORDER BY r.id_reporte DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Listar por usuario (Cliente/Admin mis reportes) ───────
    public function getByUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT r.*,
                   d.tipo, d.marca, d.modelo
            FROM T_Reportes r
            LEFT JOIN T_Dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.id_usuario = ?
            ORDER BY r.id_reporte DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    // ── Obtener por ID ────────────────────────────────────────
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT r.*,
                   u.nombre AS nombre_usuario, u.paterno AS paterno_usuario,
                   d.tipo, d.marca, d.modelo
            FROM T_Reportes r
            LEFT JOIN T_Usuario u ON r.id_usuario = u.id_usuario
            LEFT JOIN T_Dispositivos d ON r.id_dispositivo = d.id_dispositivo
            WHERE r.id_reporte = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear ─────────────────────────────────────────────────
    public function crear($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO T_Reportes (id_usuario, id_dispositivo, descripcion, estado, fecha)
            VALUES (?, ?, ?, 'Abierto', CURDATE())
        ");
        return $stmt->execute([
            $data['id_usuario'], $data['id_dispositivo'], $data['descripcion']
        ]);
    }

    // ── Actualizar estado y solución ──────────────────────────
    public function actualizar($data) {
        // Si se cierra el reporte, limpiar valoración para que el cliente vuelva a valorar
        $stmt = $this->pdo->prepare("
            UPDATE T_Reportes SET
                estado = ?,
                solucion = ?,
                valoracion = NULL
            WHERE id_reporte = ?
        ");
        return $stmt->execute([
            $data['estado'], $data['solucion'], $data['id_reporte']
        ]);
    }

    // ── Eliminar ──────────────────────────────────────────────
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM T_Reportes WHERE id_reporte = ?
        ");
        return $stmt->execute([$id]);
    }


    // ── Reabrir reporte con nueva descripción ─────────────────
    public function reabrir($id_reporte, $descripcion) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Reportes SET
                estado = 'Abierto',
                descripcion = ?,
                valoracion = 'no_resuelto',
                solucion = NULL
            WHERE id_reporte = ?
        ");
        return $stmt->execute([$descripcion, $id_reporte]);
    }
    // ── Valorar reporte ───────────────────────────────────────
    public function valorar($id_reporte, $valoracion) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Reportes SET valoracion = ? WHERE id_reporte = ?
        ");
        return $stmt->execute([$valoracion, $id_reporte]);
    }

    // ── Verificar si puede eliminarse ─────────────────────────
    public function puedeEliminar($id_reporte) {
        $stmt = $this->pdo->prepare("
            SELECT estado, valoracion FROM T_Reportes WHERE id_reporte = ? LIMIT 1
        ");
        $stmt->execute([$id_reporte]);
        $r = $stmt->fetch();
        if (!$r) return ['puede' => false, 'msg' => 'Reporte no encontrado'];
        if ($r['estado'] !== 'Cerrado') return ['puede' => false, 'msg' => 'Solo se pueden eliminar reportes cerrados'];
        if (empty($r['valoracion'])) return ['puede' => false, 'msg' => 'Debes valorar el reporte antes de eliminarlo'];
        return ['puede' => true, 'msg' => '', 'valoracion' => $r['valoracion']];
    }
}
?>