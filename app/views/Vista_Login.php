<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Login
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 71
 */
class Vista_Login {

    /**
     * Muestra el formulario de inicio de sesión
     * Utilizado en CU1: Iniciar Sesion
     */
    public function mostrarFormulario($error = null) {
        Vista_Layout::renderHeader("Iniciar Sesión - Gym Architect");
        ?>
        <div class="login-wrapper">
            <div class="login-card">
                <div class="login-header">
                    <h2>Bienvenido</h2>
                    <p>Ingresa tus credenciales para acceder a la plataforma</p>
                </div>

                <?php if ($error): ?>
                    <?= $this->mostrarErrorAutenticacion($error) ?>
                <?php endif; ?>

                <?= $this->capturarCredenciales() ?>

                <div class="demo-credentials">
                    <p><strong>Cuentas de Prueba:</strong></p>
                    <ul>
                        <li><strong>Entrenador:</strong> Usuario: <code>Carlos Entrenador</code> | Clave: <code>123456</code> (Seleccionar Entrenador)</li>
                        <li><strong>Cliente:</strong> Correo: <code>juan@gmail.com</code> | Clave: <code>123456</code> (Seleccionar Cliente)</li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Genera el formulario HTML para capturar las credenciales
     * Utilizado en CU1: Iniciar Sesion
     */
    public function capturarCredenciales() {
        ob_start();
        ?>
        <form action="index.php?c=Auth&a=iniciarSesion" method="POST" class="login-form">
            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuario:</label>
                <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                    <option value="entrenador">Entrenador</option>
                    <option value="cliente">Cliente</option>
                </select>
            </div>

            <div class="form-group">
                <label for="identificador">Nombre de Usuario o Correo:</label>
                <input type="text" name="identificador" id="identificador" class="form-control" placeholder="Ej: Carlos Entrenador o juan@gmail.com" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Despliega mensaje de error cuando falla la autenticación
     * Utilizado en CU1: Iniciar Sesion
     */
    public function mostrarErrorAutenticacion($mensaje) {
        return '<div class="alert alert-danger">' . htmlspecialchars($mensaje) . '</div>';
    }
}
