<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Imagen
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 68
 */
class Modelo_Imagen {

    /**
     * Inserta una nueva imagen asociada a un ejercicio y devuelve su ID
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarImagenBD($url_iamgen, $ejercicio_id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO imagen (url_iamgen, ejercicio_id) VALUES (:url_iamgen, :ejercicio_id)");
        $stmt->execute([
            ':url_iamgen' => $url_iamgen,
            ':ejercicio_id' => $ejercicio_id
        ]);
        return $db->lastInsertId();
    }

    /**
     * Elimina una imagen de la BD
     */
    public function eliminarImagenBD($id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM imagen WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
