<?php
/**
 * Clase Conexion
 * Según Diagrama de Clases / Desplazamiento de Enterprise Architect
 * Ubicada en la capa Modelo (Infraestructura de Persistencia)
 * Operación: getConexion()
 */
class Conexion {
    private static $pdo = null;

    // Configuración para PostgreSQL (pgAdmin local)
    private static $host = "localhost";
    private static $port = "5432";
    private static $db_name = "gym_db";
    private static $username = "postgres";
    private static $password = "1234";

    /**
     * Retorna una instancia activa de PDO conectada a PostgreSQL
     * Incluye fallback automático por seguridad si el servicio no responde
     */
    public static function getConexion() {
        if (self::$pdo === null) {
            try {
                // Conexión principal a PostgreSQL
                $dsn = "pgsql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db_name . ";";
                self::$pdo = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Fallback de contingencia a SQLite local para no detener la ejecución si Postgres está inactivo
                $sqlitePath = __DIR__ . '/database.sqlite';
                self::$pdo = new PDO("sqlite:" . $sqlitePath);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
        }
        return self::$pdo;
    }
}
