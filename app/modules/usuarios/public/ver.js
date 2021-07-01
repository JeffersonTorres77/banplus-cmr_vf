/**
 * Guardar datos
 */
$("#form-datos").on('submit', function(e) {
    e.preventDefault();
    let form = Form.json( $(this) );

    AJAX.enviar({
        url: `${BASE_URL}/Usuarios/API/modificar-datos/`,
        data: {
            id: ID,
            ...form
        },
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Modificar datos', mensaje);
        },
        ok(data) {
            $("#collapse-login").collapse('hide');
            $("#label-nombre-completo").html(`${data.nombres} ${data.apellidos}`);
            Alerta.ok('Modificar datos', 'Datos modificados exitosamente.');
        },
        final() {
            Loader.hide();
        }
    });
})

/**
 * Guardar otros datos
 */
$("#form-otros").on('submit', function(e) {
    e.preventDefault();
    let form = Form.json( $(this) );

    AJAX.enviar({
        url: `${BASE_URL}/Usuarios/API/modificar-otros-datos/`,
        data: {
            id: ID,
            ...form
        },
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Modificar datos', mensaje);
        },
        ok(data) {
            $("#form-otros [name=clave]").val('');
            Alerta.ok('Modificar datos', 'Datos modificados exitosamente.');
        },
        final() {
            Loader.hide();
        }
    });
})