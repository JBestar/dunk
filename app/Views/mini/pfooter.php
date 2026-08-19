

                        <div class="betting_board">
                            <button type="button" class="betting_board_m_close"></button>

                            <div class="betting_board_inner betting_board_slip">
                                <div class="betting_board_top">
                                    <p class="betting_board_day"><span class="betting_board_date" id="cart_date"></span><span class="betting_board_round" id="cart_round"></span></p>
                                    <p class="betting_board_time"><span class="betting_board_count" id="cart_time"></span><button type="button" class="betting_board_refresh_btn" id="refresh_game"></button></p>
                                </div>
                                <div class="betting_board_txt">
                                    <ul>
                                        <li>
                                            <span><?=lang('common.game_select')?></span>
                                            <p id="board_game"></p>
                                        </li>
                                        <li>
                                            <span><?=lang('common.game_rate')?></span>
                                            <p id="board_rate"></p>
                                        </li>
                                        <li>
                                            <span><?=lang('common.game_follow')?></span>
                                            <p>
                                                <b id="board_follow" style="color:black"></b>
                                                <button class="betting_board_follow_btn" id="follow_game"></button>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="betting_board_info">
                                    <ul>
                                        <li>
                                            <dl>
                                                <dt><?=lang('common.money')?></dt>
                                                <dd class="highlight" data-amount="<?=$user_money?>" id="u_money"><?=$user_money?> <?=lang('common.won')?></dd>
                                            </dl>
                                        </li>
                                        <li>
                                            <dl>
                                                <dt><?=lang('common.win_max')?></dt>
                                                <dd id="dist_max" data-amount="0" ></dd>
                                            </dl>
                                        </li>
                                        <li>
                                            <dl>
                                                <dt><?=lang('common.bet_min')?></dt>
                                                <dd id="bet_min" data-amount="0"></dd>
                                            </dl>
                                        </li>
                                        <li>
                                            <dl>
                                                <dt><?=lang('common.bet_max')?></dt>
                                                <dd id="bet_max" data-amount="0"></dd>
                                            </dl>
                                        </li>
                                    </ul>
                                </div>
                                <div class="betting_board_box">
                                    <div class="betting_board_box_input">
                                        <span class="tit"> <?=lang('common.amount')?></span>
                                        <input type="text" class="betting_board_box_input_bet" id="bet_money" value="0" placeholder=""  />
                                    </div>
                                    <div class="betting_board_box_input">
                                        <span class="tit"><?=lang('common.win_amount')?></span>
                                        <input type="text" class="betting_board_box_input_hit" value="0" id="hit_money_input" placeholder="" readonly="" />
                                    </div>
                                    <div class="betting_board_box_btn">
                                        <?php if(array_key_exists('bet.pan_type', $_ENV) && $_ENV['bet.pan_type'] == 1): ?>
                                            <ul>
                                                <li><button type="button" id="bet_price_10000">10,000</button></li>
                                                <li><button type="button" id="bet_price_50000">50,000</button></li>
                                                <li><button type="button" id="bet_price_100000">100,000</button></li>
                                            </ul>
                                            <ul>
                                                <li><button type="button" id="bet_price_300000">300,000</button></li>
                                                <li><button type="button" id="bet_price_500000">500,000</button></li>
                                                <li><button type="button" id="bet_price_1000000">1,000,000</button></li>
                                            </ul>
                                        <?php else: ?>
                                            <ul>
                                                <li><button type="button" id="bet_price_5000">5,000</button></li>
                                                <li><button type="button" id="bet_price_10000">10,000</button></li>
                                                <li><button type="button" id="bet_price_50000">50,000</button></li>
                                            </ul>
                                            <ul>
                                                <li><button type="button" id="bet_price_100000">100,000</button></li>
                                                <li><button type="button" id="bet_price_500000">500,000</button></li>
                                                <li><button type="button" id="bet_price_1000000">1,000,000</button></li>
                                            </ul>
                                        <?php endif ?>
                                        <ul>
                                            <li class="small"><button type="button" id="bet_price_0">MAX</button></li>
                                            <li class="reset"><button type="button" id="refresh_money"><?=lang('common.reset')?></button></li>
                                        </ul>
                                    </div>
                                    <div class="betting_board_box_input">
                                        <span class="tit"><?=lang('common.direct_input')?></span>
                                        <input type="text" id="input_money" placeholder="0" onkeydown="return checkNumber(event, this)" />
                                    </div>
                                    <button type="button" class="betting_btn" id="mini_betting"><?=lang('common.betting')?></button>
                                </div>
                            </div>
                            <!-- betting_board_slip -->
                        </div>
                        <!--//betting_board -->

                        <!-- <div class="rst_link pc_none">
                            <ul>
                                <li><a href="/retlist">지난회차결과</a></li>
                                <li><a href="/betlist">전체베팅내역</a></li>
                            </ul>
                        </div> -->
                        <script src="<?php echo site_furl('/js/mini/np_bet.js?v=1'); ?>"></script>

                        <div class="betting_wrapper">

                        </div>
                    </div>
                    <!-- content end-->
                </div>
                <!--//content_wrap -->

            </section>
            <!--//section -->
                <style>
                #layer2 .follow_input{
                    position: relative;
                    margin-top: 3px;
                }

                #layer2 .follow_input .tit{
                    color:#222;
                    position: absolute;
                    top: 5px;
                    left: 20px;
                    font-size: 12px;
                }

                #layer2 .follow_input input{
                    float: none;
                    width: 100%;
                    height: 40px;
                    line-height: 40px;
                    font-size: 13px;
                    font-weight: 700;
                    text-align: right;
                    border-radius: 10px;
                    padding: 0 19px;
                }

            </style>
            <div id="layer2" class="pop_layer" style="display:none; width: 320px;">
                <div class="pop_container">
                    <div class="pop_top">
                        <p class="tit"><?=lang('common.follower_set')?></p>
                    </div>
                    <div class="pop_con" style="min-height:150px;">
                        <div style="text-align:center; font-size:16px; line-height:30px; padding:20px;">
                            <div class="follow_input">
                                <span class="tit"><?=lang('common.id')?></span>
                                <input type="text" id="follow_uid" >
                            </div>
                            <div  class="follow_input">
                                <span class="tit"><?=lang('common.amount')?> (%)</span>
                                <input type="number" id="follow_rate" placeholder="0">
                            </div>    
                        </div>
                    </div>
                    <button type="button" class="pop_close"><span class="ir_wa"><?=lang('common.close')?></span></button>
                </div>
                <div class="btn_wrap">
                    <button type="button" class="btn btn_red pop_close"><?=lang('common.close')?></button>
                    <button type="button" class="btn" id="btn_save_follow"><?=lang('common.save')?></button>
                </div>
            </div>


            <script src="<?php echo site_furl('/js/mini/np_com.js?v=2'); ?>"></script>
            <script src="<?php echo site_furl('/js/mini/np_req.js?v=1'); ?>"></script>
            <script src="<?php echo site_furl('/js/mini/front.js?v=1'); ?>"></script>

    </div>
    <!--//wrap --> 

</body>

</html>

                        