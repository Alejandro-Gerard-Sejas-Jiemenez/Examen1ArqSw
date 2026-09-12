<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Perfil_Entrenador
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 64
 */
class Vista_Perfil_Entrenador {

    /**
     * Muestra la interfaz con los datos del perfil del entrenador
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function mostrarDatosPerfil($datos = []) {
        Vista_Layout::renderHeader("Mi Perfil - Entrenador");
        ?>
        <div class="container">
            <div class="card form-card">
                <div class="card-header">
                    <h2>Perfil del Entrenador</h2>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php endif; ?>

                <div class="card-body">
                    <?= $this->capturarCambiosPerfil($datos) ?>
                    
                    <hr class="divider">

                    <div class="danger-zone">
                        <h3>Zona de Peligro</h3>
                        <p>Al dar de baja tu cuenta, se eliminarán tus credenciales de acceso a la plataforma.</p>
                        <?= $this->solicitarBajaCuenta() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Formulario para capturar los cambios al perfil
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function capturarCambiosPerfil($datos = []) {
        ob_start();
        ?>
        <form action="index.php?c=Entrenador&a=actualizarPerfilEntrenador" method="POST" class="standard-form">
            <div class="form-group">
                <label for="name">Nombre Completo del Entrenador:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($datos['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Nueva Contraseña:</label>
                <input type="password" name="password" id="password" class="form-control" value="<?= htmlspecialchars($datos['password'] ?? '') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Cambios de Perfil</button>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Botón para solicitar baja de la cuenta de entrenador
     * Utilizado en CU6: Gestionar Perfil de Entrenador
     */
    public function solicitarBajaCuenta() {
        return '<a href="index.php?c=Entrenador&a=darDeBajaEntrenador" 
                   class="btn btn-danger" 
                   onclick="return confirm(\'¿Está seguro de que desea dar de baja su cuenta de entrenador? Esta acción no se puede deshacer.\')">
                   Dar de Baja Mi Cuenta
                </a>';
    }
}
