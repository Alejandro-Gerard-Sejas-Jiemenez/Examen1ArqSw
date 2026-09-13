<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Cliente
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 62
 */
class Vista_Cliente {

    /**
     * Muestra la interfaz para capturar datos de registro o modificación de un cliente
     * Utilizado en CU3: Gestionar Cliente
     */
    public function capturarDatosCliente($cliente = null) {
        $esEdicion = !empty($cliente);
        $accion = $esEdicion ? "modificarCliente" : "registrarCliente";
        $titulo = $esEdicion ? "Modificar Cliente" : "Registrar Nuevo Cliente";

        Vista_Layout::renderHeader($titulo);
        ?>
        <div class="container">
            <div class="card form-card">
                <div class="card-header">
                    <h2><?= htmlspecialchars($titulo) ?></h2>
                    <a href="index.php?c=Cliente&a=listarClientes" class="btn btn-secondary">Volver a la Lista</a>
                </div>
                <div class="card-body">
                    <form action="index.php?c=Cliente&a=<?= $accion ?>" method="POST" class="standard-form">
                        <?php if ($esEdicion): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id']) ?>">
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group col">
                                <label for="name">Nombre Completo:</label>
                                <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($cliente['name'] ?? '') ?>" required>
                            </div>
                            <div class="form-group col">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($cliente['email'] ?? '') ?>" required>
                            </div>
                        </div>

                        <?php if (!$esEdicion): ?>
                        <div class="form-group">
                            <label for="password">Contraseña Inicial:</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••" required>
                        </div>
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group col">
                                <label for="peso">Peso (kg):</label>
                                <input type="number" step="0.1" name="peso" id="peso" class="form-control" value="<?= htmlspecialchars($cliente['peso'] ?? '') ?>" placeholder="Ej: 70.5">
                            </div>
                            <div class="form-group col">
                                <label for="altura">Altura (m):</label>
                                <input type="number" step="0.01" name="altura" id="altura" class="form-control" value="<?= htmlspecialchars($cliente['altura'] ?? '') ?>" placeholder="Ej: 1.75">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success"><?= $esEdicion ? "Guardar Cambios" : "Registrar Cliente" ?></button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Despliega la tabla con todos los clientes registrados
     * Utilizado en CU3: Gestionar Cliente
     */
    public function desplegarTablaClientes($lista = []) {
        Vista_Layout::renderHeader("Gestión de Clientes - Control de Acceso");
        ?>
        <div class="container">
            <div class="header-action-bar">
                <div>
                    <h2>Gestión de Clientes</h2>
                    <p class="subtitle">Administración de clientes y sus datos antropométricos</p>
                </div>
                <a href="index.php?c=Cliente&a=registrarCliente" class="btn btn-primary">Nuevo Cliente</a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <div class="card table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Peso (kg)</th>
                            <th>Altura (m)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista)): ?>
                            <tr><td colspan="6" class="text-center">No hay clientes registrados aún.</td></tr>
                        <?php else: ?>
                            <?php foreach ($lista as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['id']) ?></td>
                                    <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                                    <td><?= htmlspecialchars($c['email']) ?></td>
                                    <td><?= htmlspecialchars($c['peso'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($c['altura'] ?? 'N/A') ?></td>
                                    <td class="action-buttons">
                                        <a href="index.php?c=Cliente&a=modificarCliente&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <?= $this->solicitarConfirmacionBaja($c['id']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Genera el botón con confirmación para remover/dar de baja un cliente
     * Utilizado en CU3: Gestionar Cliente
     */
    public function solicitarConfirmacionBaja($id = null) {
        return '<a href="index.php?c=Cliente&a=removerCliente&id=' . urlencode($id) . '" 
                   class="btn btn-sm btn-danger" 
                   onclick="return confirm(\'¿Está seguro de que desea remover este cliente del sistema?\')">Eliminar</a>';
    }
}
