<?php
require_once __DIR__ . '/../models/Modelo_Entrenador.php';
require_once __DIR__ . '/../models/Modelo_Cliente.php';
require_once __DIR__ . '/../views/Vista_Login.php';
require_once __DIR__ . '/../views/Vista_Layout.php';

/**
 * Clase Controlador_Auth
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia (CU1, CU2)
 * Paquete: Diagramas de Desplazamiento / Controller
 * ElementID: 73
 */
class Controlador_Auth {

    private $modeloEntrenador;
    private $modeloCliente;
    private $vistaLogin;
    private $vistaLayout;

    public function __construct() {
        $this->modeloEntrenador = new Modelo_Entrenador();
        $this->modeloCliente = new Modelo_Cliente();
        $this->vistaLogin = new Vista_Login();
        $this->vistaLayout = new Vista_Layout();
    }


    /**
     * Procesa la autenticación del usuario
     * Utilizado en CU1: Iniciar Sesion
     */
    public function iniciarSesion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->vistaLogin->mostrarFormulario();
            return;
        }

        $tipo = $_POST['tipo_usuario'] ?? 'entrenador';
        $identificador = trim($_POST['identificador'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($identificador) || empty($password)) {
            $this->vistaLogin->mostrarFormulario("Por favor complete todos los campos.");
            return;
        }

        if ($tipo === 'entrenador') {
            // Secuencia CU1: Entrenador
            $usuario = $this->modeloEntrenador->obtenerUsuarioPorEmailBD($identificador);
            if ($usuario && $usuario['password'] === $password) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = 'entrenador';
                $this->verificarRolUsuario();
                return;
            }
        } else {
            // Secuencia CU1: Cliente
            $usuario = $this->modeloCliente->validarCredencialesBD($identificador, $password);
            if ($usuario) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = 'cliente';
                $this->verificarRolUsuario();
                return;
            }
        }

        // Si fallan las credenciales
        $this->vistaLogin->mostrarFormulario("Credenciales inválidas. Por favor verifique sus datos.");
    }

    /**
     * Verifica el rol del usuario autenticado y redirige al subsistema correspondiente
     * Utilizado en CU1: Iniciar Sesion
     */
    public function verificarRolUsuario() {
        if (!isset($_SESSION['rol'])) {
            $this->vistaLayout->redirigirAFormularioLogin();
            return;
        }

        if ($_SESSION['rol'] === 'entrenador') {
            header("Location: index.php?c=Cliente&a=listarClientes");
            exit();
        } else {
            header("Location: index.php?c=Rutina&a=obtenerPlanificacionSemanalActiva");
            exit();
        }
    }

    /**
     * Finaliza la sesión del usuario
     * Utilizado en CU2: Cerrar Sesion
     */
    public function finalizarSesionUsuario() {
        $this->invalidarTokenAcceso();
        $this->vistaLayout->redirigirAFormularioLogin();
    }

    /**
     * Destruye la sesión activa y los tokens
     * Utilizado en CU2: Cerrar Sesion
     */
    public function invalidarTokenAcceso() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();
        }
    }
}
