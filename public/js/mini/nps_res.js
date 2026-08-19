$(function() {
    $(".btn_select").click(function(e) {
        e.preventDefault();

        if ($(".betting_board_inner").hasClass("betting_board_none") == true) {
            clear();
            return;
        }

        // 기존 선택 제거 및 선택 처리
        $(".btn_select").removeClass("bet_selected");
        $(this).addClass("bet_selected");

        var categoryId = $(this).parent().attr("id");
        var selId = $(this).attr("id");
        var gameTitle = "";
        var gameName = "";
        var selIdx = 0;

        select_rate = gameRate = $(this).find(".game_rate").text();

        if (categoryId == 1) {
            gameName = "";
            selIdx = 1;
            if (selId == 1) {
                gameTitle = langMessage.ball_left;
            } else {
                gameTitle = langMessage.ball_right;
            }
            setLimitAmount(1);
        } else if (categoryId == 2) {
            gameName = "";
            selIdx = 2;
            if (selId == 1) {
                gameTitle = langMessage.ball_three;
            } else {
                gameTitle = langMessage.ball_foure;
            }
            setLimitAmount(1);
        } else if (categoryId == 3) {
            gameName = "";
            selIdx = 3;
            if (selId == 1) {
                gameTitle = langMessage.ball_odd;
            } else {
                gameTitle = langMessage.ball_even;
            }
            setLimitAmount(1);
        } else if (categoryId == 4) {
            gameName = langMessage.combination;
            if (selId == 1) {
                gameTitle = langMessage.ball_left +"/3";
                selIdx = 4;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_right + "/3";
                selIdx = 6;
            } else if (selId == 3) {
                gameTitle = langMessage.ball_left +"/4";
                selIdx = 5;
            } else if (selId == 4) {
                gameTitle = langMessage.ball_right + "/4";
                selIdx = 7;
            }
            setLimitAmount(2);
        } else return;

        if (gameName != "" && gameName.length > 0) {
            $("#board_game").text(gameName + " - " + gameTitle);
        } else {
            $("#board_game").text(gameTitle);
        }
        $("#board_rate").text(select_rate);
        select_idx = selIdx;
        select_id = selId;
        var tmp_price = parseInt($("#bet_money").val().replace(/,/g, ""));
        var ret = money_max_check(tmp_price);
        betting_money(ret[1]);
    });

    $(".btn_hide_video").click(function() {
        changeVideoBtnText();
        $(".game_wrap").toggle();
        $(".betting_area_flex").toggleClass("hide_video");
        $(".game_pip").toggle();
        setIFrameScale();
    });

});

function clear() {
    $(".btn_select").each(function() {
        $(this).removeClass("active");
    });
}

function changeVideoBtnText() {
    if ($(".betting_area_flex").hasClass("hide_video") === true) {
        $(".btn_hide_video").html(langMessage.hide_screen);
    } else {
        $(".btn_hide_video").html(langMessage.show_screen);
    }
}


function showRoundResult(arrRound) {
    var game_id = $(".game_list").attr("id");
    var tHtml = "";
    if (arrRound && Array.isArray(arrRound) && arrRound.length > 0) {
        arrRound.forEach(function(round) {

            tHtml += '<div class="sub_game_item">';
            tHtml += '<div class="game_title">' + round.round_num + '</div>';

            tHtml += '<div class="game_content">';
            if (round.round_state == 1) {
                if (round.round_result_1) {
                    if (round.round_result_1 == "P") {
                        tHtml += '<span class="rst_ico blue">'+langMessage.ball_left+'</span>&nbsp;';
                    } else {
                        tHtml += '<span class="rst_ico red">'+langMessage.ball_right+'</span>&nbsp;';
                    }
                }
                if (round.round_result_2) {
                    if (round.round_result_2 == "P") {
                        tHtml += '<span class="rst_ico blue">3</span>&nbsp;';
                    } else {
                        tHtml += '<span class="rst_ico red">4</span>&nbsp;';
                    }
                }
                if (round.round_result_3) {
                    if (round.round_result_3 == "P") {
                        tHtml += '<span class="rst_ico blue">'+langMessage.ball_odd+'</span>&nbsp;';
                    } else {
                        tHtml += '<span class="rst_ico red">'+langMessage.ball_even+'</span>&nbsp;';
                    }
                }

            } else {
                tHtml += '<p> - - - - - - </p>';
            }
            tHtml += '</div></div>';
        });
    }
    $("#prev_result").html(tHtml);
}