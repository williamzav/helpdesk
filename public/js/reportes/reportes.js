// ==========================================
// JS: reportes.js
// Ver, actualizar y eliminar reportes
// ==========================================

window.addEventListener('load', function () {
    cargarReportes();

    document.getElementById('modalReporte').addEventListener('show.bs.modal', function () {
        $('#rep_solucion').val('');
        $('#rep_estado').val('Abierto');
    });
});

// ── CARGAR TABLA ──────────────────────────────────────────────
function cargarReportes() {
    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'listar' },
        dataType: 'json',
        success: function (res) {
            if (!res.success) return;
            const tbody = $('#bodyReportes');
            tbody.empty();

            if (res.data.length === 0) {
                tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted">No hay reportes registrados</td></tr>');
                return;
            }

            res.data.forEach((r, i) => {
                const estado = r.estado === 'Abierto'
                    ? '<span class="badge bg-warning text-dark">Abierto</span>'
                    : '<span class="badge bg-success">Cerrado</span>';

                const dispositivo = r.tipo ? `${r.tipo} ${r.marca} ${r.modelo}` : '—';

                // Solo eliminar si está cerrado
                const btnEliminar = r.estado === 'Cerrado'
                    ? `<button class="btn btn-sm btn-danger rounded-3 ms-1" onclick="eliminarReporte(${r.id_reporte})" title="Eliminar"><i class="fas fa-trash"></i></button>`
                    : `<button class="btn btn-sm btn-danger rounded-3 ms-1" disabled title="Solo se puede eliminar si está cerrado"><i class="fas fa-trash"></i></button>`;

                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${r.nombre_usuario} ${r.paterno_usuario}</td>
                    <td>${dispositivo}</td>
                    <td>${r.descripcion.length > 50 ? r.descripcion.substring(0, 50) + '...' : r.descripcion}</td>
                    <td>${r.fecha}</td>
                    <td>${estado}</td>
                    <td>
                        <button class="btn btn-sm btn-primary rounded-3" onclick="abrirModalReporte(${r.id_reporte})" title="Ver/Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${btnEliminar}
                    </td>
                </tr>`);
            });
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar los reportes.' });
        }
    });
}

// ── ABRIR MODAL REPORTE ───────────────────────────────────────
function abrirModalReporte(id) {
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
            $('#rep_id').val(r.id_reporte);
            $('#rep_cliente').val(`${r.nombre_usuario} ${r.paterno_usuario}`);
            $('#rep_dispositivo').val(r.tipo ? `${r.tipo} ${r.marca} ${r.modelo}` : 'Sin dispositivo');
            $('#rep_descripcion').val(r.descripcion);
            $('#rep_fecha').val(r.fecha);
            $('#rep_estado').val(r.estado);
            $('#rep_solucion').val(r.solucion || '');
            $('#modalReporte').modal('show');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
        }
    });
}

// ── GUARDAR CAMBIOS ───────────────────────────────────────────
function guardarReporte() {
    const id       = $('#rep_id').val();
    const estado   = $('#rep_estado').val();
    const solucion = $('#rep_solucion').val().trim();

    // Si se cierra debe tener solución
    if (estado === 'Cerrado' && !solucion) {
        Swal.fire({
            icon: 'warning',
            title: 'Solución requerida',
            text: 'Para cerrar un reporte debes ingresar la solución aplicada.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    const btn = $('#btnGuardarReporte');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');

    $.ajax({
        url: '../../controller/ReporteController.php',
        method: 'POST',
        data: { action: 'actualizar', id_reporte: id, estado, solucion },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalReporte').modal('hide');
                cargarReportes();
                Swal.fire({ icon: 'success', title: '¡Listo!', text: res.msg, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar cambios');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#0d6efd' });
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar cambios');
        }
    });
}

// ── ELIMINAR REPORTE ──────────────────────────────────────────
function eliminarReporte(id) {
    Swal.fire({
        title: '¿Eliminar reporte?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../controller/ReporteController.php',
                method: 'POST',
                data: { action: 'eliminar', id_reporte: id },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        cargarReportes();
                        Swal.fire({ icon: 'success', title: '¡Eliminado!', text: res.msg, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                    }
                }
            });
        }
    });
}