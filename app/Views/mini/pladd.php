                    <!-- content start -->
                    <div class="content content_mini">
                        <div class="game_wrap">
                            <div class="game_area_bg nsl_bg">
                            <?php if($rate->game_index == GAME_BOGLE_LADDER) { ?>
                                <div class="game_area nsl_game" style="height: 550px;">
                                    <iframe
                                        marginwidth="0"
                                        marginheight="0"
                                        frameborder="0"
                                        scrolling="no"
                                        id="game"
                                        loading="lazy"
                                        class="lazy"
                                        data-loader="frame"
                                        src="https://bepick.net/live/bubbleladder"
                                        style="transform: scale(0.851393); position: inherit; width: 830px; height: 646px;"
                                    ></iframe>
                                </div>
                                <?php } ?>
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
                                        <img class="betting_board_refresh_btn lazy" src="/images/common/refresh_m_btn.png" style="" />
                                        <button class="btn btn_hide_video"><?=lang('common.hide_screen')?></button>
                                    </div>
                                </div>
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.normal')?></span>
                                        <button class="btn btn_hide_video m_none"><?=lang('common.hide_screen')?></button>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="1">
                                                <div class="bet bet_left large btn_select" id="1" pc="NSL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_left')?></span></div> 
                                                    <span class="rate game_rate"><?=$rate->game_ratio_1?></span>
                                                </div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NSL">
                                                    <div><span class="game_side red"><?=lang('common.ball_right')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_1?></span>
                                                </div>
                                            </li>

                                            <li class="board_node" id="2">
                                                <div class="bet bet_left large btn_select" id="1" pc="NSL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_three')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_2?></span>
                                                </div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NSL">
                                                <div><span class="game_side red"><?=lang('common.ball_four')?></span></span></div>
                                                <span class="rate game_rate"><?=$rate->game_ratio_2?></span>
                                            </div>
                                            </li>

                                            <li class="board_node" id="3">
                                                <div class="bet bet_left large btn_select" id="1" pc="NSL">
                                                    <div><span class="game_side blue"><?=lang('common.ball_odd')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_3?></span>
                                                </div>
                                                <div class="vs">vs</div>
                                                <div class="bet bet_right large btn_select" id="2" pc="NSL">
                                                    <div><span class="game_side red"><?=lang('common.ball_even')?></span></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_3?></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!--//game_item -->

                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit"><?=lang('common.combination')?></span>
                                    </div>
                                    <div class="game_content">
                                        <ul>
                                            <li class="board_node" id="4">
                                                <div class="bet bet_left small btn_select" id="1" pc="NSLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_left')?></span><span class="game_side blue"><?=lang('common.ball_three')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_4?></span>
                                                </div>
                                                <div class="bet bet_left small btn_select" id="2" pc="NSLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_right')?></span><span class="game_side blue"><?=lang('common.ball_three')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_6?></span>
                                                </div>
                                                <div class="break_flex pc_none"></div>
                                                <div class="bet bet_right small btn_select" id="3" pc="NSLC">
                                                    <div><span class="game_side blue"><?=lang('common.ball_left')?></span><span class="game_side red"><?=lang('common.ball_four')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_5?></span>
                                                </div>
                                                <div class="bet bet_right small btn_select" id="4" pc="NSLC">
                                                    <div><span class="game_side red"><?=lang('common.ball_right')?></span><span class="game_side red"><?=lang('common.ball_four')?></span></div>
                                                    <span class="rate game_rate"><?=$rate->game_ratio_7?></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!--//game_item -->
                            </div>
                            <!--//col_left -->

                            <!--//col_left -->
                            <div class="col_right m_none">
                                <!--
                                <div class="game_item">
                                    <div class="game_title">
                                        <span class="tit">지난회차</span>
                                    </div>
                                    <div class="game_content game_content_with_sub" id="prev_result">
                                        
                                        <div class="sub_game_item">
                                            <div class="game_title">146</div>
                                            <div class="game_content"><span class="rst_ico blue">좌</span>&nbsp;<span class="rst_ico red">4</span>&nbsp;<span class="rst_ico blue">홀</span>&nbsp;</div>
                                        </div>
                                    </div>
                                </div>
                                //game_item -->
                            </div>
                            <!--//col_right -->

                            <div class="break_flex"></div>
                        </div>
                        <!--//betting_area -->

                        <script src="<?php echo site_furl('/js/mini/nps_res.js?v=1'); ?>"></script>
                        