$(document).ready(function() {

    /*Attempt register user jquery ajax*/
    $('#login-okbtn-id').click(function() {
        login();
    });

    $("#login-user-input-id").keypress(function(event) {
        var nKeyCode = event.which || event.keyCode;
        if (nKeyCode == 13)
            login();

    });


    $("#login-pwd-input-id").keypress(function(event) {
        var nKeyCode = event.which || event.keyCode;
        if (nKeyCode == 13)
            login();

    });


});

function login() {
    var user_name = $('#login-modal-panel-id').find('#login-user-input-id').val();

    var user_password = $('#login-modal-panel-id').find('#login-pwd-input-id').val();

    if (user_name == "" || user_password == "")
        return;

    var login_data = { "username": user_name, "password": user_password };
    var jsonData = JSON.stringify(login_data);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/login",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                setCookie('logged', 'yes', 0);
                window.location.replace( FURL +jResult.data.redirect);

            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });


}

function setCookie(name, value, expiredays) {
    var expires = "";

    if (expiredays && expiredays > 0) {
        var date = new Date();
        date.setTime(date.getTime() + expiredays * 24 * 60 * 60 * 1000);
        expires = "; expires=" + date.toUTCString();
    }

    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(";");

    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];

        while (c.charAt(0) === " ") {
            c = c.substring(1);
        }

        if (c.indexOf(nameEQ) === 0) {
            return decodeURIComponent(c.substring(nameEQ.length, c.length));
        }
    }

    return "";
}