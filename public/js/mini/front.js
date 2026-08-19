$(function() {
    var $windowTop = $(window).scrollTop();
    var $windowInner = window.innerWidth;

    $(window).scroll(function() {
        $windowTop = $(window).scrollTop();
        $windowInner = window.innerWidth;
        var headerH = $('header').height();
        if ($windowInner <= 1140) {
            if ($windowTop > 0) {
                $('header').addClass('fixed');
                $('#container').css('margin-top', headerH);
            } else {
                $('header').removeClass('fixed');
                $('#container').css('margin-top', '10px');
            }
        } else {
            $('header').removeClass('fixed');
            $('#container').css('margin-top', '0px');

            scrollBettingBoard();
        }
    });

    $(window).resize(function() {
        $windowTop = $(window).scrollTop();
        $windowInner = window.innerWidth;

        if ($windowInner <= 1279) {
            $('img.responsive_img').each(function() {
                $(this).attr('src', $(this).attr('img-mobile'));
            });
        } else if ($windowInner >= 1280) {
            $('img.responsive_img').each(function() {
                $(this).attr('src', $(this).attr('img-web'));
            });
        }

        var headerH = $('header').height();
        if ($windowInner <= 1279) {
            if ($windowTop > 0) {
                $('header').addClass('fixed');
                $('#container').css('margin-top', headerH);
            } else {
                $('header').removeClass('fixed');
                $('#container').css('margin-top', '10px');
            }
        } else {
            $('header').removeClass('fixed');
            $('#container').css('margin-top', '0px');
        }

        scrollBettingBoard();
    });

    // resize_img
    if ($windowInner <= 1279) {
        $('img.responsive_img').each(function() {
            $(this).attr('src', $(this).attr('img-mobile'));
        });
    } else if ($windowInner >= 1280) {
        $('img.responsive_img').each(function() {
            $(this).attr('src', $(this).attr('img-web'));
        });
    }


    $('#game').on('load', function() {
        $(this).parent().removeClass('wait');
    });

    betting_board();
    scrollBettingBoard();
    betting_slip();
    game_pip();
    btnSelect();
});

$(window).on('load', function() {
    closeLoader();
});


function closeLoader() {
    setTimeout(function() {
        //$('.loading').fadeOut();
        $('.loading').hide();
        return true;
    });
}

function betting_board() {
    var flag = 1;

    $('.betting_con button, .dividend_team, .game_item .bet, .betting_board_open').on('click', function(e) {
        $windowInner = window.innerWidth;
        if (flag == 1 && $windowInner <= 1140) {
            $('.betting_board').stop().animate({ bottom: '0' });
            flag = 0;
        } else {
            return true;
        }
    });

    $('.betting_board_open').on('click', function(e) {
        $('.betting_board_m_close').addClass('close');
        $('.betting_board_box').addClass('open');
    });

    $('.betting_board_m_close').on('click', function(e) {
        if ($(this).hasClass('close') === false) {
            $(this).addClass('close');
            $('.betting_board_box').addClass('open');
        } else {
            $windowInner = window.innerWidth;
            if ($windowInner <= 1140) {
                $('.betting_board').stop().animate({ bottom: '-100%' });
            } else if ($windowInner > 1140) {
                $('.betting_board').stop().animate({ bottom: '-100%' });
            }
            // $('.betting_board_m').stop().animate({bottom:'20px'});
            flag = 1;

            $(this).removeClass('close');
        }
    });
}

function betting_slip() {
    $('.betting_slip_btn').on('click', function(e) {
        e.preventDefault();
        $('.betting_slip').toggleClass('betting_slip_open');
        $('.aside_m_close').show();
    });

    $('.aside_m_close').on('click', function(e) {
        e.preventDefault();
        $('.betting_slip').toggleClass('betting_slip_open');
        $('.aside_m_close').hide();
    });
}

function game_pip() {
    $(window).scroll(function() {
        var sclTop = $(this).scrollTop();
        if ($('.betting_area_flex').length === 0 || $('.betting_area_flex').hasClass('hide_video') === false) {
            if (sclTop >= 400) {
                $('.game_pip').show();
            } else {
                $('.game_pip').fadeOut();
            }
        }
    });
}


function datePicker() {
    $.datepicker.setDefaults({
        dateFormat: "yy-mm-dd",
        prevText: "이전 달",
        nextText: "다음 달",
        monthNames: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        monthNamesShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
        dayNames: ["일", "월", "화", "수", "목", "금", "토"],
        dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
        dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
        showMonthAfterYear: true,
        yearSuffix: "년"
    });

    $(".datepicker").datepicker({ dateFormat: "yy-mm-dd" });
}

function btnSelect() {
    $(".btn_select > .state_btn").on("click", function() {
        $(".btn_pop").not($(this).next(".btn_pop")).hide();
        $(this).next(".btn_pop").toggle();
    });
    $(".btn_pop .state_btn").on("click", function() {
        $(this).parents(".btn_pop").hide();
    });
}


function scrollBettingBoard() {

    if ($('.betting_board').length < 1)
        return;

    if ($('.betting_board').css("position") !== "absolute") {
        $('.betting_board').css("top", "inherit");
        return;
    }

    var windowTop = $(window).scrollTop();
    var gameWrapHeight = $('.betting_area').height() ;//
    if ($('.game_wrap').css("display") !== "none") {
        gameWrapHeight += $('.game_wrap').height() + 30;// 
    }

    var bettingBoardTop = windowTop;
    var bettingBoardHeight = $('.betting_board').height();

    if (bettingBoardHeight > gameWrapHeight) {
        bettingBoardTop = 0;
    } else if(bettingBoardTop < 120){
        bettingBoardTop = 0;
    } else if (bettingBoardHeight + bettingBoardTop > gameWrapHeight) {
        bettingBoardTop = gameWrapHeight - bettingBoardHeight;
    } else bettingBoardTop -= 120;

    if (bettingBoardTop >= 0) {
        bettingBoardTop += 20;
        // $('.betting_board').css("top", bettingBoardTop);
        $('.betting_board').stop().animate({ top: bettingBoardTop + 'px' });
    }



}