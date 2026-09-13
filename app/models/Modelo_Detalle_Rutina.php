<?php
require_once __DIR__ . '/Conexion.php';

/**
 * Clase Modelo_Detalle_Rutina
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 69
 */
class Modelo_Detalle_Rutina {

    /**
     * Inserta un lote de detalles de ejercicios para una rutina
     * Utilizado en CU5: Gestionar Rutina
     */
    public function insertarDetalleLoteBD($rutina_id, array $detalles) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO detalle_rutina (rutina_id, ejercicio_id, series, repeticiones, tiempo) 
                              VALUES (:rutina_id, :ejercicio_id, :series, :repeticiones, :tiempo)");
        
        foreach ($detalles as $item) {
            $stmt->execute([
                ':rutina_id' => $rutina_id,
                ':ejercicio_id' => $item['ejercicio_id'],
                ':series' => $item['series'],
                ':repeticiones' => $item['repeticiones'],
                ':tiempo' => $item['tiempo'] ?? null
            ]);
        }
        return true;
    }

    /**
     * Elimina todos los detalles de ejercicios pertenecientes a una rutina
     * Utilizado en CU5: Gestionar Rutina
     */
    public function eliminarDetallesPorRutinaBD($rutina_id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM detalle_rutina WHERE rutina_id = :rutina_id");
        return $stmt->execute([':rutina_id' => $rutina_id]);
    }

    /**
     * Consulta los ejercicios asignados en el detalle de una rutina
     * Utilizado en CU7: Visualizar Rutina Asignada
     */
    public function consultarEjerciciosAsignadosBD($rutina_id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT dr.*, e.nombre AS nombre_ejercicio, e.descripcion AS descripcion_ejercicio,
                                     (SELECT url_iamgen FROM imagen WHERE ejercicio_id = e.id LIMIT 1) AS imagen_url,
                                     (SELECT url_video FROM video WHERE ejercicio_id = e.id LIMIT 1) AS video_url
                              FROM detalle_rutina dr
                              INNER JOIN ejercicio e ON dr.ejercicio_id = e.id
                              WHERE dr.rutina_id = :rutina_id
                              ORDER BY dr.id ASC");
        $stmt->execute([':rutina_id' => $rutina_id]);
        return $stmt->fetchAll();
    }
}
