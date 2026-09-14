<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Ejercicio
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 59
 */
class Modelo_Ejercicio
{

    /**
     * Inserta un nuevo ejercicio en la BD y devuelve el ID generado
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarEjercicioBD($nombre, $descripcion)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO ejercicio (nombre, descripcion) VALUES (:nombre, :descripcion)");
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
        return $db->lastInsertId();
    }

    /**
     * Actualiza un ejercicio existente
     */
    public function actualizarEjercicioBD($id, $nombre, $descripcion)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("UPDATE ejercicio SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion
        ]);
    }

    /**
     * Elimina un ejercicio de la BD
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function eliminarEjercicioBD($id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM ejercicio WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Consulta el catálogo completo de ejercicios con sus recursos multimedia
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function consultarConMultimediaBD()
    {
        $db = Conexion::getConexion();
        $sql = "SELECT e.id, e.nombre, e.descripcion,
                       (SELECT url_iamgen FROM imagen WHERE ejercicio_id = e.id ORDER BY id ASC LIMIT 1) AS imagen_url,
                       (SELECT url_video FROM video WHERE ejercicio_id = e.id ORDER BY id ASC LIMIT 1) AS video_url
                FROM ejercicio e
                ORDER BY e.id DESC";
        $stmt = $db->query($sql);
        $ejercicios = $stmt->fetchAll();

        foreach ($ejercicios as &$ej) {
            $stmtImg = $db->prepare("SELECT url_iamgen FROM imagen WHERE ejercicio_id = :id ORDER BY id ASC");
            $stmtImg->execute([':id' => $ej['id']]);
            $ej['todas_imagenes'] = $stmtImg->fetchAll(PDO::FETCH_COLUMN);
        }
        return $ejercicios;
    }

    /**
     * Obtiene los recursos multimedia asociados a un ejercicio específico
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function obtenerRecursosMultimediaBD($ejercicio_id)
    {
        $db = Conexion::getConexion();
        $stmtImg = $db->prepare("SELECT url_iamgen FROM imagen WHERE ejercicio_id = :id");
        $stmtImg->execute([':id' => $ejercicio_id]);
        $imagenes = $stmtImg->fetchAll();

        $stmtVid = $db->prepare("SELECT url_video FROM video WHERE ejercicio_id = :id");
        $stmtVid->execute([':id' => $ejercicio_id]);
        $videos = $stmtVid->fetchAll();

        return [
            'imagenes' => $imagenes,
            'videos' => $videos
        ];
    }
}
