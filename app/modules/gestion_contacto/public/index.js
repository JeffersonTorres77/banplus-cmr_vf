$("#form-search").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/gestion_contacto/api/consultar_datos/`,
        data: Form.json( $("#form-search") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consultar datos', mensaje);
        },
        ok(data) {
            console.log(data);
        },
        final() {
            Loader.hide();
        }
    });
});