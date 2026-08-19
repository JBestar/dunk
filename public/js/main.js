$(document).ready(function() {
    if(is_login()){
        checkNotice();
        startWorker();

        var logged = getLogCookie("logged");
        if (logged != "yes") {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: FURL + "/api/logout",
                success: function(jResult) {
                    location.reload();
                },
                error: function(request, status, error) {}
            });
        }
    }

    
    $('#btn_close_oneday_layer1').click(function(e) {
        setCookie('layer1_check', true, 1);
        $('#layer1').hide();
    });

    $('#btn_close_oneday_layer3').click(function(e) {
        setCookie('layer3_check', true, 1);
        $('#layer3').hide();
    });

    $('#btn_close_oneday_layer4').click(function(e) {
        setCookie('layer4_check', true, 1);
        $('#layer4').hide();
    });

    $('#btn_close_oneday_layer5').click(function(e) {
        setCookie('layer5_check', true, 1);
        $('#layer5').hide();
    });
    
    $('#btn_close_oneday_layer6').click(function(e) {
        setCookie('layer6_check', true, 1);
        $('#layer6').hide();
    });
    
    $('#btn_close_oneday_layer7').click(function(e) {
        setCookie('layer7_check', true, 1);
        $('#layer7').hide();
    });

    $('.pop_close').on('click', function() {
        $(this).parent().parent().hide();
    });
    
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

    window.onmessage = function(event) {
        console.log(event.data);
        if(event.data == "change_point"){
            session_check();
        }
    };
});

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


function is_login(){
    if($("#wrapper").data("login") == "1")
        return true;
    else return false;
}

function check_login(){
    if(!is_login()){
        showAlert("로그인 해주세요", 3);
        return false;
    } return true;
}


function closeTimer(){
    
    stopWorker();
}

function money_check(){
    requestEggInfo();

    setTimeout(function() {
        session_check();
    }, 3000);
}


function requestEggInfo() {

    $.ajax({
        type: "POST",
        url: FURL + "/api/egginfo",
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        },
    });

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

                UIkit.modal.alert("세션이 만료되었습니다. 다시 로그인하세요.", {labels: {'ok': '확인'}}).then(function () {
                    reloadPage();
                });
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function checkNotice() {
    $.ajax({
        type: 'POST',
        url: FURL + '/api/check_notice',
        dataType: "json",
        success: function(data) {
            // console.log(data);
            if (data.status == "success") {
                showNotice(data.notice_main, data.boards);
            } else if (data.status == "logout") {
                
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function updateCurrency(money, point) {
    $("#wrapper ._has_cash").html(numberWithCommas(money));
    $("#wrapper ._has_point").html(numberWithCommas(point));
}

// 금액 , 표시
function numberWithCommas(x) {
    if (isNaN(x)) {
        return '0'
    }
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

var tmViewForm = 0;
var mUnreadMsg = 0;
function showUnread(msg, cus) {

    mUnreadMsg = msg;
    if (msg > 0) {
        $("#_btn_memo").addClass('flicker');
        if(Date.now() - tmViewForm > 60000){
            tmViewForm = Date.now(); 
            SLB_POPUP(FURL + '/mypage', 'my_memo');
        }
    } else {
        $("#_btn_memo").removeClass('flicker');
    }
        
    if (cus > 0) {
        $("#_btn_qna").addClass('flicker');
        if(Date.now() - tmViewForm > 60000){
            tmViewForm = Date.now(); 
            SLB_POPUP(FURL + '/mypage', 'my_qna');
        }
    } else {
        $("#_btn_qna").removeClass('flicker');
    }

}

function checkUnread(){
    if(mUnreadMsg > 0){
        showAlert(langMessage.message_to_read, 2);
        SLB_POPUP(FURL + '/mypage', 'my_memo');
        return false;
    } 
    return true;
}

function showNotice(main, boards) {
    if (main == 0) {
       $(".scroll_area").hide();
    } else {
       $(".scroll_area").show();
    }

    if (boards.length < 5 || getCookie('layer1_check') == 'true') {
        $('#layer1').hide();
    } else {
        $('#layer1').show();
    }

    if (boards.length < 6 || getCookie('layer3_check') == 'true') {
        $('#layer3').hide();
    } else {
        $('#layer3').show();
    }

    if(boards.length < 1 || getCookie('layer4_check') == 'true'){
        $('#layer4').hide();
    } else {
        $('#layer4').show();
    }

    if(boards.length < 2 || getCookie('layer5_check') == 'true'){
        $('#layer5').hide();
    } else {
        $('#layer5').show();
    }

    if(boards.length < 3 || getCookie('layer6_check') == 'true'){
        $('#layer6').hide();
    } else {
        $('#layer6').show();
    }
    
    if(boards.length < 4 || getCookie('layer7_check') == 'true'){
        $('#layer7').hide();
    } else {
        $('#layer7').show();
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
