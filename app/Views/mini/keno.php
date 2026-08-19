                

                    <!-- content start -->
                    <div class="content content_mini">
                        <div class="game_wrap">
                            <div class="game_area_bg npl_bg">
                                <?php if($rate->game_index == GAME_SPKN_BALL) : ?>
                                <div class="game_area npl_game" style="height: 550px;">
                                    <iframe
                                        marginwidth="0"
                                        marginheight="0"
                                        frameborder="0"
                                        scrolling="no"
                                        id="game"
                                        loading="lazy"
                                        class="lazy"
                                        data-loader="frame"
                                        data-width="840"
                                        data-height="650"
                                        src="https://rdombox.com/games/speedkeno"
                                        style="transform: scale(0.859375); position: inherit; width: 830px; height: 640px;"
                                    ></iframe>
                                </div>
                                <?php endif ?>
                                <!--//game_area -->
                            </div>
                            <!--//game_area_bg -->
                        </div>
                        <!--//game_wrap -->

                        <div class="betting_area betting_area_flex">
                            <div class="col_left">
                                <div class="mobile_header pc_none">
                                    <div class="tit"><span class="betting_board_date" id="board_date"></span><span class="betting_board_round color_special" id="board_round"></span></div>
                                    <div class="tit_right">
                                        <div class="time betting_board_time" id="board_time"></div>
                                        <img class="betting_board_refresh_btn lazy" src="<?php echo site_furl('/images/common/refresh_m_btn.png'); ?>" style="" />
                                        <button class="btn btn_hide_video"><?=lang('common.hide_screen')?></button>
                                    </div>
                                </div>
                                
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.powerball')?></span>
                                        <button class="btn btn_hide_video m_none"><?=lang('common.hide_screen')?></button>
                                    </div>
                                    
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="1">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_1?></span>
                                                </div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_1?></span>
                                                </div>
                                            </li>

                                            <li class="board_node" id="2">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_2?></span>
                                                </div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_2?></span>
                                                </div>
                                            </li>

                                            <li class="board_node" id="5">
                                                <div class="bet bet_left small btn_select" id="1" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_5?></span>
                                                </div>
                                                <div class="bet bet_left small btn_select" id="2" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_6?></span>
                                                </div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="3" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_7?></span>
                                                </div>
                                                <div class="bet bet_right small btn_select" id="4" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_8?></span>
                                                </div>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.superball')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="3">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_3?></span></div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_3?></span></div>
                                            </li>

                                            <li class="board_node" id="4">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_4?></span></div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_4?></span></div>
                                            </li>
                                            <li class="board_node" id="6">
                                                <div class="bet bet_left small btn_select" id="1" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_9?></span></div>
                                                <div class="bet bet_left small btn_select" id="2" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_10?></span></div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="3" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_11?></span></div>
                                                <div class="bet bet_right small btn_select" id="4" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_12?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.hupball')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="7">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_21?></span></div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_21?></span></div>
                                            </li>

                                            <li class="board_node" id="8">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_22?></span></div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NPL">
                                                    <div><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_22?></span></div>
                                            </li>
                                            <li class="board_node" id="12">
                                                <div class="bet bet_left small btn_select" id="1" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_23?></span></div>
                                                <div class="bet bet_left small btn_select" id="2" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_24?></span></div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="3" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_25?></span></div>
                                                <div class="bet bet_right small btn_select" id="4" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.ball_over')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_26?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <?php if(!array_key_exists('bet.np_deny', $_ENV) || !$_ENV['bet.np_deny']) : ?>
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.superball')?> + <?=lang('common.powerball')?> <?=lang('common.combination')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="9">
                                                <div class="bet bet_left small btn_select" id="1" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.superball_')?> <?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_13?></span></div>
                                                <div class="bet bet_left small btn_select" id="2" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.superball_')?> <?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_14?></span></div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="3" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.superball_')?> <?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_15?></span></div>
                                                <div class="bet bet_right small btn_select" id="4" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.superball_')?> <?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_16?></span></div>
                                            </li>
                                            <li class="board_node" id="9">
                                                <div class="bet bet_left small btn_select" id="5" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.superball_')?> <?=lang('common.ball_under_')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_under_')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_17?></span></div>
                                                <div class="bet bet_left small btn_select" id="6" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.superball_')?> <?=lang('common.ball_under_')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_over_')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_18?></span></div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="7" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.superball_')?> <?=lang('common.ball_over_')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_under_')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_19?></span></div>
                                                <div class="bet bet_right small btn_select" id="8" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.superball_')?> <?=lang('common.ball_over_')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_over_')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_20?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php endif ?>

                                <?php if(!array_key_exists('bet.n2p_deny', $_ENV) || !$_ENV['bet.n2p_deny']) : ?>
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.superball_mix')?> + <?=lang('common.powerball')?> <?=lang('common.ball_odd')?><?=lang('common.ball_even')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="10">
                                                <div class="bet bet_left large btn_select" id="1" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="bet bet_left large btn_select" id="2" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="break_flex pc_none"></div>
                                            </li>
                                            <li class="board_node" id="10">
                                                <div class="bet bet_right large btn_select" id="3" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.ball_over')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="bet bet_right large btn_select" id="4" pc="NPLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span><span class="game_side red"><?=lang('common.ball_over')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                   <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                            </li>
                                            <li class="board_node" id="10">
                                                <div class="bet bet_left large btn_select" id="5" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="bet bet_left large btn_select" id="6" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side blue"><?=lang('common.ball_under')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                   <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="break_flex pc_none"></div>
                                            </li>
                                            <li class="board_node" id="10">
                                                <div class="bet bet_right large btn_select" id="7" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.ball_over')?></span><span class="game_side blue"><?=lang('common.powerball_')?> <?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                                <div class="bet bet_right large btn_select" id="8" pc="NPLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span><span class="game_side red"><?=lang('common.ball_over')?></span><span class="game_side red"><?=lang('common.powerball_')?> <?=lang('common.ball_even')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_31?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php endif ?>

                                <?php if(!array_key_exists('bet.pn_deny', $_ENV) || !$_ENV['bet.pn_deny']) : ?>
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.powerball_digit')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="11">
                                                <div class="bet bet_left x_small btn_select" id="0" pc="NPLC"><span class="game_side blue"><b>0</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_right x_small btn_select" id="1" pc="NPLC"><span class="game_side red"><b>1</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_left x_small btn_select" id="2" pc="NPLC"><span class="game_side green"><b>2</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_right x_small btn_select" id="3" pc="NPLC"><span class="game_side blue"><b>3</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_left x_small btn_select" id="4" pc="NPLC"><span class="game_side red"><b>4</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                            </li>
                                            <li class="board_node" id="11">
                                                <div class="bet bet_left x_small btn_select" id="5" pc="NPLC"><span class="game_side blue"><b>5</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_right x_small btn_select" id="6" pc="NPLC"><span class="game_side red"><b>6</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_left x_small btn_select" id="7" pc="NPLC"><span class="game_side green"><b>7</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_right x_small btn_select" id="8" pc="NPLC"><span class="game_side blue"><b>8</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                                <div class="bet bet_left x_small btn_select" id="9" pc="NPLC"><span class="game_side red"><b>9</b></span><span class="rate game_rate"><?=$rate->game_ratio_30?></span></div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php endif ?>
                            </div>
                            <!--//col_left -->
                        </div>
                        <!--//betting_area -->

                        <script src="<?php echo site_furl('/js/mini/worker_sec.js'); ?>"></script>
                        <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
                            <script src="<?php echo site_furl('/js/mini/nke_res.js?v=1'); ?>"></script>
                        <?php else : ?>
                            <script src="<?php echo site_furl('/js/mini/nke_res.js?t='.time()); ?>"></script>
                        <?php endif ?>

                        