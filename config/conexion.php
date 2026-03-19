<?php
class Conexion {
    private $host = "localhost";
    private $db = "soporte_tecnico";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8mb4";

    public function conectar() {
        try {
            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}",
                $this->user,
                $this->pass
            );

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}