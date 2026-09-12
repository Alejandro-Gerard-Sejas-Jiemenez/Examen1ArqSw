<?php
require_once __DIR__ . '/../../config/Conexion.php';

/**
 * Clase Modelo_Cliente
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Model
 * ElementID: 58
 */
class Modelo_Cliente {

    /**
     * Consulta todos los clientes registrados en la BD
     * Utilizado en CU3: Gestionar Cliente
     */
    public function consultarTodosBD() {
        $db = Conexion::getConexion();
        $stmt = $db->query("SELECT id, name, email, peso, altura FROM cliente ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Inserta un nuevo cliente en la BD
     * Utilizado en CU3: Gestionar Cliente
     */
    public function insertarClienteBD($name, $email, $password, $peso = null, $altura = null) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("INSERT INTO cliente (name, email, password, peso, altura) VALUES (:name, :email, :password, :peso, :altura)");
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':peso' => $peso,
            ':altura' => $altura
        ]);
    }

    /**
     * Actualiza los datos de un cliente en la BD
     */
    public function actualizarClienteBD($id, $name, $email, $peso = null, $altura = null) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("UPDATE cliente SET name = :name, email = :email, peso = :peso, altura = :altura WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':peso' => $peso,
            ':altura' => $altura
        ]);
    }

    /**
     * Elimina un cliente de la BD
     * Utilizado en CU3: Gestionar Cliente
     */
    public function eliminarClienteBD($id) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("DELETE FROM cliente WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Valida las credenciales del cliente en la BD
     * Utilizado en CU1: Iniciar Sesion
     */
    public function validarCredencialesBD($email, $password) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT id, name, email FROM cliente WHERE email = :email AND password = :password");
        $stmt->execute([':email' => $email, ':password' => $password]);
        return $stmt->fetch();
    }

    /**
     * Obtiene los datos del cliente por su correo electrónico
     */
    public function obtenerUsuarioPorEmailBD($email) {
        $db = Conexion::getConexion();
        $stmt = $db->prepare("SELECT id, name, email, password FROM cliente WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}
