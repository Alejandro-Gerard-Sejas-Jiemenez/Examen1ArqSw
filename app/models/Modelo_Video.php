<?php
require_once __DIR__ . '/../../config/Conexion.php';

/**
 * Clase Modelo_Video
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 67
 */
class Modelo_Video {

    /**
     * Inserta un nuevo video asociado a un ejercicio y devuelve su ID
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarVideoBD($url_video, $ejercicio_id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO video (url_video, ejercicio_id) VALUES (:url_video, :ejercicio_id)");
        $stmt->execute([
            ':url_video' => $url_video,
            ':ejercicio_id' => $ejercicio_id
        ]);
        return $db->lastInsertId();
    }

    /**
     * Elimina un video de la BD
     */
    public function eliminarVideoBD($id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM video WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
