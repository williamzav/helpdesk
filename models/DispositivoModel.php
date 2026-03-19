<?php
// ==========================================
// MODEL: DispositivoModel.php
// ==========================================
require_once __DIR__ . '/../config/conexion.php';

class DispositivoModel {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // ── Listar todos ──────────────────────────────────────────
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT d.*, u.nombre, u.paterno
            FROM T_Dispositivos d
            LEFT JOIN T_Usuario u ON d.id_usuario = u.id_usuario
            ORDER BY d.id_dispositivo DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Listar por usuario ────────────────────────────────────
    public function getByUsuario($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM T_Dispositivos
            WHERE id_usuario = ?
            ORDER BY id_dispositivo DESC
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    // ── Obtener por ID ────────────────────────────────────────
    public function getById($id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM T_Dispositivos WHERE id_dispositivo = ? LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ── Crear ─────────────────────────────────────────────────
    public function crear($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO T_Dispositivos
                (id_usuario, tipo, marca, modelo, color,
                 descripcion, memoria, disco_duro, procesador, imagen)
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['tipo'],    $data['marca'],    $data['modelo'],
            $data['color'],   $data['descripcion'], $data['memoria'],
            $data['disco_duro'], $data['procesador'], $data['imagen']
        ]);
    }

    // ── Editar ────────────────────────────────────────────────
    public function editar($data) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Dispositivos SET
                tipo = ?, marca = ?, modelo = ?, color = ?,
                descripcion = ?, memoria = ?, disco_duro = ?,
                procesador = ?, imagen = ?
            WHERE id_dispositivo = ?
        ");
        return $stmt->execute([
            $data['tipo'],       $data['marca'],      $data['modelo'],
            $data['color'],      $data['descripcion'],$data['memoria'],
            $data['disco_duro'], $data['procesador'], $data['imagen'],
            $data['id_dispositivo']
        ]);
    }

    // ── Eliminar ──────────────────────────────────────────────
    public function eliminar($id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM T_Dispositivos WHERE id_dispositivo = ?
        ");
        return $stmt->execute([$id]);
    }


    // ── Verificar si tiene usuario o reportes antes de eliminar ──
    public function puedeEliminar($id) {
        // Verificar usuario asignado
        $stmt = $this->pdo->prepare("
            SELECT id_usuario FROM T_Dispositivos 
            WHERE id_dispositivo = ? AND id_usuario IS NOT NULL LIMIT 1
        ");
        $stmt->execute([$id]);
        if ($stmt->fetch()) return ['puede' => false, 'msg' => 'El dispositivo tiene un usuario asignado. Quítalo antes de eliminar.'];

        // Verificar reportes asociados
        $stmt2 = $this->pdo->prepare("
            SELECT id_reporte FROM T_Reportes 
            WHERE id_dispositivo = ? LIMIT 1
        ");
        $stmt2->execute([$id]);
        if ($stmt2->fetch()) return ['puede' => false, 'msg' => 'El dispositivo tiene reportes asociados y no puede eliminarse.'];

        return ['puede' => true, 'msg' => ''];
    }
    // ── Asignar usuario ───────────────────────────────────────
    public function asignarUsuario($id_dispositivo, $id_usuario) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Dispositivos SET id_usuario = ? WHERE id_dispositivo = ?
        ");
        return $stmt->execute([$id_usuario, $id_dispositivo]);
    }

    // ── Quitar usuario ────────────────────────────────────────
    public function quitarUsuario($id_dispositivo) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Dispositivos SET id_usuario = NULL WHERE id_dispositivo = ?
        ");
        return $stmt->execute([$id_dispositivo]);
    }
}
?>