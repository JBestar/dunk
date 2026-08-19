                <!-- content start -->
                <div class="content">
                    <div class="content_inner">
                        <!--//slide_menu -->
                        <div class="slide_list">
                            <div class="slide_con on">
                                <div class="search_tab">
                                    <ul>
                                        <li><a href="javascript:;" id="<?=$dates[0]?>"class="on"><?=lang('common.today')?></a></li>
                                        <li><a href="javascript:;" id="<?=$dates[1]?>"><?=lang('common.yestoday')?></a></li>
                                        <li><a href="javascript:;" id="<?=$dates[2]?>"><?=substr($dates[2], 5)?></a></li>
                                        <li><a href="javascript:;" id="<?=$dates[3]?>"><?=substr($dates[3], 5)?></a></li>
                                        <li><a href="javascript:;" id="<?=$dates[4]?>"><?=substr($dates[4], 5)?></a></li>
                                    </ul>
                                </div>
                                <!--//search_tab -->

                                <div class="result_list" id="<?=$game_id?>">
                                    
                                </div>
                            </div>
                            <!-- //slide_con  -->
                        </div>
                        <!--//slide_list -->

                        <div class="pagenation" style="display:none;">
                            <div class="inner">
                                <button class="prev" onclick="prevPage()"></button>
                                <span id="pagenation-num">
                                    <a class="on">1</a>
                                </span>
                                <button class="next" onclick="nextPage()"></button>
                            </div>
                        </div>
                        <!--//pagenation -->
                    </div>
                    <!--//content_inner -->
                </div>
                <!-- content end -->
            </div>
            <!--//content_wrap -->
        </section>
        <!--//section -->

    <?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
        <script src="<?php echo site_furl('/js/mini/page.js?v=1'); ?>"></script>
        <script src="<?php echo site_furl('/js/mini/np_com.js?v=2'); ?>"></script>
        <script src="<?php echo site_furl('/js/mini/betlist.js?v=1'); ?>"></script>
    <?php else : ?>
        <script src="<?php echo site_furl('/js/mini/page.js?t='.time()); ?>"></script>
        <script src="<?php echo site_furl('/js/mini/np_com.js?t='.time()); ?>"></script>
        <script src="<?php echo site_furl('/js/mini/betlist.js?t='.time()); ?>"></script>
    <?php endif ?>

    </div>
    <!--//wrap --> 
</body>
</html>

