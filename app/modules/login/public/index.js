$("#form-login").on('submit', (e) => {
    e.preventDefault();
    let url = `${BASE_URL}/Login/Acceder/`;
    let data = Form.json( $("#form-login") );

    AJAX.enviar({
        url: url,
        data: data,
        antes() {
            Loader.show();
        },
        error(mensaje) {
            Loader.hide();
            $("#login-errors-label").html(mensaje);
            $("#login-errors").collapse('show');
        },
        ok(data) {
            $("#login-errors").collapse('hide');
            let url = BASE_URL;
            if(IR_A != "") url += `/${IR_A}`;
            location.href = url;
        },
        final() {
            $('[name=pass]').val('');
        }
    });
});