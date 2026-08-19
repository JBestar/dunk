var mSecWorker;
var mRound = new Object;
var mRoundError = 0;
var mRoundState = 0;
var mClientTime = 0;
var mCancelEnable = false;
var mConfig = null;

$(document).ready(function() {
    mRound.date = "";
    mRound.round_id = 0;
    mRound.round_no = 0;
    mRound.round_current = 0;
    mRound.round_betend = 0;
    mRound.round_end = 0;

    startSecWorker();
    reqGameResult();

});

var setIFrameScale = function() {
    var setWidth = 830;     //789
    var setHeight = 641;    //609
    var contWidth = 806;

    let gameW = $("#game").data("width");
    let gameH = $("#game").data("height");
    if( gameW !== 'undefined' && gameW > 0 && gameH > 0)
    {
        setWidth = gameW;
        setHeight = gameH;
    }    

    var boxWidth = $(".game_area").width();
    var boxHeight = $(".game_area_bg").height() - 50;
    var scaleHeight;
    if (window.innerWidth < 1140) {
        scale = boxWidth / setWidth;
        scaleHeight = setHeight * scale;
        cssScale(scale);
        $("#game").css("position", "inherit");
    } else if (window.innerWidth >= 1140 && boxWidth >= contWidth) {
        scale = boxHeight / setHeight;
        scaleHeight = setHeight * scale;
        cssScale(scale);
        $("#game").css("position", "inherit");
    } else if (window.innerWidth >= 1140 && boxWidth < contWidth) {
        scale = boxWidth / setWidth;
        scaleHeight = setHeight * scale;
        cssScale(scale);
        $("#game").css("position", "absolute");
    }

    $("#game").css("width", setWidth);
    $("#game").css("height", setHeight);
    $(".game_area").css("height", scaleHeight);
};

function cssScale(i) {
    $("#game").css({
        "-webkit-transform": "scale(" + i + ")",
        "-ms-transform": "scale(" + i + ")",
        "-o-transform": "scale(" + i + ")",
        transform: "scale(" + i + ")",
    });
}

window.addEventListener("resize", function() {
    setIFrameScale();
});
var _orientation = window.matchMedia("(orientation: portrait)");
_orientation.addListener(function(m) {
    if (m.matches) {
        setIFrameScale();
    } else {
        setIFrameScale();
    }
});
$(window)
    .on("orientationchange", function(e) {
        if (e.orientation) {
            if (e.orientation == "portrait") {
                setIFrameScale();
            } else if (e.orientation == "landscape") {
                setIFrameScale();
            }
        }
    })
    .trigger("orientationchange");

$(window).on("load", function() {
    setIFrameScale();
});



$(function() {
    game_reset();
    reqCurrentRound();
    setTimeout(function() {
        reqFollowInfo();
    }, 1000);
    $("#refresh_game").click(function(e) {
        e.preventDefault();

        $(this).attr("disabled", true);

        toast("게임을 새로고침하였습니다.");

        game_reset();
        reqCurrentRound();
        setTimeout(function() {
            $("#refresh_game").attr("disabled", false);
        }, 10000);
    });
});


var select_rate = 0;
var select_idx = "";
var select_id = "";
var select_type = "";

// 전 / 후
function money_max_check(bet_money) {
    var user_money = parseInt($("#u_money").data("amount"));
    var reward = 0;
    var total_rate = select_rate;

    if (bet_money == "undefined" || bet_money == null) {
        bet_money = 0;
    }

    user_money = Number(user_money);
    bet_money = Number(bet_money);

    max_betting = parseInt($("#bet_max").data("amount"));
    bet_min = parseInt($("#bet_min").data("amount"));
    max_hit = parseInt($("#dist_max").data("amount"));

    total_rate = (Math.floor(total_rate * 100) / 100).toFixed(2);

    if (max_betting > 0 && bet_money > max_betting) {
        if (max_betting * total_rate > max_hit) {
            toast("베팅 최대금액은 " + numberWithCommas(max_betting) + ", 적중 최대금액은" + numberWithCommas(max_hit) + " 입니다.");
            var tmp = Math.floor(Math.floor(max_hit / total_rate) / 10) * 10;
            return [false, tmp];
        } else {
            toast("베팅 최대금액은 " + numberWithCommas(max_betting) + " 입니다.");
        }
        return [false, max_betting];
    }
    reward = bet_money * total_rate;
    if (max_hit > 0 && reward > max_hit) {
        toast("적중 최대금액은 " + numberWithCommas(max_hit) + " 입니다.");
        var tmp = Math.floor(Math.floor(max_hit / total_rate) / 10) * 10;
        return [false, tmp];
    }

    return [true, bet_money];
}



function select_reset() {
    closeAlert();
    
    $("#bet_min").data("amount", 0);
    $("#bet_min").text("");
    $("#bet_max").data("amount", 0);
    $("#bet_max").text("");
    $("#dist_max").data("amount", 0);
    $("#dist_max").text("");

    // variable
    select_idx = "";
    select_id = "";
    select_type = "";
    select_rate = 0;

    // board
    $(".btn_select").each(function() {
        $(".btn_select").removeClass("active");
        $(".btn_select").removeClass("on");
        $(".btn_select").removeClass("bet_selected");
    });

    betting_money(0);

    $("#board_game").text("");
    $("#board_rate").text("");

    $("#input_money").val("");
}

function game_reset() {
    select_reset();

}

function showProgress(type) {
    if (type == "board") {
        if (!$(".betting_con").hasClass("wait")) {
            $(".betting_con").addClass("wait");
        }
        if (!$(".all_result_wrap").hasClass("wait")) {
            $(".all_result_wrap").addClass("wait");
        }
    } else if (type == "prev") {
        if (!$("#prev_result").hasClass("wait")) {
            $("#prev_result").addClass("wait");
        }
    } else if (type == "bets") {
        if (!$(".betting_history_wrap").hasClass("wait")) {
            $(".betting_history_wrap").addClass("wait");
        }
    }
}

function hideProgress(type) {
    if (type == "board") {
        if ($(".betting_con").hasClass("wait")) {
            $(".betting_con").removeClass("wait");
        }
        if ($(".all_result_wrap").hasClass("wait")) {
            $(".all_result_wrap").removeClass("wait");
        }
    } else if (type == "prev") {
        if ($("#prev_result").hasClass("wait")) {
            $("#prev_result").removeClass("wait");
        }
    } else if (type == "bets") {
        if ($(".betting_history_wrap").hasClass("wait")) {
            $(".betting_history_wrap").removeClass("wait");
        }
    }
}



function confirm_ok() {

    var game_id = $(".game_list").attr("id");
    var bet_money = $("#bet_money")
        .val()
        .replace(/[^0-9]/g, "");
    if (!$.isNumeric(bet_money)) {
        confirmAlert(langMessage.bet_rule, function() {
            reloadPage();
        });
    }

    if (select_idx == "") {
        confirmAlert(langMessage.bet_enable, function() {
            reloadPage();
        });

        return;
    }
    let target = select_id == 1 ? "P" : "B";
    if (select_idx == 30)
        target = select_id;
    var jsonData = {
        "game": game_id,
        "roundno": mRound.round_no,
        "mode": select_idx,
        "target": target,
        "amount": bet_money
    };

    jsonData = JSON.stringify(jsonData);

    $("#mini_betting").attr("disabled", true);

    $.ajax({
        type: "POST",
        url: FURL + FURL + "/api/betting",
        dataType: "json",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                game_reset();
                reqFollowBet(jResult.data);
                toast(langMessage.bet_complete);
                $(".betting_board_m_close").trigger("click");
                session_check();
                setTimeout(function() {
                    reqBetResult();
                }, 1500);
            } else if (jResult.status == "fail" || jResult.status == "stop") {

                confirmAlert(jResult.msg, function() {
                    //reloadPage();
                });

            } else if (jResult.status == "logout") {
                closeTimer();
                confirmAlert(langMessage.session_expired, function() {
                    reloadPage();
                });
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        },
        complete: function(xhr, status) {
            $("#mini_betting").removeAttr("disabled");
        },
    });
}

function reqFollowBet(betId) {
    var game_id = $(".game_list").attr("id");
    var jsonData = {
        "game": game_id,
        "betId": betId
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        url: FURL + FURL + "/api/bet_follow",
        dataType: "json",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);

        },
    });
}


function reqGameResult() {
    // reqRoundResult();
    setTimeout(function() {
        reqBetResult();
    }, 1000);
}

function reqBetCancel(fid, objBtn) {

    
    basic2Alert(langMessage.bet_cancel_msg, function() {

        var game_id = $(".game_list").attr("id");
        $(objBtn).attr("disabled", true);
        var objData = { "game": game_id, "fid": fid };
        var jsonData = JSON.stringify(objData);
        $.ajax({
            url: FURL + '/api/bet_cancel',
            type: 'post',
            data: { json_: jsonData },
            dataType: "json",
            success: function(jResult) {
                $(objBtn).attr("disabled", false);
                // console.log(jResult);
                if (jResult.status == "success") {
                    reqBetResult();
                    setTimeout(function() {
                        session_check();
                    }, 500);
                    setTimeout(function() {
                        reqFollowCancel(objData);
                    }, 1000);
                } else if (jResult.status == "fail") {
                    if(jResult.msg){
                        confirmAlert(jResult.msg);
                    }
                } else if (jResult.status == "logout") {
                    // closeTimer();
                    // reloadPage();
                }
            },
            error: function(request, status, error) {
                $(objBtn).attr("disabled", false);
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });
    }, null);
}

function reqFollowCancel(objData) {

    var jsonData = JSON.stringify(objData);
    $.ajax({
        url: FURL + '/api/follow_cancel',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function reqCurrentRound() {
    var game_id = $(".game_list").attr("id");

    var objData = { "game": game_id };
    var jsonData = JSON.stringify(objData);
    $.ajax({
        url: FURL + '/api/round_current',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                mConfig = jResult.config;
                showCurrentRound(jResult.data);

            } else if (jResult.status == "fail") {
                mConfig = jResult.config;
            } else if (jResult.status == "logout") {
                closeTimer();
                reloadPage();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function reqRoundResult() {
    var game_id = $(".game_list").attr("id");

    var objData = { "game": game_id, "page": 1, "count": 10 };
    var jsonData = JSON.stringify(objData);
    $.ajax({
        url: FURL + '/api/page_round',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showRoundResult(jResult.data);

            } else if (jResult.status == "logout") {
                closeTimer();
                reloadPage();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function reqFollowInfo() {
    var game_id = $(".game_list").attr("id");

    var objData = { "game": game_id };
    var jsonData = JSON.stringify(objData);
    $.ajax({
        url: FURL + '/api/get_follow',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showFollow(jResult.data);

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                closeTimer();
                reloadPage();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}



function saveFollow() {
    var game_id = $(".game_list").attr("id");
    var follow_id = $("#follow_uid").val();
    var follow_rate = $("#follow_rate").val();

    var objData = { "game": game_id, "uid": follow_id, "rate": follow_rate, "stop": 0 };
    var jsonData = JSON.stringify(objData);

    $.ajax({
        url: FURL + '/api/save_follow',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                reqFollowInfo();
                $('#layer2').hide();
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                closeTimer();
                confirmAlert(langMessage.session_expired, function() {
                    reloadPage();
                });
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function reqBetResult() {
    var game_id = $(".game_list").attr("id");

    var objData = { "game": game_id, "page": 1, "count": 20 };
    var jsonData = JSON.stringify(objData);
    $.ajax({
        url: FURL + '/api/page_bet',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                mCancelEnable = jResult.cancel_enable;
                showBetResult(jResult.game, jResult.data);
            } else if (jResult.status == "logout") {
                closeTimer();
                reloadPage();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function showBetResult(game, list) {
    var html = getBetResultHtml(game, list);
    $(".betting_wrapper").html(html);

}

function showFollow(info) {
    var html = "";
    if (info.follow_id.length > 0) {
        html = info.follow_id + " (" + info.follow_rate + " %)";
    }
    $("#board_follow").data('user', info.follow_id);
    $("#board_follow").data('rate', info.follow_rate);
    $("#board_follow").data('stop', info.follow_stop);

    $("#board_follow").text(html);
}


function showCurrentRound(objRound) {

    if (parseInt(mRound.round_no) != parseInt(objRound.round_no)) {

        mRound.game = objRound.game;
        mRound.round_date = objRound.round_date;
        mRound.round_no = objRound.round_no;
        mRound.round_id = objRound.round_id;
        mRound.round_current = new Date(objRound.round_current).getTime();
        mRound.round_start = new Date(objRound.round_start).getTime();
        mRound.round_end = new Date(objRound.round_end).getTime();
        mRound.round_betend = new Date(objRound.round_bet_end).getTime();

        mRoundError = 0;
        let sDate = mRound.round_date.substr(5, 2) + langMessage.month + mRound.round_date.substr(8, 2) + langMessage.day;
        let sRound = "[" + mRound.round_no + langMessage.round + "]";
        $("#board_date").text(sDate);
        $("#board_round").text(sRound);
        $("#cart_date").text(sDate);
        $("#cart_round").text(sRound);

        setTimeout(function() {
            reqGameResult();
        }, 10000);

    } else {
        mRoundError++;
    }

}

// worker 실행
function startSecWorker() {

    // Worker 지원 유무 확인
    if (!!window.Worker) {

        // 실행하고 있는 워커 있으면 중지시키기
        if (mSecWorker) {
            stopWorker();
        }

        mSecWorker = new Worker('/js/mini/worker_sec.js');
        mSecWorker.postMessage('워커 실행'); // 워커에 메시지를 보낸다.

        // 워커로 부터 메시지를 수신한다.
        mSecWorker.onmessage = function(e) {
            showTime();

        };
    }

}


// worker 중지
function stopWorker() {

    if (mSecWorker) {
        mSecWorker.terminate();
        mSecWorker = null;
    }

}



function showTime() {

    let tmCurrent = new Date();

    if (mClientTime == 0) {
        mClientTime = tmCurrent.getTime();
    }

    let nCurSec = tmCurrent.getSeconds();
    if (nCurSec % 20 == 0) {}

    mRound.round_current = parseInt(mRound.round_current) + tmCurrent.getTime() - mClientTime;
    mClientTime = tmCurrent.getTime();


    var nRemainTm = 0,
        nRemainMin = 0,
        nRemainSec = 0,
        nLastSec = 0;
    if (mRound.round_current < mRound.round_end) {
        nRemainTm = mRound.round_end - mRound.round_current;
        nRemainMin = Math.floor((nRemainTm % (1000 * 60 * 60)) / (1000 * 60));
        nRemainSec = Math.floor((nRemainTm % (1000 * 60)) / 1000);

    }

    var showTime = ("0" + nRemainMin).slice(-2) + ":" + ("0" + nRemainSec).slice(-2);
    $("#games_time").html(showTime);

    if (mRound.round_current < mRound.round_betend) {

        if (mRoundState != 1) {
            mRoundState = 1;

            if ($(".betting_board_inner").hasClass("betting_board_none") == true) {
                $(".betting_board_inner").removeClass("betting_board_none");
            }
        }
        $("#cart_time").html(showTime);
        $("#board_time").html(showTime);


    } else if (mRound.round_current < mRound.round_end) {
        if (mRoundState != 2) {
            mRoundState = 2;

            if(mCancelEnable){
                reqBetResult();
            }
            $("#cart_time").html(langMessage.end);
            $("#board_time").html(langMessage.end);

            if ($(".betting_board_inner").hasClass("betting_board_none") == false) {
                $(".betting_board_inner").addClass("betting_board_none");
            }
            game_reset();

        }
    } else {
        if (mRoundState != 3) {
            mRoundState = 3;
            if ($(".betting_board_inner").hasClass("betting_board_none") == false) {
                $(".betting_board_inner").addClass("betting_board_none");
            }
            // initBet();
        }
    }

    //회차요청
    if (mRoundError < 180 && mRound.round_current >= mRound.round_end &&
        mRound.round_current < mRound.round_end + 600000) {
        reqCurrentRound();
    }
}