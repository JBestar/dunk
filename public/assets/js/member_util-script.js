
var mArrMember = null;
var mConfs = null;
var mOrder = 0;
var mCall = 0;

function procMember(tdLevel, empId, bShow){

    let html = ""; 
    if(tdLevel < 1 || mArrMember.length < 1)
        return html;

    // mCall++;
    // console.log("Level="+tdLevel+" empId="+empId+" call="+mCall);
    let subHtml = "";
    for (var nRow in mArrMember) {

        mArrMember[nRow].mb_level = parseInt(mArrMember[nRow].mb_level);
        if(mArrMember[nRow].mb_level != tdLevel)
            continue;

        mArrMember[nRow].mb_emp_fid = parseInt(mArrMember[nRow].mb_emp_fid);
        if(mArrMember[nRow].mb_emp_fid != empId)
            continue;

        if(mArrMember[nRow].mb_state_active == 2 || mArrMember[nRow].mb_state_active == 3)
            continue;

        subHtml = procMember(mArrMember[nRow].mb_level-1, mArrMember[nRow].mb_fid, true);
        html += getMemberTr(mArrMember[nRow], subHtml.length > 0, bShow);
        html += subHtml;
    }
    return html;
}


function showMember(arrMember, confs, refresh=true) {

    if(!refresh && arrMember.length == mArrMember.length){
        mArrMember = arrMember;
        // console.log('refresh showMember');
        for (let objMember of mArrMember) {

            if(objMember.mb_money_all !== undefined){
                $("#mm_"+objMember.mb_fid).text(parseFloat(objMember.mb_money_all).toLocaleString());
            }
            else $("#mm_"+objMember.mb_fid).text(parseFloat(objMember.mb_money).toLocaleString());
            $("#mp_"+objMember.mb_fid).text(parseFloat(objMember.mb_point).toLocaleString());
        }

    } else{
        mArrMember = arrMember;
        mConfs = confs;
        mOrder = 1;
        mCall = 0;

        var strBuf = "";
        for (let objMember of mArrMember) {
            objMember.mb_state_active = parseInt(objMember.mb_state_active);
            if(objMember.mb_state_active == 2 || objMember.mb_state_active == 3 || !confs.tree){
                strBuf += getMemberTr(objMember, false, true);
            }
        }
    
        if(confs.tree && arrMember.length > 0){
            let lvTop = parseInt(arrMember[0].mb_level) ;
            let empId = parseInt(arrMember[0].mb_emp_fid) ;
            strBuf += procMember(lvTop, empId, true);
        }
    
        if (strBuf.length < 1) {
            strBuf = "<tr><td colspan='21'>자료가 없습니다.</td></tr>";
        }
    
        document.getElementById("user-member-table-id").innerHTML = strBuf;
        addBtnEvent();
    }
    if(typeof sumOfMember === "function"){
        sumOfMember();
    }
}

function toggle(level, fid){
    let theButton = document.getElementById("exp-btn_"+fid);
    if(!theButton)
        return;

    let bChild = false;
    // if (theButton.getAttribute("aria-expanded") == "true" || level == LEVEL_MARKET) {
        bChild = true;
    // }

    let strIds = subIds(level-1, fid, bChild);
    // console.log(strIds);

    let trRows = [];
    let trIds = "";
    let btnIds = "";
    let btnExps = [];

    if(strIds.length > 0){
        let ids = strIds.split(',');

        for(let idx in ids){
            if(ids[idx].length == 0)
                continue;

            if(idx != 0){
                btnIds+=",";
                trIds+=",";   
            }
            btnIds += "#exp-btn_"+ids[idx];
            trIds += "#tr_"+ids[idx];
        }

        // console.log(trIds);
        trRows = document.querySelectorAll(trIds);
        btnExps = document.querySelectorAll(btnIds);
    }


    if (theButton.getAttribute("aria-expanded") == "false") {
        for (var i = 0; i < trRows.length; i++) {
          trRows[i].classList.remove("hidden");
        }
        for (var i = 0; i < btnExps.length; i++) {
            btnExps[i].innerText = "▼";
            btnExps[i].classList.add("expand");
            btnExps[i].setAttribute("aria-expanded", "true");
        }

        theButton.innerText = "▼";
        theButton.classList.add("expand");
        theButton.setAttribute("aria-expanded", "true");
      } else {
        for (var i = 0; i < trRows.length; i++) {
          trRows[i].classList.add("hidden");
        }
        // console.log(btnIds);
        for (var i = 0; i < btnExps.length; i++) {
            btnExps[i].innerText = "▶";
            btnExps[i].classList.remove("expand");
            btnExps[i].setAttribute("aria-expanded", "false");
        }
        theButton.innerText = "▶";
        theButton.classList.remove("expand");
        theButton.setAttribute("aria-expanded", "false");
      }
}


function subIds(tdLevel, empId, bChild=false, minLevel = 0){

    let ids = ""; 
    if(tdLevel < 1 || mArrMember.length < 1)
        return ids;

    // mCall++;
    // console.log("Level="+tdLevel+" empId="+empId+" call="+mCall);
    let chIds = "";
    for (var nRow in mArrMember) {

        mArrMember[nRow].mb_level = parseInt(mArrMember[nRow].mb_level);
        if(mArrMember[nRow].mb_level != tdLevel)
            continue;

        mArrMember[nRow].mb_emp_fid = parseInt(mArrMember[nRow].mb_emp_fid);
        if(mArrMember[nRow].mb_emp_fid != empId)
            continue;

        if(mArrMember[nRow].mb_state_active == 2 || mArrMember[nRow].mb_state_active == 3)
            continue;

        if(bChild && mArrMember[nRow].mb_level > minLevel)
            chIds = subIds(mArrMember[nRow].mb_level-1, mArrMember[nRow].mb_fid, bChild, minLevel);
        ids += mArrMember[nRow].mb_fid + ","+chIds;
    }
    return ids;
}

function getLevelTd(objMember, subUrl){

    // let link = "<a href='"+FURL+subUrl+objMember.mb_fid+"' class='link-member'>"+objMember.mb_uid+"</a>";
    let link = "<a onclick='"+subUrl+"(" + objMember.mb_fid + ", "+objMember.mb_fid+ ")' class='link-member'>"+ objMember.mb_uid+ "</a>";

    let td = "</td> <td>";
    let html = "";
    if(objMember.mb_level == LEVEL_COMPANY){
        html += link+td+td+td+td;
    } else if(objMember.mb_level == LEVEL_AGENCY){
        html += td+link+td+td+td;
    } else if(objMember.mb_level == LEVEL_EMPLOYEE){
        html += td+td+link+td+td;
    } else if(objMember.mb_level == LEVEL_MARKET){
        html += td+td+td+link+td;
    } else 
        html += td+td+td+td+link;
    return html;
}

function togleList(open = true){

    if(mArrMember.length < 1)
        return ;

    let lvTop = parseInt(mArrMember[0].mb_level) ;

    for (let objMember of mArrMember) {
        if(parseInt(objMember.mb_level) == lvTop){
            setToggle(lvTop, objMember.mb_fid, open);
        }
    }

    if(open){
        for (let objMember of mArrMember) {
            if(parseInt(objMember.mb_level) == LEVEL_EMPLOYEE){
                setToggle(LEVEL_EMPLOYEE, objMember.mb_fid, !open);
            }
        }
    } else {
        if(lvTop == LEVEL_COMPANY){
            for (let objMember of mArrMember) {
                if(parseInt(objMember.mb_level) == LEVEL_COMPANY){
                    setToggle(LEVEL_COMPANY, objMember.mb_fid, true);
                }
            }
            for (let objMember of mArrMember) {
                if(parseInt(objMember.mb_level) == LEVEL_AGENCY){
                    setToggle(LEVEL_AGENCY, objMember.mb_fid, false);
                }
            }
        }
        
    }
}

function setToggle(level, fid, open=true, minLevel = 0){
    let theButton = document.getElementById("exp-btn_"+fid);
    if(!theButton)
        return;

    let bChild = true;

    // let minLevel = 0;

    let strIds = subIds(level-1, fid, bChild, minLevel);
    // console.log("open="+open + " ids = "+ strIds);

    let trRows = [];
    let trIds = "";
    let btnIds = "";
    let btnExps = [];

    if(strIds.length > 0){
        let ids = strIds.split(',');

        for(let idx in ids){
            if(ids[idx].length == 0)
                continue;

            if(idx != 0){
                btnIds+=",";
                trIds+=",";   
            }
            btnIds += "#exp-btn_"+ids[idx];
            trIds += "#tr_"+ids[idx];
        }

        // console.log(trIds);
        trRows = document.querySelectorAll(trIds);
        btnExps = document.querySelectorAll(btnIds);
    }


    if (open) {
        for (var i = 0; i < trRows.length; i++) {
          trRows[i].classList.remove("hidden");
        }
        for (var i = 0; i < btnExps.length; i++) {
            btnExps[i].innerText = "▼";
            btnExps[i].classList.add("expand");
            btnExps[i].setAttribute("aria-expanded", "true");
        }

        theButton.innerText = "▼";
        theButton.classList.add("expand");
        theButton.setAttribute("aria-expanded", "true");
      } else {
        for (var i = 0; i < trRows.length; i++) {
          trRows[i].classList.add("hidden");
        }
        // console.log(btnIds);
        for (var i = 0; i < btnExps.length; i++) {
            btnExps[i].innerText = "▶";
            btnExps[i].classList.remove("expand");
            btnExps[i].setAttribute("aria-expanded", "false");
        }
        theButton.innerText = "▶";
        theButton.classList.remove("expand");
        theButton.setAttribute("aria-expanded", "false");
      }
}