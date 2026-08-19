<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?=$site_name?></title>
        <link rel="shortcut icon" href="<?php echo site_furl('/favicon_'.$_ENV['app.logo'].'.ico?v=1'); ?>">

        <link rel="stylesheet" href="<?php echo site_furl('/css/jquery-ui.css?ver=1'); ?>" />

        <link rel="stylesheet" href="<?php echo site_furl('/css/mini/reset.css?v=1'); ?>">
        <link rel="stylesheet" href="<?php echo site_furl('/css/mini/common.css?v=1'); ?>">
        <link rel="stylesheet" href="<?php echo site_furl('/css/mini/content.css?v=1'); ?>">
        <link rel="stylesheet" href="<?php echo site_furl('/css/mini/style.css?v=1'); ?>">
        <link rel="stylesheet" href="<?php echo site_furl('/css/mini/game.css?v=1'); ?>">

        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-1.12.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-ui.js?v=1'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.form.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.validate.js'); ?>"></script>

        <script src="<?php echo site_furl('/js/worker.js?v=1'); ?>"></script>
        <script>
        const FURL = "<?=$_ENV['app.furl']?>";
        var langMessage = {
            amount : '<?=lang('common.amount')?>',
            ball_even : '<?=lang('common.ball_even')?>',
            ball_four : '<?=lang('common.ball_four')?>',
            ball_large : '<?=lang('common.ball_large')?>',
            ball_left : '<?=lang('common.ball_left')?>',
            ball_lms : '<?=lang('common.ball_lms')?>',
            ball_medium : '<?=lang('common.ball_medium')?>',
            ball_odd : '<?=lang('common.ball_odd')?>',
            ball_over : '<?=lang('common.ball_over')?>',
            ball_over_ : '<?=lang('common.ball_over_')?>',
            ball_right : '<?=lang('common.ball_right')?>',
            ball_small : '<?=lang('common.ball_small')?>',
            ball_three : '<?=lang('common.ball_three')?>',
            ball_under : '<?=lang('common.ball_under')?>',
            ball_under_ : '<?=lang('common.ball_under_')?>',
            bet : '<?=lang('common.bet')?>',
            bet_amount : '<?=lang('common.bet_amount')?>',
            bet_amount_select : '<?=lang('common.bet_amount_select')?>',
            bet_amount_small : '<?=lang('common.bet_amount_small')?>',
            bet_ask : '<?=lang('common.bet_ask')?>',
            bet_cancel : '<?=lang('common.bet_cancel')?>',
            bet_cancel_msg : '<?=lang('common.bet_cancel_msg')?>',
            bet_complete : '<?=lang('common.bet_complete')?>',
            bet_enable : '<?=lang('common.bet_enable')?>',
            bet_history : '<?=lang('common.bet_history')?>',
            bet_none : '<?=lang('common.bet_none')?>',
            bet_rule : '<?=lang('common.bet_rule')?>',
            bet_time : '<?=lang('common.bet_time')?>',
            cancel : '<?=lang('common.cancel')?>',
            combination : '<?=lang('common.combination')?>',
            day : '<?=lang('common.day')?>',
            end : '<?=lang('common.end')?>',
            game_rate : '<?=lang('common.game_rate')?>',
            game_result : '<?=lang('common.game_result')?>',
            game_result_ : '<?=lang('common.game_result_')?>',
            game_result_none : '<?=lang('common.game_result_none')?>',
            game_round : '<?=lang('common.game_round')?>',
            game_select_amount : '<?=lang('common.game_select_amount')?>',
            game_time : '<?=lang('common.game_time')?>',
            game_type : '<?=lang('common.game_type')?>',
            hide_screen : '<?=lang('common.hide_screen')?>',
            hupball : '<?=lang('common.hupball')?>',
            hupball_ : '<?=lang('common.hupball_')?>',
            hupball_mix : '<?=lang('common.hupball_mix')?>',
            infinite : '<?=lang('common.infinite')?>',
            loss : '<?=lang('common.loss')?>',
            month : '<?=lang('common.month')?>',
            normal : '<?=lang('common.normal')?>',
            normalball : '<?=lang('common.normalball')?>',
            normalball_ : '<?=lang('common.normalball_')?>',
            normalball_mix : '<?=lang('common.normalball_mix')?>',
            number : '<?=lang('common.number')?>',
            number_ : '<?=lang('common.number_')?>',
            number_result : '<?=lang('common.number_result')?>',
            ok : '<?=lang('common.ok')?>',
            powerball : '<?=lang('common.powerball')?>',
            powerball_ : '<?=lang('common.powerball_')?>',
            powerball_boggle : '<?=lang('common.powerball_boggle')?>',
            powerball_coin3 : '<?=lang('common.powerball_coin3')?>',
            powerball_coin5 : '<?=lang('common.powerball_coin5')?>',
            powerball_dhp : '<?=lang('common.powerball_dhp')?>',
            powerball_digit : '<?=lang('common.powerball_digit')?>',
            powerball_eos3 : '<?=lang('common.powerball_eos3')?>',
            powerball_eos5 : '<?=lang('common.powerball_eos5')?>',
            powerball_evol : '<?=lang('common.powerball_evol')?>',
            powerball_happy : '<?=lang('common.powerball_happy')?>',
            powerball_pbg : '<?=lang('common.powerball_pbg')?>',
            powerball_spkn : '<?=lang('common.powerball_spkn')?>',
            powerball_rand3 : '<?=lang('common.powerball_rand3')?>',
            powerball_rand5 : '<?=lang('common.powerball_rand5')?>',
            powerladder_boggle : '<?=lang('common.powerladder_boggle')?>',
            round : '<?=lang('common.round')?>',
            session_expired : '<?=lang('common.session_expired')?>',
            show_screen : '<?=lang('common.show_screen')?>',
            sum : '<?=lang('common.sum')?>',
            super : '<?=lang('common.super')?>',
            superball : '<?=lang('common.superball')?>',
            superball_ : '<?=lang('common.superball_')?>',
            superball_mix : '<?=lang('common.superball_mix')?>',
            win : '<?=lang('common.win')?>',
            win_no : '<?=lang('common.win_no')?>',
            win_status : '<?=lang('common.win_status')?>',
            won : '<?=lang('common.won')?>',
        };
    </script>  
    <style>
        .betting_slip.betting_slip_none .inner:after {
            content: "<?=lang('common.bet_cannot')?>";
        }
        #container .content .betting_board .betting_board_inner.betting_board_none:after {
            content: "<?=lang('common.bet_cannot')?>";
        }
        .dropdown button {
            width:90px;
        }
        .result_menu li{
            margin-right:1px;
        }
    </style>
    </head>




