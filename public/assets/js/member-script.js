$(document).ready(function() {
    addEventListner();
    requestTotalPage();

    setTimeout(function() {
        memberLoop();
    }, 60000);
});

function requestPageInfo() {
    requestMember();
}

function memberLoop() {
    requestMember(true);
    // 60초뒤에 다시 실행
    setTimeout(function() {
        memberLoop();
    }, 60000);
}

function showMember(arrMember, confs) {

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
        strBuf += "<a href='"+FURL+"/user/member/";
        strBuf += arrMember[nRow].mb_emp_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_empname;
        strBuf += "</a>";

        strBuf += "</td> <td>";
        strBuf += "<a href='"+FURL+"/user/member/";
        strBuf += arrMember[nRow].mb_fid;
        strBuf += "' class='link-member'>"
        strBuf += arrMember[nRow].mb_uid;
        strLevel = getMemberLevelString(arrMember[nRow].mb_level);
        if (strLevel != null)
            strBuf += " | " + strLevel;
        strBuf += "</a>";

        strBuf += "</td> <td>";
        strBuf += "<a onclick='popupMemberEdit(" + arrMember[nRow].mb_fid + ", "+arrMember[nRow].mb_fid+ ")' class='link-member'>"+ arrMember[nRow].mb_nickname+ "</a>";
        if(arrMember[nRow].mb_state_delete > 0){
            strBuf += "<br>(오프라인)";    
        }
        if(arrMember[nRow].mb_state_test > 0){
            strBuf += "<br>(테스트)";    
        }
        strBuf += "</td> <td>";
        strBuf += arrMember[nRow].mb_fid;
        strBuf += "</td> <td>";
        strBuf += "Lv " + parseInt(arrMember[nRow].mb_grade).toLocaleString();
        strBuf += "</td><td> <span id='mm_" + arrMember[nRow].mb_fid + "'>";
        strBuf += Math.floor(arrMember[nRow].mb_money_all).toLocaleString() + "</span>";
        strBuf += '<button class="refresh_btn" onclick="refreshEgg(' + arrMember[nRow].mb_fid + ', this);"></button>';
        strBuf += "</td> <td id='mp_" + arrMember[nRow].mb_fid + "'>";
        strBuf += Math.floor(arrMember[nRow].mb_point).toLocaleString();
        strBuf += "</td> <td>";
        if (confs.emp_level >= LEVEL_ADMIN) {
            if (!confs.slot_deny) {
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "' class='blank-btn_" + arrMember[nRow].mb_fid + "' >-</button>   ";
                strBuf += "<input type='text' id='blank_" + arrMember[nRow].mb_fid + "' value='" + arrMember[nRow].mb_blank_count + "' disabled>   ";
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "' class='blank-btn_" + arrMember[nRow].mb_fid + "' >+</button>   ";
                strBuf += "<br><button name='" + arrMember[nRow].mb_fid + "' class='blank-btn_" + arrMember[nRow].mb_fid + "' >-10</button>   ";
                strBuf += "<button name='" + arrMember[nRow].mb_fid + "' class='blank-btn_" + arrMember[nRow].mb_fid + "' >+10</button>   ";
                strBuf += "</td> <td>";
            }
            strBuf += arrMember[nRow].mb_ip_last.substring(0, 20);
            if (arrMember[nRow].block_state == 1) {
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >차단해제</button>";
            } else
                strBuf += "<br><br><button name='" + arrMember[nRow].mb_ip_last + "' >IP차단</button>";
            strBuf += "</td> <td>";
            
        }
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
        } else {
            strBuf += ">차단</button>";
        }
        
        // strBuf += "<a href='"+FURL+"/user/member_edit/" + arrMember[nRow].mb_fid + "' >수정</a>";
        strBuf += "<button onclick='popupMemberEdit("+arrMember[nRow].mb_fid + ", "+arrMember[nRow].mb_fid+")'>수정</button>";
        if (confs.emp_level >= LEVEL_ADMIN) {
            var strEncodeURI = FURL+"/board/message_edit/0/" + arrMember[nRow].mb_fid;
            strBuf += "<a href='" + strEncodeURI + "' >쪽지</a>";
            strBuf += "<button name='" + arrMember[nRow].mb_fid + "'>삭제</button>   ";
        }

        if (confs.emp_level >= LEVEL_ADMIN) {
            strBuf += "</td> <td>";
            if(!confs.pbg_deny){
                if (arrMember[nRow].mb_game_pb == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>PBG</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >PBG</button>";
                }
            }
            if(!confs.dhp_deny){
                if (arrMember[nRow].mb_game_ps == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>동행</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >동행</button>";
                }
            }
            if(!confs.spk_deny){
                if (arrMember[nRow].mb_game_ks == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>키노</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >키노</button>";
                }
            }
            if(!confs.bpg_deny){
                if (arrMember[nRow].mb_game_bb == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>보글볼</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >보글볼</button>";
                }
                if (arrMember[nRow].mb_game_bs == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>보사달</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >보사달</button>";
                }
            }
            if(!confs.eos5_deny || !confs.eos3_deny){
                if (arrMember[nRow].mb_game_eo == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>EOS</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >EOS</button>";
                }
            }
            if(!confs.coin5_deny || !confs.coin3_deny){
                if (arrMember[nRow].mb_game_co == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>코인</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >코인</button>";
                }
            }
            if(!confs.evol_deny || !confs.cas_deny){
                if (arrMember[nRow].mb_game_cs == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>카지노</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >카지노</button>";
                }
            }
            if(!confs.slot_deny){
                if (arrMember[nRow].mb_game_sl == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>슬롯</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >슬롯</button>";
                }
            }
            if(!confs.hold_deny){
                if (arrMember[nRow].mb_game_hl == 1) {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "'  class='button-active'>홀덤</button>";
                } else {
                    strBuf += "<button name='" + arrMember[nRow].mb_fid + "' >홀덤</button>";
                }
            }
        }
        strBuf += "</td></tr>";
    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='11'>자료가 없습니다.</td></tr>";
    }

    document.getElementById("user-member-table-id").innerHTML = strBuf;
    addBtnEvent();
}



function addEventListner() {
    $("#userpanel-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#userpanel-level-select-id").change(function() {
        requestTotalPage();
    });

    $("#userpanel-state-select-id").change(function() {
        requestTotalPage();
    });

    $("#userpanel-number-select-id").change(function() {
        requestTotalPage();
    });
}



function requestMember(auto=false) {

    var nPage = getActivePage();
    var userGrad = $("#userpanel-level-select-id").val();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "mb_grade": userGrad,
        "mb_uid": strMbUid,
        "mb_emp_uid": strEmpUid,
        "mb_state": iState,
        "auto":auto,
    };


    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/getmembers",
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
    var userGrad = $("#userpanel-level-select-id").val();
    var strMbUid = $("#userpanel-userid-input-id").val();
    var iState = $("#userpanel-state-select-id").val();
    var empIdEle = document.getElementById("userpanel-empid-input-id");
    var strEmpUid = "";
    if (typeof(empIdEle) != undefined && empIdEle != null)
        strEmpUid = empIdEle.value;
    var jsonData = {
        "count": CountPerPage,
        "mb_grade": userGrad,
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
        let jsonData = null;

        if (this.innerHTML.search("삭제") >= 0) {
            jsonData = { "mb_fid": this.name };
            requestDeleteMember(jsonData);
        } else if (this.innerHTML.search("IP차단") >= 0) {
            jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("차단해제") >= 0) {
            jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("차단") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("신청") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 3 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML.search("대기") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML == "PBG") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("파사달") >= 0) {

            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("PBG") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("동행") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("키노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_ks": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_ks": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("보글볼") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_bb": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_bb": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("보사달") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_bs": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_bs": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("코인") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_co": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_co": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("카지노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_cs": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_cs": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("슬롯") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_sl": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_sl": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "EOS") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_eo": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_eo": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "홀덤") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_hl": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_hl": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "-") {
            countBlank(this.name, -1);
        } else if (this.innerHTML === "-10") {
            countBlank(this.name, -10);
        } else if (this.innerHTML === "+") {
            countBlank(this.name, 1);
        } else if (this.innerHTML === "+10") {
            countBlank(this.name, 10);
        } 
    });
}

function addBtnEvent() {

    var elemTable = document.getElementById("user-member-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}


