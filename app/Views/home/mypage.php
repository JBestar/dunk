<!DOCTYPE html>
<html lang="ko" class=""  style="background-color: #000;">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=no" />
        <script src="<?php echo site_furl('/js/vue.js'); ?>"></script>
        <script src="<?php echo site_furl('/js/vuejs-paginate.js'); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/js/semantic-ui/semantic.css'); ?>" />

        <link rel="stylesheet" href="<?php echo site_furl('/css/jquery-ui.css?ver=1'); ?>" />
        <link rel="stylesheet" href="<?php echo site_furl('/css/devel.css?v=1'); ?>" />

        <!-- JS FILES -->
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-1.12.4.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-ui.js?v=1'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.form.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.validate.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/common.js?v=3'); ?>"></script>

        <link rel="stylesheet" type="text/css" href="<?php echo site_furl('/js/uikit/uikit.min.css'); ?>" />
        <script src="<?php echo site_furl('/js/uikit/uikit.min.js'); ?>"></script>
        <script src="<?php echo site_furl('/js/uikit/uikit-icons.min.js'); ?>"></script>

        <link rel="stylesheet" href="<?php echo site_furl('/js/sweet/sweetalert2.min.css'); ?>" />
        <script type="text/javascript" src="<?php echo site_furl('/js/sweet/sweetalert2.min.js'); ?>"></script>

        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.form.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/jquery-form/jquery.validate.js'); ?>"></script>
        <!--semantic ui-->
        <!--ui.table-->
        <script src="<?php echo site_furl('/js/semantic-ui/semantic.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo site_furl('/js/toaster.js?v=1'); ?>"></script>
        <script>
            Vue.component("paginate", VuejsPaginate);
            const FURL = "<?=$_ENV['app.furl']?>";
            var langMessage = {
                ask_content_input : '<?=lang('common.ask_content_input')?>',
                ask_quick : '<?=lang('common.ask_quick')?>',
                ask_receive : '<?=lang('common.ask_receive')?>',
                ask_title_input : '<?=lang('common.ask_title_input')?>',
                cancel : '<?=lang('common.cancel')?>',
                change_point_request : '<?=lang('common.change_point_request')?>',
                change_point_result : '<?=lang('common.change_point_result')?>',
                delete_all_ask : '<?=lang('common.delete_all_ask')?>',
                delete_ask : '<?=lang('common.delete_ask')?>',
                delete_check : '<?=lang('common.delete_check')?>',
                deposit_account_answer : '<?=lang('common.deposit_account_answer')?>',
                deposit_account_ask : '<?=lang('common.deposit_account_ask')?>',
                deposit_account_check : '<?=lang('common.deposit_account_check')?>',
                deposit_account_request : '<?=lang('common.deposit_account_request')?>',
                deposit_permit : '<?=lang('common.deposit_permit')?>',
                deposit_quick : '<?=lang('common.deposit_quick')?>',
                deposit_success : '<?=lang('common.deposit_success')?>',
                id_input : '<?=lang('common.id_input')?>',
                id_input_4 : '<?=lang('common.id_input_4')?>',
                id_input_16 : '<?=lang('common.id_input_16')?>',
                inspection : '<?=lang('common.inspection')?>',
                message_to_read : '<?=lang('common.message_to_read')?>',
                nickname_input : '<?=lang('common.nickname_input')?>',
                ok : '<?=lang('common.ok')?>',
                password_change_ok : '<?=lang('common.password_change_ok')?>',
                password_input : '<?=lang('common.password_input')?>', 
                password_input_4 : '<?=lang('common.password_input_4')?>', 
                password_current_input : '<?=lang('common.password_current_input')?>',
                password_new_input : '<?=lang('common.password_new_input')?>',
                password_verify : '<?=lang('common.password_verify')?>',
                password_verify_c : '<?=lang('common.password_verify_c')?>',
                read_all_ask : '<?=lang('common.read_all_ask')?>',
                recovery_eggs_request : '<?=lang('common.recovery_eggs_request')?>',
                recovery_eggs_result : '<?=lang('common.recovery_eggs_result')?>',
                request_amount_10th : '<?=lang('common.request_amount_10th')?>',
                request_amount_input : '<?=lang('common.request_amount_input')?>',
                signup_complete : '<?=lang('common.signup_complete')?>',
                signup_permit : '<?=lang('common.signup_permit')?>',
                status_check : '<?=lang('common.status_check')?>',
                status_complete : '<?=lang('common.status_complete')?>',
                status_current : '<?=lang('common.status_current')?>',
                status_permit : '<?=lang('common.status_permit')?>',
                status_refuse : '<?=lang('common.status_refuse')?>',
                status_uncheck : '<?=lang('common.status_uncheck')?>',
                status_wait : '<?=lang('common.status_wait')?>',
                thanks : '<?=lang('common.thanks')?>',
                withdrawal_bank_select : '<?=lang('common.withdrawal_bank_select')?>',
                withdrawal_number_input : '<?=lang('common.withdrawal_number_input')?>',
                withdrawal_owner_input : '<?=lang('common.withdrawal_owner_input')?>',
                withdrawal_password_input : '<?=lang('common.withdrawal_password_input')?>',
                withdrawal_permit : '<?=lang('common.withdrawal_permit')?>',
                withdrawal_quick : '<?=lang('common.withdrawal_quick')?>',
                withdrawal_success : '<?=lang('common.withdrawal_success')?>',
            };

            function showAlert(msg, type=1){
                if(!toaster)
                    alert(msg);
                else if(type == 0)
                    toaster.error(msg);
                else if(type == 2)
                    toaster.info(msg);
                else if(type == 3)
                    toaster.warning(msg);
                else toaster.success(msg);
            }
        </script>  
        <link rel="stylesheet" href="<?php echo site_furl('/css/darkmode.css?v=3'); ?>" />
        <script src="<?php echo site_furl('/js/darkmode.js'); ?>"></script>
        <style>
                        
            @media screen and (min-width:680px) { 
                ::-webkit-scrollbar {width:10px; height:3px; }
                ::-webkit-scrollbar-track {background:#1e1e1e; border-radius:2px 2px 0 0; }
                ::-webkit-scrollbar-thumb {background:#383838; border-radius:2px; }
                ::-webkit-scrollbar-thumb:hover {background:#383838; }
            }
            /* modal header semantic -> uikir first*/
            h1:last-child,
            h2:last-child,
            h3:last-child,
            h4:last-child,
            h5:last-child {
                margin-top: 0px;
            }

            .uk-modal-header {
                background: steelblue;
            }

            .uk-modal-title {
                color: white;
            }

            .uk-close {
                color: white;
            }

            .ui.table {
                font-size: 0.85em;
            }

            .ui.tabular.menu .item.active {
                background: steelblue;
                color: white;
                font-weight: bold;
            }
            .ui.tab .inline.field .ui.button {
                margin-left:10px;
            }
            .inline.field span {
                color:#aaa;
                margin-right:10px;
            }

            .red {
                color: #ff0000;
            }

            .blue {
                color: #0000ff;
            }

            .green {
                color: #00ff00;
            }

            .yellow {
                color: #ffff00;
            }

            .loading {
                background: #1B2430;
            }
            .ui.table.no_border thead th, .ui.table.no_border th, .ui.table.no_border td{
                border:none;
            }
            .ui.celled.table tr th:first-child, .ui.celled.table tr td:first-child{
                border-left: 1px solid #064663;
            }
            .ui.form input[type="date"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"]{
                color:#eeeeee;
                background:#24425b;
            }
            .ui.message, .ui.form textarea{
                color:white;
                background:#24425b;
                font-size: 1.1em;
            }
            .ui.form .inline.field .input{
                width:calc(100% - 80px);
            }
            #dashboard .ui.table thead th{
                background:#24425b;
            }

            <?php if($_ENV['app.name'] == APP_ATM || $_ENV['app.name'] == APP_FUN || $_ENV['app.name'] == APP_DUNK) :?>
                form.ui.form{
                    border:1px solid #ffb08e;
                }
                .btn {
                    background: #1b1f25;
                }
                .btn:hover {
                    background: #5f5f5f;
                    color: #eee;
                }
                .uk-modal-dialog {
                    background: #232323;
                }
                .uk-modal-footer, .uk-modal-header {
                    background: #2f3031;
                }
                .ui.form input[type="text"], .ui.form input[type="password"], .ui.form input[type="number"] {
                    color: #eeeeee;
                    background: #494949;
                }
                .ui.inverted.blue.buttons .button, .ui.inverted.blue.button {
                    background-color: transparent;
                    -webkit-box-shadow: 0px 0px 0px 2px #9b9b9b inset;
                    box-shadow: 0px 0px 0px 2px #9b9b9b inset;
                    color: #adadad;
                }
                .ui.inverted.blue.buttons .button:hover, .ui.inverted.blue.button:hover {
                    background-color: #6d7477;
                }
                .ui.button {
                    background-color: #4b4b4b;
                    color: #FFFFFF;
                }
                .ui.button:active, .ui.active.button:active,.ui.button:hover {
                    background-color: #6b6b6b;
                }

                #dashboard, #SLB_content {
                    background: #000000;
                }
                .ui.table, .ui.tab.segment, .ui.table thead th, .ui.table th, .ui.table td {
                    background: #000000;
                }
                #dashboard .ui.table thead th {
                    background: #484949;
                }
                .ui.message, .ui.form textarea {
                    color: white;
                    background: #3d3d3d;
                }
                .ui.table thead tr > th:first-child, .ui.celled.table tr td:first-child {
                    border: 1px solid #616161;
                }
                .ui.table thead th, .ui.table th, .ui.table td {
                    border: 1px solid #616161;
                }
                .ui.tabular.menu .item {
                    background: #2d2d2d;
                }
                .ui.tabular.menu .item.active, .ui.tabular.menu .item:hover {
                    background: #858585;
                    color: white;
                }
                .ui.form input[type="date"] {
                    background: #4d4d4d;
                }
                .ui.blue.buttons .button, .ui.blue.button {
                    background-color: #4d4d4d;
                }
                .ui.blue.buttons .button:hover, .ui.blue.button:hover {
                    background-color: #6d6d6d;
                }
            <?php endif ?>

            /******* ON/OFF Switch ******************/

            .switch {
                position: relative;
                display: inline-block;
                vertical-align: top;
                width: 65px;
                height: 25px;
                padding: 3px;
                margin-right: 5px;
                cursor: pointer;
                border-radius: 18px;
            }

            .switch-input {
                position: absolute;
                top: 0;
                left: 0;
                opacity: 0;
            }

            .switch-label {
                position: relative;
                display: block;
                height: inherit;
                font-size: 10px;
                text-transform: uppercase;
                background: #eceeef;
                border-radius: inherit;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
            }

            .switch-label:before,
            .switch-label:after {
                position: absolute;
                top: 50%;
                margin-top: -.5em;
                line-height: 1;
                -webkit-transition: inherit;
                -moz-transition: inherit;
                -o-transition: inherit;
                transition: inherit;
            }

            .switch-label:before {
                content: attr(data-off);
                right: 11px;
                color: #aaaaaa;
                text-shadow: 0 1px rgba(255, 255, 255, 0.5);
            }

            .switch-label:after {
                content: attr(data-on);
                left: 11px;
                color: #FFFFFF;
                text-shadow: 0 1px rgba(0, 0, 0, 0.2);
                opacity: 0;
            }

            .switch-input:checked~.switch-label {
                background: #00af00;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
            }

            .switch-input:checked~.switch-label:before {
                opacity: 0;
            }

            .switch-input:checked~.switch-label:after {
                opacity: 1;
            }

            .switch-handle {
                position: absolute;
                top: 4px;
                left: 6px;
                width: 21px;
                height: 22px;
                background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0);
                background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0);
                border-radius: 100%;
                box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
            }

            .switch-handle:before {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                margin: -6px 0 0 -6px;
                width: 12px;
                height: 12px;
                background: linear-gradient(to bottom, #eeeeee, #FFFFFF);
                background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF);
                border-radius: 6px;
                box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
            }

            .switch-input:checked~.switch-handle {
                left: 39px;
                box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
            }

            .switch-label,
            .switch-handle {
                transition: All 0.3s ease;
                -webkit-transition: All 0.3s ease;
                -moz-transition: All 0.3s ease;
                -o-transition: All 0.3s ease;
            }
        </style>
        
    </head>
    <body style="">
        <div id="dashboard" class="ui loading segment" style="margin: 0px; ">
            <div class="ui message inverted">
                <span id="user_welcome"></span>
                <div style="float:right;">
                    <i class="bell icon" style="margin-top:0.2em"></i>
                    <label class="switch">
                        <input class="switch-input" type="checkbox" id="alarm_check" onchange="changeAlarmState();"/>
                        <span class="switch-label" data-on="<?=lang('common.on')?>" data-off="<?=lang('common.off')?>"></span>
                        <span class="switch-handle"></span>
                    </label>
                    <!-- <p> <i class="bell icon"></i></p> -->
			    </div>
            </div>
            <div class="ui grid top attached tabular menu grey">
                <a data-tab="my_info" class="item "><?=lang('common.info_user')?></a> 
                <a data-tab="my_charge" class="item"><?=lang('common.deposit_history')?></a>
                <a data-tab="my_exchange" class="item"><?=lang('common.withdrawal_history')?></a> 
                <?php if($_ENV['app.name'] == APP_ATM || $_ENV['app.name'] == APP_FUN || $_ENV['app.name'] == APP_DUNK) :?>
                    <a data-tab="my_point" class="item"><?=lang('common.change_point')?></a>
                <?php endif ?>
                <a data-tab="my_memo" class="item">&nbsp;&nbsp;&nbsp;&nbsp;<?=lang('common.message')?>&nbsp;&nbsp;&nbsp;&nbsp;</a>
                <a data-tab="my_qna" class="item"><?=lang('common.customer')?></a>
                <a data-tab="notice" class="item"><?=lang('common.notice_list')?></a>
            </div>
            
            <div data-tab="my_info" class="ui tab segment ">
                <table class="ui celled table no_border">
                    <tbody>
                        <tr>
                            <td class="collapsing"><?=lang('common.nickname')?> ( <?=lang('common.nickname')?> )</td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui teal label">
                                            {{ myInfo.user_id }}
                                            <div class="ui detail">( {{ myInfo.user_name }} )</div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                    <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] != 1) :?>
                                        <div id="btnChangePwd" onclick="showPwdModal();"  tabindex="0" aria-expanded="false"  class="ui tiny orange labeled icon button">
                                            <i class="key icon"></i> <span class="hideOnMobile"><?=lang('common.password_change')?></span>
                                        </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.money_site')?></td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui basic label">
                                            <div class="detail" v-html="strMoney(myInfo.user_wallet)"></div>
                                            <div class="detail"><?=lang('common.money_site')?></div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <?php if(!$user_off) :?>
                                        <div id="btnRequestCharge" onclick="showChargeModal();" tabindex="0" aria-expanded="false" class="ui tiny blue labeled icon button">
                                            <i class="cloud download icon"></i> <span class="hideOnMobile"><?=lang('common.deposit')?></span>
                                        </div>
                                        <div id="btnRequestExchange" onclick="showWithdrawModal();" tabindex="0" aria-expanded="false" class="ui tiny green labeled icon button">
                                            <i class="cloud upload icon"></i> <span class="hideOnMobile"><?=lang('common.withdrawal')?></span>
                                        </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.game_egg')?></td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui basic label">
                                            <div class="detail" v-html="strMoney(myInfo.user_egg)"></div>
                                            <div class="detail"><?=lang('common.game_egg')?></div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <div id="btnRecoveryEgg" tabindex="0" class="ui tiny yellow labeled icon button">
                                            <i class="refresh icon"></i> <span class="hideOnMobile"><?=lang('common.recovery_eggs')?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.point')?></td>
                            <td>
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="ui basic label">
                                            <div class="detail" v-html="strMoney(myInfo.user_point)"></div>
                                            <div class="detail"><?=lang('common.point')?></div>
                                        </div>
                                    </div>
                                    <div class="ten wide column">
                                        <!-- <div id="btnRequestCash" uk-toggle="target: #request_cash" tabindex="0" aria-expanded="false" class="ui tiny yellow labeled icon button"> -->
                                        <div id="btnRequestCash" tabindex="0" class="ui tiny yellow labeled icon button">
                                            <i class="refresh icon"></i> <span class="hideOnMobile"><?=lang('common.change_to_money')?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.recommender')?></td>
                            <td>
                                {{ myInfo.user_emp }}
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.phone_number')?></td>
                            <td>{{ myInfo.user_phone }}</td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.account_info')?></td>
                            <td>
                                <!-- <div class="ui divided selection list"> -->
                                <div class="ui grid">
                                    <div class="six wide column">
                                        <div class="" style="margin-bottom:5px;">
                                            <div class="ui horizontal basic label" style="width:100px"><?=lang('common.account_owner')?></div>
                                            {{ myInfo.user_bank_own }}
                                        </div>
                                        <div class="" style="margin-bottom:5px;">
                                            <div class="ui horizontal basic label" style="width:100px"><?=lang('common.bank_name')?></div>
                                            {{ myInfo.user_bank_name }}
                                        </div>
                                    <?php if($_ENV['app.name'] != APP_ATM && $_ENV['app.name'] != APP_FUN && $_ENV['app.name'] != APP_DUNK) :?>
                                        <div class="" style="margin-bottom:2px;">
                                            <div class="ui horizontal basic label" style="width:100px"><?=lang('common.account_number')?></div>
                                            {{ myInfo.user_bank_num }}
                                        </div>
                                    <?php endif ?>

                                    </div>
                                    <div class="ten wide column">
                                        <!-- <div id="btnChangePwd"  uk-toggle="target: #change_account" tabindex="0" aria-expanded="false"  class="ui tiny red labeled icon button">
                                            <i class="address book icon"></i> <span class="hideOnMobile">계좌변경</span>
                                        </div> -->
                                    </div>

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.signup_date')?></td>
                            <td>{{ myInfo.user_join_at }}</td>
                        </tr>
                        <tr>
                            <td class="collapsing"><?=lang('common.login_last')?></td>
                            <td>
                                <div class="ui divided selection list">
                                    <span class="">
                                        {{ myInfo.user_login_last }}
                                        <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] != 1) :?>
                                            <div class="ui horizontal basic label"  style="margin-left:20px;"> IP : {{ myInfo.user_ip_last }} </div>
                                        <?php endif ?>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div data-tab="my_charge" id="my_charge" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.charge"/> 
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.charge"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyChargeList"><?=lang('common.search')?></button>
                        <button class="ui tiny red right floated button" onclick="deleteCharge(0)"><i class="ui times circle icon"></i><?=lang('common.delete_all')?></button>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th><?=lang('common.type')?></th>
                            <th><?=lang('common.deposit_amount')?></th>
                            <th><?=lang('common.request_date')?></th>
                            <th><?=lang('common.depositor')?></th>
                            <th><?=lang('common.status_current')?></th>
                            <th><?=lang('common.permit_date')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in chargeList">
                            <td> {{ strChargeType(item.charge_action_state) }} </td>
                            <td v-html="strMoney(item.charge_money)"> </td>
                            <td> {{ item.charge_time_require }} </td>
                            <td> {{ item.charge_mb_realname }} </td>
                            <td v-html="strChState(item.charge_action_state)"> </td>
                            <td> {{ item.charge_time_process }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.charge"
                        :page-count="totalPageCount.charge"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationCharge"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_exchange" id="my_exchange" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.exchange"/> 
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.exchange"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyExchangeList"><?=lang('common.search')?></button>
                        <button class="ui tiny red right floated button" onclick="deleteExchange(0)"><i class="ui times circle icon"></i><?=lang('common.delete_all')?></button>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th><?=lang('common.type')?></th>
                            <th><?=lang('common.withdrawal_amount')?></th>
                            <th><?=lang('common.request_date')?></th>
                            <th><?=lang('common.account')?></th>
                            <th><?=lang('common.status_current')?></th>
                            <th><?=lang('common.permit_date')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in exchangeList">
                            <td> {{ strExchangeType(item.exchange_action_state) }} </td>
                            <td v-html="strMoney(item.exchange_money)"> </td>
                            <td> {{ item.exchange_time_require }} </td>
                            <td> {{ item.exchange_bank_name }} : {{ item.exchange_bank_account }} : {{ item.exchange_bank_serial }} </td>
                            <td v-html="strChState(item.exchange_action_state)"> </td>
                            <td> {{ item.exchange_time_process }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.exchange"
                        :page-count="totalPageCount.exchange"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationExchange"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_point" id="my_point" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.point"/> 
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.point"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyPointList"><?=lang('common.search')?></button>
                        <button class="ui tiny red right floated button" onclick="deletePoint(0)"><i class="ui times circle icon"></i><?=lang('common.delete_all')?></button>
                        <button onclick="showPointModal()" class="ui tiny green right floated button" aria-expanded="false"><i class="refresh icon"></i> <?=lang('common.change_point')?></button>
                    </div>
                </div>
                <table class="ui line table">
                    <thead>
                        <tr>
                            <th><?=lang('common.type')?></th>
                            <th><?=lang('common.changed_amount')?></th>
                            <th><?=lang('common.proceed_date')?></th>
                            <th><?=lang('common.change_before')?></th>
                            <th><?=lang('common.change_after')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in pointList">
                            <td> <?=lang('common.change_point')?> </td>
                            <td v-html="strMoney(item.money_amount)"> </td>
                            <td> {{ item.money_update_time }} </td>
                            <td v-html="strMoney(item.money_before)"> </td>
                            <td v-html="strMoney(item.money_after)"> </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.point"
                        :page-count="totalPageCount.point"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationPoint"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_memo" id="my_memo" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.memo"/> 
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.memo"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyMemoList"><?=lang('common.search')?></button>
                        <button class="ui tiny red right floated button" onclick="deleteMemo(0)"><i class="ui times circle icon"></i><?=lang('common.delete_all')?></button>
                        <button class="ui tiny green right floated button" onclick="readMemo(0)"><i class="ui check circle icon"></i><?=lang('common.read_all')?></button>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th><?=lang('common.sender')?></th>
                            <th><?=lang('common.title')?></th>
                            <th><?=lang('common.send_date')?></th>
                            <th><?=lang('common.check')?></th>
                            <th><?=lang('common.delete')?></th>
                        </tr>
                    </thead>
                    <tbody v-for="item in memoList">
                        <tr class="ui title" v-on:click="viewMemo(item.notice_fid, item.notice_read_count)">
                            <td><span><?=lang('common.administrator')?></span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                            <td><span>{{ item.notice_time_create }}</span></td>
                            <td v-html="strMsgCheck(item.notice_read_count)"> </td>
                            <td><div class="ui orange horizontal label" v-on:click="deleteMemo(item.notice_fid)"><?=lang('common.delete')?></div></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header">{{ item.notice_title }}</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.memo"
                        :page-count="totalPageCount.memo"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationMemo"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="my_qna" id="my_qna" class="ui tab segment">
                <div class="ui form">
                    <div class="inline field">
                        <div class="ui mini icon input">
                            <input type="date" v-model="start.qna"/>
                        </div>
                        <span>~</span> 
                        <div class="ui mini icon input">
                            <input type="date" v-model="end.qna"/> 
                        </div>
                        <button class="ui tiny blue button" v-on:click="getMyQnaList" style="margin-bottom:10px" ><?=lang('common.search')?></button>
                        <button class="ui tiny red right floated button" onclick="deleteCus(0)"><i class="ui times circle icon"></i><?=lang('common.delete_all')?></button>
                        <button onclick="showQnaModal()" class="ui tiny blue right floated button" aria-expanded="false"><i class="pencil alternate icon"></i> <?=lang('common.ask_1on1')?></button>
                        <button class="ui tiny green right floated button" onclick="requestAccount()"><i class="ui question circle icon"></i><?=lang('common.ask_account')?></button>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th><?=lang('common.sender')?></th>
                            <th><?=lang('common.ask_title')?></th>
                            <th><?=lang('common.ask_time')?></th>
                            <th><?=lang('common.answer')?></th>
                            <th><?=lang('common.delete')?></th>
                        </tr>
                    </thead>
                    <tbody v-for="item in qnaList">
                        <tr class="ui title" >
                            <td><span>{{ item.notice_mb_uid }}</span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                            <td><span>{{ item.notice_time_create }}</span></td>
                            <td v-html="strQnaCheck(item.notice_state_active)"> </td>
                            <td><div class="ui orange horizontal label" v-on:click="deleteCus(item.notice_fid)"><?=lang('common.delete')?></div></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header"><?=lang('common.ask_content')?></div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                    <div class="header"><?=lang('common.answer_content')?></div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_answer"></p>
                                    
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.qna"
                        :page-count="totalPageCount.qna"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationQna"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
            </div>
            <div data-tab="notice" id="notice" class="ui tab segment">
                <div class="ui form">
                    <div class="fields">
                        <div class="field">
                            <div class="inline field">
                                <div class="ui mini icon input">
                                    <input type="date" v-model="start.notice"/> 
                                </div>
                                <span>~</span> 
                                <div class="ui mini icon input">
                                    <input type="date" v-model="end.notice"/> 
                                </div>
                                <button class="ui tiny blue button" v-on:click="getNoticeList"><?=lang('common.search')?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="ui unstackable accordion celled table">
                    <thead>
                        <tr>
                            <th><?=lang('common.notice')?></th>
                            <th><?=lang('common.writer')?></th>
                            <th><?=lang('common.title')?></th>
                        </tr>
                    </thead>
                    <tbody v-for="item in noticeList">
                        <tr class="ui title" >
                            <td><span>[<?=lang('common.notice')?>]</span></td>
                            <td><span><?=lang('common.administrator')?></span></td>
                            <td><span>{{ item.notice_title }}</span> <i class="ui dropdown icon"></i></td>
                        </tr>
                        <tr>
                            <td colspan="100%" class="full-width transition hidden">
                                <div class="ui message">
                                    <div class="header">{{ item.notice_title }}</div>
                                    <p style="text-align: center;"></p>
                                    <p style=" white-space: pre-wrap;" v-html="item.notice_content"></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination_box">
                    <paginate
                        v-if="totalPageCount.notice"
                        :page-count="totalPageCount.notice"
                        :page-range="3"
                        :margin-pages="1"
                        :click-handler="paginationNotice"
                        :prev-text="'＜'"
                        :next-text="'＞'"
                        :container-class="'_pagination'"
                        :page-class="''">
                    </paginate>
                </div>
                    
            </div>
            <div id="qnaWriteModal" uk-modal="" class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="qnaForm" id="qnaForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><?=lang('common.ask_1on1')?></h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field required"><label><?=lang('common.ask_title')?></label> <input type="text" name="title" placeholder="<?=lang('common.ask_title')?>" /></div>
                            <div class="field required"><label><?=lang('common.ask_content')?></label> <textarea name="contents" rows="5" placeholder="<?=lang('common.ask_content')?>" class="ui-textarea"></textarea></div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.asking')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="change_point" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog">
                    <form name="ptchangeForm" id="ptchangeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><?=lang('common.change_point')?></h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field">
                                <label><?=lang('common.current_point')?></label>
                                <div class="ui label">
                                    <span v-html="strMoney(myInfo.user_point)"></span>
                                    &nbsp;&nbsp;&nbsp;POINT
                                </div>
                            </div>
                            <div class="field required">
                                <label><?=lang('common.request_amount')?></label> <input type="number" name="point" id="point" value="" placeholder="<?=lang('common.request_point_msg')?>" class="ui text" />
                                <div style="padding-top: 5px;">
                                    <button type="button" onclick="setMoneyField('point',1000)" class="ui inverted blue mini button"><?=lang('common.1_thousand')?></button> <button type="button" onclick="setMoneyField('point',5000)" class="ui inverted blue mini button"><?=lang('common.5_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('point',10000)" class="ui inverted blue mini button"><?=lang('common.10_thousands')?></button> <button type="button" onclick="setMoneyField('point',100000)" class="ui inverted blue mini button"><?=lang('common.100_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('point',1000000)" class="ui inverted blue mini button"><?=lang('common.1_million')?></button> 
                                    <button type="button" onclick="setMoneyField('point','max' )" class="ui inverted blue mini button">MAX</button>
                                    <button type="button" onclick="setMoneyField('point',0)" class="ui inverted blue mini button"><?=lang('common.reenter')?></button>
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.point_change_to')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                $(".menu .item").tab(); //탭메뉴 활성화
                var tab = "<?=$tab?>";
                if (tab != "") {
                    $(".menu .item").tab("change tab", tab);
                    if (tab == "charge") {
                        UIkit.modal("#request_charge").show();
                    } else if (tab == "withdraw") {
                        UIkit.modal("#request_exchange").show();
                    } else if (tab == "my_qna") {
                        objDashBoard.getMyQnaList();
                    } else if (tab == "notice") {
                        objDashBoard.getNoticeList();
                    } else if (tab == "my_memo") {
                        objDashBoard.getMyMemoList();
                    } else if (tab == "my_point") {
                        objDashBoard.getMyPointList();
                    } 
                }

                $(".menu .item").on("click", function (e) {
                    var tab = $(this).data('tab');

                    // if(objDashBoard.unreadMemo > 0){
                    //     tab = 'my_memo';
                    //     alert(langMessage.message_to_read);
                    //     $(".menu .item").tab("change tab", tab);
                    // }
                    // console.log(tab);
                    // var elem = $(".menu .item.active").tab();
                    if (tab == "my_info") {
                        objDashBoard.getMyInfo();
                    } else if (tab == "my_charge") {
                        objDashBoard.getMyChargeList();
                    } else if (tab == "my_exchange") {
                        objDashBoard.getMyExchangeList();
                    } else if (tab == "my_qna") {
                        objDashBoard.getMyQnaList();
                    } else if (tab == "notice") {
                        objDashBoard.getNoticeList();
                    } else if (tab == "my_memo") {
                        objDashBoard.getMyMemoList();
                    } else if (tab == "my_point") {
                        objDashBoard.getMyPointList();
                    } 
                });

                $('input[type="number"]').on("keypress", function (e) {
                    if (e.keyCode < 48 || e.keyCode > 57) {
                        showAlert("숫자만 입력해주세요", 3);
                        return false;
                    }
                });

                $(".ui.accordion").accordion({
                    closeNested: false,
                    exclusive: true,
                    onOpen: function () {},
                    selector: {
                        trigger: ".title",
                    },
                });

                // validate rule check
                var validationRules = {
                    cash: {
                        identifier: "cash",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.request_amount_input,
                            },
                            {
                                type: "minLength[5]",
                                prompt: langMessage.request_amount_10th,
                            },
                        ],
                    },
                    point: {
                        identifier: "point",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.request_amount_input,
                            },
                            {
                                type: "minLength[4]",
                                prompt: langMessage.request_amount_1th,
                            },
                        ],
                    },
                    req_name: {
                        identifier: "req_name",
                        rules: [
                            {
                                type: "empty",
                                prompt: "입금하실분의명칭을 입력해주세요",
                            },
                        ],
                    },
                    bank_passwd: {
                        identifier: "bank_passwd",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.withdrawal_password_input,
                            },
                        ],
                    },
                    title: {
                        identifier: "title",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.ask_title_input,
                            },
                        ],
                    },
                    contents: {
                        identifier: "contents",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.ask_content_input,
                            },
                        ],
                    },
                    pwd_old: {
                        identifier: "pwd_old",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.password_current_input,
                            }
                        ],
                    },
                    pwd_new: {
                        identifier: "pwd_new",
                        rules: [
                            {
                                type: "empty",
                                prompt: langMessage.password_new_input,
                            },
                            {
                                type: "minLength[3]",
                                prompt: "최소 3글자 이상 입력해주세요",
                            },
                        ],
                    },
                };

                $(".ui.form").form({
                    fields: validationRules,
                    inline: true,
                    on: "submit",
                    onSuccess: function (event) {
                        return true;
                    },
                });

                $("#chargeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/register_charge",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chargeForm").valid();
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            showAlert(langMessage.deposit_success);
                            UIkit.modal("#request_charge").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                });

                $("#exchangeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/register_exchange",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#exchangeForm").valid();
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            showAlert(langMessage.withdrawal_success);
                            objDashBoard.getMyInfo();
                            UIkit.modal("#request_exchange").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                });

                $("#ptchangeForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/change_point",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#ptchangeForm").valid();
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            if(window.parent){
                                window.parent.postMessage('change_point', '*');
                            }
                            showAlert(langMessage.change_point_result);
                            objDashBoard.getMyInfo();
                            objDashBoard.getMyPointList();
                            UIkit.modal("#change_point").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                });

                $("#chgpwdForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/change_pass",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chgpwdForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            showAlert(langMessage.password_change_ok);
                            UIkit.modal("#change_pwd").hide();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                //request changing of account
                $("#chgaccForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/change_acc",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#chgaccForm").valid();
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.status == "success") {
                            showAlert("계좌정보가 변경되었습니다.");
                            objDashBoard.getMyInfo();
                        } else if (response.status == "fail") {
                            showAlert(response.msg, 0);
                        } else if (response.status == "logout") {
                            reloadPage();
                        }
                    },
                    error: function(request, status, error) {
                        // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });

                $("#btnRequestCash").on("click", function(e) {

                    UIkit.modal.confirm(langMessage.change_point_request, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                        function () {
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: "/api/change_point",
                                // data: $(this).serialize(),
                                success: function (response) {
                                    if (response.status == "success") {
                                        if(window.parent){
                                            window.parent.postMessage('change_point', '*');
                                        }
                                        showAlert(langMessage.change_point_result);
                                        objDashBoard.getMyInfo();
                                    } else {
                                        // showAlert(response.msg, 0);
                                    }
                                },
                            });
                        },
                        function () {
                            //취소
                        }
                    );
                   
                });

                $("#btnRecoveryEgg").on("click", function(e) {

                    UIkit.modal.confirm(langMessage.recovery_eggs_request, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                        function () {
                            $("#dashboard").addClass('loading');
                            $.ajax({
                                dataType: "json",
                                type: "POST",
                                url: "/api/change_egg",
                                // data: $(this).serialize(),
                                success: function (response) {
                                    $("#dashboard").removeClass('loading');
                                    if (response.status == "success") {
                                        showAlert(langMessage.recovery_eggs_result);
                                        objDashBoard.getMyInfo();
                                    } else {
                                        // showAlert(response.msg, 0);
                                    }
                                }, 
                                error: function(request, status, error) {
                                    $("#dashboard").removeClass('loading');
                                }
                            });
                        },
                        function () {
                            //취소
                        }
                    );
                });
                
                $("#qnaForm").ajaxForm({
                    dataType: "json",
                    type: "POST",
                    url: "/api/write_customer",
                    data: $(this).serialize(),
                    beforeSubmit: function () {
                        return $("#qnaForm").valid();
                    },
                    success: function (response) {
                        if (response.status == "success") {
                            showAlert(langMessage.ask_receive);
                            objDashBoard.getMyQnaList();
                            UIkit.modal("#qnaWriteModal").hide();
                        } else {
                            showAlert(response.msg, 0);
                        }
                    },
                });
            });

            function showPwdModal() {
                $("#pwd_old").val('');
                $("#pwd_new").val('');
                UIkit.modal("#change_pwd").show();
            }

            function showChargeModal() {
                $("#cash").val('');
                UIkit.modal("#request_charge").show();
            }

            function showWithdrawModal() {
                objMain.getMyInfo();
                $("#cash_out").val('');
                $("#bank_passwd").val('');
                UIkit.modal("#request_exchange").show();
            }

            function showPointModal() {
                objDashBoard.getMyInfo();
                $("#point").val('');
                UIkit.modal("#change_point").show();
            }

            function showQnaModal() {
                $("#qnaWriteModal input[name=title]").val('');
                $("#qnaWriteModal textarea[name=contents]").val('');
                UIkit.modal("#qnaWriteModal").show();
            }

            function requestAccount() {

                let title = "["+langMessage.ask_quick+"] "+langMessage.deposit_account_request;
                let content = ""+langMessage.ask_quick+" : "+langMessage.deposit_account_request;
                if (confirm(langMessage.deposit_account_ask) == false) return false;
                
                $.post(
                    "/api/request_account3",
                    {
                        title: title,
                        content: content,
                    },
                    function (response) {
                        if (response.status == "success") {
                            showAlert(langMessage.deposit_account_answer);
                            objDashBoard.getMyQnaList();
                        } else {
                            showAlert(response.message, 0);
                        }
                    },
                    "json"
                );
            }
                  
            function readMemo(idx) {
                UIkit.modal.confirm(langMessage.read_all_ask, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/check_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyMemoList();
                                    if(window.parent){
                                        window.parent.postMessage('change_point', '*');
                                    }
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }
            function deleteMemo(idx) {
                if(idx==0 && objDashBoard.unreadMemo > 0){
                    showAlert(langMessage.delete_check, 3);
                    return;
                }

                let msg = langMessage.delete_ask;
                if(idx == 0)
                    msg = langMessage.delete_all_ask;

                UIkit.modal.confirm(msg, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/delete_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyMemoList();
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }

            function deleteCus(idx) {

                let msg = langMessage.delete_ask;
                if(idx == 0)
                    msg = langMessage.delete_all_ask;

                UIkit.modal.confirm(msg, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/delete_customer",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyQnaList();
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }

            function deleteCharge(idx) {

                let msg = langMessage.delete_ask;
                if(idx == 0)
                    msg = langMessage.delete_all_ask;

                UIkit.modal.confirm(msg, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/delete_charge",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyChargeList();
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }
            
            function deleteExchange(idx) {

                let msg = langMessage.delete_ask;
                if(idx == 0)
                    msg = langMessage.delete_all_ask;

                UIkit.modal.confirm(msg, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/delete_exchange",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyExchangeList();
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }

            function deletePoint(idx) {

                let msg = langMessage.delete_ask;
                if(idx == 0)
                    msg = langMessage.delete_all_ask;

                UIkit.modal.confirm(msg, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                    function () {
                        $.post(
                            "/api/delete_point",
                            {
                                idx: idx,
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.getMyPointList();
                                } else {
                                    showAlert(response.msg, 0);
                                }
                            },
                            "json"
                        );
                    },
                    function () {}
                );
            }

            function changeAlarmState() {
                stopAlarm();
                if($("#alarm_check").length == 0)
                    return;

                var alarmState = $("#alarm_check").prop('checked') ? 1 : 0;

                var jsData = { "mb_state_alarm": alarmState }
                var jsonData = JSON.stringify(jsData);
                $.ajax({
                    type: "POST",
                    data: { json_: jsonData },
                    dataType: "json",
                    url: "/api/change_alarmstate",
                    success: function(jResult) {
                        // console.log(jResult);
                        if (jResult.status == "success") {

                        } else if (jResult.status == "fail") {

                        } else if (jResult.status == "logout") {
                            // window.location.replace( FURL +"/");
                        }
                    },
                    error: function(request, status, error) {
                        //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                    }
                });

            }

            var mAudio = new Audio();
            var mAlarm = {name:'<?=$alarm_name?>', volume: <?=$alarm_volume?>};
            
            function playAlarm(){
                stopAlarm();
                if(!$("#alarm_check").prop('checked'))
                    return;
                mAudio.src = '/sound/' +mAlarm.name;
                let nVolume = 1;
                if (parseInt(mAlarm.volume) <= 100) {
                    nVolume = mAlarm.volume / 100.0;
                }
                mAudio.volume = nVolume;
                mAudio.load();
                mAudio.play();
            } 
            function stopAlarm(){
                if(mAudio)
                    mAudio.pause();
            }
        </script>

        <script>
            var objDashBoard = new Vue({
                el: "#dashboard",
                data: {
                    myInfo: [],
                    inSafe: 0,
                    unreadMemo: 0,
                    chargeList: [],
                    exchangeList: [],
                    bettingList: [],
                    cashList: [],
                    pointList: [],
                    memoList: [],
                    qnaList: [],
                    noticeList: [],
                    eventList: [],
                    favoriteQuestionList: [],
                    countAll: {
                        charge: 0,
                        exchange: 0,
                        betting: 0,
                        cash: 0,
                        point: 0,
                        memo: 0,
                        qna: 0,
                        notice: 0,
                        event: 0,
                    },
                    curPage: {
                        charge: 1,
                        exchange: 1,
                        betting: 1,
                        cash: 1,
                        point: 1,
                        memo: 1,
                        qna: 1,
                        notice: 1,
                        event: 1,
                    },
                    totalPageCount: {
                        charge: 1,
                        exchange: 1,
                        betting: 1,
                        cash: 1,
                        point: 1,
                        memo: 1,
                        qna: 1,
                        notice: 1,
                        event: 1,
                    },
                    rowCount: 5,
                    start: {
                        charge: "<?=$start_at?>",
                        exchange: "<?=$start_at?>",
                        betting: "<?=$start_at?>",
                        cash: "<?=$start_at?>",
                        point: "<?=$start_at?>",
                        memo: "<?=$start_at?>",
                        qna: "<?=$start_at?>",
                        notice: "<?=$start_at?>",
                        event: "<?=$start_at?>",
                    },
                    end: {
                        charge: "<?=$end_at?>",
                        exchange: "<?=$end_at?>",
                        betting: "<?=$end_at?>",
                        cash: "<?=$end_at?>",
                        point: "<?=$end_at?>",
                        memo: "<?=$end_at?>",
                        qna: "<?=$end_at?>",
                        notice: "<?=$end_at?>",
                        event: "<?=$end_at?>",
                    },
                },
                methods: {
                    paginationCharge: function (pageNum) {
                        this.curPage.charge = pageNum;
                        this.getMyChargeList();
                    },
                    paginationExchange: function (pageNum) {
                        this.curPage.exchange = pageNum;
                        this.getMyExchangeList();
                    },
                    paginationBetting: function (pageNum) {
                        this.curPage.betting = pageNum;
                        this.getBettingList();
                    },
                    paginationCash: function (pageNum) {
                        this.curPage.betting = pageNum;
                        this.getMyCashList();
                    },
                    paginationPoint: function (pageNum) {
                        this.curPage.point = pageNum;
                        this.getMyPointList();
                    },
                    paginationMemo: function (pageNum) {
                        this.curPage.memo = pageNum;
                        this.getMyMemoList();
                    },
                    paginationQna: function (pageNum) {
                        this.curPage.qna = pageNum;
                        this.getMyQnaList();
                    },
                    paginationNotice: function (pageNum) {
                        this.curPage.notice = pageNum;
                        this.getNoticeList();
                    },
                    paginationEvent: function (pageNum) {
                        this.curPage.event = pageNum;
                        this.getEventList();
                    },
                    getMyInfo: function () {
                        $.get(
                            FURL + "/api/myinfo",
                            function (response) {
                                $("#dashboard").removeClass('loading');
                                if (response.status == "success") {
                                    objDashBoard.myInfo = response.data;

                                    $("#user_welcome").text(`${ objDashBoard.myInfo.user_id } (${ objDashBoard.myInfo.user_name }) <?=lang('common.nice_meet')?>.`);
                                    if($("#alarm_check").length > 0)
                                        $("#alarm_check").prop('checked', objDashBoard.myInfo.user_alarm == 1);
                                } 
                            },
                            "json"
                        );
                    },
                    getMyChargeList: function () {
                        $.get(
                            "/api/page_charge",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.charge,
                                start_at: this.start.charge + " 00:00:00",
                                end_at: this.end.charge + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.chargeList = response.rows;
                                    objDashBoard.countAll.charge = response.totalRows;
                                    objDashBoard.totalPageCount.charge = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } 
                            },
                            "json"
                        );
                    },
                    getMyExchangeList: function () {
                        $.get(
                            "/api/page_exchange",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.exchange,
                                start_at: this.start.exchange + " 00:00:00",
                                end_at: this.end.exchange + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.exchangeList = response.rows;
                                    objDashBoard.countAll.exchange = response.totalRows;
                                    objDashBoard.totalPageCount.exchange = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } 
                            },
                            "json"
                        );
                    },
                    getMyPointList: function () {
                        $.get(
                            "/api/page_point",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.point,
                                start_at: this.start.point + " 00:00:00",
                                end_at: this.end.point + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.pointList = response.rows;
                                    objDashBoard.countAll.point = response.totalRows;
                                    objDashBoard.totalPageCount.point = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                } 
                            },
                            "json"
                        );
                    },
                    getMyCashList: function () {
                        // $.get(
                        //     "/mypage/cash",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.cash,
                        //         start_at: this.start.cash + " 00:00:00",
                        //         end_at: this.end.cash + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "200") {
                        //             objDashBoard.cashList = response.rows;
                        //             objDashBoard.countAll.cash = response.totalRows;
                        //             objDashBoard.totalPageCount.cash = Math.ceil(response.totalRows / objDashBoard.rowCount);
                        //         } 
                        //     },
                        //     "json"
                        // );
                    },
                    getBettingList: function () {
                        // $.get(
                        //     "/mypage/bet",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.betting,
                        //         start_at: this.start.betting + " 00:00:00",
                        //         end_at: this.end.betting + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "200") {
                        //             objDashBoard.bettingList = response.rows;
                        //             objDashBoard.countAll.betting = response.totalRows;
                        //             objDashBoard.totalPageCount.betting = Math.ceil(response.totalRows / objDashBoard.rowCount);
                        //         } 
                        //     },
                        //     "json"
                        // );
                    },
                    getMyMemoList: function () {
                        $.get(
                            "/api/page_message",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.memo,
                                start_at: this.start.memo + " 00:00:00",
                                end_at: this.end.memo + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.memoList = response.rows;
                                    objDashBoard.countAll.memo = response.totalRows;
                                    objDashBoard.totalPageCount.memo = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                    objDashBoard.unreadMemo = response.unread;
                                    if(objDashBoard.unreadMemo > 0)
                                        playAlarm();
                                    else stopAlarm();
                                } 
                            },
                            "json"
                        );
                    },
                    getMyQnaList: function () {
                        $.get(
                            "/api/page_customer",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.qna,
                                start_at: this.start.qna + " 00:00:00",
                                end_at: this.end.qna + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.qnaList = response.rows;
                                    objDashBoard.countAll.qna = response.totalRows;
                                    objDashBoard.totalPageCount.qna = Math.ceil(response.totalRows / objDashBoard.rowCount);
                                    if(response.unread > 0)
                                        playAlarm();
                                    else stopAlarm();

                                    setTimeout(openFirstNotice, 500);

                                } 
                            },
                            "json"
                        );
                    },
                    getNoticeList: function () {
                        $.get(
                            "/api/page_notice",
                            {
                                rowCount: this.rowCount,
                                page: this.curPage.notice,
                                start_at: this.start.notice + " 00:00:00",
                                end_at: this.end.notice + " 23:59:59",
                            },
                            function (response) {
                                if (response.status == "success") {
                                    objDashBoard.noticeList = response.rows;
                                    objDashBoard.countAll.notice = response.totalRows;
                                    objDashBoard.totalPageCount.notice = Math.ceil(response.totalRows / objDashBoard.rowCount);

                                    setTimeout(openFirstNotice, 500);
                                } 
                            },
                            "json"
                        );
                    },
                    getEventList: function () {
                        // $.get(
                        //     "/mypage/event",
                        //     {
                        //         rowCount: this.rowCount,
                        //         page: this.curPage.event,
                        //         start_at: this.start.event + " 00:00:00",
                        //         end_at: this.end.event + " 23:59:59",
                        //     },
                        //     function (response) {
                        //         if (response.status == "success") {
                        //             objDashBoard.eventList = response.rows;
                        //             objDashBoard.countAll.event = response.totalRows;
                        //             objDashBoard.totalPageCount.event = Math.ceil(response.totalRows / objDashBoard.rowCount);

                        //             setTimeout(openFirstEvent, 500);
                        //         } 
                        //     },
                        //     "json"
                        // );
                    },
                    changeSafe: function () {
                        // if (this.inSafe < 0) {
                        //     showAlert("금고에 보관하실 금액을 0보다 크게 입력해주세요", 3);
                        //     return;
                        // }
                        // UIkit.modal.confirm("금고보관금액을 " + this.myInfo.cash_safe + " 에서 " + this.inSafe + " 로 변경하시겠습니까?").then(
                        //     function () {
                        //         $.post(
                        //             "/mypage/insafe",
                        //             { insafe: objDashBoard.inSafe },
                        //             function (response) {
                        //                 if (response.status == 200) {
                        //                     showAlert("Success");
                        //                     objDashBoard.getMyInfo();
                        //                 } else {
                        //                     showAlert(response.message, 0);
                        //                 }
                        //             },
                        //             "json"
                        //         );
                        //     },
                        //     function () {
                        //         //취소
                        //     }
                        // );
                    },
                    viewMemo: function (idx, read) {
                        if(read > 0)
                            return;
                        console.log("viewMemo="+idx);
                        $.post(
                            "/api/check_message",
                            {
                                idx: idx,
                            },
                            function (response) {
                                objDashBoard.getMyMemoList();
                                if(window.parent){
                                    window.parent.postMessage('change_point', '*');
                                }
                            },
                            "json"
                        );
                    
                    },
                    deleteMemo: function (idx) {
                        UIkit.modal.confirm(langMessage.delete_ask, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                            function () {
                                $.post(
                                    "/api/delete_message",
                                    {
                                        idx: idx,
                                    },
                                    function (response) {
                                        if (response.status == "success") {
                                            objDashBoard.getMyMemoList();
                                        } else {
                                            showAlert(response.msg, 0);
                                        }
                                    },
                                    "json"
                                );
                            },
                            function () {}
                        );
                    },
                    deleteCus: function (idx) {
                        UIkit.modal.confirm(langMessage.delete_ask, {labels: {'ok': langMessage.ok, 'cancel': langMessage.cancel}}).then(
                            function () {
                                $.post(
                                    "/api/delete_customer",
                                    {
                                        idx: idx,
                                    },
                                    function (response) {
                                        if (response.status == "success") {
                                            objDashBoard.getMyQnaList();
                                        } else {
                                            showAlert(response.msg, 0);
                                        }
                                    },
                                    "json"
                                );
                            },
                            function () {}
                        );
                    },
                    getFavoriteQuestion: function () {
                        // $.get(
                        //     "/api/qna/favorite/question",
                        //     {},
                        //     function (response) {
                        //         objDashBoard.favoriteQuestionList = response.rows;
                        //     },
                        //     "json"
                        // );
                    },
                    fastQuestion: function (index, idx) {
                        // let title = this.favoriteQuestionList[index].title;
                        // let contents = "빠른문의 : " + this.favoriteQuestionList[index].title;
                        // if (confirm("문의 : " + title + " \n를 보내시겠습니까?") == false) return false;

                        // $.post(
                        //     "/mypage/qna",
                        //     {
                        //         title: title,
                        //         contents: contents,
                        //     },
                        //     function (response) {
                        //         if (response.status == 200) {
                        //             showAlert("정상적으로 처리 되었습니다");
                        //             objDashBoard.getMyQnaList();
                        //         } else {
                        //             showAlert(response.message, 0);
                        //         }
                        //     },
                        //     "json"
                        // );
                    },
                    viewQna: function (idx) {
                        // $.post(
                        //     "/mypage/qna/view",
                        //     {
                        //         idx: idx,
                        //     },
                        //     function (response) {
                        //         try {
                        //             $("#qna_unread_" + idx).remove();
                        //             --objDashBoard.myInfo.answered_qna;
                        //         } catch (e) {}
                        //     },
                        //     "json"
                        // );
                    },
                    strChargeType: function (type){
                        if(type == 5){
                            return langMessage.deposit_quick;
                        } else return langMessage.deposit_permit;
                    },
                    strExchangeType: function (type){
                        if(type == 5){
                            return langMessage.withdrawal_quick;
                        } else return langMessage.withdrawal_permit;
                    },
                    strChState: function (type){
                        if(type == 2){
                            return "<span class='green'>"+langMessage.status_permit+"</span>";
                        } else if(type == 3){
                            return "<span class='red'>"+langMessage.status_refuse+"</span>";
                        } else if(type == 5){
                            return langMessage.status_complete;
                        } else return "<span class='yellow'>"+langMessage.status_wait+"</span>";
                    },
                    strMsgCheck: function (read){
                        if(read == 0){
                            return "<span class='red'>"+langMessage.status_uncheck+"</span>";
                        } else {
                            return "<span >"+langMessage.status_check+"</span>";
                        } 
                    },
                    strQnaCheck: function (state){
                        if(state == 0){
                            return "<span class='yellow'>"+langMessage.status_wait+"</span>";
                        } else if(state == 1) {
                            return "<span class='green'>"+langMessage.status_complete+"</span>";
                        } else 
                            return "<span ></span>";
                    },
                    strMoney: function (amount){
                        if(amount !== undefined)
                            return parseInt(amount).toLocaleString();
                        return "0";
                    },
                },
                mounted: function () {

                    this.getMyInfo();
                    // this.getFavoriteQuestion();
                },
                filters: {
                    number_format: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0,
                        }).format(value);
                    },
                    number_format2: function (value) {
                        return new Intl.NumberFormat("ko-KR", {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2,
                        }).format(value);
                    },
                },
            });

            function openFirstNotice() {
                $(".ui.title").first().trigger("click");
            }

            function openFirstEvent() {
                $(".ui.title").first().trigger("click");
            }
        </script>

        <div id="vue_modal">
            <div id="request_charge" uk-modal="" class="uk-modal" style="" tabindex="-1">
                <div class="uk-modal-dialog">
                    <form name="chargeForm" id="chargeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud download icon"></i> <?=lang('common.deposit_request')?></h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label><?=lang('common.request_amount')?></label> <input type="number" name="cash" id="cash" placeholder="<?=lang('common.request_deposit_msg')?>" step="10000" />
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash',10000)" class="ui inverted blue mini button"><?=lang('common.10_thousands')?></button> <button type="button" onclick="setMoneyField('cash',50000)" class="ui inverted blue mini button"><?=lang('common.50_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash',100000)" class="ui inverted blue mini button"><?=lang('common.100_thousands')?></button> <button type="button" onclick="setMoneyField('cash',500000)" class="ui inverted blue mini button"><?=lang('common.500_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash',1000000)" class="ui inverted blue mini button"><?=lang('common.1_million')?></button> <button type="button" onclick="setMoneyField('cash',0)" class="ui inverted blue mini button"><?=lang('common.reenter')?></button>
                                </div>
                            </div>
                            <div class="field required"><label><?=lang('common.depositor')?></label> <input type="text" name="req_name" placeholder="입금자 명" v-model="myInfo.user_bank_own" /></div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.deposit_request_to')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
            <div id="request_exchange" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog">
                    <form name="exchangeForm" id="exchangeForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud upload icon"></i> <?=lang('common.withdrawal_request')?></h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <div class="inline field">
                                <label><?=lang('common.current_money')?></label>
                                <div class="ui teal label">
                                    {{ myInfo.user_money }} <?=lang('common.won')?>
                                </div>
                            </div>
                            <div class="inline field required">
                                <label><?=lang('common.withdrawal_amount_in')?></label> 
                                <div class="ui input"><input type="number" name="cash" id="cash_out" placeholder="<?=lang('common.request_withdrawal_msg')?>" step="10000" /></div>
                                <div style="padding-top: 5px; text-align:right;">
                                    <button type="button" onclick="setMoneyField('cash_out',10000)" class="ui inverted blue mini button"><?=lang('common.10_thousands')?></button> <button type="button" onclick="setMoneyField('cash_out',50000)" class="ui inverted blue mini button"><?=lang('common.50_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash_out',100000)" class="ui inverted blue mini button"><?=lang('common.100_thousands')?></button> <button type="button" onclick="setMoneyField('cash_out',500000)" class="ui inverted blue mini button"><?=lang('common.500_thousands')?></button>
                                    <button type="button" onclick="setMoneyField('cash_out',1000000)" class="ui inverted blue mini button"><?=lang('common.1_million')?></button> <button type="button" onclick="setMoneyField('cash_out',0)" class="ui inverted blue mini button"><?=lang('common.reenter')?></button>
                                </div>
                            </div>
                            <h4 class="ui dividing teal header"><?=lang('common.withdrawal_information')?></h4>
                            <div class="inline field">
                            <label style="min-width:80px; margin-right:0px;"><?=lang('common.account_owner')?></label>
                                <div class="ui input"><input type="text" readonly="readonly" name="bank_owner" v-model="myInfo.user_bank_own" /></div>
                            </div>
                            <div class="inline field">
                            <label style="min-width:80px; margin-right:0px;"><?=lang('common.bank_name')?></label>
                                <div class="ui input"><input type="text" name="bank_name" readonly="readonly" v-model="myInfo.user_bank_name" /></div>
                            </div>
                            <div class="inline field">
                            <label style="min-width:80px; margin-right:0px;"><?=lang('common.account_number')?></label>
                                <div class="ui input"><input type="text" readonly="readonly" v-model="myInfo.user_bank_num"/> </div>
                            </div>
                            <div class="inline field">
                            <label style="min-width:80px; margin-right:0px;"><?=lang('common.withdrawal_pwd')?></label>
                                <div class="ui input"><input type="text" name="bank_passwd" id="bank_passwd" /></div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button"><?=lang('common.withdrawal_request_to')?></div>
                            <div class="ui uk-modal-close button"><?=lang('common.cancel')?></div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
            <div id="change_account" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog">
                    <form name="chgaccForm" id="chgaccForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title"><i class="ui cloud upload icon"></i> 계좌변경</h3></div>
                        <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                        </button>
                        <div class="uk-modal-body">
                            <h4 class="ui dividing teal header">계좌변경은 출금비번으로만 변경가능합니다.</h4>
                            <div class="inline field">
                                <label>계좌주 &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <div class="ui input"><input type="text" name="bank_owner"/></div>
                            </div>
                            <div class="inline field">
                                <label>계좌은행 </label>
                                <div class="ui input"><input type="text" name="bank_name" /></div>
                            </div>
                            <div class="inline field">
                                <label>계좌번호 </label>
                                <div class="ui input"><input type="text" name="bank_num" /> </div>
                            </div>
                            <div class="inline field">
                                <label>출금비번 </label>
                                <div class="ui input"><input type="text" name="bank_passwd" /></div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">변경하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                </div>
            </div>
            
            <div id="change_pwd" uk-modal class="uk-modal">
                <div class="uk-modal-dialog">
                    <form name="chgpwdForm" id="chgpwdForm" class="ui form equal width">
                        <div class="uk-modal-header"><h3 class="uk-modal-title">비번변경</h3></div>
                        <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                        <div class="uk-modal-body">
                            <div class="field required">
                                <label>현재 비밀번호</label>
                                <div class="ui input">
                                    <input type="text" name="pwd_old" id="pwd_old" placeholder="현재 비밀번호" /> 
                                </div>
                            </div>
                            <div class="field required">
                                <label>새 비밀번호</label>
                                <div class="ui input">
                                    <input type="text" name="pwd_new" id="pwd_new" placeholder="새 비밀번호" /> 
                                </div>
                            </div>
                        </div>
                        <div class="uk-modal-footer">
                            <div class="ui primary submit button">변경하기</div>
                            <div class="ui uk-modal-close button">취소</div>
                        </div>
                    </form>
                    <button class="uk-button uk-modal-close-default uk-icon uk-close" uk-close></button>
                </div>
            </div>
            <div id="inSafeModal" uk-modal="" class="uk-modal" tabindex="-1" style="">
                <div class="uk-modal-dialog ui form">
                    <div class="uk-modal-header"><h3 class="uk-modal-title">금고보관금액 변경</h3></div>
                    <button uk-close="" class="uk-button uk-modal-close-default uk-icon uk-close">
                    </button>
                    <div class="uk-modal-body">
                        <div class="field">
                            <div class="ui large teal label">
                                Cash
                                <div class="detail">0</div>
                            </div>
                        </div>
                        <div class="field required">
                            <div class="ui large labeled input">
                                <label for="amount" class="ui label">금고보관금액(최종)</label> <input type="number" name="amount" id="amount" placeholder="최종 금고에 보관될 금액을 만원단위로 입력해주세요" step="10000" />
                            </div>
                            <div style="padding-top: 5px;">
                                <button type="button" onclick="setMoneyField('amount',10000)" class="ui inverted blue mini button">1만</button> <button type="button" onclick="setMoneyField('amount',50000)" class="ui inverted blue mini button">5만</button>
                                <button type="button" onclick="setMoneyField('amount',100000)" class="ui inverted blue mini button">10만</button> <button type="button" onclick="setMoneyField('amount',500000)" class="ui inverted blue mini button">50만</button>
                                <button type="button" onclick="setMoneyField('amount',1000000)" class="ui inverted blue mini button">100만</button> <button type="button" onclick="setMoneyField('amount',0)" class="ui inverted blue mini button">다시입력</button>
                            </div>
                        </div>
                        <div class="ui mini red basic label">최종 금고에 보관될 금액을 입력해주세요</div>
                    </div>
                    <div class="uk-modal-footer">
                        <button class="ui primary submit button">보관하기</button>
                        <div class="ui uk-modal-close button">취소</div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var objMain = new Vue({
                el: "#vue_modal",
                data: {
                    myInfo: [],
                },
                methods: {
                    getMyInfo: function () {
                        $.get(
                            "/api/myinfo",
                            function (response) {
                                if (response.status == "success") {
                                    objMain.myInfo = response.data;
                                }  else if (response.status == "logout") {
                                    // console.log("myinfo logout");
                                }
                            },
                            "json"
                        );
                    },
                },
                mounted: function () {
                    this.getMyInfo();
                },
            });
        </script>
    </body>
</html>
