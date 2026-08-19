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
                $("#ip_addr").val(json.ip);
                console.log("jsonip="+json.ip);
            }
        }
    );

    $.getJSON("https://api.ipify.org?format=jsonp&callback=?",
        function(json) {
            // console.log("ip1="+json.ip);
            if(json.ip !== undefined && json.ip.length > 0){
                $("#ip_addr").val(json.ip)
                console.log("ipify="+json.ip);
            }
        }
    );


    let mainNavbarDropDown = document.getElementById("main-navbar-dropdown-container-id");
    var btnLang = document.getElementById("lang-button");
    $('#lang-button').on('click', function() {
        if (mainNavbarDropDown.style.display == "none")
            mainNavbarDropDown.style.display = "block";
        else mainNavbarDropDown.style.display = "none";
    });
    $('#main-navbar-dropdown-ko-id').on('click', function() {
        $("#lang-img").attr("src", "/images/common/ko.png?v=1");
        $("#lang-code").text($("#lang-ko").text() );
        mainNavbarDropDown.style.display = "none";
        setLang("ko");
    });
    $('#main-navbar-dropdown-cn-id').on('click', function() {
        $("#lang-img").attr("src", "/images/common/cn.png?v=1");
        $("#lang-code").text($("#lang-cn").text());
        mainNavbarDropDown.style.display = "none";
        setLang("cn");
    });
    
    let imgLang = document.getElementById("lang-img");
    let spanLang = document.getElementById("lang-code");
    let spanLangUp = document.getElementById("lang-up");

    window.onclick = function(event) {
        if (mainNavbarDropDown.style.display == "block" && event.target != btnLang && event.target != imgLang && event.target != spanLang && event.target != spanLangUp) {
            mainNavbarDropDown.style.display = "none";
        }
    }




});

// 영어/숫자만 입력
$(".english").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();

        var regex = /[^a-z0-9]/gi;
        if (regex.test(inputVal)) {
            $(this).val(inputVal.replace(/[^a-z0-9]/gi, ''));
            if ($(this).attr('id') === 'user_id') {
                $('#login_alert').text(langMessage.login_rule);
            } else if ($(this).attr('id') === 'proposer') {
                $('#prop_alert').text(langMessage.login_rule);
            } else if ($(this).attr('id') === 'login_email_m') {
                $('.login_alert').show();
            } else if ($(this).attr('id') === 'login_email') {
                confirmAlert(langMessage.login_rule);
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
                confirmAlert(langMessage.login_rule, 'handleWindowsKeyboard()')
            }
        }
    }
});

let handleWindowsKeyboardTimer;
/**
 * in windows, the "enter" key will trigger Korean character one more time.
 * it causes the alert to show 2 times.
 * this function solve that problem.
 *
 */
function handleWindowsKeyboard() {
    pop_open = 'true';

    window.clearTimeout(handleWindowsKeyboardTimer);
    handleWindowsKeyboardTimer = window.setTimeout(function() {
        pop_open = 'false';
    }, 200);
}

// 영어/숫자/특수 키만 입력
$(".english_s").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();
        //console.log(inputVal);
        $(this).val(inputVal.replace(/[^a-z0-9~!@#$%^&*_:;,.=+-]/gi, ''));
    }
});

// 한글만 입력
$(".korean").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();

        // 한글 영문인경우 아래 주석 사용
        $(this).val(inputVal.replace(/[^(ㄱ-힣a-zA-Z0-9)]/gi, ''));
        //$(this).val(inputVal.replace(/[^(ㄱ-힣)]/gi, ''));
        // $(this).val(inputVal.replace(/[^(ㄱ-힣0-9)]/gi, ''));
    }
});

function aes_encrypt(key, iv, data) {
    var keyBytes = CryptoJS.enc.Hex.parse(key);
    var ivBytes = CryptoJS.enc.Hex.parse(iv);

    var encrypted_str = CryptoJS.AES.encrypt(data, keyBytes, {
        iv: ivBytes,
        padding: CryptoJS.pad.ZeroPadding
    }).ciphertext.toString(CryptoJS.enc.Base64);

    return encrypted_str;
}

// 금액 , 표시
function numberWithCommas(x) {
    if (isNaN(x)) {
        return '0'
    }
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function removeComma(str) {
    n = parseInt(str.replace(/,/g, ""));
    return n;
}

function commas(t) {

    var x = t.value;
    x = x.replace(/,/gi, '');

    var regexp = /^[0-9]*$/;
    if (!regexp.test(x)) {
        $(t).val("");
        alert("숫자만 입력 가능합니다.");
    } else {
        x = x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(t).val(x);
    }
}


function isMobile() {    
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

// 02d
function pad(n, width) {
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

// html 태그 제거
function no_tag(tmp) {
    if (tmp == null)
        return "";
    tmp = tmp.replace(/<(\/?)p>/gi, ""); //p태그 제거
    tmp = tmp.replace(/(\n|\r\n)/g, ' ');
    tmp = tmp.replace(/<br>/g, ' ');

    return tmp;
}

function no_all_tag(tmp) {
    tmp = tmp.replace(/(<([^>]+)>)/ig, "");
    return tmp;
}

function number_format(number) {
    // number=number.replace(/\,/g,"");
    // nArr = String(number).split('').join(',').split('');
    // for( var i=nArr.length-1, j=1; i>=0; i--, j++)  if( j%6 != 0 && j%2 == 0) nArr[i] = '';

    // return nArr.join('');
    if (isNaN(number)) {
        return '0'
    }
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function double_quote_to_quotes(tmp) {
    tmp = tmp.replace(/"/g, /'/g);

    return tmp;
}

function checkNumber(event) {

    event = event || window.event;
    var keyID = (event.which) ? event.which : event.keyCode;
    if ((keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39) {
        return;
    } else {
        event.target.value = event.target.value.replace(/[^0-9]/g, "");
    }
}

// 한글 입력 방지
function removeChar(event) {

    event = event || window.event;
    var keyID = (event.which) ? event.which : event.keyCode;
    if (keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39)
        return;
    else
        event.target.value = event.target.value.replace(/[^0-9]/g, "");

    // 미니게임 하단
    if ($("#bet_money").is(":focus")) {

        // console.error('11111');

        var bet_money = $('#bet_money').val().replace(/,/g, '');
        var match_money = parseInt(bet_money * select_rate);
        if (isNaN(match_money)) {
            match_money = 0;
        }
        $('#hit_money_input').val(numberWithCommas(match_money));
        return;
    }

    // PC 카트
    if ($("#input_money").is(":focus")) {

        // console.error('22222');

        var bet_money = parseInt($("input#input_money").val().replace(/,/g, ''));
        var total_rate = parseFloat($("#total_rate").text());

        var reward = bet_money * total_rate;
        reward = Math.floor(reward / 1) * 1; // 소수점 버리고 1원단위절사

        $("#hit_money").text(numberWithCommas(reward));
    }

    // 모바일 카트
    if ($("#input_money_m").is(":focus")) {

        // console.error('33333');

        var bet_money = parseInt($("input#input_money_m").val().replace(/,/g, ''));
        var total_rate = parseFloat($("#total_rate_m").text());

        var reward = bet_money * total_rate;
        reward = Math.floor(reward / 1) * 1; // 소수점 버리고 1원단위절사

        $("#hit_money_m").text(numberWithCommas(reward));
    }
}

// 빈값 check
function is_empty(value) {
    if (value == "" || value == null || value == undefined || (value != null && typeof value == "object" && !Object.keys(value).length)) {
        return true
    } else {
        return false
    }
}
// 쿠키 사용하기
function setCookie(cookie_name, value, days) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + days);
    // 설정 일수만큼 현재시간에 만료값으로 지정

    var cookie_value = escape(value) + ((days == null) ? '' : '; expires=' + exdate.toUTCString());
    document.cookie = cookie_name + '=' + cookie_value;
}

function getCookie(cookie_name) {
    var x, y;
    var val = document.cookie.split(';');

    for (var i = 0; i < val.length; i++) {
        x = val[i].substr(0, val[i].indexOf('='));
        y = val[i].substr(val[i].indexOf('=') + 1);
        x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤 공배 제거
        if (x == cookie_name) {
            return unescape(y); // unescape로 디코딩
        }
    }
}

function byte_length(str) {

    var count = 0;
    var ch = '';

    for (var i = 0; i < str.length; i++) {
        ch = str.charAt(i);
        if (escape(ch).length == 6) {
            count++;
        }
        count++;
    }
    return count;
}

function reloadPage() {
    setTimeout(() => {
        window.location.reload()
    }, 1000);
}

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}


function setFocus(obj) {
    if (obj) {
        setTimeout(() => {
            obj.focus()
        }, 1);
    }
}

function isEmptyObj(obj) {
    // 객체 타입체크
    if (obj.constructor !== Object) {
        return false;
    }

    // property 체크
    for (let prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            return false;
        }
    }

    return true;
}

function ObjKeys(obj) {
    return Object.keys(obj).map(function(key) {
        return key;
    });
}


history.replaceState({
    data: 'replace'
}, '', '/');

// 초기값
var show_view = "login";
var chat_open = 'false';
var pop_open = 'false';

$("#user_id").focus();

//////////////////////////////////////////////////
// 공통 
//////////////////////////////////////////////////

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

// Close 버튼 
$('.join_close_btn').on('click', function(e) {
    e.preventDefault();

    $(this).parent().hide();
    $('.login_area').show();
    controlLoginForm('.users_wrap .login_wrap');

    show_view = "login";
    reset_input();
    $('#user_id').focus();
});

// 입력 필드 삭제 
function reset_input() {
    $("#user_id").val('');
    $("#user_pw").val('');
    $("#proposer").val('');
    $("#input_id").val('');
    $("#input_nickname").val('');
    $("#input_pw").val('');
    $("#input_pw_check").val('');
    $("#user_name").val('');
    $("#user_phone").val('');
    $("#bank_name").val('');
    $("#bank_account").val('');
    $("#bank_pw").val('');
    if ($("#security_id").length > 0) {
        $("#security_id").val('');
    }
}

// 엔터키 처리 
$(document).keypress(function(e) {
    if (e.which == 13 || e.keyCode == 13) {

        if (pop_open == 'true') {
            $("#confirm_ok").click();
        } else if (chat_open == 'true') {
            $("#send_customer").click();
        } else if (show_view == "login") {
            $("#btnLogin").click();
        } else if (show_view == "proposer") {
            if ($("#proposer").is(':focus')) {
                $(".step01 .join01_btn").click()
            } else if ($(".step01 .join01_btn").is(':focus')) {
                $(".step01 .join01_btn").click()
            } else {
                $(".step01 .prev_btn").click()
            }
        } else if (show_view == "user_info") {
            $("#btn_next").click();
        } else if (show_view == "account") {
            $("#btn_done").click();
        } else if (show_view == "done") {
            $("#btn_login").click();
        }
    }
});


//////////////////////////////////////////////////
// 로그인 화면 
//////////////////////////////////////////////////

// 로그인 버튼 
$("#btnLogin").click(function(e) {
    e.preventDefault();

    var user_id = $("#user_id").val();
    var user_pw = $("#user_pw").val();

    if (user_id == "") {
        confirmAlert(langMessage.login_id_input);
        return false;
    }

    if (user_pw == "") {
        confirmAlert(langMessage.password_input);
        return false;
    }

    if ($("#security_id").length > 0) {
        var security_id = $("#security_id").val();
        if (security_id == "") {
            confirmAlert('보안문자를 입력해 주세요.');
            $("#security_id").focus();
            return false;
        }
    }

    var data = {
        'userid': user_id,
        'passwd': user_pw,
        'ip':$("#ip_addr").val(),
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
                if(jResult.code == 9){  //점검중
                    $(".alert_wrap .alert_bot p").css("text-align", "left");
                } else {
                    $(".alert_wrap .alert_bot p").css("text-align", "center");
                }
                confirmAlert(jResult.msg, 'reloadPage()');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            // confirmAlert(langMessage.administrator_ask+"\n" + request.status, 'reloadPage()');
        }
    });
});

//////////////////////////////////////////////////
//  회원가입
//////////////////////////////////////////////////
$('.join_btn').on('click', function(e) {
    e.preventDefault();

    show_view = "proposer";

    $('.login_area').hide();
    $('.step01').fadeIn().slideDown();
    controlLoginForm('.users_wrap .login_wrap');

    $('#proposer').focus();
});

//////////////////////////////////////////////////
//  STEP1 : 추천인 코드 입력
//////////////////////////////////////////////////

// 추천인 코드 : 이전 
$('.step01 .prev_btn').on('click', function(e) {
    e.preventDefault();

    show_view = "login";

    $('.step01').hide();
    $('.login_area').show();
    $('#user_id').focus();
    controlLoginForm('.users_wrap .login_wrap');

    reset_input();
});

// 추천인 코드 : START
$('.step01 .join01_btn').on('click', function(e) {
    e.preventDefault();

    var proposer = $("#proposer").val();

    if (proposer == "") {
        confirmAlert(langMessage.recommender_input);
        return;
    }

    var data = {
        'recommender_id': proposer
    };

    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_proposer',
        dataType: 'json',
        data: data ,
        success: function(jResult) {

            if ((jResult.status == 'success')) {
                show_view = "user_info";

                $('.step01').hide();
                $('.step02').show();
                controlLoginForm('.users_wrap .login_wrap');
                $('#input_id').focus();
            } else {
                confirmAlert(jResult.msg);
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            confirmAlert(langMessage.administrator_ask);
        }
    });
});


//////////////////////////////////////////////////
//  STEP2 : 아이디, 닉네임, 비밀번호
//////////////////////////////////////////////////

// 아이디 텍스트 입력 
$("#input_id").focusout(function() {
    var input_id = $("#input_id").val();

    if (input_id.length < 4) {
        $("#id_desc").html("<span style='color:red'>"+langMessage.login_id_input+".(4~16)</span>");
        return;
    }

    var data = {
        'userid': input_id
    };
    if (data.userid == "") {
        $("#id_desc").html("<span style='color:red'>"+langMessage.login_id_input+".(4~16)</span>");
        return;
    }

    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_account',
        dataType: 'json',
        data: data ,
        success: function(jResult) {
            // console.log(jResult);
            if ((jResult.status == 'success')) {
                $("#id_desc").text(langMessage.id_available);
            } else {
                $("#id_desc").html("<span style='color:red'>" + jResult.msg + "</span>");
            }
        },
        error: function(request, status, error) {
            $("#id_desc").html("<span style='color:red'>"+langMessage.administrator_ask+"</span>");
        }
    });
});

// 닉네임 텍스트 입력 
$("#input_nickname").focusout(function() {
    var input_nickname = $("#input_nickname").val();

    if (input_nickname.length < 3) {
        $("#nickname_desc").html("<span style='color:red'>"+langMessage.nickname_input+"(3~12)</span>");
        return;
    }

    var data = {
        'nickname': input_nickname
    };
    if (data.nickname == "") {
        $("#nickname_desc").html("<span style='color:red'>"+langMessage.nickname_input+"(3~12)</span>");
        return;
    }

    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_account',
        dataType: 'json',
        data:  data,
        success: function(jResult) {
            // console.log(jResult);
            if ((jResult.status == 'success')) {
                $("#nickname_desc").text(langMessage.nickname_available);
            } else {
                $("#nickname_desc").html("<span style='color:red'>" + jResult.msg + "</span>");
            }
        },
        error: function(request, status, error) {
            $("#nickname_desc").html("<span style='color:red'>"+langMessage.administrator_ask+"</span>");
        }
    });
});

// 아이디, 닉네임, 비밀 번호 입력 : 이전 
$('.step02 .prev_btn').on('click', function(e) {
    e.preventDefault();
    show_view = "proposer";

    $('.step02').hide();

    $('.step01').show();
    controlLoginForm('.users_wrap .login_wrap');
});

// 아이디, 닉네임, 비밀 번호 입력 : 다음
$('.step02 .next_btn').on('click', function(e) {
    e.preventDefault();

    var input_id = $("#input_id").val();
    var input_nickname = $("#input_nickname").val();
    var input_pw = $("#input_pw").val();
    var input_pw_check = $("#input_pw_check").val();

    if (input_id.length < 4) {
        confirmAlert(langMessage.login_id_input+"(4~16)");
        return;
    }

    if (input_nickname.length < 3) {
        confirmAlert(langMessage.nickname_input+"(3~12)");
        return;
    }

    if (input_pw != input_pw_check) {
        confirmAlert(langMessage.password_match);
        return;
    }

    const regex = new RegExp('^[A-Za-z0-9]*[~!@#$%^&*_:;,.=+-]+[A-Za-z0-9]*$');
    if (input_pw.length < 8 || input_pw.length > 20 || !regex.test(input_pw)) {
        confirmAlert(langMessage.password_rule);
        return;
    }

    var data = JSON.stringify({
        'userid': input_id,
        'nickname': input_nickname
    });

    if (data.userid == "" || data.nickname == "") {
        confirmAlert(langMessage.signup_check);
        return false;
    }

    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_account',
        dataType: 'json',
        data:  data,
        success: function(jResult) {
            // console.log(jResult);
            if ((jResult.status == 'success')) {
                show_view = "account";

                $('.step02').hide();
                $('.step03').show();
                controlLoginForm('.users_wrap .login_wrap');
                $('#user_name').focus();
            } else if ((jResult.status == 'warn')) {
                if(confirm(langMessage.id_deleted)){
                    $('.step02').hide();
                    $('.step03').show();
                    controlLoginForm('.users_wrap .login_wrap');
                    $('#user_name').focus();
                }
            } else {
                confirmAlert(jResult.msg);
            }
        },
        error: function() {
            confirmAlert(langMessage.administrator_ask);
        }
    });
});

//////////////////////////////////////////////////
//  STEP3 : 이름, 연락처, 은행명...
//////////////////////////////////////////////////

// 이름, 연락처, 은행명 등 입력 : 이전 
$('.step03 .prev_btn').on('click', function(e) {
    e.preventDefault();

    show_view = "user_info";

    $('.step03').hide();
    $('.step02').show();
    controlLoginForm('.users_wrap .login_wrap');
});

// 이름, 연락처, 은행명 등 입력 : 다음 
$('.step03 .next_btn').on('click', function(e) {
    e.preventDefault();

    var user_name = $("#user_name").val();
    var user_phone = $("#user_phone").val();
    var bank_name = $("#bank_name").val();
    var bank_account = $("#bank_account").val();
    var bank_pw = $("#bank_pw").val();

    if (user_name.length < 1) {
        confirmAlert(langMessage.login_name_input);
        return;
    }

    if (user_phone.length < 1) {
        confirmAlert(langMessage.phone_number_input);
        return;
    }

    if (bank_name.length < 1) {
        confirmAlert(langMessage.bank_name_input);
        return;
    }

    if (bank_account.length < 1) {
        confirmAlert(langMessage.account_number_input);
        return;
    }

    if (bank_pw.length < 1) {
        confirmAlert(langMessage.withdrawal_password_input);
        return;
    }

    var check = /[ㄱ-ㅎ|ㅏ-ㅣ|가-힣]/;
    if (check.test(bank_pw)) {
        confirmAlert(langMessage.withdrawal_rule);
        return;
    }

    var proposer = $("#proposer").val();
    var input_id = $("#input_id").val();
    var nickname = $("#input_nickname").val();
    var input_pw = $("#input_pw").val();

    var data = {
        'agent_id': proposer,
        'userid': input_id,
        'nickname': nickname,
        'passwd': input_pw,
        'bank_owner': user_name,
        'phone': user_phone,
        'bank_name': bank_name,
        'bank_account': bank_account,
        'refund_password': bank_pw,
        'ip':$("#ip_addr").val(),
    };

    if (data == null) {
        confirmAlert(langMessage.signup_fail);
        return false;
    }

    $.ajax({
        type: 'POST',
        url: FURL + '/api/register',
        dataType: 'json',
        data: data,
        success: function(jResult) {
            // console.log(jResult);
            if ((jResult.status == 'success')) {
                show_view = "done";

                $('.step03').hide();
                $('.step04').show();
                controlLoginForm('.users_wrap .login_wrap');

                $('#join_commment').html(jResult.msg);
            } else {
                confirmAlert(jResult.msg);
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);

            confirmAlert(langMessage.administrator_ask);
        }
    });
});


//////////////////////////////////////////////////
//  STEP4 : 가입 완료 
//////////////////////////////////////////////////

// 회원 가입 완료 
$('.step04 .next_btn').on('click', function(e) {
    e.preventDefault();

    show_view = "login";

    $('.step04').hide();
    $('.login_area').show();
    controlLoginForm('.users_wrap .login_wrap');

    var user_id = $("#input_id").val();

    reset_input();
    $("#user_id").val(user_id);
    $('#user_pw').focus();
});


//////////////////////////////////////////////////
//  고객센터 상담하기 
//////////////////////////////////////////////////

function loadScript(url) {
    if (url == null || url == "") {
        return;
    }
    var script = document.createElement('script');
    script.src = url;
    document.head.appendChild(script);
}


function controlLoginForm(sElement){
    // console.log(window.innerWidth);
    // if(window.innerWidth <= 679)
    //     return;

    let height = $(sElement).css('height');
    height = parseInt(height);
    // console.log(height);
    if(height > 200){
        $(sElement).css('margin-top', '-200px');
        $(sElement).css('background', 'rgb(20, 21, 25, 0.9)');
        $('.users_border').hide();
    } else {
        $(sElement).css('margin-top', '115px');
        $(sElement).css('background', 'rgb(20, 21, 25, 0)');
        $('.users_border').css('height', (height+100) + 'px')
        $('.users_border').show();
    }
}

function setLang(lang) {
    console.log(lang);
    var data = {
        'lang': lang,
    };

    $.ajax({
        type: 'POST',
        url: FURL + '/api/change_lang',
        dataType: 'json',
        data:  data,
        success: function(jResult) {
            // console.log(jResult);
            location.reload();
        },
        error: function(request, status, error) {
            // confirmAlert(langMessage.administrator_ask+"\n" + request.status, 'reloadPage()');
        }
    });
};