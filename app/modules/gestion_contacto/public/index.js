const FLAGS = [
    { id: 'flag-natural-bs',        key: 'natural-bs' },
    { id: 'flag-natural-divisas',   key: 'natural-divisas' },
    { id: 'flag-natural-bi',        key: 'natural-bi' },
    { id: 'flag-juridica-bs',       key: 'juridica-bs' },
    { id: 'flag-juridica-divisas',  key: 'juridica-divisas' },
    { id: 'flag-juridica-bi',       key: 'juridica-bi'  }
];

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
            // Boton nueva gestion
            $("#btn-nueva-gestion").removeAttr('cedula');
            // Limpiamos seccion 1
            $("[data=cedula]").val('');
            $("[data=celular]").val('');
            $("[data=gerente_banca_persona]").val('');
            $("[data=nombre]").val('');
            $("[data=otro_telefono]").val('');
            $("[data=gerente_juridico]").val('');
            $("[data=segmento]").val('');
            $("[data=correo]").val('');
            $("[data=vpr_juridico]").val('');
            // Limpiamos seccion 2
            for(let flag of FLAGS) {
                $(`#${ flag.id }`).removeClass('text-success');
                $(`#${ flag.id }`).removeClass('text-warning');
                $(`#${ flag.id }`).removeClass('text-danger');
                $(`#${ flag.id }`).addClass('text-secondary');
            }
            // Limpiamos seccion 3
        },
        ok(data) {
            // Principal
            let seccion_1 = data.seccion_1;
            let seccion_2 = data.seccion_2;
            let seccion_3 = data.seccion_3;

            // Boton nueva gestion
            $("#btn-nueva-gestion").attr('cedula', seccion_1.cedula);
            
            // Seccion 1
            let valor_defecto = "(No aplica)";
            if( seccion_1.celular == null )                  seccion_1.celular = valor_defecto;
            if( seccion_1.gerente_banca_president == null )  seccion_1.gerente_banca_president = valor_defecto;
            if( seccion_1.nombre == null )                   seccion_1.nombre = valor_defecto;
            if( seccion_1.otro_telefono == null )            seccion_1.otro_telefono = valor_defecto;
            if( seccion_1.gerente_juridico == null )         seccion_1.gerente_juridico = valor_defecto;
            if( seccion_1.segmento == null )                 seccion_1.segmento = valor_defecto;
            if( seccion_1.correo == null )                   seccion_1.correo = valor_defecto;
            if( seccion_1.vpr_juridico == null )             seccion_1.vpr_juridico = valor_defecto;
            
            $("[data=cedula]").val(seccion_1.cedula);
            $("[data=celular]").val(seccion_1.celular);
            $("[data=gerente_banca_persona]").val(seccion_1.gerente_banca_president);
            $("[data=nombre]").val(seccion_1.nombre);
            $("[data=otro_telefono]").val(seccion_1.otro_telefono);
            $("[data=gerente_juridico]").val(seccion_1.gerente_juridico);
            $("[data=segmento]").val(seccion_1.segmento);
            $("[data=correo]").val(seccion_1.correo);
            $("[data=vpr_juridico]").val(seccion_1.vpr_juridico);

            // Seccion 2
            for(let flag of FLAGS) {
                $(`#${ flag.id }`).removeClass('text-success');
                $(`#${ flag.id }`).removeClass('text-warning');
                $(`#${ flag.id }`).removeClass('text-danger');

                if( seccion_2[ flag.key ] == '1' )
                    $(`#${ flag.id }`).addClass('text-success');
                if( seccion_2[ flag.key ] == '2' )
                    $(`#${ flag.id }`).addClass('text-warning');
                if( seccion_2[ flag.key ] == '3' )
                    $(`#${ flag.id }`).addClass('text-danger');
                else
                    $(`#${ flag.id }`).addClass('text-secondary');
            }
            
            // Seccion 3
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Nueva gestion
 */
$("#btn-nueva-gestion").on('click', function() {
    if($(this).attr('cedula') == "" || $(this).attr('cedula') == null || $(this).attr('cedula') == undefined) {
        Alerta.warning('Cargar nueva gestion', 'Debe seleccionar la cedula antes.');
    }
    else {
        $("#modal-registro-gestion").modal('show');
    }
});

$("#modal-registro-gestion form").on('submit', function(e) {
    e.preventDefault();
});