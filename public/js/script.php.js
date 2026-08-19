
var iUnreadQna = null;

function checkUnreadQna() {
    $.get('/mypage/myinfo', {'field': 'answered'}, function (response) {
        var unreadCount = parseInt(response.row.answered);
        if (unreadCount>0 ) {
            iUnreadQna = setInterval(function() {
                playSoundUnreadQna(unreadCount)
            }, 1000*5);
        }
    }, 'json')
}

var cntUnreadQnaCheck = 0;
function playSoundUnreadQna(cnt) {
    var alarmSound2 = new Audio('/sound/notice.mp3');
    alarmSound2.loop = false;
    var promise = alarmSound2.play();

    if (promise !== undefined) {
        promise.then(_ => {
            // Autoplay started!
            clearInterval(iUnreadQna);
        }).catch(error => {
            cntUnreadQnaCheck++;
            if(cntUnreadQnaCheck>5) {
                UIkit.modal.alert('문의하신내용에 답변이 달렸습니다.', {labels: {'ok': '확인'}}).then(function () {
                    clearInterval(iUnreadQna);
                    alarmSound2.play();
                    SLB_POPUP(FURL + '/mypage', 'my_qna');
                })
            }
        });
    }

    $("#answered_count").text(parseInt(cnt));
    $("#_btn_qna").css('background','red');
    $("#_txt_qna").css('color','yellow');
}

function openCasinoGame(cid, gameId) {
    if(!check_login()){
        return;
    }
    let url = FURL + "/cas?prd=" + cid;
    if(gameId == GAME_CASINO_SIGMA){
        let ip = $("#loginModal #ip_addr").val();
        url += `&ip=${ip}`;
    }
    // if(gameId == GAME_CASINO_EVOL)     // GAME_CASINO_EVOL
    //     window.open(FURL + "/evl", "games", "width=1200, height=800, left=100, top=50");
    // else if(gameId == GAME_CASINO_STAR) // GAME_CASINO_STAR
    //     window.open(FURL + "/cas_h?prd=" + cid , "games", "width=1200, height=800, left=100, top=50");
    // else if(gameId == GAME_CASINO_RAVE) // GAME_CASINO_RAVE
    //     window.open(FURL + "/cas_r?prd=" + cid , "games", "width=1200, height=800, left=100, top=50");
    // else if(gameId == GAME_CASINO_TREEM) // GAME_CASINO_TREEM
    //     window.open(FURL + "/cas_t?prd=" + cid , "games", "width=1200, height=800, left=100, top=50");
    // else if(gameId == GAME_CASINO_SIGMA) // GAME_CASINO_SIGMA
    //     window.open(FURL + "/cas_s?prd=" + cid + "&ip=" + ip , "games", "width=1200, height=800, left=100, top=50");
    // else // GAME_CASINO_KGON

    window.open(url , "games", "width=1200, height=800, left=100, top=50");
    
}

function openSlotGame(cid, cname) {
    // $.get('/game/cx/notice', {'cid': cid}, function (response) {
    //     //console.log(response);
    //     if(response.status=='200') { // 슬롯게임시작전 알림공지있음
    //         $('#game_notice .gameStart.button').click(function() {
    //             SLB('/xslotlist?prd=' + cid, {'width': 'fifteen wide column ','height' : $(window).height()*0.9,'caption': '<i class=\'th icon\'></i>' + cname});
    //             UIkit.modal('#game_notice').hide();
    //         });
    //         $('#game_notice #game_notice_title').html(response.title);
    //         $('#game_notice #game_notice_message').html(response.message);
    //         UIkit.modal('#game_notice').show();
    //     } else {
    //         SLB('/xslotlist?prd=' + cid, {'width': 'fifteen wide column ','height' : $(window).height()*0.9,'caption': '<i class=\'th icon\'></i>' + cname});
    //     }
    // }, 'json')
    if(!check_login()){
        return;
    }
    let ip = $("#loginModal #ip_addr").val();

    SLB(FURL+'/xslotlist?prd='+cid+'&ip='+ip, {'width': 'fifteen wide column ','height' : $(window).height()*0.9,'caption': '<i class=\'th icon\'></i>' + cname});

}

$(document).ready(function() {
    // setInterval(LoadUserHasInfo, 1000*10);

    // checkUnreadQna();
});




