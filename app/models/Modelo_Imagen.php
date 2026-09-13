<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Imagen
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 68
 */
class Modelo_Imagen
{

    /**
     * Inserta una nueva imagen asociada a un ejercicio y devuelve su ID
     * Utilizado en CU4: Gestionar Ejercicio con Multimedia
     */
    public function insertarImagenBD($url_iamgen, $ejercicio_id)
    {
        // Si se envió un archivo desde el dispositivo ($_FILES), gestiona el almacenamiento físico
        if (is_array($url_iamgen) && !empty($url_iamgen['tmp_name']) && $url_iamgen['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(__DIR__, 2) . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = pathinfo($url_iamgen['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $nombreArchivo = 'img_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($url_iamgen['tmp_name'], $uploadDir . $nombreArchivo)) {
                $url_iamgen = 'uploads/' . $nombreArchivo;
            } else {
                $url_iamgen = '';
            }
        }

        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO imagen (url_iamgen, ejercicio_id) VALUES (:url_iamgen, :ejercicio_id)");
        $stmt->execute([
            ':url_iamgen' => is_string($url_iamgen) ? $url_iamgen : '',
            ':ejercicio_id' => $ejercicio_id
        ]);
        return $db->lastInsertId();
    }

    /**
     * Elimina una imagen de la BD
     */
    public function eliminarImagenBD($id)
    {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM imagen WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
