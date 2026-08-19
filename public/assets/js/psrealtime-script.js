$(document).ready(function() {
    setNavBarElement();
    requestBetRealtime();
    psrealtimeLoop();
});

//Function to Show Betting History
function ShowBetRealtime(jRealBet) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    strBuf = "<tr><th>번호</th> <th>경기시간</th><th>회차</th><th>배팅</th><th>배당율</th><th>금액</th><th>밸런스금액</th></tr>"
    elemBetDataTb.innerHTML = strBuf;

    var iTemp = 0;

    var i = 0;
    for (i = 0; i < 10; i++) {
        strBuf += "<tr><td>";

        if (i % 2 == 0 || i > 5) {
            if (i < 6)
                strBuf += (i / 2 + 1);
            else strBuf += (i - 2);
            strBuf += "</td><td>";
            strBuf += jRealBet.betend;
            strBuf += "</td><td>";
            strBuf += jRealBet.roundid;
            strBuf += "회차 ";
            switch (i) {
                case 0:
                    strBuf += "좌우배팅";
                    break;
                case 2:
                    strBuf += "줄수배팅";
                    break;
                case 4:
                    strBuf += "홀짝배팅";
                    break;
                case 6:
                    strBuf += "좌3배팅";
                    break;
                case 7:
                    strBuf += "좌4배팅";
                    break;
                case 8:
                    strBuf += "우3배팅";
                    break;
                case 9:
                    strBuf += "우4배팅";
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
                strBuf += "<div  class = 'pb-home-odd-span'>좌</div>";
                break;
            case 1:
                strBuf += "<div  class = 'pb-home-even-span'>우</div>";
                break;
            case 2:
                strBuf += "<div  class = 'pb-home-odd-span'>3</div>";
                break;
            case 3:
                strBuf += "<div  class = 'pb-home-even-span'>4</div>";
                break;
            case 4:
                strBuf += "<div  class = 'pb-home-odd-span'>홀</div>";
                break;
            case 5:
                strBuf += "<div  class = 'pb-home-even-span'>짝</div>";
                break;
            case 6:
                strBuf += "<div  class = 'pb-home-odd-span'>좌</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>3</div>";
                break;
            case 7:
                strBuf += "<div  class = 'pb-home-odd-span'>좌</div>";
                strBuf += "<div  class = 'pb-home-even-span'>4</div>";
                break;
            case 8:
                strBuf += "<div  class = 'pb-home-even-span'>우</div>";
                strBuf += "<div  class = 'pb-home-odd-span'>3</div>";
                break;
            case 9:
                strBuf += "<div  class = 'pb-home-even-span'>우</div>";
                strBuf += "<div  class = 'pb-home-even-span'>4</div>";
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
                strBuf += jRealBet.config.game_ratio_5;
                break;
            case 8:
                strBuf += jRealBet.config.game_ratio_6;
                break;
            case 9:
                strBuf += jRealBet.config.game_ratio_7;
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
            default:
                iTemp = jRealBet.betsums[i - 3][0];
                            
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
    var jsonData = {
        "game": mGameId,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/' + '/pbapi/betrealtime',
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



function psrealtimeLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10) {
        requestBetRealtime();
    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        psrealtimeLoop();
    }, 1000);

}