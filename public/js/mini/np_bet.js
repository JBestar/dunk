var first_click = true;

function updateCart(money) {

    if (money >= 0) {
        $("#u_money").text(numberWithCommas(money) + " " + langMessage.won);
        $("#u_money").data("amount", money);
    }
}

// 베팅 머니 계산
function betting_money(money) {

    var price = parseInt(money);
    var bet_min = parseInt($("#bet_min").data("amount"));
    var max_hit = parseInt($("#dist_max").data("amount"));

    var total_rate = select_rate;
    if (isNaN(price) || price <= 0) {
        price = bet_min;
        $("#input_money").val(0);
        first_click = true;
    }

    $("#bet_money").val(numberWithCommas(price));
    total_rate = (Math.floor(total_rate * 100) / 100).toFixed(2);

    var hit_money = Math.floor(price * total_rate);
    if (max_hit > 0 && hit_money > max_hit ) {
        hit_money = max_hit;
    }

    $("#hit_money_input").val(numberWithCommas(hit_money));
}

$(function() {
    $("#input_money").keyup(function(e) {
        e.preventDefault();

        if (select_rate == 0) {
            bet_min = parseInt($("#bet_min").data("amount"));
            $("#bet_money").val(bet_min);
            confirmAlert(langMessage.game_select_amount);
            return;
        }

        var tmp_price = parseInt(
            $(this)
            .val()
            .replace(/[^0-9]/g, "")
        );
        if (isNaN(tmp_price)) {
            tmp_price = 0;
        } else {
            tmp_price = Number(tmp_price);
        }
        // console.log("keyup:" + tmp_price);
        ret = money_max_check(tmp_price);
        if (ret[0] === false) {
            if (tmp_price.toString().length == 1) {
                tmp_price = 0;
            } else {
                tmp_price = Number(tmp_price.toString().slice(0, -1));
            }
            betting_money(tmp_price);
        } else {
            betting_money(tmp_price);
        }
        $(this).val(number_format(tmp_price.toString()));
    });

    $("#bet_money").bind("focus", function(e) {
        // 반복.
        $("#bet_money").val("");
    });

    $('[id^="bet_price_"]').click(function(e) {
        e.preventDefault();

        var id = $(this).attr("id");
        var tmp_price = parseInt(id.replace("bet_price_", ""));
        var money = parseInt(
            $("#bet_money")
            .val()
            .replace(/[^0-9]/g, "")
        );
        var total = 0;
        var bet_max = parseInt($("#bet_max").data("amount"));
        var bet_min = parseInt($("#bet_min").data("amount"));
        var max_hit = parseInt($("#dist_max").data("amount"));
        var user_money = parseInt($("#u_money").data("amount"));

        tmp_price = Number(tmp_price);
        user_money = Number(user_money);
        money = Number(money);

        if (select_rate == 0) {
            confirmAlert(langMessage.game_select_amount);
            return;
        }
        if (tmp_price == 0) {
            if (bet_max > 0 && user_money > bet_max) {
                total = bet_max;
            } else {
                total = user_money;
            }
        } else {
            if (first_click) {
                total = tmp_price;
                first_click = false;
            } else {
                total = money + tmp_price;
            }
        }

        ret = money_max_check(total);
        if (ret[0] === false) {
            betting_money(ret[1]);
        } else {
            betting_money(total);
        }
    });

    $("#input_money").keyup(function() {
        if (select_rate == 0) {
            $("#input_money").val("");
            confirmAlert(langMessage.game_select_amount);
            return;
        }

        var tmp_price = parseInt(
            $(this)
            .val()
            .replace(/[^0-9]/g, "")
        );
        if (isNaN(tmp_price)) {
            tmp_price = 0;
        } else {
            tmp_price = Number(tmp_price);
        }
        ret = money_max_check(tmp_price);
        if (ret[0] === false) {
            if (tmp_price.toString().length == 1) {
                tmp_price = 0;
            } else {
                tmp_price = Number(tmp_price.toString().slice(0, -1));
            }
            betting_money(tmp_price);
        } else {
            betting_money(tmp_price);
        }
    });

    $("#refresh_money").click(function(e) {
        e.preventDefault();

        betting_money(0);
    });

    $("#mini_betting").click(function(e) {
        e.preventDefault();
        if ($(".alert_wrap").is(":visible")) {
            return false;
        }

        var bet_money = $("#bet_money")
            .val()
            .replace(/[^0-9]/g, "");

        var bet_min = parseInt($("#bet_min").data("amount"));

        if (Number(bet_money) <= 0) {
            confirmAlert(langMessage.bet_amount_select);
            return false;
        }
        if (bet_min>0 && Number(bet_money) < Number(bet_min)) {
            confirmAlert(langMessage.bet_amount_small);
            return;
        }

        if ($("#hit_money_input").val() != "0" && parseInt(select_idx) != 0) {

            if(mConfig != null && mConfig.bet_confirm){
                var betTitle = $("#board_game").text();
                var betRate = $("#board_rate").text();
                var betRound = $("#cart_round").text();
    
                var text = betRound + "<br/>" + betTitle + "<br/>"+langMessage.game_rate+" : " + betRate + "<br/>"+langMessage.amount+" : " + bet_money;
                // basicAlert(text + '<br/><br/>베팅 하시겠습니까?', "confirm_ok()");
                basic2Alert(
                    text + "<br/><br/>"+langMessage.bet_ask,
                    function() {
                        confirm_ok();
                    },
                    () => {
                        select_reset();
                        $(".betting_board_m_close").trigger("click");
                    }
                );
            } else{
                confirm_ok();
                $(".betting_board_m_close").trigger("click");
            }

        } else {
            confirmAlert(langMessage.bet_enable);
        }
    });


    $('#follow_game').on('click', function() {
        if ($("#board_follow").data('user').length > 0) {
            $("#follow_uid").val($("#board_follow").data('user'));
            $("#follow_rate").val($("#board_follow").data('rate'));
            // $("#board_follow").data('stop');
        } else {
            $("#follow_uid").val("");
            $("#follow_rate").val(100);
        }

        $('#layer2').show();
    });

    $('.pop_close').on('click', function() {
        $(this).parent().parent().hide();
    });

    $('#btn_save_follow').on('click', function() {

        saveFollow();
    });

});