<?php
/**
 * Clase Vista_Layout
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 72
 */
class Vista_Layout {

    /**
     * Genera el enlace o acción para solicitar cierre de sesión
     * Utilizado en CU2: Cerrar Sesion
     */
    public function solicitarCierreSesion() {
        return '<a href="index.php?c=Auth&a=finalizarSesionUsuario" class="btn-logout" onclick="return confirm(\'¿Está seguro de cerrar sesión?\')">Cerrar Sesión</a>';
    }

    /**
     * Redirige al formulario de inicio de sesión
     * Utilizado en CU2: Cerrar Sesion
     */
    public function redirigirAFormularioLogin() {
        header("Location: index.php?c=Auth&a=mostrarFormulario");
        exit();
    }

    /**
     * Renderiza el encabezado y navegación del layout principal
     */
    public static function renderHeader($titulo = "Sistema de Gestión de Entrenamientos") {
        $usuario = $_SESSION['usuario'] ?? null;
        $rol = $_SESSION['rol'] ?? null;
        $layout = new self();
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($titulo) ?></title>
            <link rel="stylesheet" href="app/views/css/style.css">
        </head>
        <body>
            <header class="app-header">
                <div class="header-container">
                    <div class="logo">
                        <span class="brand-badge">GA</span>
                        <h1>GYM ARCHITECT</h1>
                    </div>
                    <?php if ($usuario): ?>
                    <nav class="nav-links">
                        <?php if ($rol === 'entrenador'): ?>
                            <a href="index.php?c=Cliente&a=listarClientes" class="nav-item">Clientes</a>
                            <a href="index.php?c=Ejercicio&a=listarEjerciciosConMultimedia" class="nav-item">Ejercicios</a>
                            <a href="index.php?c=Rutina&a=mostrarFormularioTransaccional" class="nav-item">Nueva Rutina</a>
                            <a href="index.php?c=Rutina&a=listarHistorialRutinas" class="nav-item">Historial</a>
                            <a href="index.php?c=Entrenador&a=consultarPerfil" class="nav-item">Mi Perfil</a>
                        <?php elseif ($rol === 'cliente'): ?>
                            <a href="index.php?c=Rutina&a=obtenerPlanificacionSemanalActiva" class="nav-item">Mi Rutina</a>
                        <?php endif; ?>
                    </nav>
                    <div class="user-badge">
                        <span class="user-name">Hola, <strong><?= htmlspecialchars($usuario['name']) ?></strong> (<?= ucfirst($rol) ?>)</span>
                        <?= $layout->solicitarCierreSesion() ?>
                    </div>
                    <?php endif; ?>
                </div>
            </header>
            <main class="main-content">
        <?php
    }

    /**
     * Renderiza el pie de página del layout
     */
    public static function renderFooter() {
        ?>
            </main>
            <footer class="app-footer">
                <p>&copy; 2026 Sistema de Gestión de Rutinas - Arquitectura de Software MVC</p>
            </footer>
        </body>
        </html>
        <?php
    }
}
