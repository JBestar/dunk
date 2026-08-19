
function getGameName(game) {
    var sGame = "";
    switch (game) {
        case 1:
            sGame = langMessage.powerball_pbg;
            break;
        case 2:
            sGame = langMessage.powerball_evol;
            break;
        case 5:
            sGame = langMessage.powerball_boggle;
            break;
        case 6:
            sGame = langMessage.powerladder_boggle;
            break;
        case 9:
            sGame = langMessage.powerball_eos5;
            break; 
        case 10:
            sGame = langMessage.powerball_eos3;
            break;
        case 11:
            sGame = langMessage.powerball_coin5;
            break; 
        case 12:
            sGame = langMessage.powerball_coin3;
            break;
        case 14:
            sGame = langMessage.powerball_spkn;
            break;
        default:
            break;
    }
    return sGame;
}

function getModeName(game, mode) {
    var sMode = "";
    if (game == 1 || game == 5 || (game >= 9 && game <= 12) || game == 2 ) {
        if (mode == 1) {
            sMode = langMessage.powerball + " " + langMessage.ball_odd + langMessage.ball_even; //"파워볼 홀짝"
        } else if (mode == 2) {
            sMode = langMessage.powerball + " " + langMessage.ball_under_ + langMessage.ball_over; //"파워볼 언오버"
        } else if (mode == 3) {
            sMode = langMessage.normalball + " " + langMessage.ball_odd + langMessage.ball_even; //"일반볼 홀짝"
        } else if (mode == 4) {
            sMode = langMessage.normalball + " " + langMessage.ball_under_ + langMessage.ball_over;// "일반볼 언오버";
        } else if (mode >= 5 && mode <= 8) {
            sMode = langMessage.powerball + " " + langMessage.combination;// "파워볼 조합";
        } else if (mode >= 9 && mode <= 12) {
            sMode = langMessage.normalball + " " + langMessage.combination;// "일반볼 조합";
        } else if (mode >= 13 && mode <= 20) {
            sMode = langMessage.normalball + " + " + langMessage.powerball + " " + langMessage.combination;// "일반볼 + 파워볼 조합";
        } else if (mode >= 21 && mode <= 29) {
            sMode = langMessage.normalball + " " + langMessage.ball_lms;//"일반볼 대중소";
        } else if (mode == 30) {
            sMode = langMessage.powerball + " " + langMessage.number_;// "파워볼 숫자";
        } else if (mode >= 31 && mode <= 38) {
            sMode = langMessage.normalball_mix + " " + langMessage.powerball + " " + langMessage.ball_odd + langMessage.ball_even;// "일반볼조합 + 파워볼 홀짝";
        }
    } else if (game == 6) {
        if (mode >= 1 && mode <= 3) {
            sMode = langMessage.normal; // "일반";
        } else if (mode >= 4 && mode <= 7) {
            sMode = langMessage.combination; // "조합";
        }
    } else if (game == 14 ) {
        if (mode == 1) {
            sMode = langMessage.powerball + " " + langMessage.ball_odd + langMessage.ball_even; //"파워볼 홀짝"
        } else if (mode == 2) {
            sMode = langMessage.powerball + " " + langMessage.ball_under_ + langMessage.ball_over; //"파워볼 언오버"
        } else if (mode == 3) {
            sMode = langMessage.superball + " " + langMessage.ball_odd + langMessage.ball_even; //"슈퍼볼 홀짝"
        } else if (mode == 4) {
            sMode = langMessage.superball + " " + langMessage.ball_under_ + langMessage.ball_over;// "슈퍼볼 언오버";
        } else if (mode >= 5 && mode <= 8) {
            sMode = langMessage.powerball + " " + langMessage.combination;// "파워볼 조합";
        } else if (mode >= 9 && mode <= 12) {
            sMode = langMessage.superball + " " + langMessage.combination;// "슈퍼볼 조합";
        } else if (mode >= 13 && mode <= 20) {
            sMode = langMessage.superball + " + " + langMessage.powerball + " " + langMessage.combination;// "일반볼 + 파워볼 조합";
        } else if (mode == 21) {
            sMode = langMessage.hupball + " " + langMessage.ball_odd + langMessage.ball_even; //"숫자합 홀짝"
        } else if (mode == 22) {
            sMode = langMessage.hupball + " " + langMessage.ball_under_ + langMessage.ball_over; //"숫자합 언오버"
        } else if (mode >= 23 && mode <= 26) {
            sMode = langMessage.hupball + " " + langMessage.combination;// "숫자합 조합";
        } else if (mode == 30) {
            sMode = langMessage.powerball + " " + langMessage.number_;// "파워볼 숫자";
        } else if (mode >= 31 && mode <= 38) {
            sMode = langMessage.superball_mix + " " + langMessage.powerball + " " + langMessage.ball_odd + langMessage.ball_even;// "일반볼조합 + 파워볼 홀짝";
        }
    }
    return sMode;
}

function getTargetHtml(side, name) {
    html = "";
    switch (side) {
        case 1:
            html = "<span class='txt_blue'>" + name + "</span>";
            break;
        case 2:
            html = "<span class='txt_red'>" + name + "</span>";
            break;
        case 3:
            html = "<span class='txt_green'>" + name + "</span>";
            break;
        case 4:
            html = "<span class='txt_gray'>" + name + "</span>";
            break;
    }
    return html;
}

function getResultName(game, mode, result) {
    var sResult = "";
    let normalball_ = langMessage.normalball_;
    if(game == 14)
        normalball_ = langMessage.superball_;

    if (game == 1 || game == 2 || game == 5 || (game >= 9 && game <= 12) || game == 14 ) {
        switch (parseInt(mode)) {
            case 1:
            case 3:
                sResult = result == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                break;
            case 2:
            case 4:
                sResult = result == "P" ? getTargetHtml(1, langMessage.ball_under) : getTargetHtml(2, langMessage.ball_over);
                break;
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:
                if (result == "PP")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under);
                else if (result == "PB")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over);
                else if (result == "BP")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under);
                else if (result == "BB")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over);
                break;
            case 13:
            case 14:
            case 15:
            case 16:


                if (result == "PP")
                    sResult = getTargetHtml(1, normalball_ + " " + langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "PB")
                    sResult = getTargetHtml(1, normalball_ + " " + langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                else if (result == "BP")
                    sResult = getTargetHtml(2, normalball_ + " " + langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "BB")
                    sResult = getTargetHtml(2, normalball_ + " " + langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 17:
            case 18:
            case 19:
            case 20:
                if (result == "PP")
                    sResult = getTargetHtml(1, normalball_ + " " + langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_under);
                else if (result == "PB")
                    sResult = getTargetHtml(1, normalball_ + " " + langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_over);
                else if (result == "BP")
                    sResult = getTargetHtml(2, normalball_ + " " + langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_under);
                else if (result == "BB")
                    sResult = getTargetHtml(2, normalball_ + " " + langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_over);
                break;
            case 21:
            case 22:
            case 23:
            case 24:
            case 25:
            case 26:
                if(game == 14){
                    if(mode == 21)
                        sResult = result == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                    else if(mode == 22)
                        sResult = result == "P" ? getTargetHtml(1, langMessage.ball_under) : getTargetHtml(2, langMessage.ball_over);
                    else {
                        if (result == "PP")
                            sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under);
                        else if (result == "PB")
                            sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over);
                        else if (result == "BP")
                            sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under);
                        else if (result == "BB")
                            sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over);
                    }
                } else {
                    if (result == "PL")
                        sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_large);
                    else if (result == "PM")
                        sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_medium);
                    else if (result == "PS")
                        sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_small);
                    else if (result == "BL")
                        sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_large);
                    else if (result == "BM")
                        sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_medium);
                    else if (result == "BS")
                        sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_small);
                }
                break;
            case 27:
            case 28:
            case 29:
                if (result == "L")
                    sResult = getTargetHtml(3, langMessage.ball_large);
                else if (result == "M")
                    sResult = getTargetHtml(3, langMessage.ball_medium);
                else if (result == "S")
                    sResult = getTargetHtml(3, langMessage.ball_small);
                break;
            case 30:
                sResult = getTargetHtml(4, result);
                break;
            case 31:
            case 32:
            case 33:
            case 34:
            case 35:
            case 36:
            case 37:
            case 38:
                if (result == "PPP")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "PPB")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                else if (result == "PBP")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "PBB")
                    sResult = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                else if (result == "BPP")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "BPB")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                else if (result == "BBP")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                else if (result == "BBB")
                    sResult = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            default:
                break;
        }
    } else if (game == 6) {
        switch (parseInt(mode)) {
            case 1:
                sResult = result == "P" ? getTargetHtml(1, langMessage.ball_left) : getTargetHtml(2, langMessage.ball_right);
                break;
            case 2:
                sResult = result == "P" ? getTargetHtml(1, "3") : getTargetHtml(2, "4");
                break;
            case 3:
                sResult = result == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                break;
            case 4:
            case 5:
            case 6:
            case 7:
                if (result == "PP")
                    sResult = getTargetHtml(1, langMessage.ball_left) + " / " + getTargetHtml(1, "3");
                else if (result == "PB")
                    sResult = getTargetHtml(1, langMessage.ball_left) + " / " + getTargetHtml(2, "4");
                else if (result == "BP")
                    sResult = getTargetHtml(2, langMessage.ball_right) + " / " + getTargetHtml(1, "3");
                else if (result == "BB")
                    sResult = getTargetHtml(2, langMessage.ball_right) + " / " + getTargetHtml(2, "4");
                break;
            default:
                break;
        }
    }
    return sResult;
}

function getTargetName(game, mode, target) {
    let normalball_ = langMessage.normalball_;
    if(game == 14)
        normalball_ = langMessage.superball_;
    var sTarget = "";
    if (game == 1 || game == 5 || (game >= 9 && game <= 12) || game == 2 || game == 14) {
        switch (parseInt(mode)) {
            case 1:
            case 3:
                sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                break;
            case 2:
            case 4:
                sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_under) : getTargetHtml(2, langMessage.ball_over);
                break;
            case 5:
            case 9:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under);
                break;
            case 6:
            case 10:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over);;
                break;
            case 7:
            case 11:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under);
                break;
            case 8:
            case 12:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over);
                break;
            case 13:
                sTarget = getTargetHtml(1, normalball_ + " " + langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 14:
                sTarget = getTargetHtml(1, normalball_ + " " + langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 15:
                sTarget = getTargetHtml(2, normalball_ + " " + langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 16:
                sTarget = getTargetHtml(2, normalball_ + " " + langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 17:
                sTarget = getTargetHtml(1, normalball_ + " " + langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_under);
                break;
            case 18:
                sTarget = getTargetHtml(1, normalball_ + " " + langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_over);
                break;
            case 19:
                sTarget = getTargetHtml(2, normalball_ + " " + langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_under);
                break;
            case 20:
                sTarget = getTargetHtml(2, normalball_ + " " + langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_over);
                break;
            case 21:
                if(game == 14){
                    sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                } else sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_large);
                break;
            case 22:
                if(game == 14){
                    sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_under) : getTargetHtml(2, langMessage.ball_over);
                } else sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_medium);
                break;
            case 23:
                if(game == 14){
                    sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under);
                } else sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(3, langMessage.ball_small);
                break;
            case 24:
                if(game == 14){
                    sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over);
                } else sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_large);
                break;
            case 25:
                if(game == 14){
                    sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under);
                } else sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_medium);
                break;
            case 26:
                if(game == 14){
                    sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over);
                } else 
                    sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(3, langMessage.ball_small);
                break;
            case 27:
                sTarget = getTargetHtml(3, langMessage.ball_large);
                break;
            case 28:
                sTarget = getTargetHtml(3, langMessage.ball_medium);
                break;
            case 29:
                sTarget = getTargetHtml(3, langMessage.ball_small);
                break;
            case 30:
                sTarget = getTargetHtml(4, target);
                break;
            case 31:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 32:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 33:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 34:
                sTarget = getTargetHtml(1, langMessage.ball_odd) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 35:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 36:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(1, langMessage.ball_under) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;
            case 37:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(1, langMessage.powerball_ + " " + langMessage.ball_odd);
                break;
            case 38:
                sTarget = getTargetHtml(2, langMessage.ball_even) + " / " + getTargetHtml(2, langMessage.ball_over) + " / " + getTargetHtml(2, langMessage.powerball_ + " " + langMessage.ball_even);
                break;

            default:
                break;
        }
    } else if (game == 6) {
        switch (parseInt(mode)) {
            case 1:
                sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_left) : getTargetHtml(2, langMessage.ball_right);
                break;
            case 2:
                sTarget = target == "P" ? getTargetHtml(1, "3") : getTargetHtml(2, "4");
                break;
            case 3:
                sTarget = target == "P" ? getTargetHtml(1, langMessage.ball_odd) : getTargetHtml(2, langMessage.ball_even);
                break;
            case 4:
                sTarget = getTargetHtml(1, langMessage.ball_left) + " / " + getTargetHtml(1, "3");
                break;
            case 5:
                sTarget = getTargetHtml(1, langMessage.ball_left) + " / " + getTargetHtml(2, "4");
                break;
            case 6:
                sTarget = getTargetHtml(2, langMessage.ball_right) + " / " + getTargetHtml(1, "3");
                break;
            case 7:
                sTarget = getTargetHtml(2, langMessage.ball_right) + " / " + getTargetHtml(2, "4");
                break;
        }

    }
    return sTarget;
}

function getBetResultHtml(gameId, list) {
    var html = "";
    if (list) {
        html += '<div class="betting_history">';
        html += '<div class="inner">';
        html += '<div class="betting_history_wrap">';
        html += '<div class="betting_history_tit">';

        html += '<p class="round">'+langMessage.game_round+'</p>'; //회차
        html += '<p class="time">'+langMessage.bet_time+'</p>';     //베팅시간
        html += '<div class="con">';
        html += '<p class="game">'+langMessage.game_type+'</p>';     //게임분류
        html += '<p class="bet">'+langMessage.bet_history+'</p>';      //베팅내역
        html += '<p class="bet">'+langMessage.game_result_+'</p>';      //경기결과
        html += '<p class="rate">'+langMessage.game_rate+'</p>';       //배당율
        html += "</div>";
        html += '<p class="money">'+langMessage.bet_amount+'</p>';    //베팅금액
        html += '<p class="money">'+langMessage.win+'/'+langMessage.loss+'</p>';   //적중/손실
        html += '<p class="rst">'+langMessage.win_status+'</p>';      //적중여부
        if(typeof mCancelEnable !== 'undefined' && mCancelEnable)
            html += '<p class="money">'+langMessage.bet_cancel+'</p>';    //베팅취소

        html += "</div>";

        html += '<div class="betting_history_con">';
        html += "<ul>";
        if (list.length == 0) {
            html += '<li class="none"><p>'+langMessage.bet_none+'</p></li>'; //베팅 내역이 없습니다.
        } else {
            list.forEach((element) => {

                html += '<li class="checked_li" id="' + element.bet_fid + '">';
                html += '<input type="hidden" name="cbox' + element.bet_fid + '" value="' + element.bet_fid + '">';
                html += '<p class="round">' + element.bet_round_no + "</p>";
                html += '<p class="time">' + element.bet_time.slice(5) + "</p>";
                html += '<div class="con">';

                html += '<p class="game">' + getModeName(gameId, element.bet_mode) + "</p>";

                html += '<p class="bet">' + getTargetName(gameId, element.bet_mode, element.bet_target) + "</p>";
                if (element.bet_state > 1) {
                    html += '<p class="bet">' + getResultName(gameId, element.bet_mode, element.bet_result) + "</p>";
                } else {
                    html += '<p class="bet"></p>';
                }

                html += '<p class="rate">' + element.bet_ratio + "</p>";
                html += "</div>";

                html += '<p class="money">' + numberWithCommas(element.bet_money) + "</p>";
                if (element.bet_state == 3) {
                    html += '<p class="money hit">+' + element.bet_win_money + "</p>";
                    html += '<p class="rst hit">'+langMessage.win+'</p>';
                } else if (element.bet_state == 2) {
                    html += '<p class="money">-' + element.bet_money + "</p>";
                    html += '<p class="rst">'+langMessage.win_no+'</p>';
                } else {
                    html += '<p class="money"></p>';
                    html += '<p class="rst">'+langMessage.bet+'</p>';
                }
                if(typeof mCancelEnable !== 'undefined' && mCancelEnable){
                    if(element.bet_round_no == mRound.round_no && mRoundState == 1)
                        html += "<button class='btn' onclick='reqBetCancel(" + element.bet_fid + ", this);'>"+langMessage.bet_cancel+"</button>";
                    else html += '<p class="money"></p>';
                }
                html += "</li>";
            });
        }
        html += "</ul>";
        html += "</div>";

        html += "</div>";
        html += "</div>";
        html += "</div>";

        html += '<div class="betting_history_m">';
        html += "<ul>";
        if (list.length == 0) {
            html += '<li class="none"><p>'+langMessage.bet_none+'</p></li>';
        } else {
            list.forEach((element) => {
                html += '<li class="checked_li_m" id="' + element.bet_fid + '">';
                html += '<input type="hidden" name="cbox' + element.bet_fid + '" id="box_' + element.bet_fid + '" value="' + element.bet_fid + '"/>';

                html += '<div class="betting_history_m_top">';
                html += '<p class="game">' + getGameName(gameId) + "</p>";

                if (element.bet_state == 3) {
                    html += '<p class="rst_txt hit">' + langMessage.win + "</p>";
                } else if (element.bet_state == 2) {
                    html += '<p class="rst_txt">' + langMessage.loss + "</p>";
                } else {
                    html += '<p class="rst_txt">' + langMessage.bet + "</p>";
                }
                html += "</div>";

                html += '<div class="betting_history_m_con">';

                html += "<div><dl><dt>회차</dt>";
                html += '<dd>' + element.bet_round_no + "</dd>";
                html += "</dl></div>";

                html += "<div><dl><dt>"+langMessage.bet_time+"</dt>";
                html += "<dd>" + element.bet_time.slice(5) + "</dd>";
                html += "</dl></div>";

                html += "<div><dl><dt>"+langMessage.game_type+"</dt>";
                html += '<dd class="game">' + getModeName(gameId, element.bet_mode) + "</dd></dl>";

                html += "<dl><dt>"+langMessage.bet+"</dt>";
                html += "<dd>" + getTargetName(gameId, element.bet_mode, element.bet_target) + "</dd></dl>";
                if (element.bet_state > 1) {
                    html += "<dl><dt>"+langMessage.game_result_+"</dt>";
                    html += "<dd>" + getResultName(gameId, element.bet_mode, element.bet_result) + "</dd></dl>";
                }
                html += "</div>";

                html += "<div><dl><dt>"+langMessage.game_rate+"</dt>";
                html += "<dd>" + element.bet_ratio + "</dd>";
                html += "</dl><dl><dt>"+langMessage.bet_amount+"</dt>";
                html += "<dd>" + numberWithCommas(element.bet_money) + "</dd>";
                html += "</dl></div>";

                html += "</div>";

                html += '<div class="betting_history_m_bot">';
                html += "<dl>";
                if(typeof mCancelEnable !== 'undefined' && mCancelEnable && mRoundState == 1 && element.bet_round_no == mRound.round_no)
                    html += "<button class='btn' onclick='reqBetCancel(" + element.bet_fid + ", this);'>"+langMessage.bet_cancel+"</button>";
                else {
                    html += "<dt>"+langMessage.win+"/"+langMessage.loss+"</dt>";
                    if (element.bet_state == 3) {
                        html += '<dd class="hit">+' + numberWithCommas(element.bet_win_money) + "</dd>";
                    } else if (element.bet_state == 2) {
                        html += "<dd>-" + numberWithCommas(element.bet_money) + "</dd>";
                    } else {
                        html += "<dd>0</dd>";
                    }
                }

                html += "</dl>";
                html += "</div>";
                html += "</li>";
            });
        }
        html += "</ul>";
        html += "</div>";
    }

    return html;
}

function setLimitAmount($iType){
    if(mConfig == null)
        return;
    let minBet = 0;
    let maxBet = 0;
    let maxWin = 0;

    switch($iType){
        case 1: 
            minBet = parseInt(mConfig.game_min_bet_money);
            maxBet = parseInt(mConfig.game_max_bet_money);
            maxWin = parseInt(mConfig.game_max_win_money);
            break;
        case 2: 
            minBet = parseInt(mConfig.game_min2_bet_money);
            maxBet = parseInt(mConfig.game_max2_bet_money);
            maxWin = parseInt(mConfig.game_max2_win_money);
            break;
        case 3: 
            minBet = parseInt(mConfig.game_min3_bet_money);
            maxBet = parseInt(mConfig.game_max3_bet_money);
            maxWin = parseInt(mConfig.game_max3_win_money);
            break;
        case 4: 
            minBet = parseInt(mConfig.game_min4_bet_money);
            maxBet = parseInt(mConfig.game_max4_bet_money);
            maxWin = parseInt(mConfig.game_max4_win_money);
            break;
        case 5: 
            minBet = parseInt(mConfig.game_min5_bet_money);
            maxBet = parseInt(mConfig.game_max5_bet_money);
            maxWin = parseInt(mConfig.game_max5_win_money);
            break;
        case 6: 
            minBet = parseInt(mConfig.game_min6_bet_money);
            maxBet = parseInt(mConfig.game_max6_bet_money);
            maxWin = parseInt(mConfig.game_max6_win_money);
            break;
        case 7: 
            minBet = parseInt(mConfig.game_min7_bet_money);
            maxBet = parseInt(mConfig.game_max7_bet_money);
            maxWin = parseInt(mConfig.game_max7_win_money);
            break;
        default : 
            minBet = parseInt(mConfig.game_min_bet_money);
            maxBet = parseInt(mConfig.game_max_bet_money);
            maxWin = parseInt(mConfig.game_max_win_money);
            break;
    }

    $("#bet_min").data("amount", minBet);
    if(minBet <= 0 ){
        $("#bet_min").text(langMessage.infinite);
    }
    else $("#bet_min").text(minBet.toLocaleString() + " "+langMessage.won);
    $("#bet_max").data("amount", maxBet);
    if(maxBet <= 0 ){
        $("#bet_max").text(langMessage.infinite);
    }
    else $("#bet_max").text(maxBet.toLocaleString() + " "+langMessage.won);
    
    $("#dist_max").data("amount", maxWin);
    if(maxWin <= 0 ){
        $("#dist_max").text(langMessage.infinite);
    }
    else $("#dist_max").text(maxWin.toLocaleString() + " "+langMessage.won);

}