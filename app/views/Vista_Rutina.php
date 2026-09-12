<?php
require_once __DIR__ . '/Vista_Layout.php';

/**
 * Clase Vista_Rutina
 * Según Diagrama de Clases / Desplazamiento y Diagramas de Secuencia
 * Paquete: Diagramas de Desplazamiento / Vista
 * ElementID: 65
 */
class Vista_Rutina {

    /**
     * Muestra el formulario transaccional para planificar una rutina completa con múltiples ejercicios
     * Utilizado en CU5: Gestionar Rutina
     */
    public function mostrarFormularioTransaccional($clientes = [], $ejercicios = []) {
        Vista_Layout::renderHeader("Crear Rutina Transaccional - Gym Architect");
        ?>
        <div class="container">
            <div class="card form-card">
                <div class="card-header">
                    <h2>Planificación de Rutina</h2>
                    <a href="index.php?c=Rutina&a=listarHistorialRutinas" class="btn btn-secondary">Ver Historial</a>
                </div>

                <?php if (isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
                <?php endif; ?>
                <?php if (isset($_GET['err'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['err']) ?></div>
                <?php endif; ?>

                <div class="card-body">
                    <?= $this->capturarRutinaYDetalles($clientes, $ejercicios) ?>
                </div>
            </div>
        </div>
        <?= $this->agregarEjercicioATablaTemporal($ejercicios) ?>
        <?php
        Vista_Layout::renderFooter();
    }

    /**
     * Estructura los campos para capturar los datos de la rutina y sus detalles
     * Utilizado en CU5: Gestionar Rutina
     */
    public function capturarRutinaYDetalles($clientes, $ejercicios) {
        ob_start();
        ?>
        <form action="index.php?c=Rutina&a=procesarRutinaTransaccional" method="POST" id="formRutina" class="standard-form">
            <div class="form-section-title">1. Datos Generales de la Rutina</div>
            
            <div class="form-row">
                <div class="form-group col">
                    <label for="nombre">Nombre de la Rutina:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Rutina de Fuerza Acelerada" required>
                </div>
                <div class="form-group col">
                    <label for="cliente_id">Asignar a Cliente:</label>
                    <select name="cliente_id" id="cliente_id" class="form-control" required>
                        <option value="">-- Seleccione un Cliente --</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> (<?= htmlspecialchars($c['email']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="fecha_inicio">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group col">
                    <label for="fecha_fin">Fecha Fin:</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="indicaciones">Indicaciones / Observaciones:</label>
                <textarea name="indicaciones" id="indicaciones" class="form-control" rows="2" placeholder="Recomendaciones de calentamiento, descanso y nutrición..."></textarea>
            </div>

            <div class="form-section-title">2. Ejercicios Incluidos en la Rutina</div>
            <p class="subtitle">Agregue dinámicamente ejercicios a la tabla antes de guardar.</p>

            <div class="exercise-builder-bar">
                <div class="form-group col-4">
                    <label>Ejercicio:</label>
                    <select id="select_ejercicio" class="form-control">
                        <option value="">-- Seleccionar Ejercicio --</option>
                        <?php foreach ($ejercicios as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-2">
                    <label>Series:</label>
                    <input type="number" id="input_series" class="form-control" value="4" min="1">
                </div>
                <div class="form-group col-2">
                    <label>Repeticiones:</label>
                    <input type="number" id="input_repeticiones" class="form-control" value="12" min="1">
                </div>
                <div class="form-group col-2">
                    <label>Tiempo/Descanso:</label>
                    <input type="text" id="input_tiempo" class="form-control" placeholder="60 seg" value="60 seg">
                </div>
                <div class="form-group col-2 align-bottom">
                    <button type="button" id="btnAgregarFila" class="btn btn-primary btn-block">Añadir Ejercicio</button>
                </div>
            </div>

            <table class="data-table" id="tablaDetalles">
                <thead>
                    <tr>
                        <th>Ejercicio</th>
                        <th>Series</th>
                        <th>Repeticiones</th>
                        <th>Tiempo/Descanso</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTablaDetalles">
                    <!-- Filas añadidas por agregarEjercicioATablaTemporal() -->
                </tbody>
            </table>

            <div class="form-actions mt-4">
                <button type="submit" class="btn btn-success btn-lg">Guardar Rutina</button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Script que permite agregar ejercicios a la tabla temporal antes de enviar la transacción
     * Utilizado en CU5: Gestionar Rutina
     */
    public function agregarEjercicioATablaTemporal($ejercicios = []) {
        $ejerciciosMap = [];
        foreach ($ejercicios as $e) {
            $ejerciciosMap[$e['id']] = $e['nombre'];
        }
        $jsonMap = json_encode($ejerciciosMap);
        ob_start();
        ?>
        <script>
            const catalogoNombres = <?= $jsonMap ?>;
            let filaIndex = 0;

            document.getElementById('btnAgregarFila').addEventListener('click', function() {
                const select = document.getElementById('select_ejercicio');
                const id = select.value;
                const nombre = catalogoNombres[id];
                const series = document.getElementById('input_series').value;
                const reps = document.getElementById('input_repeticiones').value;
                const tiempo = document.getElementById('input_tiempo').value;

                if (!id) {
                    alert('Por favor seleccione un ejercicio.');
                    return;
                }

                const tbody = document.getElementById('cuerpoTablaDetalles');
                const tr = document.createElement('tr');
                tr.id = 'fila_' + filaIndex;
                tr.innerHTML = `
                    <td>
                        <strong>${nombre}</strong>
                        <input type="hidden" name="detalles[${filaIndex}][ejercicio_id]" value="${id}">
                    </td>
                    <td>
                        ${series}
                        <input type="hidden" name="detalles[${filaIndex}][series]" value="${series}">
                    </td>
                    <td>
                        ${reps}
                        <input type="hidden" name="detalles[${filaIndex}][repeticiones]" value="${reps}">
                    </td>
                    <td>
                        ${tiempo}
                        <input type="hidden" name="detalles[${filaIndex}][tiempo]" value="${tiempo}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="document.getElementById('fila_${filaIndex}').remove()">Quitar</button>
                    </td>
                `;
                tbody.appendChild(tr);
                filaIndex++;
                select.value = '';
            });
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Muestra la tabla con el historial de rutinas creadas
     */
    public function mostrarHistorial($rutinas = []) {
        Vista_Layout::renderHeader("Historial de Rutinas");
        ?>
        <div class="container">
            <div class="header-action-bar">
                <div>
                    <h2>Historial de Rutinas</h2>
                    <p class="subtitle">Rutinas planificadas y asignadas en el sistema</p>
                </div>
                <a href="index.php?c=Rutina&a=mostrarFormularioTransaccional" class="btn btn-primary">Nueva Rutina</a>
            </div>

            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
            <?php endif; ?>

            <div class="card table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Rutina</th>
                            <th>Cliente</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rutinas)): ?>
                            <tr><td colspan="6" class="text-center">No hay rutinas registradas.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rutinas as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['id']) ?></td>
                                    <td><strong><?= htmlspecialchars($r['nombre']) ?></strong></td>
                                    <td><?= htmlspecialchars($r['nombre_cliente'] ?? 'Cliente #' . $r['cliente_id']) ?></td>
                                    <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                                    <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                                    <td>
                                        <a href="index.php?c=Rutina&a=eliminarRutinaEnCascada&id=<?= $r['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('¿Está seguro de eliminar esta rutina y todos sus detalles?')">Eliminar en Cascada</a>
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
}
