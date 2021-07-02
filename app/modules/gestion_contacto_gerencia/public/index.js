$("#form-search").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/gestion_contacto_gerencia/api/consultar_datos/`,
        data: Form.json( $("#form-search") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Consultar datos', mensaje);
        },
        ok(data) {
            $("#celular").val(data.celular);
            $("#gerente_banca_persona").val(data.gerente_atencion_origen);
            $("#nombre").val(data.nombre);
            $("#otro_telefono").val(data.otro_celular);
            $("#gerente_juridico").val(data.gerente_atencion);
            $("#segmento").val(data.segmento);
            $("#correo").val(data.correo);
            $("#vpr_juridico").val(data.region_origen);
        },
        final() {
            Loader.hide();
        }
    });
});