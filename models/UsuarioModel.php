<?php
// ==========================================
// MODEL: UsuarioModel.php
// Solo consultas SQL con PDO
// ==========================================

require_once __DIR__ . '/../config/conexion.php';

class UsuarioModel {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // ── Buscar usuario por usuario y contraseña ──────────────────
    public function login($usuario) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre, paterno, materno, correo,
                   usuario, password, rol, telefono, ubicacion
            FROM T_Usuario
            WHERE usuario = ? AND estado = 1
            LIMIT 1
        ");
        $stmt->execute([$usuario]);
        return $stmt->fetch();
    }

    // ── Verificar que usuario y correo coincidan ──────────────────
    public function verificarUsuarioCorreo($usuario, $correo) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre FROM T_Usuario
            WHERE usuario = ? AND correo = ? AND estado = 1
            LIMIT 1
        ");
        $stmt->execute([$usuario, $correo]);
        return $stmt->fetch();
    }

    // ── Actualizar contraseña temporal ────────────────────────────
    public function actualizarPassword($id_usuario, $passwordHash) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Usuario SET password = ? WHERE id_usuario = ?
        ");
        return $stmt->execute([$passwordHash, $id_usuario]);
    }

    // ── Obtener usuario por ID ────────────────────────────────────
    public function getById($id_usuario) {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre, paterno, materno, correo,
                   usuario, rol, telefono, ubicacion, fecha_nac, sexo
            FROM T_Usuario WHERE id_usuario = ? LIMIT 1
        ");
        $stmt->execute([$id_usuario]);
        return $stmt->fetch();
    }

    // ── Obtener todos los usuarios ────────────────────────────────
    public function getAll() {
        $stmt = $this->pdo->prepare("
            SELECT id_usuario, nombre, paterno, materno, correo,
                   usuario, rol, telefono, ubicacion, estado
            FROM T_Usuario
            ORDER BY id_usuario DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ── Crear usuario ─────────────────────────────────────────────
    public function crear($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO T_Usuario
                (paterno, materno, nombre, fecha_nac, sexo, telefono,
                 correo, usuario, password, rol, ubicacion, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        return $stmt->execute([
            $data['paterno'], $data['materno'], $data['nombre'],
            $data['fecha_nac'], $data['sexo'], $data['telefono'],
            $data['correo'], $data['usuario'], $data['password'],
            $data['rol'], $data['ubicacion']
        ]);
    }

    // ── Editar usuario ────────────────────────────────────────────
    public function editar($data) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Usuario SET
                paterno = ?, materno = ?, nombre = ?, fecha_nac = ?,
                sexo = ?, telefono = ?, correo = ?, usuario = ?,
                rol = ?, ubicacion = ?
            WHERE id_usuario = ?
        ");
        return $stmt->execute([
            $data['paterno'], $data['materno'], $data['nombre'],
            $data['fecha_nac'], $data['sexo'], $data['telefono'],
            $data['correo'], $data['usuario'], $data['rol'],
            $data['ubicacion'], $data['id_usuario']
        ]);
    }

    // ── Cambiar estado (activar/desactivar) ───────────────────────
    public function cambiarEstado($id_usuario, $estado) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Usuario SET estado = ? WHERE id_usuario = ?
        ");
        return $stmt->execute([$estado, $id_usuario]);
    }

    // ── Actualizar perfil propio (editar.php) ─────────────────────
    public function editarPerfil($data) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Usuario SET
                paterno = ?, materno = ?, nombre = ?,
                fecha_nac = ?, sexo = ?, telefono = ?,
                correo = ?, usuario = ?, ubicacion = ?
            WHERE id_usuario = ?
        ");
        return $stmt->execute([
            $data['paterno'], $data['materno'], $data['nombre'],
            $data['fecha_nac'], $data['sexo'], $data['telefono'],
            $data['correo'], $data['usuario'], $data['ubicacion'],
            $data['id_usuario']
        ]);
    }

    // ── Cambiar contraseña (editar.php) ───────────────────────────
    public function cambiarPasswordPerfil($id_usuario, $passwordHash) {
        $stmt = $this->pdo->prepare("
            UPDATE T_Usuario SET password = ? WHERE id_usuario = ?
        ");
        return $stmt->execute([$passwordHash, $id_usuario]);
    }
}
?>