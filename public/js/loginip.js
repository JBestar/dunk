function setLogCookie(name, value, expiredays) {

    if (expiredays) {
        var date = new Date();
        date.setTime(date.getTime() + (expiredays * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else {
        var expires = "; expires=0";
    }

    document.cookie = name + "=" + value + expires + "; path=/";

}


function getLogCookie(name) {

    var cName = name + "=";
    var x = 0;
    while (x <= document.cookie.length) {
        var y = (x + cName.length);
        if (document.cookie.substring(x, y) == cName) {
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                endOfCookie = document.cookie.length;
            return unescape(document.cookie.substring(y, endOfCookie));
        }

        x = document.cookie.indexOf(" ", x) + 1;
        if (x == 0)
            break;
    }
    return "";
}

$(document).ready(function() {
    $.getJSON("https://jsonip.com/",
        function(json) {
            // console.log("ip2="+json.ip);
            if(json.ip !== undefined && json.ip.length > 0){
                // $("#ip_addr").val(json.ip);
                console.log("jsonip="+json.ip);
            }
        }
    );

    $.getJSON("https://api.ipify.org?format=jsonp&callback=?",
        function(json) {
            // console.log("ip1="+json.ip);
            if(json.ip !== undefined && json.ip.length > 0){
                // $("#ip_addr").val(json.ip)
                console.log("ipify="+json.ip);
            }
        }
    );
});

// 영어/숫자만 입력
$(".english").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();

        var regex = /[^a-z0-9]/gi;
        if (regex.test(inputVal)) {
            $(this).val(inputVal.replace(/[^a-z0-9]/gi, ''));
            if ($(this).attr('id') === 'user_id') {
                $('#login_alert').text('영어와 숫자만 입력가능합니다.');
            } else if ($(this).attr('id') === 'proposer') {
                $('#prop_alert').text('영어와 숫자만 입력가능합니다.');
            } else if ($(this).attr('id') === 'login_email_m') {
                $('.login_alert').show();
            } else if ($(this).attr('id') === 'login_email') {
                confirmAlert('영어와 숫자만 입력가능합니다.');
            }
        } else {
            $(this).val(inputVal.replace(/[^a-z0-9]/gi, ''));
        }
    }
});

$(".english_p").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();
        var first_len = inputVal.length;
        var reVal = inputVal.replace(/[^a-z0-9]/gi, '');
        var re_len = reVal.length;

        if (first_len !== re_len) {
            $(this).val(reVal);
            if (pop_open !== 'true') {
                confirmAlert('영어와 숫자만 입력 가능합니다', 'handleWindowsKeyboard()')
            }
        }
    }
});

var pop_open = 'false';

// $("#user_id").focus();
// 엔터키 처리 
$(document).keypress(function(e) {
    if (e.which == 13 || e.keyCode == 13) {

        if (pop_open == 'true') {
            $("#confirm_ok").click();
        } 
    }
});

// Alert 버튼
function confirmAlert(msg, callback = null) {
    pop_open = 'true';
    if (callback != null) {
        $('#confirm_alert').attr('callback', callback);
    }
    $('.question_ico').html(msg);
    $('.alert_bg').show();
    $('#confirm_alert').show(500, 'easeInOutBack');
    $('#confirm_ok').focus();
}

// Alert 닫기
function closeAlert() {
    pop_open = 'false';
    $('.alert_bg').hide();
    $('.alert_wrap').hide();

    var callbackName = $('#confirm_alert').attr('callback');
    if (callbackName) {
        eval(callbackName);
    }
}

// 로그인 버튼 
$("#btnLogin").click(function(e) {
    e.preventDefault();

    var user_id = $("#user_id").val();
    var user_pw = $("#user_pw").val();
    var user_ip = $("#ip_addr").val();

    if (user_id == "") {
        confirmAlert('아이디를 입력해 주세요.');
        return false;
    }

    if (user_pw == "") {
        confirmAlert('비밀번호를 입력해 주세요.');
        return false;
    }

    if (user_ip == "") {
        confirmAlert('아이피를 입력해 주세요.');
        return false;
    }

    var data = {
        'type': 1,
        'userid': user_id,
        'passwd': user_pw,
        'ip':user_ip,
    };

    $.ajax({
        type: 'POST',
        url: FURL + '/api/login',
        dataType: 'json',
        data:  data,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == 'success') {
                setLogCookie('logged', 'yes', 0);
                window.location.href = "/home";
            } else if (jResult.status == 'fail') {
                confirmAlert(jResult.msg, 'reloadPage()');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            // confirmAlert("관리자에게 문의 바랍니다.\n" + request.status, 'reloadPage()');
        }
    });
});

function reloadPage() {
    setTimeout(() => {
        window.location.reload()
    }, 1000);
}