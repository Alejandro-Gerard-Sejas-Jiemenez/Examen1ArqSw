<?php
require_once __DIR__ . '/../models/Modelo_Cliente.php';
require_once __DIR__ . '/../views/Vista_Cliente.php';

/**
 * Clase Controlador_Cliente
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia (CU3)
 * Paquete: Diagramas de Desplazamiento / Controller
 * ElementID: 52
 */
class Controlador_Cliente {

    private $modeloCliente;
    private $vistaCliente;

    public function __construct() {
        $this->modeloCliente = new Modelo_Cliente();
        $this->vistaCliente = new Vista_Cliente();
    }

    /**
     * Lista todos los clientes y solicita a la vista desplegarlos
     * Utilizado en CU3: Gestionar Cliente
     */
    public function listarClientes($id = null, $datos = null) {
        $lista = $this->modeloCliente->consultarTodosBD();
        $this->vistaCliente->desplegarTablaClientes($lista);
    }

    /**
     * Muestra formulario para nuevo cliente
     */
    public function capturarDatosCliente() {
        $this->vistaCliente->capturarDatosCliente();
    }

    /**
     * Registra un nuevo cliente en la BD
     * Utilizado en CU3: Gestionar Cliente
     */
    public function registrarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $peso = !empty($_POST['peso']) ? floatval($_POST['peso']) : null;
            $altura = !empty($_POST['altura']) ? floatval($_POST['altura']) : null;

            if (!empty($name) && !empty($email) && !empty($password)) {
                $this->modeloCliente->insertarClienteBD($name, $email, $password, $peso, $altura);
                header("Location: index.php?c=Cliente&a=listarClientes&msg=" . urlencode("Cliente registrado exitosamente."));
                exit();
            }
        }
        $this->vistaCliente->capturarDatosCliente();
    }

    /**
     * Modifica los datos de un cliente existente
     * Utilizado en CU3: Gestionar Cliente
     */
    public function modificarCliente() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $peso = !empty($_POST['peso']) ? floatval($_POST['peso']) : null;
            $altura = !empty($_POST['altura']) ? floatval($_POST['altura']) : null;

            if ($id > 0 && !empty($name) && !empty($email)) {
                $this->modeloCliente->actualizarClienteBD($id, $name, $email, $peso, $altura);
                header("Location: index.php?c=Cliente&a=listarClientes&msg=" . urlencode("Cliente modificado exitosamente."));
                exit();
            }
        }

        $id = intval($_GET['id'] ?? 0);
        $clientes = $this->modeloCliente->consultarTodosBD();
        $cliente = null;
        foreach ($clientes as $c) {
            if ($c['id'] == $id) {
                $cliente = $c;
                break;
            }
        }
        $this->vistaCliente->capturarDatosCliente($cliente);
    }

    /**
     * Remueve un cliente del sistema
     * Utilizado en CU3: Gestionar Cliente
     */
    public function removerCliente() {
        $id = intval($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->modeloCliente->eliminarClienteBD($id);
            header("Location: index.php?c=Cliente&a=listarClientes&msg=" . urlencode("Cliente eliminado exitosamente."));
            exit();
        }
        $this->listarClientes();
    }
}
