/**
 * Tabla
 */
 var tabla = $("#table-usuarios").DataTable({
    language: DT_SPANISH,
    processing: true,
    serverSide: true,
    ajax: {
        url: `${BASE_URL}/Usuarios/API/datatable/`
    },
    columns: [
        {
            data: "nombres",
            class: "align-middle",
            width: "auto",
            render: function(d, type, row) {
                return `${row.nombres} ${row.apellidos}`;
            }
        },
        {
            data: "rol",
            class: "align-middle text-truncate text-center",
            width: "120px",
            render: function(d, type, row) {
                return d.nombre;
            }
        },
        {
            data: "activo",
            class: "align-middle text-center",
            width: "50px",
            render: function(d, type, row) {
                d = (d == 0) ? false : true;
                return `<div class="badge badge-${ (d) ? 'success' : 'danger' }">${ (d) ? 'Si' : 'No' }</div>`;
            }
        },
        {
            data: "usuario",
            width: '40px',
            class: "align-middle text-truncate",
            orderable: false,
            render: function(d, type, row) {
                return `<div class="text-center">
                    <a class="btn btn-outline-primary btn-sm btn-opc ver" href="${BASE_URL}/Usuarios/ver/${d}/">
                        <i class="fas fa-eye fa-sm"></i>
                    </a>
                </div>`;
            }
        }
    ]
});

function actualizar_tabla() {
    tabla.ajax.reload();
}

/**
 * Registro de usuario de red
 */
 $("#modal-registrar-red").on('submit', 'form', function(e) {
    e.preventDefault();
    let form = Form.json( $("#modal-registrar-red form") );

    AJAX.enviar({
        url: `${BASE_URL}/Usuarios/API/registrar-red/`,
        data: form,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar usuario de red', mensaje);
        },
        ok(data) {
            actualizar_tabla();
            $("#modal-registrar-red").modal('hide');
            Alerta.ok('Registrar usuario de red', 'Usuario registrado existosamente.');
            $("#modal-registrar-red form")[0].reset();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Registro de usuario regular
 */
 $("#modal-registrar-regular").on('submit', 'form', function(e) {
    e.preventDefault();
    let form = Form.json( $("#modal-registrar-regular form") );

    AJAX.enviar({
        url: `${BASE_URL}/Usuarios/API/registrar-regular/`,
        data: form,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar usuario regular', mensaje);
        },
        ok(data) {
            actualizar_tabla();
            $("#modal-registrar-regular").modal('hide');
            Alerta.ok('Registrar usuario regular', 'Usuario registrado existosamente.');
            $("#modal-registrar-regular form")[0].reset();
        },
        final() {
            Loader.hide();
        }
    });
});