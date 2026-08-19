var mBetData = null;
$(document).ready(function() {
    setNavBarElement();
    requestBetHistory();
    addEventListner();
});

//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbetchange-table-id");
    var strBuf = "";

    var strBetMode = "";
    var strBetTarget = "";
    var strResultTarget = "";
    var strWinMoney = "";
    var strResult = "";

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_round_no;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td>";

        jsonBetData[nRow].bet_mode = parseInt(jsonBetData[nRow].bet_mode);
        strBetMode = "<td>";
        if (jsonBetData[nRow].bet_mode == 1 || jsonBetData[nRow].bet_mode == 2) {
            strBetMode = "<td class = 'pb-home-table-betmode-power'> 파워볼";
        } else if (jsonBetData[nRow].bet_mode == 3 || jsonBetData[nRow].bet_mode == 4) {
            strBetMode = "<td class = 'pb-home-table-betmode-normal'> 일반볼";
        } else if (jsonBetData[nRow].bet_mode >= 5 && jsonBetData[nRow].bet_mode <= 8) {
            strBetMode = "<td class = 'pb-home-table-betmode-power'> 파워볼조합";
        } else if (jsonBetData[nRow].bet_mode >= 9 && jsonBetData[nRow].bet_mode <= 12) {
            strBetMode = "<td class = 'pb-home-table-betmode-normal'> 일반볼조합";
        } else if (jsonBetData[nRow].bet_mode >= 13 && jsonBetData[nRow].bet_mode <= 20) {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 일반+파워조합";
        } else if (jsonBetData[nRow].bet_mode >= 21 && jsonBetData[nRow].bet_mode <= 29) {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 일반볼대중소";
        } else if (jsonBetData[nRow].bet_mode >= 31 && jsonBetData[nRow].bet_mode <= 38) {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 일반조합+파워홀짝";
        } else if (jsonBetData[nRow].bet_mode == 30) {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 파워볼 숫자";
        }
        strBetTarget = "";
        strResultTarget = "";
        if (jsonBetData[nRow].bet_mode >= 1 && jsonBetData[nRow].bet_mode <= 4) {
            strBetTarget = getHtmlByBet(jsonBetData[nRow].bet_mode, jsonBetData[nRow].bet_target);
            strResultTarget = getHtmlByBet(jsonBetData[nRow].bet_mode, jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode >= 5 && jsonBetData[nRow].bet_mode <= 12) {
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
            if (jsonBetData[nRow].bet_mode == 5 || jsonBetData[nRow].bet_mode == 9) {
                strBetTarget = getHtmlByBet(1, "P") + getHtmlByBet(2, "P");

            } else if (jsonBetData[nRow].bet_mode == 6 || jsonBetData[nRow].bet_mode == 10) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "B");

            } else if (jsonBetData[nRow].bet_mode == 7 || jsonBetData[nRow].bet_mode == 11) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "P");

            } else if (jsonBetData[nRow].bet_mode == 8 || jsonBetData[nRow].bet_mode == 12) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "B");

            }
        } else if (jsonBetData[nRow].bet_mode >= 13 && jsonBetData[nRow].bet_mode <= 16) {
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(1));
            }
            if (jsonBetData[nRow].bet_mode == 13) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 14) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("1", "B");
            } else if (jsonBetData[nRow].bet_mode == 15) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 16) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("1", "B");
            }
        } else if (jsonBetData[nRow].bet_mode >= 17 && jsonBetData[nRow].bet_mode <= 20) {
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
            if (jsonBetData[nRow].bet_mode == 17) {
                strBetTarget = getHtmlByBet("2", "P") + getHtmlByBet("2", "P");
            } else if (jsonBetData[nRow].bet_mode == 18) {
                strBetTarget = getHtmlByBet("2", "P") + getHtmlByBet("2", "B");
            } else if (jsonBetData[nRow].bet_mode == 19) {
                strBetTarget = getHtmlByBet("2", "B") + getHtmlByBet("2", "P");
            } else if (jsonBetData[nRow].bet_mode == 20) {
                strBetTarget = getHtmlByBet("2", "B") + getHtmlByBet("2", "B");
            }
        } else if (jsonBetData[nRow].bet_mode >= 21 && jsonBetData[nRow].bet_mode <= 26) {
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("5", jsonBetData[nRow].bet_result.charAt(1));
            }
            if (jsonBetData[nRow].bet_mode == 21) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "L");
            } else if (jsonBetData[nRow].bet_mode == 22) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "M");
            } else if (jsonBetData[nRow].bet_mode == 23) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("5", "S");
            } else if (jsonBetData[nRow].bet_mode == 24) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "L");
            } else if (jsonBetData[nRow].bet_mode == 25) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "M");
            } else if (jsonBetData[nRow].bet_mode == 26) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("5", "S");
            }
        } else if (jsonBetData[nRow].bet_mode == 27) {
            strBetTarget = getHtmlByBet("5", "L");
            strResultTarget = getHtmlByBet("5", jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode == 28) {
            strBetTarget = getHtmlByBet("5", "M");
            strResultTarget = getHtmlByBet("5", jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode == 29) {
            strBetTarget = getHtmlByBet("5", "S");
            strResultTarget = getHtmlByBet("5", jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode == 30) {
            strBetTarget = getHtmlByBet("6", jsonBetData[nRow].bet_target);
            strResultTarget = getHtmlByBet("6", jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode >= 31 && jsonBetData[nRow].bet_mode <= 38) {
            if (jsonBetData[nRow].bet_result.length > 2) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1)) +
                    getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(2));
            }
            if (jsonBetData[nRow].bet_mode == 31) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "P") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 32) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "P") + getHtmlByBet("1", "B");
            } else if (jsonBetData[nRow].bet_mode == 33) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "B") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 34) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "B") + getHtmlByBet("1", "B");
            } else if (jsonBetData[nRow].bet_mode == 35) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "P") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 36) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "P") + getHtmlByBet("1", "B");
            } else if (jsonBetData[nRow].bet_mode == 37) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "B") + getHtmlByBet("1", "P");
            } else if (jsonBetData[nRow].bet_mode == 38) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "B") + getHtmlByBet("1", "B");
            }
        }

        strBuf += strBetMode;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_ratio;
        strBuf += "</td><td>";
        strBuf += strBetTarget;
        strBuf += "</td><td>";
        strBuf += strResultTarget;
        strBuf += "</td><td>";

        strWinMoney = "";
        strResult = "<td>";
        if (jsonBetData[nRow].bet_state == "1") {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>대기중";
        } else if (jsonBetData[nRow].bet_state == "2") {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
        } else if (jsonBetData[nRow].bet_state == "3") {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
            strWinMoney = parseInt(jsonBetData[nRow].bet_win_money - jsonBetData[nRow].bet_money).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_state == "4") {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>무효";
        }

        strBuf += strWinMoney;
        strBuf += "</td>";
        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].bet_state == "4") {
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>복구처리</button>   ";
        } else {
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>결과처리</button>   ";
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>무효처리</button>   ";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='13'>자료가 없습니다.</td></tr>";
    }

    elemBetDataTb.innerHTML = strBuf;


    addTableBtnEvent();
}

function addTableBtnEvent() {
    var elemTable = document.getElementById("pbbetchange-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        elemTableBtns[i].addEventListener("click", function() {

            if (this.innerHTML.search("결과처리") >= 0 || this.innerHTML.search("복구처리") >= 0) {
                var jsData = {"data":[this.name], "game":mGameId};
                requestBetProcess(jsData);
            } else if (this.innerHTML.search("무효처리") >= 0) {
                var jsData = {"data":[this.name], "game":mGameId};
                requestBetIgnore(jsData);
            }
        });
    }
}


function getHtmlByBet(strMode, strTarget) {
    var strResult = "";
    iMode = parseInt(strMode);
    if (iMode == 1 || iMode == 3) {
        if (strTarget == "P")
            strResult = "<div  class = 'pb-home-odd-span'>홀</div> "
        else if (strTarget == "B")
            strResult = "<div  class = 'pb-home-even-span'>짝</div> ";
    } else if (iMode == 2 || iMode == 4) {
        if (strTarget == "P")
            strResult = "<div  class = 'pb-home-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></div> ";
        else if (strTarget == "B")
            strResult = "<div  class = 'pb-home-even-span'><i class='glyphicon glyphicon-arrow-up'></i></div> ";
    } else if (iMode == 5) {
        if (strTarget == 'L') strResult = "<div  class = 'pb-home-mid-span'>대</div> ";
        else if (strTarget == 'M') strResult = "<div  class = 'pb-home-mid-span'>중</div> ";
        else if (strTarget == 'S') strResult = "<div  class = 'pb-home-mid-span'>소</div> ";
    } else if (iMode == 6) {
        if (strTarget.length > 0)
            strResult = "<div  class = 'pb-home-mid-span'>" + strTarget + "</div> ";
    }
    return strResult;
}

function addEventListner() {
    $("#pbbetchange-view-but-id").click(function() {
        requestBetHistory();
    });

    $("#pbbetchange-ignore-but-id").click(function() {

        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var arrData = new Array();
        for (nRow in mBetData)
            arrData[nRow] = mBetData[nRow].bet_fid;

        var jsData = {"data":arrData, "game":mGameId};
        requestBetIgnore(jsData);
    });


    $("#pbbetchange-process-but-id").click(function() {

        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var arrData = new Array();
        for (nRow in mBetData)
            arrData[nRow] = mBetData[nRow].bet_fid;

        var jsData = {"data":arrData, "game":mGameId};
        requestBetProcess(jsData);
    });
}


//Function to Request Betting History to WebServer
function requestBetHistory() {
    var dtStart = $("#pbbetchange-date-input-id").val();
    var dtEnd = $("#pbbetchange-date-input-id").val();
    var strRound = $("#pbbetchange-round-input-id").val();
    if (strRound < 1) return;

    var jsonData = {
        "count": 100,
        "page": 1,
        "start": dtStart,
        "end": dtEnd,
        "user": "",
        "round": strRound,
        "mode": 0,
        "emp": "",
        "game":mGameId
    };
    jsonData = JSON.stringify(jsonData);

    $(".loading").show();
    $.ajax({
        url: FURL + '/pbapi/betlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                mBetData = jResult.data;
                ShowBetHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestBetIgnore(jsData) {
    var jsonData = JSON.stringify(jsData);
    $.ajax({
        url: FURL + '/pbapi/betignore',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestBetProcess(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        url: FURL + '/pbapi/betprocess',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}