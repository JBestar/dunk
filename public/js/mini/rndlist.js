$(document).ready(function() {
    requestTotalPage();
});

$(function() {

    $(".search_tab a").click(function(e) {
        e.preventDefault();

        $(".search_tab a").removeClass("on");
        $(this).addClass("on");
        requestTotalPage();
    });
});

function getDate() {
    if ($(".search_tab .on").length > 0) {
        $date = $(".search_tab .on").attr("id");
        return $date.replace(/[/]/g, "-");
    }
    return "";
}

function getGameId() {
    return $(".result_list").attr("id");
}

function requestPageInfo() {
    var nPage = getActivePage();
    var jsonData = { "game": getGameId(), "date": getDate(), "count": CountPerPage, "page": nPage };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/page_round",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showPage(jResult.game, jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestTotalPage() {
    var jsonData = { "count": CountPerPage, "date": getDate(), "game": getGameId() };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/count_round',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data;
                setFirstPage();
                requestPageInfo();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function showPage(game, data) {

    if (game == "undefined" || game == null)
        return;

    var html = "";
    let nums;
    if (data) {
        html += '<div class="all_result">';
        html += '<div class="inner">';
        html += '<div class="all_result_wrap">';
        html += '<div class="all_result_tit">';
        html += '<p class="round">'+langMessage.game_round+'</p>';
        html += '<p class="time">'+langMessage.game_time+'</p>';
        if (game == 6) {
            html += '<p class="rst">'+langMessage.ball_left+langMessage.ball_right+'</p>';
            html += '<p class="rst">3/4</p>';
            html += '<p class="rst">'+langMessage.ball_odd+langMessage.ball_even+'</p>';
        } else {
            html += '<p class="win">'+langMessage.number_result+'</p>';
            html += '<p class="rst_c">'+langMessage.number_+' '+langMessage.sum+'</p>';
            html += '<p class="rst_c">'+langMessage.number_+' '+langMessage.ball_odd+'/'+langMessage.ball_even+'</p>';
            html += '<p class="rst_c">'+langMessage.powerball+'</p>';
            html += '<p class="rst_c">'+langMessage.powerball+' '+langMessage.ball_odd+'/'+langMessage.ball_even+'</p>';
            if(game == 14)
                html += '<p class="rst_c">'+langMessage.superball+'</p>';
            else
                html += '<p class="group">'+langMessage.ball_large+'/'+langMessage.ball_medium+'/'+langMessage.ball_small+'</p>';
        }

        html += '</div>';
        html += '<div class="all_result_con">';
        html += '<ul>';

        if (data.length == 0) {
            html += '<li class="none"><p>'+langMessage.game_result_none+'</p></li>';
        } else {
            data.forEach((round) => {
                html += '<li>';
                html += '<p class="round">' + round.round_num + '</p>';
                html += '<p class="time">' + getRoundTime(game, round).substring(5) + '</p>';
                if (game == 6) {
                    if (round.round_state > 0) {
                        if (round.round_result_1 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">'+langMessage.ball_left+'</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">'+langMessage.ball_right+'</span></p>';
                        if (round.round_result_2 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">3</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">4</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst"> <span class="rst_ico blue">'+langMessage.ball_odd+'</span></p>';
                        else html += '<p class="rst"> <span class="rst_ico red">'+langMessage.ball_right+'</span></p>';
                    } else {
                        html += '<p class="rst">-</p>';
                        html += '<p class="rst">-</p>';
                        html += '<p class="rst">-</p>';
                    }
                } else {
                    if (round.round_state > 0) {
                        nums = getRoundNum(round);
                        html += '<p class="win">' + nums[0] + '</p>';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + nums[1] + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">'+langMessage.ball_odd+'</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">'+langMessage.ball_even+'</span></p>';

                        html += '<p class="rst_c"><span class="rst_ico gray">' + round.round_power + '</span></p>';
                        if (round.round_result_1 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">'+langMessage.ball_odd+'</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">'+langMessage.ball_even+'</span></p>';

                        if(game == 14){
                            html += '<p class="rst_c"><span class="rst_ico gray">' + nums[2] + '</span></p>';
                        } else {
                            if (round.round_result_5 == "L")
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_large+'</span> (81~130)</p>';
                            else if (round.round_result_5 == "M")
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_medium+'</span> (65~80)</p>';
                            else
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_small+'</span> (15~64)</p>';    
                        }
                        

                    } else {
                        html += '<p class="win">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="group">-</p>';
                    }
                }

                html += '</li>';
            });
        }
        html += '</ul></div></div></div></div>';
        html += '<div class="all_result_m">';
        html += '<div class="inner">';
        html += '<div class="all_result_wrap">';
        html += '<div class="all_result_tit">';
        if (game == 6) {
            html += '<div class="row">';
            html += '<p class="round">'+langMessage.game_round+'</p>';
            html += '<p class="win">'+langMessage.game_time+'</p>';
            html += '<p class="rst_c">결과</p>';
            html += '</div></div>';
        } else {
            html += '<div class="row">';
            html += '<p class="round">'+langMessage.game_round+'</p>';
            html += '<p class="win">'+langMessage.number_result+'</p>';
            html += '<p class="rst_c">'+langMessage.number_+' '+langMessage.sum+'</p>';
            html += ' <p class="rst_c">'+langMessage.number_+' '+langMessage.ball_odd+'/'+langMessage.ball_even+'</p>';
            html += '</div>';
            html += '<div class="row">';
            html += '<p class="rst_c">'+langMessage.powerball+'</p>';
            html += '<p class="rst_c">'+langMessage.powerball+' '+langMessage.ball_odd+'/'+langMessage.ball_even+'</p>';
            if(game == 14)
                html += '<p class="rst_c">'+langMessage.superball+'</p>';
            else
                html += '<p class="group">'+langMessage.ball_large+'/'+langMessage.ball_medium+'/'+langMessage.ball_small+'</p>';
            html += '</div></div>';
        }

        html += '<div class="all_result_con">';
        html += '<ul>';
        if (data.length == 0) {
            html += '<li class="none"  style="display:grid;"><p>'+langMessage.game_result_none+'</p></li>';
        } else {
            data.forEach((round) => {
                html += '<li class="row_wrap">';
                html += '<div class="row">';
                html += '<p class="round">' + round.round_num + '</p>';
                if (game == 6) {
                    html += '<p class="rst_c">' + getRoundTime(game, round).substring(5) + '</p>';
                    html += '<p class="rst_c">';
                    if (round.round_state > 0) {
                        if (round.round_result_1 == "P")
                            html += '<span class="rst_ico blue">'+langMessage.ball_left+'</span>';
                        else html += '<span class="rst_ico red">'+langMessage.ball_right+'</span>';
                        if (round.round_result_2 == "P")
                            html += '<span class="rst_ico blue">3</span>';
                        else html += '<span class="rst_ico red">4</span>';
                        if (round.round_result_3 == "P")
                            html += '<span class="rst_ico blue">'+langMessage.ball_odd+'</span>';
                        else html += '<span class="rst_ico red">'+langMessage.ball_even+'</span>';
                    } else {
                        html += "-";
                    }
                    html += '</p>';
                } else {
                    if (round.round_state > 0) {
                        nums = getRoundNum(round);
                        html += '<p class="win">' + nums[0] + '</p>';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + nums[1] + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">'+langMessage.ball_odd+'</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">'+langMessage.ball_even+'</span></p>';

                        html += '</div><div class="row">';
                        html += '<p class="rst_c"><span class="rst_ico gray">' + round.round_power + '</span></p>';
                        if (round.round_result_3 == "P")
                            html += '<p class="rst_c"><span class="rst_ico blue">'+langMessage.ball_odd+'</span></p>';
                        else
                            html += '<p class="rst_c"><span class="rst_ico red">'+langMessage.ball_even+'</span></p>';

                        if(game == 14){
                            html += '<p class="rst_c"><span class="rst_ico gray">' + nums[2] + '</span></p>';
                        } else {
                            if (round.round_result_5 == "L")
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_large+'</span> (81~130)</p>';
                            else if (round.round_result_5 == "M")
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_medium+'</span> (65~80)</p>';
                            else
                                html += '<p class="group"><span class="rst_ico green">'+langMessage.ball_small+'</span> (15~64)</p>';
                        }
                        html += '</div>';

                    } else {
                        html += '<p class="win">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '</div><div class="row">';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="rst_c">-</p>';
                        html += '<p class="group">-</p>';
                        html += '</div>';

                    }
                }

                html += '</li>';
            });
        }
        html += '</ul></div></div></div></div>';

    }
    $(".result_list").html(html);
}

function getRoundNum(round) {
    var sSup = -1;
    var sNum = "";
    var sSum = 0;
    arrNormal = round.round_normal.split(",");
    if (arrNormal.length >= 5) {

        arrNormal.forEach((num) => {
            sNum += parseInt(num) + ",";
            sSum += parseInt(num);
            if(sSup < 0)
             sSup = parseInt(num);
        });
        sNum += round.round_power;
    } else {
        sNum = "-";
        sSum = "-";
        sSup = "-";
    }
    return [sNum, sSum, sSup];
}

function getRoundTime(game, round) {

    return round.round_time;

    // let rt = 5;
    // if (game == 5) {
    //     rt = 2;
    // } else if (game == 6 || game == 10 || game == 12) {
    //     rt = 3;
    // }

    // let hh = parseInt(round.round_num * rt / 60);
    // let mm = (round.round_num * rt) % 60;
    // var dt = new Date(round.round_date + " " + hh + ":" + mm + ":0");
    // if(game == 1 || game == 2)      //동행복권
    //     dt.setSeconds(dt.getSeconds() - 25);

    // var year = dt.getFullYear().toString();
    // var month = (dt.getMonth() + 101).toString().slice(-2);
    // var date = (dt.getDate() + 100).toString().slice(-2);
    // var hour = (dt.getHours() + 100).toString().slice(-2);
    // var min = (dt.getMinutes() + 100).toString().slice(-2);
    // var sec = (dt.getSeconds() + 100).toString().slice(-2);

    // return year + "-" + month + "-" + date + " " + hour + ":" + min + ":" + sec;

}