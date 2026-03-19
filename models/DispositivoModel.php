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
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['id_usuario'], $data['tipo'],   $data['marca'],
            $data['modelo'],     $data['color'],  $data['descripcion'],
            $data['memoria'],    $data['disco_duro'], $data['procesador'],
            $data['imagen']
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
}
?>