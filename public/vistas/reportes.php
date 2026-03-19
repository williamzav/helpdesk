<?php include 'header.php'; ?>

<div class="container">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Gestión de Reportes</h4>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Dispositivo</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyReportes">
                        <tr><td colspan="7" class="text-center py-4">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ── MODAL: VER / ACTUALIZAR REPORTE ───────────────────────── -->
<div class="modal fade" id="modalReporte" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Detalle del Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <input type="hidden" id="rep_id">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Cliente</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="rep_cliente" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Dispositivo</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="rep_dispositivo" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción del problema</label>
                    <textarea class="form-control rounded-3 bg-light" id="rep_descripcion" rows="3" readonly></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="rep_fecha" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Estado <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3" id="rep_estado">
                            <option value="Abierto">Abierto</option>
                            <option value="Cerrado">Cerrado</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Solución</label>
                    <textarea class="form-control rounded-3" id="rep_solucion" rows="3" placeholder="Describe la solución aplicada..."></textarea>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" id="btnGuardarReporte" onclick="guardarReporte()">
                    <i class="fas fa-save me-2"></i>Guardar cambios
                </button>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="../js/reportes/reportes.js"></script>