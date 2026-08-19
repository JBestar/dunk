$(document).ready(function() {
    setNavBarElement();
    requestBetRealtime();
    pbrealtimeLoop();
});


//Function to Show Betting History
function ShowBetRealtime(jRealBet) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    strBuf = "<tr><th>번호</th> <th>경기시간</th><th>회차</th><th>배팅</th><th>배당율</th><th>금액</th><th>밸런스금액</th></tr>"
    elemBetDataTb.innerHTML = strBuf;

    var iTemp = 0;

    var i = 0;
    for (i = 0; i < 33; i++) {
        strBuf += "<tr><td>";

        if (i % 2 == 0 || i > 7) {
            if (i < 8)
                strBuf += (i / 2 + 1);
            else strBuf += (i - 3);
            strBuf += "</td><td>";
            strBuf += jRealBet.betend;
            strBuf += "</td><td>";
            strBuf += jRealBet.roundid;
            strBuf += "회차 ";
            switch (i) {
                case 0:
                    strBuf += "파워볼: 홀짝배팅";
                    break;
                case 2:
                    strBuf += "파워볼: 언오버배팅";
                    break;
                case 4:
                    strBuf += "일반볼: 홀짝배팅";
                    break;
                case 6:
                    strBuf += "일반볼: 언오버배팅";
                    break;
                case 8:
                    strBuf += "파워볼조합: 홀언더배팅";
                    break;
                case 9:
                    strBuf += "파워볼조합: 홀오버배팅";
                    break;
                case 10:
                    strBuf += "파워볼조합: 짝언더배팅";
                    break;
                case 11:
                    strBuf += "파워볼조합: 짝오버배팅";
                    break;
                case 12:
                    strBuf += "일반볼조합: 홀언더배팅";
                    break;
                case 13:
                    strBuf += "일반볼조합: 홀오버배팅";
                    break;
                case 14:
                    strBuf += "일반볼조합: 짝언더배팅";
                    break;
                case 15:
                    strBuf += "일반볼조합: 짝오버배팅";
                    break;
                case 16:
                    strBuf += "일반+파워조합: 일반홀 파워홀배팅";
                    break;
                case 17:
                    strBuf += "일반+파워조합: 일반홀 파워짝배팅";
                    break;
                case 18:
                    strBuf += "일반+파워조합: 일반짝 파워홀배팅";
                    break;
                case 19:
                    strBuf += "일반+파워조합: 일반짝 파워짝배팅";
                    break;
                case 20:
                    strBuf += "일반+파워조합: 일반언더 파워언더배팅";
                    break;
                case 21:
                    strBuf += "일반+파워조합: 일반언더 파워오버배팅";
                    break;
                case 22:
                    strBuf += "일반+파워조합: 일반오버 파워언더배팅";
                    break;
                case 23:
                    strBuf += "일반+파워조합: 일반오버 파워오버배팅";
                    break;
                case 24:
                    strBuf += "일반볼대중소: 홀대배팅";
                    break;
                case 25:
                    strBuf += "일반볼대중소: 홀중배팅";
                    break;
                case 26:
                    strBuf += "일반볼대중소: 홀소배팅";
                    break;
                case 27:
                    strBuf += "일반볼대중소: 짝대배팅";
                    break;
                case 28:
                    strBuf += "일반볼대중소: 짝중배팅";
                    break;
                case 29:
                    strBuf += "일반볼대중소: 짝소배팅";
                    break;
                case 30:
                    strBuf += "일반볼대중소: 대배팅";
                    break;
                case 31:
                    strBuf += "일반볼대중소: 중배팅";
                    break;
                case 32:
                    strBuf += "일반볼대중소: 소배팅";
                    break;
                default:
                    break;
            }
        } else {
            strBuf += "</td><td>";
            strBuf += "</td><td>";
        }
        strBuf += "</td><td>";
        switch (i) {
            case 0:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                break;
            case 1:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                break;
            case 2:
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 3:
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 4:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                break;
            case 5:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                break;
            case 6:
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 7:
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 8:
            case 12:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 9:
            case 13:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 10:
            case 14:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 11:
            case 15:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 16:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                break;
            case 17:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                break;
            case 18:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                break;
            case 19:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                break;
            case 20:
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 21:
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 22:
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                strBuf += "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div>";
                break;
            case 23:
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                strBuf += "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div>";
                break;
            case 24:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-even-span'>대</div>";
                break;
            case 25:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-mid-span'>중</div>";
                break;
            case 26:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>소</div>";
                break;
            case 27:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-even-span'>대</div>";
                break;
            case 28:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-mid-span'>중</div>";
                break;
            case 29:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>소</div>";
                break;
            case 30:
                strBuf += "<div  class = 'pb-home-mid-span'>대</div>";
                break;
            case 31:
                strBuf += "<div  class = 'pb-home-mid-span'>중</div>";
                break;
            case 32:
                strBuf += "<div  class = 'pb-home-mid-span'>소</div>";
                break;

            default:
                break;
        }
        strBuf += "</td><td>";
        switch (i) {
            case 0:
                strBuf += jRealBet.config.game_ratio_1;
                break;
            case 1:
                strBuf += jRealBet.config.game_ratio_1;
                break;
            case 2:
                strBuf += jRealBet.config.game_ratio_2;
                break;
            case 3:
                strBuf += jRealBet.config.game_ratio_2;
                break;
            case 4:
                strBuf += jRealBet.config.game_ratio_3;
                break;
            case 5:
                strBuf += jRealBet.config.game_ratio_3;
                break;
            case 6:
                strBuf += jRealBet.config.game_ratio_4;
                break;
            case 7:
                strBuf += jRealBet.config.game_ratio_4;
                break;
            case 8:
                strBuf += jRealBet.config.game_ratio_5;
                break;
            case 9:
                strBuf += jRealBet.config.game_ratio_6;
                break;
            case 10:
                strBuf += jRealBet.config.game_ratio_7;
                break;
            case 11:
                strBuf += jRealBet.config.game_ratio_8;
                break;
            case 12:
                strBuf += jRealBet.config.game_ratio_9;
                break;
            case 13:
                strBuf += jRealBet.config.game_ratio_10;
                break;
            case 14:
                strBuf += jRealBet.config.game_ratio_11;
                break;
            case 15:
                strBuf += jRealBet.config.game_ratio_12;
                break;
            case 16:
                strBuf += jRealBet.config.game_ratio_13;
                break;
            case 17:
                strBuf += jRealBet.config.game_ratio_14;
                break;
            case 18:
                strBuf += jRealBet.config.game_ratio_15;
                break;
            case 19:
                strBuf += jRealBet.config.game_ratio_16;
                break;
            case 20:
                strBuf += jRealBet.config.game_ratio_17;
                break;
            case 21:
                strBuf += jRealBet.config.game_ratio_18;
                break;
            case 22:
                strBuf += jRealBet.config.game_ratio_19;
                break;
            case 23:
                strBuf += jRealBet.config.game_ratio_20;
                break;
            case 24:
                strBuf += jRealBet.config.game_ratio_21;
                break;
            case 25:
                strBuf += jRealBet.config.game_ratio_22;
                break;
            case 26:
                strBuf += jRealBet.config.game_ratio_23;
                break;
            case 27:
                strBuf += jRealBet.config.game_ratio_24;
                break;
            case 28:
                strBuf += jRealBet.config.game_ratio_25;
                break;
            case 29:
                strBuf += jRealBet.config.game_ratio_26;
                break;
            case 30:
                strBuf += jRealBet.config.game_ratio_27;
                break;
            case 31:
                strBuf += jRealBet.config.game_ratio_28;
                break;
            case 32:
                strBuf += jRealBet.config.game_ratio_29;
                break;
            default:
                break;
        }
        strBuf += "</td><td>";
        iTemp = 0;
        switch (i) {
            case 0:
                iTemp = jRealBet.betsums[0][0];
                break;
            case 1:
                iTemp = jRealBet.betsums[0][1];
                break;
            case 2:
                iTemp = jRealBet.betsums[1][0];
                break;
            case 3:
                iTemp = jRealBet.betsums[1][1];
                break;
            case 4:
                iTemp = jRealBet.betsums[2][0];
                break;
            case 5:
                iTemp = jRealBet.betsums[2][1];
                break;
            case 6:
                iTemp = jRealBet.betsums[3][0];
                break;
            case 7:
                iTemp = jRealBet.betsums[3][1];
                break;
            default:
                iTemp = jRealBet.betsums[i - 4][0];
                break;
        }
        if (iTemp > 0)
            strBuf += iTemp.toLocaleString() + " 원";
        strBuf += "</td><td>";

        iTemp = 0;
        switch (i) {
            case 0:
                iTemp = parseInt(jRealBet.betsums[0][0]) > parseInt(jRealBet.betsums[0][1]) ? jRealBet.betsums[0][0] - jRealBet.betsums[0][1] : 0;
                break;
            case 1:
                iTemp = parseInt(jRealBet.betsums[0][1]) > parseInt(jRealBet.betsums[0][0]) ? jRealBet.betsums[0][1] - jRealBet.betsums[0][0] : 0;
                break;
            case 2:
                iTemp = parseInt(jRealBet.betsums[1][0]) > parseInt(jRealBet.betsums[1][1]) ? jRealBet.betsums[1][0] - jRealBet.betsums[1][1] : 0;
                break;
            case 3:
                iTemp = parseInt(jRealBet.betsums[1][1]) > parseInt(jRealBet.betsums[1][0]) ? jRealBet.betsums[1][1] - jRealBet.betsums[1][0] : 0;
                break;
            case 4:
                iTemp = parseInt(jRealBet.betsums[2][0]) > parseInt(jRealBet.betsums[2][1]) ? jRealBet.betsums[2][0] - jRealBet.betsums[2][1] : 0;
                break;
            case 5:
                iTemp = parseInt(jRealBet.betsums[2][1]) > parseInt(jRealBet.betsums[2][0]) ? jRealBet.betsums[2][1] - jRealBet.betsums[2][0] : 0;
                break;
            case 6:
                iTemp = parseInt(jRealBet.betsums[3][0]) > parseInt(jRealBet.betsums[3][1]) ? jRealBet.betsums[3][0] - jRealBet.betsums[3][1] : 0;
                break;
            case 7:
                iTemp = parseInt(jRealBet.betsums[3][1]) > parseInt(jRealBet.betsums[3][0]) ? jRealBet.betsums[3][1] - jRealBet.betsums[3][0] : 0;
                break;
            default:
                break;
        }
        if (iTemp > 0)
            strBuf += iTemp.toLocaleString() + " 원";

        strBuf += "</td></tr>";


    }
    elemBetDataTb.innerHTML = strBuf;

}


//Function to Request Betting History to WebServer
function requestBetRealtime() {
    $(".loading").show();
    var jsonData = {
        "game": mGameId,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/pbapi/betrealtime',
        type: 'post',
        data: { json_: jsonData },
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetRealtime(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}



function pbrealtimeLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10) {
        requestBetRealtime();
    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbrealtimeLoop();
    }, 1000);

}