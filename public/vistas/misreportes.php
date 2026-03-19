<?php include 'header.php'; ?>

<div class="container">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i>Mis Reportes</h4>
        <button class="btn btn-primary rounded-3" onclick="abrirModalCrear()">
            <i class="fas fa-plus me-2"></i>Nuevo Reporte
        </button>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Dispositivo</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Valoración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="bodyMisReportes">
                        <tr><td colspan="7" class="text-center py-4">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ── MODAL: CREAR REPORTE ───────────────────────────────────── -->
<div class="modal fade" id="modalCrearReporte" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus me-2"></i>Nuevo Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Dispositivo <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="crear_dispositivo">
                        <option value="">Selecciona un dispositivo...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción del problema <span class="text-danger">*</span></label>
                    <textarea class="form-control rounded-3" id="crear_descripcion" rows="4" placeholder="Describe detalladamente el problema..."></textarea>
                </div>
            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary rounded-3 px-4" id="btnCrearReporte" onclick="crearReporte()">
                    <i class="fas fa-paper-plane me-2"></i>Enviar Reporte
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ── MODAL: DETALLE REPORTE ────────────────────────────────── -->
<div class="modal fade" id="modalDetalleReporte" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-file-alt me-2"></i>Detalle del Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4">
                <input type="hidden" id="det_id">
                <input type="hidden" id="det_estado_actual">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Dispositivo</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="det_dispositivo" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Fecha</label>
                        <input type="text" class="form-control rounded-3 bg-light" id="det_fecha" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea class="form-control rounded-3 bg-light" id="det_descripcion" rows="3" readonly></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Estado</label>
                    <div id="det_estado_badge"></div>
                </div>

                <div class="mb-3" id="divSolucion">
                    <label class="form-label fw-semibold">Solución aplicada</label>
                    <textarea class="form-control rounded-3 bg-light" id="det_solucion" rows="2" readonly></textarea>
                </div>

                <!-- Valoración -->
                <div class="mb-3" id="divValoracion">
                    <label class="form-label fw-semibold">¿Se resolvió tu problema? <span class="text-danger">*</span></label>
                    <select class="form-select rounded-3" id="selectValoracion">
                        <option value="">-- Selecciona una opción --</option>
                        <option value="resuelto">✅ Sí, fue resuelto</option>
                        <option value="no_resuelto">❌ No fue resuelto</option>
                    </select>
                    <button class="btn btn-primary rounded-3 mt-2 w-100" onclick="valorarReporte()">
                        <i class="fas fa-paper-plane me-2"></i>Confirmar valoración
                    </button>
                </div>

            </div>

            <div class="modal-footer border-0">
                <button type="button" class="btn btn-danger rounded-3" id="btnEliminarMiReporte" onclick="eliminarMiReporte()">
                    <i class="fas fa-trash me-2"></i>Eliminar reporte
                </button>
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="../js/reportes/misreportes.js"></script>