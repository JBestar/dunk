function clear() {
    $(".btn_select").each(function() {
        $(this).removeClass("bet_selected");
    });
}

function changeVideoBtnText() {
    if ($(".betting_area_flex").hasClass("hide_video") === true) {
        $(".btn_hide_video").html(langMessage.hide_screen);
    } else {
        $(".btn_hide_video").html(langMessage.show_screen);
    }
    scrollBettingBoard();
}

$(function() {
    $(".btn_hide_video").click(function() {
        changeVideoBtnText();
        $(".game_wrap").toggle();
        $(".betting_area_flex").toggleClass("hide_video");
        $(".game_pip").toggle();
        setIFrameScale();
    });

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

        if (categoryId == 1) {
            gameName = langMessage.powerball;
            selIdx = 1;
            if (selId == 1) {
                gameTitle = langMessage.ball_odd;
            } else {
                gameTitle = langMessage.ball_even;
            }
            setLimitAmount(1);
        } else if (categoryId == 2) {
            gameName = langMessage.powerball;
            selIdx = 2;
            if (selId == 1) {
                gameTitle = langMessage.ball_under;
            } else {
                gameTitle = langMessage.ball_over;
            }
            setLimitAmount(1);
        } else if (categoryId == 3) {
            gameName = langMessage.normalball;
            selIdx = 3;
            if (selId == 1) {
                gameTitle = langMessage.ball_odd;
            } else {
                gameTitle = langMessage.ball_even;
            }
            setLimitAmount(1);
        } else if (categoryId == 4) {
            gameName = langMessage.normalball;
            selIdx = 4;
            if (selId == 1) {
                gameTitle = langMessage.ball_under;
            } else {
                gameTitle = langMessage.ball_over;
            }
            setLimitAmount(1);
        } else if (categoryId == 5) {
            gameName = langMessage.powerball + " " + langMessage.combination; //"파워볼 조합"
            if (selId == 1) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_under; //"홀/언더"
                selIdx = 5;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_over; //"홀/오버"
                selIdx = 6;
            } else if (selId == 3) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_under; //"짝/언더"
                selIdx = 7;
            } else if (selId == 4) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_over; //"짝/오버"
                selIdx = 8;
            }
            setLimitAmount(2);
        } else if (categoryId == 6) {
            gameName = langMessage.normalball + " " + langMessage.combination; //"일반볼 조합"
            if (selId == 1) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_under; //"홀/언더"
                selIdx = 9;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_over; //"홀/오버"
                selIdx = 10;
            } else if (selId == 3) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_under; //"짝/언더"
                selIdx = 11;
            } else if (selId == 4) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_over; //"짝/오버"
                selIdx = 12;
            }
            setLimitAmount(3);
        } else if (categoryId == 7) {
            gameName = langMessage.normalball + " " + langMessage.ball_lms; //"일반볼 대중소";
            if (selId == 1) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_large; //"홀/대";
                selIdx = 21;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_medium; //"홀/중";
                selIdx = 22;
            } else if (selId == 3) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_small; //"홀/소";
                selIdx = 23;
            } else if (selId == 4) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_large; //"짝/대";
                selIdx = 24;
            } else if (selId == 5) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_medium; //"짝/중";
                selIdx = 25;
            } else if (selId == 6) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_small; //"짝/소";
                selIdx = 26;
            }
            setLimitAmount(5);
        } else if (categoryId == 8) {
            gameName = langMessage.normalball + " " + langMessage.ball_lms; //"일반볼 대중소";
            if (selId == 1) {
                gameTitle = langMessage.ball_large; //"대";
                selIdx = 27;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_medium; //"중";
                selIdx = 28;
            } else {
                gameTitle = langMessage.ball_small; //"소";
                selIdx = 29;
            }
            setLimitAmount(5);
        } else if (categoryId == 9) {
            gameName = langMessage.normalball + " + " + langMessage.powerball + " " + langMessage.combination; //"일반볼+파워볼 조합";
            if (selId == 1) {
                gameTitle = langMessage.normalball_ + " " + langMessage.ball_odd + "/" + langMessage.powerball_ + " " + langMessage.ball_odd; //"일반홀/파워홀"
                selIdx = 13;
            } else if (selId == 2) {
                gameTitle = langMessage.normalball_ + " " + langMessage.ball_odd + "/" + langMessage.powerball_ + " " + langMessage.ball_even; //"일반홀/파워짝"
                selIdx = 14;
            } else if (selId == 3) {
                gameTitle = langMessage.normalball_ + " " + langMessage.ball_even + "/" + langMessage.powerball_ + " " + langMessage.ball_odd; //"일반짝/파워홀"
                selIdx = 15;
            } else if (selId == 4) {
                gameTitle = langMessage.normalball_ + " " + langMessage.ball_even + "/" + langMessage.powerball_ + " " + langMessage.ball_even; //"일반짝/파워짝"
                selIdx = 16;
            } else if (selId == 5) {
                gameTitle = langMessage.normalball_  + " " + langMessage.ball_under + "/" + langMessage.powerball_  + " " + langMessage.ball_under; //"일언더/파언더"
                selIdx = 17;
            } else if (selId == 6) {
                gameTitle = langMessage.normalball_  + " " + langMessage.ball_under + "/" + langMessage.powerball_  + " " + langMessage.ball_over; //"일언더/파오버"
                selIdx = 18;
            } else if (selId == 7) {
                gameTitle = langMessage.normalball_  + " " + langMessage.ball_over + "/" + langMessage.powerball_  + " " + langMessage.ball_under; //"일오버/파언더"
                selIdx = 19;
            } else if (selId == 8) {
                gameTitle = langMessage.normalball_  + " " + langMessage.ball_over + "/" + langMessage.powerball_  + " " + langMessage.ball_over; //"일오버/파오버"
                selIdx = 20;
            }
            setLimitAmount(4);
        } else if (categoryId == 10) {
            gameName = langMessage.normal + langMessage.combination + "+" + langMessage.powerball_+" "+langMessage.ball_odd+langMessage.ball_even; //"일반조합+파 홀짝";
            if (selId == 1) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_under + "/" + langMessage.powerball_ +langMessage.ball_odd; //"홀/언더/파 홀"
                selIdx = 31;
            } else if (selId == 2) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_under + "/" + langMessage.powerball_ +langMessage.ball_even; //"홀/언더/파 짝"
                selIdx = 32;
            } else if (selId == 3) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_over + "/" + langMessage.powerball_ +langMessage.ball_odd; //"홀/오버/파 홀"
                selIdx = 33;
            } else if (selId == 4) {
                gameTitle = langMessage.ball_odd + "/" + langMessage.ball_over + "/" + langMessage.powerball_ +langMessage.ball_even; //"홀/오버/파 짝"
                selIdx = 34;
            } else if (selId == 5) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_under + "/" +langMessage.powerball_ + langMessage.ball_odd; //"짝/언더/파 홀"
                selIdx = 35;
            } else if (selId == 6) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_under + "/" +langMessage.powerball_ + langMessage.ball_even; //"짝/언더/파 짝"
                selIdx = 36;
            } else if (selId == 7) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_over + "/" +langMessage.powerball_ + langMessage.ball_odd; //"짝/오버/파 홀"
                selIdx = 37;
            } else if (selId == 8) {
                gameTitle = langMessage.ball_even + "/" + langMessage.ball_over + "/" +langMessage.powerball_ + langMessage.ball_even; //"짝/오버/파 짝"
                selIdx = 38;
            }
            setLimitAmount(6);
        } else if (categoryId == 11) {
            gameName =  langMessage.powerball + " " + langMessage.number_//"파워볼 숫자";
            gameTitle = selId;
            selIdx = 30;
            setLimitAmount(7);
        } else return;

        select_rate = $(this).find(".game_rate").text();

        $("#board_game").text(gameName + " - " + gameTitle);
        $("#board_rate").text(select_rate);

        select_idx = selIdx;
        select_id = selId;
        var tmp_price = parseInt($("#bet_money").val().replace(/,/g, ""));
        var ret = money_max_check(tmp_price);
        betting_money(ret[1]);
    });
});




function showRoundResult(arrRound) {
    var game_id = $(".game_list").attr("id");
    var tHtml = "";
    if (arrRound && Array.isArray(arrRound) && arrRound.length > 0) {
        arrRound.forEach(function(round) {

            tHtml += '<div class="sub_game_item">';
            if (game_id == 1) {
                tHtml += '<div class="game_title">' + round.round_fid.substr(0, 4);
                tHtml += '<span class="color_special">' + round.round_fid.substr(4) + '</span></div>';
            } else tHtml += '<div class="game_title">' + round.round_num + '</div>';

            tHtml += '<div class="game_content">';
            if (round.round_state == 1) {
                ret_arr = round.round_normal.split(",");
                if (ret_arr[0]) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_' + parseInt(ret_arr[0]) + '.png"/>';
                }
                if (ret_arr[1]) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_' + parseInt(ret_arr[1]) + '.png"/>';
                }
                if (ret_arr[2]) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_' + parseInt(ret_arr[2]) + '.png"/>';
                }
                if (ret_arr[3]) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_' + parseInt(ret_arr[3]) + '.png"/>';
                }
                if (ret_arr[4]) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_' + parseInt(ret_arr[4]) + '.png"/>';
                }
                if (round.round_power) {
                    tHtml += '<img class="result_num" src="/assets/img/content/powerball_final_' + parseInt(round.round_power) + '.png"/>';
                }
            } else {
                tHtml += '<p> - - - - - - </p>';
            }
            tHtml += '</div></div>';
        });
    }
    $("#prev_result").html(tHtml);
}