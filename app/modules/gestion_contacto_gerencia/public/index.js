let cedula_actual = null;
const VINCULACION_NATURAL = {
    mes_1:      $("#vinculacion-natural .mes_1"),
    mes_2:      $("#vinculacion-natural .mes_2"),
    mes_3:      $("#vinculacion-natural .mes_3"),
    tbody:      $("#vinculacion-natural tbody"),
};
const VINCULACION_JURIDICA = {
    mes_1:      $("#vinculacion-juridica .mes_1"),
    mes_2:      $("#vinculacion-juridica .mes_2"),
    mes_3:      $("#vinculacion-juridica .mes_3"),
    tbody:      $("#vinculacion-juridica tbody"),
};

$("#form-search").on('submit', function(e) {
    e.preventDefault();

    AJAX.enviar({
        url: `${BASE_URL}/gestion_contacto_gerencia/api/consultar_datos/`,
        data: Form.json( $("#form-search") ),
        antes() {
            Loader.show();
        },
        error(mensaje) {
            cedula_actual = null;
            Alerta.error('Consultar datos', mensaje);
            limpiar_ventana();
        },
        ok(data) {
            if(!data.encontrado) {
                limpiar_ventana();
                $("[data=cedula]").html(data.cedula);
                $("#modal-usuario-no-encontrado").modal('show');
                return;
            }

            if(data.editable) {
                $("#btn-modificar-datos").attr('cedula', data.cedula).removeAttr('disabled');
                $("[data][name=celular]").removeAttr('disabled');
                $("[data][name=gerente_banca_persona]").removeAttr('disabled');
                $("[data][name=nombre]").removeAttr('disabled');
                $("[data][name=otro_telefono]").removeAttr('disabled');
                $("[data][name=gerente_juridico]").removeAttr('disabled');
                $("[data][name=segmento]").removeAttr('disabled');
                $("[data][name=correo]").removeAttr('disabled');
                $("[data][name=vpr_juridico]").removeAttr('disabled');
                $("#collapse-editar").collapse('show');
            } else {
                $("#btn-modificar-datos").removeAttr('cedula').attr('disabled', '');
                $("#collapse-editar").collapse('hide');
            }

            // Principal
            let seccion_1 = data.seccion_1;
            let seccion_2 = data.seccion_2;
            let seccion_3 = data.seccion_3;

            // Boton nueva gestion
            cedula_actual = seccion_1.cedula;
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
            
            $("[data=cedula]").attr('value', seccion_1.cedula);
            $("[data=celular]").attr('value', seccion_1.celular);
            $("[data=gerente_banca_persona]").attr('value', seccion_1.gerente_banca_president);
            $("[data=nombre]").attr('value', seccion_1.nombre);
            $("[data=otro_telefono]").attr('value', seccion_1.otro_telefono);
            $("[data=gerente_juridico]").attr('value', seccion_1.gerente_juridico);
            $("[data=segmento]").attr('value', seccion_1.segmento);
            $("[data=correo]").attr('value', seccion_1.correo);
            $("[data=vpr_juridico]").attr('value', seccion_1.vpr_juridico);

            // Seccion 2
            VINCULACION_NATURAL.mes_1.html( seccion_2.mes_1 );
            VINCULACION_NATURAL.mes_2.html( seccion_2.mes_2 );
            VINCULACION_NATURAL.mes_3.html( seccion_2.mes_3 );

            VINCULACION_NATURAL.tbody.html('');
            for(let vinculacion of seccion_2.vinculacion_natural) {
                VINCULACION_NATURAL.tbody.append(`<tr>
                    <td>${ vinculacion.label }</td>
                    <td>${ vinculacion.promedio }</td>
                    <td>${ vinculacion.mes_1 }</td>
                    <td>${ vinculacion.mes_2 }</td>
                    <td>${ vinculacion.mes_3 }</td>
                </tr>`);
            }
            
            VINCULACION_JURIDICA.mes_1.html( seccion_2.mes_1 );
            VINCULACION_JURIDICA.mes_2.html( seccion_2.mes_2 );
            VINCULACION_JURIDICA.mes_3.html( seccion_2.mes_3 );

            VINCULACION_JURIDICA.tbody.html('');
            for(let vinculacion of seccion_2.vinculacion_juridica) {
                VINCULACION_JURIDICA.tbody.append(`<tr>
                    <td>${ vinculacion.label }</td>
                    <td>${ vinculacion.promedio }</td>
                    <td>${ vinculacion.mes_1 }</td>
                    <td>${ vinculacion.mes_2 }</td>
                    <td>${ vinculacion.mes_3 }</td>
                </tr>`);
            }
            
            // Seccion 3
            actualizar_tabla_gestion(seccion_3);
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Limpiar ventana
 */
function limpiar_ventana() {
    // Boton nueva gestion
    $("#btn-modificar-datos").removeAttr('cedula');
    $("#btn-nueva-gestion").removeAttr('cedula');
    $("#collapse-editar").collapse('hide');
    // Limpiamos seccion 1
    $("[data][name=celular]").attr('disabled', '');
    $("[data][name=gerente_banca_persona]").attr('disabled', '');
    $("[data][name=nombre]").attr('disabled', '');
    $("[data][name=otro_telefono]").attr('disabled', '');
    $("[data][name=gerente_juridico]").attr('disabled', '');
    $("[data][name=segmento]").attr('disabled', '');
    $("[data][name=correo]").attr('disabled', '');
    $("[data][name=vpr_juridico]").attr('disabled', '');

    $("[data=cedula]").attr('value', '');
    $("[data=celular]").attr('value', '');
    $("[data=gerente_banca_persona]").attr('value', '');
    $("[data=nombre]").attr('value', '');
    $("[data=otro_telefono]").attr('value', '');
    $("[data=gerente_juridico]").attr('value', '');
    $("[data=segmento]").attr('value', '');
    $("[data=correo]").attr('value', '');
    $("[data=vpr_juridico]").attr('value', '');
    // Limpiamos seccion 2
    let code_vinculacion_defecto = `<tr>
        <td colspan="100">
            <h5 class="mb-0 p-3 text-center">. . .</h5>
        </td>
    </tr>`;
    VINCULACION_NATURAL.tbody.html(code_vinculacion_defecto);
    VINCULACION_NATURAL.tbody.html(code_vinculacion_defecto);
    // Limpiamos seccion 3
    actualizar_tabla_gestion([]);
}

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

    AJAX.enviar({
        url: `${BASE_URL}/Gestion_Contacto_Gerencia/api/registrar_gestion/`,
        data: {
            cedula: cedula_actual,
            ...Form.json( $("#modal-registro-gestion form") )
        },
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Alerta.error('Registrar nueva gestión', mensaje);
        },
        ok(data) {
            actualizar_tabla_gestion(data.gestiones);
            $("#modal-registro-gestion").modal('hide');
            Alerta.ok('Registrar nueva gestión', 'Gestión registrada exitosamente.');
            $('#modal-registro-gestion form')[0].reset();
            actualizar_select_estatus_gestion();
        },
        final() {
            Loader.hide();
        }
    });
});

/**
 * Selectores del modal
 */
 function actualizar_select_estatus_gestion() {
    let tipo_gestion_id = $("#tipo_gestion").val();
    let options = $("#estatus_gestion option");
    let selected_id = null;
    for(let option of options) {
        if( $(option).attr('parent_id') == tipo_gestion_id ) {
            $(option).removeAttr('disabled');
            if(selected_id == null) selected_id = $(option).val();
        }
        else {
            $(option).attr('disabled', '');
        }
    }
    $("#estatus_gestion").val(selected_id)
}

actualizar_select_estatus_gestion();
$("#tipo_gestion").on('change', function() {
    actualizar_select_estatus_gestion();
});

/**
 * Tabla de gestion
 */
 var vacio_defecto = '<span class="text-muted small">(No aplica)</span>';
 var table_gestion = $("#tabla-gestion").DataTable({
     language: DT_SPANISH,
     order: [[ 1, "desc" ]],
     data: [],
     columns: [
         {
             data: 'fecha_asignacion',
             class: 'text-center text-truncate',
             render: function(d, type, row) {
                 if(d == null) return vacio_defecto;
                 let date = new Date(d);
                 return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
             }
         },
         {
             data: 'fecha_gestion',
             class: 'text-center text-truncate',
             render: function(d, type, row) {
                 let date = new Date(d);
                 return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
             }
         },
         {
             data: 'ejecutivo',
             class: 'text-truncate',
         },
         {
             data: 'tipo_llamada',
             class: 'text-center text-truncate',
         },
         {
             data: 'tipo_gestion',
             class: 'text-center text-truncate',
         },
         {
             data: 'estatus_gestion',
             class: 'text-center text-truncate',
         },
         {
             data: 'resolucion_comite',
             render: function(d, type, row) {
                 if(d == null) return vacio_defecto;
                 else return d;
             }
         },
         {
             data: 'fecha_comite',
             class: 'text-center text-truncate',
             render: function(d, type, row) {
                 if(d == null) return vacio_defecto;
                 let date = new Date(d);
                 return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
             }
         },
         {
             data: 'membresia_president',
             render: function(d, type, row) {
                 if(d == null) return vacio_defecto;
                 else return d;
             }
         },
         {
             data: 'fecha_pago',
             class: 'text-center text-truncate',
             render: function(d, type, row) {
                 if(d == null) return vacio_defecto;
                 let date = new Date(d);
                 return `${date.getDate() + 1}/${date.getMonth() + 1}/${date.getFullYear()}`;
             }
         },
         {
            data: 'comentario',
            class: 'text-truncate',
            render: function(d, type, row) {
                let limite = 50;
                if(d.length > limite) d = d.substr(0, limite - 3) + "...";
                return `<div class="d-flex justify-content-between">
                    <span>${d}</span>
                    <button class="btn btn-sm btn-outline-primary ml-2 comentario" style="padding: 0rem .40em;">
                        <i class="fas fa-eye fa-xs"></i>
                    </button>
                </div>`;
            }
         },
     ],
 });
 function actualizar_tabla_gestion(data) {
     table_gestion.clear();
     table_gestion.rows.add(data);
     table_gestion.draw();
 }

 /**
  * Mostrar comentario
  */
  $("#tabla-gestion").on('click', '.comentario', function() {
     let data = table_gestion.row( $(this).parents('tr') ).data();
 
     $("#modal-comentario form [name=id]").val( data.id );
     $("#modal-comentario form [name=fecha_gestion]").val( data.fecha_gestion );
     $("#modal-comentario form [name=comentario]").val( data.comentario );
 
     if(data.usuario_id == USUARIO_ID) {
         $("#modal-comentario form [name=comentario]").removeAttr('disabled');
         $("#modal-comentario form [type=submit]").removeAttr('disabled').removeClass('d-none');
     }
     else {
         $("#modal-comentario form [name=comentario]").attr('disabled', '');
         $("#modal-comentario form [type=submit]").attr('disabled', '').addClass('d-none');
     }
 
     $("#modal-comentario").modal('show');
 });
 
 $("#modal-comentario form").on('submit', function(e) {
     e.preventDefault();
 
     AJAX.enviar({
         url: `${BASE_URL}/Gestion_Contacto_Gerencia/API/modificar-comentario`,
         data: Form.json( $("#modal-comentario form") ),
         antes() {
             Loader.show();
         },
         error(mensaje) {
             Alerta.error('Modificar comentario', mensaje);
         },
         ok(data) {
             actualizar_tabla_gestion(data.gestiones);
             $("#modal-comentario").modal('hide');
             Alerta.ok('Modificar comentario', 'Comentario modificado exitosamente.');
         },
         final() {
             Loader.hide();
         }
     });
 });

 /**
  * Registro de cliente
  */
 $("#registrar-usuario").on('click', function() {
     $("#modal-usuario-no-encontrado").modal('hide');
 
     $("#modal-registro-cliente form")[0].reset();
     $("#modal-registro-cliente form [name=cedula]").val( $("#form-search [name=cedula]").val() );
     $("#modal-registro-cliente").modal('show');
 });
 
 $("#modal-registro-cliente form").on('submit', function(e) {
     e.preventDefault();
 
     AJAX.enviar({
         url: `${BASE_URL}/Gestion_Contacto/API/registrar-cliente`,
         data: Form.json( $("#modal-registro-cliente form") ),
         antes() {
             Loader.show();
         },
         error(mensaje) {
             Alerta.error('Registrar Cliente', mensaje);
         },
         ok(data) {
             $("#form-search [name=cedula]").val(data.cedula);
             $("#form-search").submit();
             $("#modal-registro-cliente").modal('hide');
             Alerta.ok('Registrar Cliente', 'Cliente registrado exitosamente.');
         },
         final() {
             Loader.hide();
         }
     });
 });

 /**
  * Modificar datos del cliente
  */
 $("#btn-modificar-datos").on('click', function() {
     let cedula = $(this).attr('cedula');
     if(cedula == undefined || cedula == null || cedula == "") {
         Alerta.error('Modificar cliente', 'Error inesperado, actualice la pagina.');
         return;
     }
     let data = {
         cedula:                 cedula,
         celular:                $("[data][name=celular]").val(),
         gerente_banca_persona:  $("[data][name=gerente_banca_persona]").val(),
         nombre:                 $("[data][name=nombre]").val(),
         otro_telefono:          $("[data][name=otro_telefono]").val(),
         gerente_juridico:       $("[data][name=gerente_juridico]").val(),
         segmento:               $("[data][name=segmento]").val(),
         correo:                 $("[data][name=correo]").val(),
         vpr_juridico:           $("[data][name=vpr_juridico]").val()
     };
 
     AJAX.enviar({
         url: `${BASE_URL}/Gestion_Contacto/API/modificar-cliente`,
         data: data,
         antes() {
             Loader.show();
         },
         error(mensaje) {
             Alerta.error('Modificar Cliente', mensaje);
         },
         ok(data) {
             $("#form-search [name=cedula]").val(data.cedula);
             $("#form-search").submit();
             Alerta.ok('Modificar Cliente', 'Cliente modificar exitosamente.');
         },
         final() {
             Loader.hide();
         }
     });
 });