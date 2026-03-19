// ==========================================
// JS: usuarios.js
// ==========================================

let modoEditar = false;

window.addEventListener('load', function () {
    cargarUsuarios();

    // Solo limpiar si es modo crear
    document.getElementById('modalUsuario').addEventListener('show.bs.modal', function () {
        if (!modoEditar) {
            $('#frmUsuario')[0].reset();
            $('#usu_id').val('');
            $('#divPassword').show();
            $('#usu_password').prop('required', true);
            $('#modalUsuarioTitulo').html('<i class="fas fa-user-plus me-2"></i>Nuevo Usuario');
        }
    });

    document.getElementById('modalUsuario').addEventListener('hidden.bs.modal', function () {
        modoEditar = false;
    });
});

// ── CARGAR TABLA ──────────────────────────────────────────────
function cargarUsuarios() {
    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: { action: 'listar' },
        dataType: 'json',
        success: function (res) {
            if (!res.success) return;
            const tbody = $('#bodyUsuarios');
            tbody.empty();

            if (res.data.length === 0) {
                tbody.html('<tr><td colspan="8" class="text-center py-4 text-muted">No hay usuarios registrados</td></tr>');
                return;
            }

            res.data.forEach((u, i) => {
                const estado = u.estado == 1
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Inactivo</span>';

                const rolBadge = {
                    'Admin':   '<span class="badge bg-danger">Admin</span>',
                    'Tecnico': '<span class="badge bg-warning text-dark">Técnico</span>',
                    'Cliente': '<span class="badge bg-info text-dark">Cliente</span>'
                }[u.rol] || u.rol;

                const btnEstado = u.estado == 1
                    ? `<button class="btn btn-sm btn-warning rounded-3 me-1" onclick="cambiarEstado(${u.id_usuario}, 0)" title="Desactivar"><i class="fas fa-ban"></i></button>`
                    : `<button class="btn btn-sm btn-success rounded-3 me-1" onclick="cambiarEstado(${u.id_usuario}, 1)" title="Activar"><i class="fas fa-check"></i></button>`;

                const btnEliminar = u.estado == 0
                    ? `<button class="btn btn-sm btn-danger rounded-3" onclick="eliminarUsuario(${u.id_usuario})" title="Eliminar"><i class="fas fa-trash"></i></button>`
                    : `<button class="btn btn-sm btn-danger rounded-3" disabled title="Desactiva el usuario para eliminarlo"><i class="fas fa-trash"></i></button>`;

                tbody.append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${u.nombre} ${u.paterno}</td>
                    <td>${u.usuario}</td>
                    <td>${u.correo}</td>
                    <td>${u.telefono || '—'}</td>
                    <td>${rolBadge}</td>
                    <td>${estado}</td>
                    <td>
                        <button class="btn btn-sm btn-warning rounded-3 me-1" onclick="abrirModalEditar(${u.id_usuario})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${btnEstado}
                        ${btnEliminar}
                    </td>
                </tr>`);
            });
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar los usuarios.' });
        }
    });
}

// ── ABRIR MODAL CREAR ─────────────────────────────────────────
function abrirModalCrear() {
    modoEditar = false;
    $('#modalUsuario').modal('show');
}

// ── ABRIR MODAL EDITAR ────────────────────────────────────────
function abrirModalEditar(id) {
    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: { action: 'get_by_id', id_usuario: id },
        dataType: 'json',
        success: function (res) {
            if (!res.success) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el usuario.' });
                return;
            }
            const u = res.data;
            modoEditar = true;
            $('#modalUsuarioTitulo').html('<i class="fas fa-edit me-2"></i>Editar Usuario');
            $('#usu_id').val(u.id_usuario);
            $('#usu_nombre').val(u.nombre);
            $('#usu_paterno').val(u.paterno);
            $('#usu_materno').val(u.materno);
            $('#usu_fecha_nac').val(u.fecha_nac);
            $('#usu_sexo').val(u.sexo);
            $('#usu_telefono').val(u.telefono);
            $('#usu_correo').val(u.correo);
            $('#usu_usuario').val(u.usuario);
            $('#usu_rol').val(u.rol);
            $('#usu_ubicacion').val(u.ubicacion);
            $('#divPassword').hide();
            $('#usu_password').prop('required', false).val('');
            $('#modalUsuario').modal('show');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.' });
        }
    });
}

// ── GUARDAR (crear o editar) ──────────────────────────────────
function guardarUsuario() {
    const id       = $('#usu_id').val();
    const nombre   = $('#usu_nombre').val().trim();
    const paterno  = $('#usu_paterno').val().trim();
    const correo   = $('#usu_correo').val().trim();
    const usuario  = $('#usu_usuario').val().trim();
    const password = $('#usu_password').val().trim();
    const fecha    = $('#usu_fecha_nac').val().trim();
    const telefono = $('#usu_telefono').val().trim();

    if (!nombre || !paterno || !correo || !usuario || !fecha) {
        Swal.fire({ icon: 'warning', title: 'Campos requeridos', html: 'Nombre, Apellido Paterno, Fecha, Correo y Usuario son obligatorios.', confirmButtonColor: '#0d6efd' });
        return;
    }

    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(correo)) {
        Swal.fire({ icon: 'warning', title: 'Correo inválido', text: 'Ingresa un correo electrónico válido.', confirmButtonColor: '#0d6efd' });
        return;
    }

    if (telefono && !/^\d{10}$/.test(telefono)) {
        Swal.fire({ icon: 'warning', title: 'Teléfono inválido', text: 'El teléfono debe tener exactamente 10 dígitos.', confirmButtonColor: '#0d6efd' });
        return;
    }

    if (!id && password.length < 8) {
        Swal.fire({ icon: 'warning', title: 'Contraseña muy corta', text: 'La contraseña debe tener al menos 8 caracteres.', confirmButtonColor: '#0d6efd' });
        return;
    }

    const action = id ? 'editar' : 'crear';
    const data = {
        action:     action,
        id_usuario: id,
        nombre:     nombre,
        paterno:    paterno,
        materno:    $('#usu_materno').val().trim(),
        fecha_nac:  fecha,
        sexo:       $('#usu_sexo').val().trim(),
        telefono:   telefono,
        correo:     correo,
        usuario:    usuario,
        password:   password,
        rol:        $('#usu_rol').val(),
        ubicacion:  $('#usu_ubicacion').val().trim()
    };

    const btn = $('#btnGuardarUsuario');
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Guardando...');

    $.ajax({
        url: '../../controller/UsuarioController.php',
        method: 'POST',
        data: data,
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalUsuario').modal('hide');
                cargarUsuarios();
                Swal.fire({ icon: 'success', title: '¡Listo!', text: res.msg, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
            }
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar');
        },
        error: function () {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#0d6efd' });
            btn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Guardar');
        }
    });
}

// ── CAMBIAR ESTADO ────────────────────────────────────────────
function cambiarEstado(id, estado) {
    const accion = estado == 1 ? 'activar' : 'desactivar';
    Swal.fire({
        title: `¿${accion.charAt(0).toUpperCase() + accion.slice(1)} usuario?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: estado == 1 ? '#198754' : '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../controller/UsuarioController.php',
                method: 'POST',
                data: { action: 'cambiar_estado', id_usuario: id, estado: estado },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        cargarUsuarios();
                        Swal.fire({ icon: 'success', title: '¡Listo!', text: res.msg, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                    }
                }
            });
        }
    });
}

// ── ELIMINAR ──────────────────────────────────────────────────
function eliminarUsuario(id) {
    Swal.fire({
        title: '¿Eliminar usuario?',
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
                url: '../../controller/UsuarioController.php',
                method: 'POST',
                data: { action: 'eliminar', id_usuario: id },
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        cargarUsuarios();
                        Swal.fire({ icon: 'success', title: '¡Eliminado!', text: res.msg, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: res.msg, confirmButtonColor: '#0d6efd' });
                    }
                }
            });
        }
    });
}