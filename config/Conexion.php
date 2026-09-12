<?php
/**
 * Clase Conexion
 * Según Diagrama de Clases / Desplazamiento de Enterprise Architect
 * Paquete: Starter Class Diagram
 * Operación: getConexion()
 */
class Conexion {
    private static $pdo = null;

    // Configuración para MySQL
    private static $host = "localhost";
    private static $db_name = "gym_db";
    private static $username = "root";
    private static $password = "";

    /**
     * Retorna una instancia activa de PDO
     * Intenta conectar con MySQL y, si no está disponible, utiliza SQLite local automáticamente
     */
    public static function getConexion() {
        if (self::$pdo === null) {
            try {
                // Intento 1: Conexión a MySQL
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4";
                self::$pdo = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                // Intento 2: Fallback transparente a SQLite local para máxima portabilidad
                $sqlitePath = __DIR__ . '/../database/database.sqlite';
                $isNew = !file_exists($sqlitePath);
                self::$pdo = new PDO("sqlite:" . $sqlitePath);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                if ($isNew) {
                    self::inicializarSQLite(self::$pdo);
                }
            }
        }
        return self::$pdo;
    }

    private static function inicializarSQLite($pdo) {
        $queries = [
            "CREATE TABLE IF NOT EXISTS entrenador (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                password TEXT NOT NULL
            );",
            "CREATE TABLE IF NOT EXISTS cliente (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                peso REAL DEFAULT NULL,
                altura REAL DEFAULT NULL
            );",
            "CREATE TABLE IF NOT EXISTS ejercicio (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                descripcion TEXT DEFAULT NULL
            );",
            "CREATE TABLE IF NOT EXISTS imagen (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                url_iamgen TEXT NOT NULL,
                ejercicio_id INTEGER NOT NULL,
                FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
            );",
            "CREATE TABLE IF NOT EXISTS video (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                url_video TEXT NOT NULL,
                ejercicio_id INTEGER NOT NULL,
                FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
            );",
            "CREATE TABLE IF NOT EXISTS rutina (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nombre TEXT NOT NULL,
                indicaciones TEXT DEFAULT NULL,
                fecha_inicio TEXT DEFAULT NULL,
                fecha_fin TEXT DEFAULT NULL,
                cliente_id INTEGER NOT NULL,
                entrenador_id INTEGER NOT NULL,
                FOREIGN KEY (cliente_id) REFERENCES cliente(id) ON DELETE CASCADE,
                FOREIGN KEY (entrenador_id) REFERENCES entrenador(id) ON DELETE CASCADE
            );",
            "CREATE TABLE IF NOT EXISTS detalle_rutina (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                rutina_id INTEGER NOT NULL,
                ejercicio_id INTEGER NOT NULL,
                series INTEGER NOT NULL,
                repeticiones INTEGER NOT NULL,
                tiempo TEXT DEFAULT NULL,
                FOREIGN KEY (rutina_id) REFERENCES rutina(id) ON DELETE CASCADE,
                FOREIGN KEY (ejercicio_id) REFERENCES ejercicio(id) ON DELETE CASCADE
            );",
            "INSERT INTO entrenador (id, name, password) VALUES (1, 'Carlos Entrenador', '123456');",
            "INSERT INTO cliente (id, name, email, password, peso, altura) VALUES 
                (1, 'Juan Perez', 'juan@gmail.com', '123456', 75.50, 1.78),
                (2, 'Maria Lopez', 'maria@gmail.com', '123456', 62.00, 1.65);",
            "INSERT INTO ejercicio (id, nombre, descripcion) VALUES 
                (1, 'Sentadillas Profundas', 'Flexión de rodillas manteniendo la espalda recta.'),
                (2, 'Press de Banca', 'Empuje con barra en banco plano para pectoral.'),
                (3, 'Plancha Abdominal', 'Sostener posición isométrica en antebrazos.');",
            "INSERT INTO imagen (id, url_iamgen, ejercicio_id) VALUES 
                (1, 'https://images.unsplash.com/photo-1574680096145-d05b474e2155?w=500', 1),
                (2, 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=500', 2),
                (3, 'https://images.unsplash.com/photo-1566241142559-40e1dab266c6?w=500', 3);",
            "INSERT INTO video (id, url_video, ejercicio_id) VALUES 
                (1, 'https://www.w3schools.com/html/mov_bbb.mp4', 1),
                (2, 'https://www.w3schools.com/html/mov_bbb.mp4', 2),
                (3, 'https://www.w3schools.com/html/mov_bbb.mp4', 3);",
            "INSERT INTO rutina (id, nombre, indicaciones, fecha_inicio, fecha_fin, cliente_id, entrenador_id) VALUES 
                (1, 'Rutina Hipertrofia Semana 1', 'Descansar 90 segundos entre series y mantenerse hidratado.', '2026-09-01', '2026-09-30', 1, 1);",
            "INSERT INTO detalle_rutina (id, rutina_id, ejercicio_id, series, repeticiones, tiempo) VALUES 
                (1, 1, 1, 4, 12, '60 seg'),
                (2, 1, 2, 4, 10, '90 seg'),
                (3, 1, 3, 3, 1, '45 seg');"
        ];

        foreach ($queries as $sql) {
            $pdo->exec($sql);
        }
    }
}
