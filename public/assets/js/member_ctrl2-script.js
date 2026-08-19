var mOrdItem = '';
var mOrdDir = '';
var mArrMember = null;
var mConfs = null;

$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMember();
}


function showMember(arrMember, confs) {

    mArrMember = arrMember;
    mConfs = confs;

    let strBuf = "", strLevel = "";
    let curPage = getActivePage();
    let firstIdx = (curPage - 1) * CountPerPage;
    for (let nRow in arrMember) {
        strBuf += "<tr id ='" + arrMember[nRow].mb_fid + "'";
        if (arrMember[nRow].mb_color != null)
            strBuf += "bgcolor='" + arrMember[nRow].mb_color + "' ";
        strBuf += ">";
        strBuf += "<td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td> <td>";
        strBuf += "<a onclick='popupMemberEdit(" + arrMember[nRow].mb_fid + ", "+arrMember[nRow].mb_fid+ ")' class='link-member'>"+ arrMember[nRow].mb_uid+ "</a>";
        strBuf += "</td> <td>";
        strBuf += "<a onclick='popupMemberEdit(" + arrMember[nRow].mb_fid + ", "+arrMember[nRow].mb_fid+ ")' class='link-member'>"+ arrMember[nRow].mb_nickname+ "</a>";
        if(arrMember[nRow].mb_state_delete > 0){
            strBuf += "<br>(오프라인)";    
        }
        if(arrMember[nRow].mb_state_test > 0){
            strBuf += "<br>(테스트)";    
        }
        strBuf += "</td> <td>";
        strLevel = getMemberLevelString(arrMember[nRow].mb_level); 
        if (strLevel != null)
            strBuf += strLevel;
        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member_list2/";
        strBuf += arrMember[nRow].mb_emp_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_empname;
        strBuf += "</a>";
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_fid;
        strBuf += "</td> <td>";
        strBuf += "Lv " + parseInt(arrMember[nRow].mb_grade).toLocaleString() ;
        if(parseInt(arrMember[nRow].mb_egg) > 0 )
            strBuf += "</td><td> <span id='mm_" + arrMember[nRow].mb_fid + "' style='color:red'>";
        else strBuf += "</td><td> <span id='mm_" + arrMember[nRow].mb_fid + "' style='color:black'>";
        strBuf += Math.floor(arrMember[nRow].mb_money).toLocaleString() + "</span>";
        strBuf += '<button class="refresh_btn" onclick="refreshEgg(' + arrMember[nRow].mb_fid + ', this);"></button>';
        if (confs.emp_level >= LEVEL_ADMIN) {
            strBuf += "</td><td>";
            if(parseInt(arrMember[nRow].mb_egg) > 0 )
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>알회수</button>";
        }
        strBuf += "</td> <td id='mp_" + arrMember[nRow].mb_fid + "'>";
        strBuf += Math.floor(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].bet_sum == null)
            arrMember[nRow].bet_sum = 0;
        strBuf += Math.floor(arrMember[nRow].bet_sum).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].win_sum == null)
            arrMember[nRow].win_sum = 0;
        strBuf += Math.floor(arrMember[nRow].win_sum).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].rw_point == null)
            arrMember[nRow].rw_point = 0;
        strBuf += Math.floor(arrMember[nRow].rw_point).toLocaleString();
        strBuf += "</td> <td>";
        if(arrMember[nRow].chg_point == null)
            arrMember[nRow].chg_point = 0;
        strBuf += Math.floor(arrMember[nRow].chg_point).toLocaleString();
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_time_join.substring(0, 16);
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_time_last.substring(0, 16);
        // strBuf += "</td> <td>";
        // strBuf += "<button name='" + nRow + "' data-fid='" + arrMember[nRow].mb_fid + "' >충전</button>";
        // strBuf += "</td> <td>";
        // strBuf += "<button name='" + nRow + "' data-fid='" + arrMember[nRow].mb_fid + "' >환전</button>";
        strBuf += "</td> <td>";
        strBuf += "<button name='" + arrMember[nRow].mb_fid + "'";
        if (confs.emp_level < LEVEL_ADMIN) {
            strBuf += " disabled = 'true' ";
        }
        if (arrMember[nRow].mb_state_active == 1) {
            strBuf += " class='button-active'>승인</button>";
        } else if (arrMember[nRow].mb_state_active == 2) {
            strBuf += " class='button-req'>신청</button>";
        } else if (arrMember[nRow].mb_state_active == 3) {
            strBuf += ">대기</button>";
        } else if (arrMember[nRow].mb_state_active == 4) {
            strBuf += "style='color:red;' disabled >삭제</button>";
        } else {
            strBuf += ">차단</button>";
        }
        if (arrMember[nRow].mb_state_active != 4)
            strBuf += "<button onclick='popupMemberEdit("+arrMember[nRow].mb_fid + ", "+arrMember[nRow].mb_fid+")'>수정</button>";
        if(confs.delete == 1){
            if (arrMember[nRow].mb_state_active == 4)
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "' data-nickname='"+arrMember[nRow].mb_nickname+"'>복구</button>   ";
            else 
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>삭제</button>   ";
        }
        
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='21'>자료가 없습니다.</td></tr>";
    }

    document.getElementById("user-member-table-id").innerHTML = strBuf;
    addBtnEvent();
}



function addEventListner() {

    $(document).on("click", function(e){
        if($("#charge_modal").is(e.target)){
            closeChargeDlg();
        } else if($("#edit_member_modal").is(e.target)){
            closeMemEditDlg();
        }
    });

    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-state-select-id").change(function() {
        requestTotalPage();
    });

    $("#charge_money").on("propertychange change keyup paste input", function() {
        calcAmount("#charge_money");
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });

    $("#charge_modal .close").click(function() {
        closeChargeDlg();
    });

    $("#edit_member_modal .close").click(function() {
        closeMemEditDlg();
    });

    $(".user-table .sort-by").click(function() {
        let thName = $(this).text();
        
        let item = '';
        let dir = '';
        switch(thName){
            case "등급": item='mb_level'; break;
            case "추천인": item='mb_emp_fid'; break;
            case "아이디": item='mb_uid'; break;
            case "닉네임": item='mb_nickname'; break;
            case "등록번호": item='mb_fid'; break;
            case "Lv": item='mb_grade'; break;
            case "연락처": item='mb_phone'; break;
            case "보유금액": item='mb_money'; break;
            case "포인트": item='mb_point'; break;
            case "총배팅금": item='bet_sum'; break;
            case "총획득금": item='win_sum'; break;
            case "총롤링금": item='rw_point'; break;
            case "환전롤링금": item='chg_point'; break;
            case "등록날짜": item='mb_time_join'; break;
            case "최근접속": item='mb_time_last'; break;
            case "공배팅": item='mb_blank_count'; break;
            default:break;
        }

        if(item.length > 0){
            let bAsc = $(this).hasClass('asc');
            let bDesc = $(this).hasClass('asc');
            $(".user-table .sort-by").removeClass('asc');
            $(".user-table .sort-by").removeClass('desc');

            if(bAsc){
                $(this).removeClass('asc');
                $(this).addClass('desc');
                dir = "desc";
            } else if(bDesc){
                $(this).removeClass('desc');
                $(this).addClass('asc');
                dir = "asc";
            } else {
                $(this).addClass('asc');
                dir = "asc";
            }
            mOrdItem = item;
            mOrdDir = dir;
            requestMember();
        }

    });

}



function requestMember(auto=false) {

    var nPage = getActivePage();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "order":mOrdItem,
        "dir":mOrdDir,
        "mb_grade": 0,
        "mb_uid": strMbUid,
        "mb_emp_uid": strEmpUid,
        "mb_state": iState,
        "auto":auto
    };


    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/getmemberlist",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.confs);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#userpanel-number-select-id").val();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "mb_grade": 0,
        "mb_uid": strMbUid,
        "mb_emp_uid": strEmpUid,
        "mb_state": iState,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/userapi/getmembercnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMember();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        let tHtml = this.innerHTML; 
        if (tHtml.search("삭제") >= 0) {
            var jsonData = { "mb_fid": this.name };
            requestDeleteMember(jsonData);
        } else if (tHtml.search("승인") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateMember(jsonData);
        } else if (tHtml.search("차단") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateMember(jsonData);
        } else if (tHtml.search("복구") >= 0) {
            let nickname  = $(this).data('nickname');
            if (!confirm(nickname+" 회원을 복구하시겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestDeleteRestore(this, jsonData);
        } else if (tHtml.search("신청") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 3 };
            requestWaitToPermit(this, jsonData);
        } else if (tHtml.search("대기") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (tHtml.search("충전") >= 0) {
            showMemCharge($(this).data('fid'));
        } else if (tHtml.search("환전") >= 0) {
            showMemDischarge($(this).data('fid'));
        } /*else if (tHtml.search("수정") >= 0) {
            showMemEdit($(this).data('fid'));
        }*/ else if (tHtml.search("강제아웃") >= 0) {
            let nickname  = $(this).data('nickname');
            if (!confirm(nickname+" 회원을 강제아웃 시키겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name };
            requestLogoutMember(jsonData);
        } else if (tHtml.search("알회수") >= 0) {
            if(confirm("게임알을 회수하시겠습니까?"))
                collectEgg(this, this.name);
        } else if (tHtml === "-") {
            countBlank(this.name, -1);
        } else if (tHtml === "-10") {
            countBlank(this.name, -10);
        } else if (tHtml === "+") {
            countBlank(this.name, 1);
        } else if (tHtml === "+10") {
            countBlank(this.name, 10);
        } 
    });
}

function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("user-member-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}


/*=============================================================*/

function closeChargeDlg(){
    $('#charge_modal').slideUp(100);
}

function showChargeDlg(){
    $('#charge_modal').slideDown(200);
}

function showMemCharge(mbFid){

    if(mArrMember == null)
        return;
    
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;

    $("#charge_modal .c_type_forced").text("강제충전");
    $("#charge_modal .c_type_admin").text("충전해주는 업체");
    $("#charge_modal .c_type_money").text("충전금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").show();
    $("#btn-discharge-apply").hide();

    showChargeDlg();
}

function showMemDischarge(mbFid){
    if(mArrMember == null)
        return;
    
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;

    $("#charge_modal .c_type_forced").text("강제환전");
    $("#charge_modal .c_type_admin").text("환전해주는 업체");
    $("#charge_modal .c_type_money").text("환전금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").hide();
    $("#btn-discharge-apply").show();

    showChargeDlg();
}

function reqMemCharge(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("충전금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 직충전하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':0
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();
}

function reqMemDischarge(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("환전금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 직환전하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':1
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();

}


function showMemEditDlg(){
    $('#edit_member_modal').slideDown(200);

}

function closeMemEditDlg(){
    $('#edit_member_modal').slideUp(100);

}

function showMemCreate(){

    initMemEditDlg();
    $("#btn-mem-apply").show();
    $("#btn-mem-apply").text("추가 ");
    $("#edit_member_modal #type").text("회원정보 추가");
    showMemEditDlg();
}

function showMemEdit(mbFid){
    if(mArrMember == null)
        return;
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;
        
    $("#partner_id").val(member.mb_empname);
    // $("#partner_id").attr("disabled", true);

    $("#offline_user").prop('checked', member.mb_state_delete == 1);
    $("#user_fid").val(member.mb_fid);
    $("#user_uid").val(member.mb_uid);
    $("#user_uid").attr("disabled", true);
    
    $("#user_name").val(member.mb_nickname);
    $("#user_name").attr("disabled", true);
    
    $("#user_password").val(member.mb_pwd);
    $("#user_phone").val(member.mb_phone);
    $("#user_status").val(member.mb_state_active);
    $("#user_level").val(member.mb_grade);
    if(member.mb_color != "")
        $("#user_color").val(member.mb_color);
    $("#memo").val(member.mb_memo);
    $("#pb_ratio").val(member.mb_game_pb_ratio);
    $("#pb2_ratio").val(member.mb_game_pb2_ratio);
    $("#cs_ratio").val(member.mb_game_cs_ratio);
    $("#sl_ratio").val(member.mb_game_sl_ratio);
    $("#hl_ratio").val(member.mb_game_hl_ratio);

    $("#pb_percent").val(member.mb_game_pb_percent);
    $("#pb2_percent").val(member.mb_game_pb2_percent);
    
    $("#bank_name").val(member.mb_bank_name);
    $("#bank_owner").val(member.mb_bank_own);
    // $("#bank_owner").attr("disabled", true);
    $("#bank_number").val(member.mb_bank_num);
    // $("#bank_number").attr("disabled", true);
    $("#bank_password").val(member.mb_bank_pwd);

    $("#btn-mem-apply").text("수정 ");
    if(member.mb_state_active == 4)
        $("#btn-mem-apply").hide();
    else 
        $("#btn-mem-apply").show();

    $("#edit_member_modal #type").text("회원정보 수정");
    showMemEditDlg();
}

function memSaveApply(){

    var objMember = readConfigToObject();
    reqMemSave(objMember, closeMemEditDlg);
}

function initMemEditDlg(){
    $("#partner_id").val('');
    $("#partner_id").attr("disabled", false);
    $("#offline_user").prop('checked', false);
    $("#user_fid").val('0');
    $("#user_uid").val('');
    $("#user_uid").attr("disabled", false);
    $("#user_name").val('');
    $("#user_name").attr("disabled", false);
    $("#user_password").val('');
    $("#user_phone").val('');
    $("#user_status").val(2);
    $("#user_level").val(1);
    $("#user_color").val("#ffffff");

    $("#pb_ratio").val("0.00");
    $("#pb2_ratio").val("0.00");

    $("#cs_ratio").val("0.00");
    $("#sl_ratio").val("0.00");

    $("#memo").val('');
    $("#bank_name").val('');
    $("#bank_owner").val('');
    $("#bank_number").val('');
    $("#bank_password").val('');

}

function readConfigToObject() {

    var objMember = new Object();
    objMember.admin_level = LEVEL_ADMIN;

    objMember.mb_fid = $("#user_fid").val();
    objMember.mb_uid = $("#user_uid").val();
    objMember.mb_grade = $("#user_level").val();
    objMember.mb_nickname = $("#user_name").val();
    objMember.mb_color = $("#user_color").val();
    objMember.mb_state_active = $("#user_status").val();
    objMember.mb_pwd = $("#user_password").val();
    objMember.mb_emp_uid = $("#partner_id").val();
    objMember.mb_phone = $("#user_phone").val();
    objMember.mb_bank_name = $("#bank_name").val();
    objMember.mb_bank_own = $("#bank_owner").val();
    objMember.mb_bank_num = $("#bank_number").val();
    objMember.mb_bank_pwd = $("#bank_password").val();
    

    if($("#pb_ratio").length > 0){
        objMember.mb_game_pb_ratio = $("#pb_ratio").val();
        objMember.mb_game_pb2_ratio = $("#pb2_ratio").val();
    }
    
    if($("#pb_percent").length > 0){
        objMember.mb_game_pb_percent = $("#pb_percent").val();
        objMember.mb_game_pb2_percent = $("#pb2_percent").val();
    }
    
    if($("#cs_ratio").length > 0){
        objMember.mb_game_cs_ratio = $("#cs_ratio").val();
    } 

    if($("#sl_ratio").length > 0){
        objMember.mb_game_sl_ratio = $("#sl_ratio").val();
    } 
    
    if($("#hl_ratio").length > 0){
        objMember.mb_game_hl_ratio = $("#hl_ratio").val();
    } 

    if($("#memo").length > 0){
        objMember.mb_memo = $("#memo").val();
    } 

    if ($("#offline_user").length > 0){
        objMember.mb_state_delete = $("#offline_user").prop('checked') ? 1 : 0;
    } else objMember.mb_state_delete = 0;

    return objMember;

}

function exportTableToExcel(filename = ''){
    
    if(!mArrMember || mArrMember.length < 1){
        showAlert("자료가 없습니다.", 0);
        return;
    }

    // Specify file name
    filename = filename?filename+'.xlsx':'excel_data.xlsx';

    let strLevel = "";
    let data = [];
    let info = ['아이디', '닉네임', '비밀번호', '등급', '추천인', '등록번호', 'Lv', '보유금액', '포인트', '연락처', '은행명', '예금주', '계좌번호', '출금비번', '상태']
    data.push(info);
    for(objMember of mArrMember){
        strLevel = getMemberLevelString(objMember.mb_level);
        if(strLevel == null)
            strLevel = "";
        strState = "";
        if(objMember.mb_state_active == 1)
            strState = "승인";
        else if(objMember.mb_state_active == 2)
            strState = "신청";
        else if(objMember.mb_state_active == 3)
            strState = "대기";
        else if(objMember.mb_state_active == 4)
            strState = "삭제";
        else 
            strState = "차단";

        info = [objMember.mb_uid, objMember.mb_nickname, objMember.mb_pwd,  strLevel, objMember.mb_empname,
            objMember.mb_fid, objMember.mb_grade, Math.floor(objMember.mb_money).toLocaleString(), Math.floor(objMember.mb_point).toLocaleString(), 
            objMember.mb_phone, objMember.mb_bank_name, objMember.mb_bank_own, objMember.mb_bank_num, objMember.mb_bank_pwd, strState];
        data.push(info);
    }

    // console.log(data);
    // (C2) CREATE NEW EXCEL "FILE"
    let workbook = XLSX.utils.book_new(),
        worksheet = XLSX.utils.aoa_to_sheet(data);
    workbook.SheetNames.push("First");
    workbook.Sheets["First"] = worksheet;

    // (C3) "FORCE DOWNLOAD" XLSX FILE
    XLSX.writeFile(workbook, filename);

}