// ==========================================
// JS: dispositivos.js
// ==========================================

window.addEventListener('load', function () {
    cargarDispositivos();

    // Limpiar modal asignar cada vez que se abre
    document.getElementById('modalAsignar').addEventListener('show.bs.modal', function () {
        $('#asignar_buscar').val('');
        $('#listaUsuarios').empty();
        $('#asignar_id_usuario').val('');
        $('#usuarioSeleccionado')
            .addClass('d-none')
            .removeClass('alert-success alert-info alert-danger alert-warning')
            .html('');
    });
});

// ── CARGAR TABLA ──────────────────────────────────────────────
function cargarDispositivos() {
    $.ajax({
        url: '../../controller/DispositivoController.php',
        method: 'POST',
        data: { action: 'listar' },
        dataType: 'json',
        success: function (res) {
            if (!res.success) return;
            const tbody = $('#bodyDispositivos');
            tbody.empty();

            if (res.data.length === 0) {
                tbody.html('<tr><td colspan="7" class="text-center py-4 text-muted">No hay dispositivos registrados</td></tr>');
                return;
            }

            res.data.forEach((d, i) => {
                const usuario = d.nombre ? `${d.nombre} ${d.paterno}` : '<span class="badge bg-secondary">Sin asignar</span>';
                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${d.tipo}</td>
                    <td>${d.marca}</td>
                    <td>${d.modelo}</td>
                    <td>${d.color || '—'}</td>
                    <td>${usuario}</td>
                    <td>
                        <button class="btn btn-sm btn-warning rounded-3 me-1" onclick="abrirModalEditar(${JSON.stringify(d).replace(/"/g, '&quot;')})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-info rounded-3 me-1 text-white" onclick="abrirModalAsignar(${d.id_dispositivo}, '${d.nombre ? d.nombre + ' ' + d.paterno : ''}')">
                            <i class="fas fa-user-tag"></i>
                        </button>
                        <button class="btn btn-sm btn-danger rounded-3" onclick="eliminarDispositivo(${d.id_dispositivo})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`);
            });
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar los dispositivos.' });
        }
    });
}

// ── ABRIR MODAL CREAR ─────────────────────────────────────────
function abrirModalCrear() {
    $('#modalDispositivoTitulo').html('<i class="fas fa-plus me-2"></i>Nuevo Dispositivo');
    $('#frmDispositivo')[0].reset();
    $('#disp_id').val('');
    $('#modalDispositivo').modal('show');
}

// ── ABRIR MODAL EDITAR ────────────────────────────────────────
function abrirModalEditar(d) {
    $('#modalDispositivoTitulo').html('<i class="fas fa-edit me-2"></i>Editar Dispositivo');
    $('#disp_id').val(d.id_dispositivo);
    $('#disp_tipo').val(d.tipo);
    $('#disp_marca').val(d.marca);
    $('#disp_modelo').val(d.modelo);
    $('#disp_color').val(d.color);
    $('#disp_descripcion').val(d.descripcion);
    $('#disp_memoria').val(d.memoria);
    $('#disp_disco').val(d.disco_duro);
    $('#disp_procesador').val(d.procesador);
    $('#modalDispositivo').modal('show');
}

// ── GUARDAR (crear o editar) ──────────────────────────────────
function guardarDispositivo() {
    const tipo   = $('#disp_tipo').val().trim();
    const marca  = $('#disp_marca').val().trim();
    const modelo = $('#disp_modelo').val().trim();
    const id     = $('#disp_id').val();

    // Validaciones
    if (!tipo || !marca || !modelo) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Tipo, Marca y Modelo son obligatorios.',
            confirmButtonColor: '#0d6efd'
        });
        return;
    }

    const action = id ? 'editar' : 'crear';
    const data = {
        action:        action,
        id_dispositivo:id,
        tipo:          tipo,
        marca:         marca,
        modelo:        modelo,
        color:         $('#disp_color').val().trim(),
        descripcion:   $('#disp_descripcion').val().trim(),
        memoria:       $('#disp_memoria').val().trim(),
        disco_duro:    $('#disp_disco').val().trim(),
        procesador:    $('#disp_procesador').val().trim(),
        imagen:        ''
    };

    const btn = $('button[onclick="guardarDispositivo()"]');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');

    $.ajax({
        url: '../../controller/DispositivoController.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalDispositivo').modal('hide');
                cargarDispositivos();
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: res.msg,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar');
        }
    });
}

// ── ELIMINAR ──────────────────────────────────────────────────
function eliminarDispositivo(id) {
    Swal.fire({
        title: '¿Eliminar dispositivo?',
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
                url: '../../controller/DispositivoController.php',
                method: 'POST',
                data: { action: 'eliminar', id_dispositivo: id },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        cargarDispositivos();
                        Swal.fire({ icon: 'success', title: '¡Eliminado!', text: res.msg, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                    }
                }
            });
        }
    });
}

// ── ABRIR MODAL ASIGNAR ───────────────────────────────────────
function abrirModalAsignar(id_dispositivo, usuario_actual) {
    $('#asignar_id_dispositivo').val(id_dispositivo);
    $('#asignar_usuario_actual').val(usuario_actual || 'Sin asignar');
    $('#asignar_buscar').val('');
    $('#listaUsuarios').empty();
    $('#asignar_id_usuario').val('');
    // Ocultar y limpiar completamente el mensaje
    $('#usuarioSeleccionado')
        .addClass('d-none')
        .removeClass('alert-success alert-info alert-danger alert-warning')
        .html('');
    $('#modalAsignar').modal('show');
}

// ── BUSCAR USUARIOS ───────────────────────────────────────────
function buscarUsuarios() {
    const buscar = $('#asignar_buscar').val().trim();
    if (buscar.length < 2) {
        $('#listaUsuarios').empty();
        return;
    }

    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: { action: 'buscar', buscar: buscar },
        dataType: 'json',
        success: function (res) {
            const lista = $('#listaUsuarios');
            lista.empty();
            if (!res.success || res.data.length === 0) {
                lista.html('<div class="list-group-item text-muted">No se encontraron usuarios</div>');
                return;
            }
            res.data.forEach(u => {
                lista.append(`
                <button type="button" class="list-group-item list-group-item-action"
                    onclick="seleccionarUsuario(${u.id_usuario}, '${u.nombre} ${u.paterno}')">
                    <i class="fas fa-user me-2 text-primary"></i>
                    ${u.nombre} ${u.paterno}
                    <small class="text-muted ms-2">(${u.usuario})</small>
                </button>`);
            });
        }
    });
}

// ── SELECCIONAR USUARIO ───────────────────────────────────────
function seleccionarUsuario(id, nombre) {
    $('#asignar_id_usuario').val(id);
    $('#listaUsuarios').empty();
    $('#asignar_buscar').val('');
    // Mostrar mensaje claro: seleccionado pero NO asignado todavía
    $('#usuarioSeleccionado')
        .removeClass('d-none alert-info alert-danger alert-warning')
        .addClass('alert-success')
        .html('✅ Seleccionado: <strong>' + nombre + '</strong> &mdash; Presiona <b>Asignar</b> para confirmar.');
}

// ── ASIGNAR USUARIO ───────────────────────────────────────────
function asignarUsuario() {
    const id_dispositivo = $('#asignar_id_dispositivo').val();
    const id_usuario     = $('#asignar_id_usuario').val();

    if (!id_usuario) {
        Swal.fire({ icon: 'warning', title: 'Selecciona un usuario', text: 'Busca y selecciona un usuario de la lista.', confirmButtonColor: '#0d6efd' });
        return;
    }

    $.ajax({
        url: '../../controller/DispositivoController.php',
        method: 'POST',
        data: { action: 'asignar_usuario', id_dispositivo, id_usuario },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalAsignar').modal('hide');
                cargarDispositivos();
                Swal.fire({ icon: 'success', title: '¡Asignado!', text: res.msg, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
        }
    });
}

// ── QUITAR USUARIO ────────────────────────────────────────────
function quitarUsuario() {
    const id_dispositivo = $('#asignar_id_dispositivo').val();

    Swal.fire({
        title: '¿Quitar usuario?',
        text: 'El dispositivo quedará sin usuario asignado.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, quitar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../controller/DispositivoController.php',
                method: 'POST',
                data: { action: 'quitar_usuario', id_dispositivo },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        $('#modalAsignar').modal('hide');
                        cargarDispositivos();
                        Swal.fire({ icon: 'success', title: '¡Listo!', text: res.msg, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                    }
                }
            });
        }
    });
}