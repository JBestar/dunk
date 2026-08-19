<div class="main-sidebar" id = "main-sidebar-id">
  
  <button class="main-dropdown-btn <?=$confdropdownbtn?>"><i class="glyphicon glyphicon-home"></i>  기본설정
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$confdropdown?> >
    <?php if($mb_level >= LEVEL_ADMIN) {  ?>
    <a href="<?php echo siteFurl().'home/conf_site';?>" class="<?=$conf_site?>"><i class="glyphicon glyphicon-cog"></i>  본사설정</a>
    <?php if(!$cas_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_casino';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$evol_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_evol';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$slot_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_slot_1';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$pbg_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_powerball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$bpg_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_bogleball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$eos5_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_eos5ball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$eos3_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_eos3ball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$coin5_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_coin5ball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$coin3_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_coin3ball';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php elseif(!$hold_deny) :?>
      <a href="<?php echo siteFurl().'home/conf_hold';?>" class="<?=$conf_game?>"><i class="glyphicon glyphicon-play-circle"></i>  게임설정</a>
    <?php endif ?>  

      <?php if(isEBalMode()) :?>
        <a href="<?php echo siteFurl().'home/conf_ebal';?>" class="<?=$conf_ebal?>"><i class="glyphicon glyphicon-record"></i> 에볼밸런스</a>
      <?php endif ?>  
      <?php if(array_key_exists('app.pbal', $_ENV) && $_ENV['app.pbal'] > 0) :?>
        <a href="<?php echo siteFurl().'home/conf_pbal';?>" class="<?=$conf_pbal?>"><i class="glyphicon glyphicon-record"></i> 프라그밸런스</a>
      <?php endif ?>  
    
    <?php } ?>
    <a href="<?php echo siteFurl().'home/conf_password';?>" class="<?=$conf_password?>"><i class="glyphicon glyphicon-lock"></i> 정보변경</a>

    <?php if($follow_en) :  ?>
      <a href="<?php echo siteFurl().'home/conf_follow';?>" class="<?=$conf_follow?>"><i class="glyphicon glyphicon-screenshot"></i> 따라가기</a>
    <?php endif ?>

  </div>
  
  <button class="main-dropdown-btn <?=$userdropdownbtn?>"><i class="glyphicon glyphicon-user"></i>  회원관리
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$userdropdown?>>

    <?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) :?>
      <a href="<?php echo siteFurl().'user/member_list/0';?>" class="<?=$user_member?>"><i class="glyphicon glyphicon-cd"></i> 회원관리</a>
      <?php if($mb_level >= LEVEL_ADMIN) :  ?>
        <a href="<?php echo siteFurl().'user/member_list2/0';?>" class="<?=$user_ctrl2?>"><i class="glyphicon glyphicon-cd"></i> 회원관리Ⅱ</a>
      <?php endif  ?>
    <?php else:  ?>
      <a href="<?php echo siteFurl().'user/member/0';?>" class="<?=$user_member?>"><i class="glyphicon glyphicon-cd"></i> 회원관리</a>
    <?php endif  ?>
  
    <?php if($mb_level >= LEVEL_ADMIN) :  ?>
    
    <?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) :?>
      <a href="<?php echo siteFurl().'user/member_class/0';?>" class="<?=$user_ctrl?>"><i class="glyphicon glyphicon-cd"></i> 매장전용</a>
    <?php else:  ?>
      <a href="<?php echo siteFurl().'user/member_ctrl/0';?>" class="<?=$user_ctrl?>"><i class="glyphicon glyphicon-cd"></i> 매장전용</a>
    <?php endif  ?>

    <a href="<?php echo siteFurl().'user/member_connect';?>" class="<?=$user_log?>"><i class="glyphicon glyphicon-time"></i> 실시간접속</a>
    <?php if(array_key_exists('app.tree', $_ENV) && $_ENV['app.tree'] == 1) :?>
      <a href="<?php echo siteFurl().'user/member_ip';?>" class="<?=$user_ip?>"><i class="glyphicon glyphicon-info-sign"></i> 아이피관리</a>
    <?php endif  ?>
    <a href="<?php echo siteFurl().'user/member_block';?>" class="<?=$user_block?>"><i class="glyphicon glyphicon-ban-circle"></i> 블록아이피</a>
    <?php endif  ?>
  </div>
  
  <button class="main-dropdown-btn <?=$bankdropdownbtn?>"><i class="glyphicon glyphicon-usd"></i>  충환전관리
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$bankdropdown?>>
    <?php if($mb_level >= LEVEL_ADMIN) :  ?>
    <a href="<?php echo siteFurl().'bank/deposit';?>" class="<?=$bank_deposit?>"><i class="glyphicon glyphicon-plus-sign"></i>  충전관리</a>
    <a href="<?php echo siteFurl().'bank/withdraw';?>" class="<?=$bank_withdraw?>"><i class="glyphicon glyphicon-minus-sign"></i>  환전관리</a>
    <?php endif ?>
    <?php if($mb_level >= LEVEL_ADMIN || $_ENV['app.name'] != APP_DUNK) :?>
    <a href="<?php echo siteFurl().'bank/exchange';?>" class="<?=$bank_exchange?>"><i class="glyphicon glyphicon-ok-sign"></i> 머니거래내역</a>
    <?php endif ?>
    <a href="<?php echo siteFurl().'bank/transfer';?>" class="<?=$bank_transfer?>"><i class="glyphicon glyphicon-transfer"></i>  머니이동내역</a>
  </div>

  <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] == 0) :?>
    <?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny) :?>
      <?php if($mb_level >= LEVEL_ADMIN) {  ?>
      <button class="main-dropdown-btn  <?=$resultdropdownbtn?>"><i class="glyphicon glyphicon-th-list"></i>  게임관리
        <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
      </button>
      <div class="main-dropdown-container" <?=$resultdropdown?>>
          <?php if(!$pbg_deny) :?>
            <a href="<?php echo siteFurl().'result/pbresult';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/pbbetchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php elseif(!$bpg_deny) :?>
            <a href="<?php echo siteFurl().'result/bbresult';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/bbbetchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php elseif(!$eos5_deny) :?>
            <a href="<?php echo siteFurl().'result/e5result';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/e5betchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php elseif(!$eos3_deny) :?>
            <a href="<?php echo siteFurl().'result/e3result';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/e3betchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php elseif(!$coin5_deny) :?>
            <a href="<?php echo siteFurl().'result/c5result';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/c5betchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php else :?>
            <a href="<?php echo siteFurl().'result/c3result';?>" class="<?=$gameresult?>"><i class="glyphicon glyphicon-book"></i>  게임결과</a>
            <a href="<?php echo siteFurl().'result/c3betchange/0/0';?>" class="<?=$gameedit?>"><i class="glyphicon glyphicon-tag"></i>  적중특례</a>
          <?php endif ?>   
      </div>
      <?php } ?>
    <?php endif ?>   
  <?php endif ?>   

  <button class="main-dropdown-btn  <?=$betdropdownbtn?>"><i class="glyphicon glyphicon-refresh"></i>  배팅
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
  </button>
  <div class="main-dropdown-container" <?=$betdropdown?>>

  <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] == 0) :?>
    <?php if(!$pbg_deny || !$bpg_deny || !$eos5_deny || !$eos3_deny || !$coin5_deny || !$coin3_deny) :?>
      <?php if($mb_level >= LEVEL_ADMIN) {  ?>
        <?php if(!$pbg_deny) :?>
          <a href="<?php echo siteFurl().'bet/pbrealtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php elseif(!$bpg_deny) :?>
          <a href="<?php echo siteFurl().'bet/bbrealtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php elseif(!$eos5_deny) :?>
          <a href="<?php echo siteFurl().'bet/e5realtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php elseif(!$eos3_deny) :?>
          <a href="<?php echo siteFurl().'bet/e3realtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php elseif(!$coin5_deny) :?>
          <a href="<?php echo siteFurl().'bet/c5realtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php else:?>
          <a href="<?php echo siteFurl().'bet/c3realtime';?>" class="<?=$bet_realtime?>"><i class="glyphicon glyphicon-dashboard"></i>  실시간배팅</a>
        <?php endif ?>   
      <?php } ?>  
    <?php endif ?>   
  <?php endif ?>   

    <?php if(!$hold_deny) :?>
    <a href="<?php echo siteFurl().'bet/hlhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$evol_deny || !$cas_deny) :?>
    <a href="<?php echo siteFurl().'bet/cshistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(isEBalMode()) :?>
    <a href="<?php echo siteFurl().'bet/evhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$pbg_deny) :?>
    <a href="<?php echo siteFurl().'bet/pbhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$bpg_deny) :?>
    <a href="<?php echo siteFurl().'bet/bbhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$eos5_deny) :?>
    <a href="<?php echo siteFurl().'bet/e5history';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$eos3_deny) :?>
    <a href="<?php echo siteFurl().'bet/e3history';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$coin5_deny) :?>
    <a href="<?php echo siteFurl().'bet/c5history';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$coin3_deny) :?>
    <a href="<?php echo siteFurl().'bet/c3history';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php elseif(!$slot_deny) :?>
    <a href="<?php echo siteFurl().'bet/slhistory';?>" class="<?=$bet_history?>"><i class="glyphicon glyphicon-book"></i>  배팅내역</a>
    <?php endif ?>

    <?php if(!array_key_exists('app.hold', $_ENV) || $_ENV['app.hold'] == 0) :?>
      <a href="<?php echo siteFurl().'bet/allcalculate';?>" class="<?=$bet_calculate?>"><i class="glyphicon glyphicon-tag"></i> 정산내역</a>
    <?php else: ?>
      <a href="<?php echo siteFurl().'bet/hlcalculate';?>" class="<?=$bet_calculate?>"><i class="glyphicon glyphicon-tag"></i> 정산내역</a>
    <?php endif ?>   

  </div>
  <?php if($mb_level >= LEVEL_ADMIN) {  ?>
  <button class="main-dropdown-btn  <?=$boarddropdownbtn?>" ><i class="glyphicon glyphicon-envelope"></i>  게시판
    <i class="glyphicon glyphicon-chevron-right" style = "float:right; padding-top: 3px; font-size: 10px"></i>
  </button>  
  <div class="main-dropdown-container" <?=$boarddropdown?>>
    <a href="<?php echo siteFurl().'board/notice';?>" class="<?=$board_notice?>"><i class="glyphicon glyphicon-info-sign"></i>  공지사항</a>
    <a href="<?php echo siteFurl().'board/message';?>" class="<?=$board_message?>"><i class="glyphicon glyphicon-info-sign"></i>  쪽지관리</a>
  </div>
  <?php } ?>
  <a href="<?php echo siteFurl().'pages/logout';?>"><i class="glyphicon glyphicon-log-out"></i>  로그아웃</a>
</div>
