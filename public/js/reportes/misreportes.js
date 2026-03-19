// ==========================================
// JS: misreportes.js
// ==========================================

window.addEventListener('load', function () {
    cargarMisReportes();
});

// ── CARGAR TABLA ──────────────────────────────────────────────
function cargarMisReportes() {
    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'mis_reportes' },
        dataType: 'json',
        success: function (res) {
            if (!res.success) return;
            const tbody = $('#bodyMisReportes');
            tbody.empty();

            if (res.data.length === 0) {
                tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted">No tienes reportes registrados</td></tr>');
                return;
            }

            res.data.forEach((r, i) => {
                const estado = r.estado === 'Abierto'
                    ? '<span class="badge bg-warning text-dark">Abierto</span>'
                    : '<span class="badge bg-success">Cerrado</span>';

                const dispositivo = r.tipo ? `${r.tipo} ${r.marca} ${r.modelo}` : '—';

                const valoracion = r.valoracion
                    ? (r.valoracion === 'resuelto'
                        ? '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Resuelto</span>'
                        : '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>No resuelto</span>')
                    : '<span class="text-muted">—</span>';

                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${dispositivo}</td>
                    <td>${r.descripcion.length > 50 ? r.descripcion.substring(0, 50) + '...' : r.descripcion}</td>
                    <td>${r.fecha}</td>
                    <td>${estado}</td>
                    <td>${valoracion}</td>
                    <td>
                        <button class="btn btn-sm btn-primary rounded-3" onclick="verDetalle(${r.id_reporte})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>`);
            });
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar tus reportes.' });
        }
    });
}

// ── ABRIR MODAL CREAR ─────────────────────────────────────────
function abrirModalCrear() {
    $('#crear_dispositivo').html('<option value="">Cargando dispositivos...</option>');
    $('#crear_descripcion').val('');

    // Cargar dispositivos del usuario
    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'mis_dispositivos' },
        dataType: 'json',
        success: function (res) {
            const sel = $('#crear_dispositivo');
            sel.html('<option value="">Selecciona un dispositivo...</option>');
            if (res.success && res.data.length > 0) {
                res.data.forEach(d => {
                    sel.append(`<option value="${d.id_dispositivo}">${d.tipo} ${d.marca} ${d.modelo}</option>`);
                });
            } else {
                sel.html('<option value="">No tienes dispositivos asignados</option>');
            }
        }
    });

    $('#modalCrearReporte').modal('show');
}

// ── CREAR REPORTE ─────────────────────────────────────────────
function crearReporte() {
    const id_dispositivo = $('#crear_dispositivo').val();
    const descripcion    = $('#crear_descripcion').val().trim();

    if (!id_dispositivo) {
        Swal.fire({ icon: 'warning', title: 'Selecciona un dispositivo', text: 'Debes seleccionar el dispositivo con el problema.', confirmButtonColor: '#0d6efd' });
        return;
    }

    if (!descripcion) {
        Swal.fire({ icon: 'warning', title: 'Descripción requerida', text: 'Describe el problema de tu dispositivo.', confirmButtonColor: '#0d6efd' });
        return;
    }

    if (descripcion.length < 10) {
        Swal.fire({ icon: 'warning', title: 'Descripción muy corta', text: 'Por favor describe el problema con más detalle.', confirmButtonColor: '#0d6efd' });
        return;
    }

    const btn = $('#btnCrearReporte');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Enviando...');

    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'crear', id_dispositivo, descripcion },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalCrearReporte').modal('hide');
                cargarMisReportes();
                Swal.fire({ icon: 'success', title: '¡Reporte enviado!', text: 'Tu reporte ha sido registrado correctamente.', timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Enviar Reporte');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#0d6efd' });
            btn.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Enviar Reporte');
        }
    });
}

// ── VER DETALLE ───────────────────────────────────────────────
function verDetalle(id) {
    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'get', id_reporte: id },
        dataType: 'json',
        success: function (res) {
            if (!res.success) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el reporte.' });
                return;
            }
            const r = res.data;
            $('#det_id').val(r.id_reporte).data('valoracion', r.valoracion || '');
            $('#det_estado_actual').val(r.estado);
            $('#det_dispositivo').val(r.tipo ? `${r.tipo} ${r.marca} ${r.modelo}` : 'Sin dispositivo');
            $('#det_fecha').val(r.fecha);
            $('#det_descripcion').val(r.descripcion);
            $('#det_estado_badge').html(r.estado === 'Abierto'
                ? '<span class="badge bg-warning text-dark fs-6">Abierto</span>'
                : '<span class="badge bg-success fs-6">Cerrado</span>');

            // Solución
            if (r.solucion) {
                $('#det_solucion').val(r.solucion);
                $('#divSolucion').show();
            } else {
                $('#divSolucion').hide();
            }

            // Valoración: solo si está cerrado y no tiene valoración
            if (r.estado === 'Cerrado' && !r.valoracion) {
                $('#selectValoracion').val('');
                $('#divValoracion').show();
            } else {
                $('#divValoracion').hide();
            }

            // Botón eliminar: solo si está cerrado Y ya valoró
            if (r.estado === 'Cerrado' && r.valoracion) {
                $('#btnEliminarMiReporte').show();
            } else {
                $('#btnEliminarMiReporte').hide();
            }

            $('#modalDetalleReporte').modal('show');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
        }
    });
}

// ── VALORAR REPORTE ───────────────────────────────────────────
function valorarReporte() {
    const id        = $('#det_id').val();
    const valoracion = $('#selectValoracion').val();

    if (!valoracion) {
        Swal.fire({ icon: 'warning', title: 'Selecciona una opción', text: 'Debes indicar si tu problema fue resuelto o no.', confirmButtonColor: '#0d6efd' });
        return;
    }

    if (valoracion === 'resuelto') {
        // Marcar como resuelto directo
        $.ajax({
            url: '../../controller/ReporteController.php',
            method: 'POST',
            data: { action: 'valorar', id_reporte: id, valoracion: 'resuelto' },
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    $('#modalDetalleReporte').modal('hide');
                    cargarMisReportes();
                    Swal.fire({ icon: 'success', title: '¡Gracias!', text: 'Nos alegra que tu problema haya sido resuelto.', timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                }
            }
        });

    } else {
        // Cerrar modal Bootstrap primero, luego abrir SweetAlert
        $('#modalDetalleReporte').modal('hide');
        setTimeout(function() {
        Swal.fire({
            title: 'Describe el problema pendiente',
            html: `
                <p class="text-muted mb-3">El reporte volverá a <b>Abierto</b> con tu nueva descripción.</p>
                <textarea id="nuevaDescripcion" class="swal2-textarea" rows="4"
                    placeholder="Describe qué sigue sin resolverse..."
                    style="width:90%; resize:vertical; box-sizing:border-box; overflow-x:hidden;"></textarea>
            `,
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                document.getElementById('nuevaDescripcion').focus();
            },
            preConfirm: () => {
                const desc = document.getElementById('nuevaDescripcion').value.trim();
                if (!desc) {
                    Swal.showValidationMessage('Debes describir el problema pendiente');
                    return false;
                }
                if (desc.length < 10) {
                    Swal.showValidationMessage('La descripción es muy corta');
                    return false;
                }
                return desc;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const nuevaDesc = 'No resuelto: ' + result.value;
                $.ajax({
                    url: '../../controller/ReporteController.php',
                    method: 'POST',
                    data: {
                        action:      'valorar',
                        id_reporte:  id,
                        valoracion:  'no_resuelto',
                        descripcion: nuevaDesc
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.success) {
                            $('#modalDetalleReporte').modal('hide');
                            cargarMisReportes();
                            Swal.fire({
                                icon: 'info',
                                title: 'Reporte reabierto',
                                text: 'Tu reporte ha sido reabierto con la nueva descripción.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                        }
                    }
                });
            }
        });
        }, 500);
    }
}

// ── ELIMINAR MI REPORTE ───────────────────────────────────────
function eliminarMiReporte() {
    const id         = $('#det_id').val();
    const valoracion = $('#det_id').data('valoracion');

    // Si no ha valorado, bloquear
    if (!valoracion) {
        Swal.fire({
            icon: 'warning',
            title: 'Valoración requerida',
            text: 'Debes indicar si tu problema fue resuelto antes de eliminar el reporte.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    // Si no fue resuelto, mostrar mensaje extra
    if (valoracion === 'no_resuelto') {
        Swal.fire({
            icon: 'warning',
            title: 'Tu problema sigue pendiente',
            html: `<p>Marcaste este reporte como <b class="text-danger">No resuelto</b>.</p>
                   <p>Si eliminas el reporte, el técnico ya no podrá dar seguimiento a tu problema.</p>
                   <p>¿Deseas eliminarlo de todas formas?</p>`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar de todas formas',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) confirmarEliminar(id);
        });
        return;
    }

    // Si fue resuelto, confirmación normal
    Swal.fire({
        title: '¿Eliminar reporte?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) confirmarEliminar(id);
    });
}

// ── CONFIRMAR ELIMINAR ────────────────────────────────────────
function confirmarEliminar(id) {
    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'eliminar', id_reporte: id },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalDetalleReporte').modal('hide');
                cargarMisReportes();
                Swal.fire({ icon: 'success', title: '¡Eliminado!', text: res.msg, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
        }
    });
}