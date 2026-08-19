$(document).ready(function() {
    // checkNotice();
    startWorker();
});

// // 타이머 팝업
function toast(msg, duration = 2000) {

    $('.warning_ico').html(msg);
    $('.alert_bg').show();
    $('#time_alert').show(500, 'easeInOutBack');
    setTimeout(function() {
        $('.alert_bg').fadeOut();
        $('.time_alert').fadeOut();
        return true;
    }, duration);
}

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
        $(this).val(inputVal.replace(/[^a-z0-9~!@#$%^&*_:;,.=+-]/gi, ''));
    }
});

// 한글만 입력
$(".korean").keyup(function(event) {
    if (!(event.keyCode >= 37 && event.keyCode <= 40)) {
        var inputVal = $(this).val();

        // 한글 영문인경우 아래 주석 사용
        //$(this).val(inputVal.replace(/[^(ㄱ-힣a-zA-Z)]/gi, ''));
        //$(this).val(inputVal.replace(/[^(ㄱ-힣)]/gi, ''));
        $(this).val(inputVal.replace(/[^(ㄱ-힣0-9)]/gi, ''));
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

function checkMenu() {
    $(".oneDepth").each(function() {

        if (!$(this).hasClass("nog") && ($(this).next().hasClass('twoDepth') || $(this).next().hasClass('lnb_twoDepth'))) {

            if ($(this).parent().find("li") && $(this).parent().find("li").length == 0) {
                $(this).hide();
            }
        }
    });
}

var windowWidth = window.innerWidth;
var egg_check = 0;
$(function() {

    $(window).resize(function() {
        windowWidth = window.innerWidth;

        if (windowWidth <= 1279) {
            $('.message_wrap').animate({
                bottom: '5px'
            }, 700);
        } else if (windowWidth >= 1280) {
            $('.message_wrap').animate({
                right: '15px',
                bottom: '10px'
            }, 700);
        }
    });

    $('.logout_btn, .logout_m_btn').on('click', function(e) {
        e.preventDefault();

        basicAlert('로그아웃 하시겠습니까?', "log_out()");
    });

    $('.change_btn, .point_change_m').on('click', function(e) {
        e.preventDefault();

        if ($(this).hasClass('none')) {
            return;
        }
        var m_coin = '0'
        if ($(this).hasClass('change_btn')) {
            m_coin = $('#h_point').text()
        } else if ($(this).hasClass('point_change_m')) {
            m_coin = $('#h_point_m').text()
        }

        if (m_coin < 1) {
            confirmAlert("포인트 전환 시 전환할 포인트가 부족합니다. ");
        } else {

            basic2Alert("포인트 '" + m_coin + "'를  전환하시겠습니까?", function() {
                changePoint();
            }, null);
        }
    });

    $("#egg_check").on("click", function(e) {
        e.preventDefault();

        if (egg_check > 0) {
            var now = moment().unix();
            if (now - egg_check <= 10) {
                confirmAlert("잠시 후 다시 시도해 주세요. <br>남은 시간:" + (10 - (now - egg_check)) + "초");
                return;
            }
        }

        egg_check = moment().unix();

        $(".loading").show();

        requestEggInfo();
    });

    checkMenu();
});

// 확인, 취소 팝업
function basicAlert(msg, callback) {
    if (callback != null) {
        $('#basic_alert').attr('callback', callback);
    }
    $('#basic_alert').find('#alert_content').html(msg);
    $('.alert_bg').show();
    $('#basic_alert').show(500, 'easeInOutBack');
    $('#basic_ok').focus();
}

function basic2Alert(msg, callback, callback2 = null) {
    // $('#alert2_content').html(msg);
    $('#basic_alert2').find('#alert2_content').html(msg);
    $('.alert_bg').show();
    $('#basic_alert2').show(500, 'easeInOutBack');
    $('#basic2_ok').focus();

    $('#basic2_ok').click(function(e) {
        e.preventDefault()
        closeAlert()
        if (callback) {
            callback()
        }
        callback = null
        callback2 = null
    });
    $('#basic2_cancel').click(function(e) {
        e.preventDefault()
        closeAlert()
        if (callback2) {
            callback2()
        }
        callback = null
        callback2 = null
    });
}

// Alert 버튼
function confirmAlert(msg, callback = null) {
    pop_open = 'true';
    if (callback != null) {
        // $('#confirm_alert').attr('callback', callback);
    }
    $('.question_ico').html(msg);
    $('.alert_bg').show();
    $('#confirm_alert').show(500, 'easeInOutBack');
    setTimeout(() => {
        $('#confirm_ok').focus();
    }, 10);
    $('#confirm_ok').click(function(e) {
        e.preventDefault()
        closeAlert()
        if (callback) {
            callback()
        }
        callback = null
    })
}

function okAlert() {
    pop_open = 'false';
    $('.alert_bg').hide();
    $('.alert_wrap').hide();

    var callbackName = $('#basic_alert').attr('callback');
    eval(callbackName);
}

// Alert 닫기
function closeAlert() {
    pop_open = 'false';
    $('.alert_bg').hide();
    $('.alert_wrap').hide();
}


function log_out() {
    window.location.href = "/logout";
    closeTimer();
    reloadPage();
}

function closeTimer(){
    
    stopWorker();
}

function updateCurrency(money, point) {
    if (money != null) {
        $("#h_money").html(numberWithCommas(money));
        $("#h_money_m").html(numberWithCommas(money));
        if($("#s_money").length > 0){
            $("#s_money").html(numberWithCommas(money));
        }
    }
    if (point != null) {
        $("#h_point").html(numberWithCommas(point))
        $("#h_point_m").html(numberWithCommas(point))
    }

    if (typeof updateCart !== 'undefined' && typeof updateCart === 'function') {
        updateCart(money);
    }
}

function changePoint() {
    $(".loading").show();
    $.ajax({
        type: 'POST',
        url: FURL + '/api/change_point',
        dataType: "json",
        success: function(data) {
            $(".loading").hide();
            if (data.status == "success") {
                reloadPage();
            } else if (data.status == "logout") {
                closeTimer();

                confirmAlert("세션이 만료되었습니다. 다시 로그인하세요.", function() {
                    reloadPage();
                });
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            toast('관리자에게 문의 바랍니다. ');
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function money_check(){
    requestEggInfo();

    setTimeout(function() {
        session_check();
    }, 3000);
}

function session_check() {
    // console.log("session_check");
    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_session',
        dataType: "json",
        success: function(result) {
            // console.log(result);
            if (result.status == "success") {
                updateCurrency(result.data.money, result.data.point);
                showUnread(result.data.msg, result.data.cus);
            } else if (result.status == "logout") {
                closeTimer();

                confirmAlert("세션이 만료되었습니다. 다시 로그인하세요.", function() {
                    reloadPage();
                });

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            if (request.status == 400) {
                closeTimer();

                confirmAlert("세션이 만료되었습니다. 다시 로그인하세요.", function() {
                    reloadPage();
                });
            }
        }
    });

}


var htmlMsg = '<p>읽지 않은 쪽지가 있습니다.</p><a href="/message" class="message_btn">쪽지읽기</a>';
var htmlCus = '<p>문의해답이 도착하였습니다.</p><a href="/customer" class="message_btn">해답보기</a>';

function showUnread(msg, cus) {
    // windowWidth = window.innerWidth;

    // if (window.location.pathname != "/message" &&
    //     window.location.pathname != "/customer") {
    //     if (msg > 0) {
    //         $('.message_wrap .message_con').html(htmlMsg);
    //     } else if (cus > 0) {
    //         $('.message_wrap .message_con').html(htmlCus);
    //     }

    //     if (msg > 0 || cus) {
    //         $('.message_wrap').show();
    //         $('.message_bg').show();
    //     } else {
    //         $('.message_wrap').hide();
    //         $('.message_bg').hide();
    //     }
    // } else {
    //     $('.message_wrap').hide();
    //     $('.message_bg').hide();
    // }

    // if (windowWidth <= 1279) {
    //     $('.message_wrap').animate({
    //         bottom: '5px'
    //     }, 700);
    // } else if (windowWidth >= 1280) {
    //     $('.message_wrap').animate({
    //         right: '15px',
    //         bottom: '10px'
    //     }, 700);
    // }
}

// function showNotice(main, urgent, bank) {
//     if (main == 0) {
//         $('.top_notice').hide();
//         $('#wrap').addClass('top_notice_none');
//     } else {
//         $('.top_notice').show();
//         $('#wrap').removeClass('top_notice_none');
//     }
//     if (urgent == 0 || getCookie('layer1_check') == 'true') {
//         $('#layer1').hide();
//     } else {
//         $('#layer1').show();
//     }
//     if (bank == 0 || getCookie('layer3_check') == 'true') {
//         $('#layer3').hide();
//     } else {
//         $('#layer3').show();
//     }

// }

// function checkNotice() {
//     $.ajax({
//         type: 'POST',
//         url: FURL + '/api/check_notice',
//         dataType: "json",
//         success: function(data) {
//             // console.log(data);
//             if (data.status == "success") {
//                 showNotice(data.notice_main, data.notice_urgent, data.notice_bank);
//             } else if (data.status == "logout") {
//                 closeTimer();
//                 reloadPage();
//             }
//         },
//         error: function(request, status, error) {
//             // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
//         }
//     });
// }


function requestEggInfo() {

    $.ajax({
        type: "POST",
        url: FURL + "/api/egginfo",
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
            } else {
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        },
    });

}

var worker; 
// worker 실행
function startWorker() {
    // Worker 지원 유무 확인
    if ( !!window.Worker ) {
  
      // 실행하고 있는 워커 있으면 중지시키기
      if ( worker ) {
        stopWorker();
      }
  
      worker = new Worker( '/js/worker.js' );
      worker.postMessage( '워커 실행' );    // 워커에 메시지를 보낸다.
  
      // 메시지는 JSON구조로 직렬화 할 수 있는 값이면 사용할 수 있다. Object등
      // worker.postMessage( { name : '302chanwoo' } );
  
      // 워커로 부터 메시지를 수신한다.
      worker.onmessage = function( e ) {      
        money_check();
        
      };
    }
  
  }

  

// worker 중지
function stopWorker() {

    if ( worker ) {
      worker.terminate();
      worker = null;
    }
  
  }
  