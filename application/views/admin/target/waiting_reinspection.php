<style>
    iframe{
        width: 100%;
        overflow: hidden;
        border: 0;
    }
    #creditManagementTable{
        height: 2500px;
    }
    #riskPage{
        margin: 16px 0 5px 0;
        height: 243px;
    }
    .targetAction{
        width: 100%;
        text-align: center;
    }
    .targetAction button{
        width: 200px;
    }
    .opinion_item {
        display: flex;
        margin: 10px;
        border-bottom-style: ridge;
        position: relative;
    }
    .opinion_item div {
        margin: 0 10px;
    }
    .opinion_status {
        display: flex;
        width: 10%;
        justify-content: center;
        align-items: center;
    }
    .opinion_description {
        display: flex;
        width: 10%;
        justify-content: center;
        align-items: center;
    }
    .opinion_info {
        width: 70%;
    }
    .opinion_info input{
        width: 100%;
    }
    .opinion_info textarea{
        width: 100%;
        height: 70px;
        resize : none;
        word-break:keep-all;
    }
    .opinion_info div{
        display: flex;
    }
    .opinion_button {
        display: flex;
        width: 10%;
        justify-content: center;
        align-items: center;
    }
    .mask {
        z-index: 10;
        background-color: gray;
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0.3;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 50px;
        font-weight:bold;
        letter-spacing: 25px;
    }
</style>
<script type="text/javascript" src="<?php echo base_url();?>assets/admin/js/common/datetime.js" ></script>
<script src="<?=base_url()?>assets/admin/js/mapping/loan/credit.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var caseId = <?=isset($targetInfo)?$targetInfo->id:''?>;
        var userId = <?=isset($targetInfo)?$targetInfo->user_id:''?>;
        var targetInfoAjaxLock = false;
        var relatedUserAjaxLock = false;
        let bank = 1;

        fillFakeBrookesiaUserHitRule();
        fillFakeRelatedUsers();

        fetchBrookesiaUserRuleHit(userId);
        fetchRelatedUsers(userId);
        fetchJudicialyuanData(userId)
        $("#load-more").hide();


        var brookesiaData = [];
        function fetchBrookesiaUserRuleHit(userId) {
            $.ajax({
                type: "GET",
                url: "/admin/brookesia/user_rule_hit" + "?userId=" + userId,
                beforeSend: function () {
                    brookesiaDatalock = true;
                },
                complete: function () {
                    brookesiaDatalock = false
                },
                success: function (response) {
                    if (response.status.code != 200) {
                        return;
                    }
                    brookesiaData = response.response.results
                    fillBrookesiaUserHitRule();

                }
            });
        }
        function fillFakeBrookesiaUserHitRule(show = true) {
            if (!show) {
                $("#brookesia_results tr:gt(0)").remove();
                return;
            }
            pTag = '<p class="form-control-static"></p>'

            for (var i = 0; i < 1; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                ).appendTo("#brookesia_results");
            }
        }
        function fillBrookesiaUserHitRule() {
            for (var i = 0; i < brookesiaData.length; i++) {
                var risk = '<p class="form-control-static">' + brookesiaData[i].risk + '</p>';
                var updatedAt = '<p class="form-control-static">' +
                    new DateTime(brookesiaData[i].updatedAt).values()+ '</p>';
                var description = '<p class="form-control-static">' + brookesiaData[i].description + '</p>';
                var riskColor = (brookesiaData[i].risk === "拒絕") ?"red"
                    : (brookesiaData[i].risk === "高") ? "Tomato"
                        : (brookesiaData[i].risk === "中") ? "Orange"
                            : (brookesiaData[i].risk === "低") ? "green" : "black"

                $("<tr>").append(
                    $('<td class="center-text" style="color:'+ riskColor +';">').append(risk),
                    $('<td class="center-text" style="color:black;">').append(updatedAt),
                    $('<td class="center-text" style="color:black;">').append(description),
                ).appendTo("#brookesia_results");
            }
        }
        var relatedUsers = [];
        function fetchRelatedUsers(userId) {
            $.ajax({
                type: "GET",
                url: "/admin/brookesia/user_related_user" + "?userId=" + userId,
                beforeSend: function () {
                    relatedUserAjaxLock = true;
                },
                complete: function () {
                    relatedUserAjaxLock = false;
                },
                success: function (response) {
                    fillFakeRelatedUsers(false);
                    if (response.status.code != 200) {
                        return;
                    }

                    relatedUsers = response.response.results;
                    fillRelatedUsers();
                }
            });
        }
        function fillRelatedUsers() {
            for (var i = 0; i < relatedUsers.length; i++) {
                var relatedUserId = relatedUsers[i].relatedUserId
                var userLink = '<a href="' + '/admin/user/display?id=' + relatedUserId + '" target="_blank"><p>' + relatedUserId + '</p></a>'
                var descriptionText = relatedUsers[i].description;
                var description = '<p class="form-control-static">' + descriptionText + '</p>';
                if (relatedUsers[i]){
                    var relatedInfo = '<p class="form-control-static">' + relatedUsers[i].relatedKey +
                        ' : '+ relatedUsers[i].relatedValue +'</p>';
                } else { var relatedInfo = "無"}

                $("<tr>").append(
                    $('<td class="center-text">').append(userLink),
                    $('<td class="center-text">').append(description),
                    $('<td class="center-text">').append(relatedInfo),
                ).appendTo("#related-users");
            }
        }

        $('#load-more').on('click', function() {
            fillRelatedUsers();
        });

        var judicialyuanDataIndex = 1;
        var judicialyuanData = [];
        function fetchJudicialyuanData(userId) {
            $.ajax({
                type: "GET",
                url: "/admin/User/judicialyuan" + "?id=" + userId,
                beforeSend: function () {
                    judicialyuanDataock = true;
                },
                complete: function () {
                    judicialyuanDataock = false;
                },
                success: function (response) {
                    fillFakejudicialyuanData(false);
                    if (response.status.code != 200) {
                        return;
                    }
                    judicialyuanData = response.response;
                    filljudicialyuanData();
                }
            });
        }
        function fillFakejudicialyuanData(show = true) {
            if (!show) {
                $("#judicial-yuan tr:gt(0)").remove();
                return;
            }
            pTag = '<p class="form-control-static"></p>'

            for (var i = 0; i < 3; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                ).appendTo("#judicial-yuan");
            }
        }

        function filljudicialyuanData() {
            var maxNumInPage = 5;
            var start = (judicialyuanDataIndex-1) * maxNumInPage;
            var end = judicialyuanDataIndex * maxNumInPage;
            if (end > judicialyuanData.length) end = judicialyuanData.judicial_yuan.length;
            if (start > end) {
                $("#load-more").hide();
            } else {
                $("#load-more").show();
            }

            for (var i = start; i < (end - 1); i++) {
                var name = '<p class="form-control-static">' + judicialyuanData.judicial_yuan[i].name + '</p>';
                var count = '<a target="_blank" href="../certification/judicial_yuan_case?name=' + judicialyuanData.userName + '&amp;case=' + judicialyuanData.judicial_yuan[i].name + '&amp;page=1&amp;count=' + judicialyuanData.judicial_yuan[i].count + '">' + judicialyuanData.judicial_yuan[i].count + '</a>';
                $("<tr>").append(
                    $('<td class="center-text" style="color:red;">').append(name),
                    $('<td class="center-text" style="color:red;">').append(count),
                ).appendTo("#judicial-yuan");
            }
            judicialyuanDataIndex+=1;
        }

        function fillFakeRelatedUsers(show = true) {
            if (!show) {
                $("#related-users tr:gt(0)").remove();
                return;
            }
            pTag = '<p class="form-control-static"></p>'

            for (var i = 0; i < 3; i++) {
                $("<tr>").append(
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                    $('<td class="fake-fields center-text">').append(pTag),
                ).appendTo("#related-users");
            }
        }

        function setDisabled(e){
            $(e).attr("disabled",true);
            $(e).text("已送出");
        }

        $(document).off("click","#reinspection_opinion.comment").on("click","#reinspection_opinion.comment" ,  function(){
            setDisabled('#reinspection_opinion.comment');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                    "target_id="+caseId+
                    "&type=reinspection_opinion"+
                    "&for=comment"+
                    "&val="+$('#reinspection_opinion[type=text]').val(),true);
        });

        $(document).off("click","#CRO_opinion.comment").on("click","#CRO_opinion.comment" ,  function(){
            setDisabled('#CRO_opinion.comment');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                "target_id="+caseId+
                "&type=CRO_opinion"+
                "&for=comment"+
                "&val="+$('#CRO_opinion[type=text]').val(),true);
        });
        $(document).off("click","#CRO_opinion.score").on("click","#CRO_opinion.score" ,  function(){
            setDisabled('#CRO_opinion.score');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                    "target_id="+caseId+
                    "&type=CRO_opinion"+
                    "&for=score"+
                    "&val="+$('#CRO_opinion[type=number]').val(),true);
        });

        $(document).off("click","#general_manager_opinion.comment").on("click","#general_manager_opinion.comment" ,  function(){
            setDisabled('#general_manager_opinion.comment');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                "target_id="+caseId+
                "&type=general_manager_opinion"+
                "&for=comment"+
                "&val="+$('#general_manager_opinion[type=text]').val(),true);
        });
        $(document).off("click","#general_manager_opinion.score").on("click","#general_manager_opinion.score" ,  function(){
            setDisabled('#general_manager_opinion.score');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                "target_id="+caseId+
                "&type=general_manager_opinion"+
                "&for=score"+
                "&val="+$('#general_manager_opinion[type=number]').val(),true);
        });
        $(document).off("click","#manual_handling").on("click","#manual_handling" ,  function(){
            setDisabled('#manual_handling');
            ajpost(location.origin+"/admin/target/waiting_reinspection",
                "target_id="+caseId+
                "&manual_handling=1",true);
        });
        $(document).off("click","#closeWindow").on("click","#closeWindow" ,  function(){
            window.close();
        });

        //新光收件檢核表送出
        $(document).off("click","#skbank_text_send_btn").on("click","#skbank_text_send_btn" ,  function(){
            $("#skbank_text_send_btn").prop("disabled", true);
            $("#skbank_text_send_btn").text("資料處理中");
            $.ajax({
                type: "GET",
                url: "/admin/target/skbank_text_send" + "?target_id=" + caseId + "&bank=1",
                success: function (response) {
                    if(response.status.code == 200){
                        $('#skbankCompId').text(response.response.CompId);
                        $.ajax({
                           type: "POST",
                           data: JSON.stringify(response.response),
                           url: '/api/skbank/v1/LoanRequest/apply_text',
                           dataType: "json",
                           success: function (response) {
                               let skbank_response = response.success ? '成功' : '失敗';
                               $('#skbankMsgNo').text(response.msg_no);
                               $('#skbankReturn').text(skbank_response);
                               $('#skbankCaseNo').text(response.case_no);
                               $('#skbankMetaInfo').text(response.meta_info);

                               if(skbank_response == '成功'){
                                   $("#skbank_img_send_btn").prop("disabled", false);
                                   $("#skbank_approve_send_btn").prop("disabled", false);
                               }else{
                                   $("#skbank_text_send_btn").prop("disabled", false);
                               }
                               alert(`新光送出結果 ： ${skbank_response}\n回應內容 ： ${response.error}\n新光案件編號 ： ${response.case_no}\n新光交易序號 ： ${response.msg_no}\n新光送出資料資訊 ： ${response.meta_info}\n`);
                           },
                           error: function(error) {
                             alert(error);
                           }
                       });
                       $("#skbank_text_send_btn").text("新光 收件檢核表送出");
                    }
                }
            });
        });

        // 新光圖片附件送出
        $(document).off("click","#skbank_img_send_btn").on("click","#skbank_img_send_btn" ,  function(){
            $("#skbank_img_send_btn").prop("disabled", true);
            $("#skbank_img_send_btn").text("資料處理中");
            bank = 1;
            $.ajax({
                type: "GET",
                url: "/admin/target/skbank_file_get" + "?target_id=" + caseId + "&bank=" + bank,
                success: function (response) {
                  if(response.status.code == 200){
                      let case_no = $('#skbankCaseNo').text();
                      let comp_id = $('#skbankCompId').text();
                      if(case_no && comp_id){
                          let request_data = [];
                          let data_count = 0;
                          Object.keys(response.response).forEach( (image_type_key) => {
                              data_count += Object.keys(response.response[image_type_key]).length;
                          });
                          Object.keys(response.response).forEach( (image_type_key) => {
                              Object.keys(response.response[image_type_key]).forEach( (key) => {
                                  console.log(response.response[image_type_key][key]);
                                  let skbank_image_type = image_type_key;
                                  let skbank_image_url = response.response[image_type_key][key];
                                  let image_count = key;
                                  let doc_file_type = 0;

                                  let img_type = skbank_image_url.replace(/.*\./, '');
                                    switch (img_type) {
                                        case 'pdf':
                                            doc_file_type = 1;
                                            break;
                                        case 'jpg':
                                            doc_file_type = 2;
                                            break;
                                        case 'jpeg':
                                            doc_file_type = 3;
                                            break;
                                        case 'png':
                                            doc_file_type = 4;
                                            break;
                                        case 'tiff':
                                            doc_file_type = 5;
                                            break;
                                        case 'heic':
                                            doc_file_type = 6;
                                            break;
                                        case 'heif':
                                            doc_file_type = 7;
                                            break;
                                      default:
                                            doc_file_type = 2;
                                            break;
                                    }
                                  getMappingMsgNo(caseId, 'send', image_type_key, bank, (data) => {
                                      msg_data = data;
                                      msg_no = msg_data.data.msg_no;
                                      request_data.push({
                                          'MsgNo' : msg_no,
                                          'CompId' : comp_id,
                                          'CaseNo' : case_no,
                                          'DocType' : image_type_key,
                                          'DocSeq' : parseInt(image_count)+1,
                                          'DocFileType' : doc_file_type,
                                          'DocUrl' : skbank_image_url
                                      });
                                      if(Object.keys(request_data).length == data_count){
                                          image_list_data = JSON.stringify({"request_image_list":request_data});
                                          $.ajax({
                                              type: "POST",
                                              data: image_list_data,
                                              url: '/api/skbank/v1/LoanRequest/apply_image_list',
                                              dataType: "json",
                                              success: function (response) {
                                                  let skbank_response = response.success ? '成功' : '失敗';
                                                  alert(`新光送出結果 ： ${skbank_response}\n回應內容 ： ${response.error}\n新光送出圖片總數 ： ${response.total_image_count}\n`);
                                              },
                                              error: function(error) {
                                                alert(error);
                                              }
                                          });
                                          $("#skbank_img_send_btn").prop("disabled", false);
                                          $("#skbank_img_send_btn").text("新光 圖片送出");
                                      }
                                  });
                              })
                          })
                      }
                  }
                },
                error: function(error) {
                  alert(error);
                }
            });
        });

        function getMappingMsgNo(target_id,action,data_type,bank,result){
            $.ajax({
                type: "GET",
                url: `/admin/bankdata/waiting_reinspection/getMappingMsgNo?target_id=${target_id}&action=${action}&data_type=${data_type}&bank=${bank}`,
                success: function (response) {
                    response = response.response;
                    result(response);
                },
                error: function(error) {
                    alert(error);
                }
            });
        }

        //新光附件上傳完成 API
        $(document).off("click","#skbank_approve_send_btn").on("click","#skbank_approve_send_btn" ,  function(){
            $("#skbank_approve_send_btn").prop("disabled", true);
            $("#skbank_approve_send_btn").text("資料處理中");
            let case_no = $('#skbankCaseNo').text();
            let comp_id = $('#skbankCompId').text();
            if(case_no && comp_id){
                getMappingMsgNo(caseId, 'send', 'image_complete', bank,  (data) => {
                    msg_data = data;
                    msg_no = msg_data.data.msg_no;

                    if (msg_no) {
                        $.ajax({
                            type: "POST",
                            url: "/api/skbank/v1/LoanRequest/apply_image_complete",
                            dataType: "json",
                            data: JSON.stringify({
                                'CaseNo':case_no,
                                'CompId':comp_id,
                                'MsgNo':msg_no,
                            }),
                            success: function (response) {
                                let skbank_response = response.success ? '成功' : '失敗';
                                if (skbank_response == '失敗') {
                                    $("#skbank_approve_send_btn").prop("disabled", true);
                                }
                                alert(`送出結果 ： ${skbank_response}\n回應內容 ： ${response.error}`);
                            }
                        });
                    }else {
                        alert(`新光發送交易序號生成失敗`);
                    }
                    $("#skbank_approve_send_btn").text("新光 通過");
                });
            }
        });

        //凱基收件檢核表送出
        $(document).off("click", "#kgibank_text_send_btn").on("click", "#kgibank_text_send_btn", function () {
            $("#kgibank_text_send_btn").prop("disabled", true);
            $("#kgibank_text_send_btn").text("凱基 資料處理中");
            $.ajax({
                type: "GET",
                url: "/admin/target/skbank_text_send" + "?target_id=" + caseId + "&bank=2",
                success: function (response) {
                    if (response.status.code == 200) {
                        $('#kgibankCompId').text(response.response.CompId);
                        $.ajax({
                            type: "POST",
                            data: JSON.stringify(response.response),
                            url: '/api/kgibank/v1/LoanRequest/apply_text',
                            dataType: "json",
                            success: function (response) {
                                let bank_response = response.success ? '成功' : '失敗';
                                $('#kgibankMsgNo').text(response.msg_no);
                                $('#kgibankReturn').text(bank_response);
                                $('#kgibankCaseNo').text(response.case_no);
                                $('#kgibankMetaInfo').text(response.meta_info);

                                if (bank_response == '成功') {
                                    $("#kgibank_img_send_btn").prop("disabled", false);
                                    $("#kgibank_approve_send_btn").prop("disabled", false);
                                } else {
                                    $("#kgibank_text_send_btn").prop("disabled", false);
                                }
                                alert(`凱基送出結果 ： ${bank_response}\n回應內容 ： ${response.error}\n凱基案件編號 ： ${response.case_no}\n凱基交易序號 ： ${response.msg_no}\n凱基送出資料資訊 ： ${response.meta_info}\n`);
                            },
                            error: function (error) {
                                alert(error);
                            }
                        });
                        $("#kgibank_text_send_btn").text("凱基 收件檢核表送出");
                    }
                }
            });
        });

        // 凱基圖片附件送出
        $(document).off("click", "#kgibank_img_send_btn").on("click", "#kgibank_img_send_btn", function () {
            $("#kgibank_img_send_btn").prop("disabled", true);
            $("#kgibank_img_send_btn").text("凱基 資料處理中");
            bank = 2;
            $.ajax({
                type: "GET",
                url: "/admin/target/skbank_image_get" + "?target_id=" + caseId + "&bank="+bank,
                success: function (response) {
                    console.log(response);
                    if (response.status.code == 200) {
                        let case_no = $('#kgibankCaseNo').text();
                        let comp_id = $('#kgibankCompId').text();
                        console.log(case_no);
                        if (case_no && comp_id) {
                            let request_data = [];
                            let data_count = 0;
                            Object.keys(response.response).forEach((image_type_key) => {
                                data_count += Object.keys(response.response[image_type_key]).length;
                            });
                            Object.keys(response.response).forEach((image_type_key) => {
                                Object.keys(response.response[image_type_key]).forEach((key) => {
                                    console.log(response.response[image_type_key][key]);
                                    let kgibank_image_type = image_type_key;
                                    let kgibank_image_url = response.response[image_type_key][key];
                                    let image_count = key;
                                    let doc_file_type = 0;

                                    let img_type = kgibank_image_url.replace(/.*\./, '');
                                    switch (img_type) {
                                        case 'pdf':
                                            doc_file_type = 1;
                                            break;
                                        case 'jpg':
                                            doc_file_type = 2;
                                            break;
                                        case 'jpeg':
                                            doc_file_type = 3;
                                            break;
                                        case 'png':
                                            doc_file_type = 4;
                                            break;
                                        case 'tiff':
                                            doc_file_type = 5;
                                            break;
                                        case 'heic':
                                            doc_file_type = 6;
                                            break;
                                        case 'heif':
                                            doc_file_type = 7;
                                            break;
                                        default:
                                            doc_file_type = 2;
                                            break;
                                    }
                                    getMappingMsgNo(caseId, 'send', image_type_key, bank, (data) => {
                                        msg_data = data;
                                        msg_no = msg_data.data.msg_no;
                                        request_data.push({
                                            'MsgNo': msg_no,
                                            'CompId': comp_id,
                                            'CaseNo': case_no,
                                            'DocType': image_type_key,
                                            'DocSeq': parseInt(image_count) + 1,
                                            'DocFileType': doc_file_type,
                                            'DocUrl': kgibank_image_url
                                        });
                                        if (Object.keys(request_data).length == data_count) {
                                            image_list_data = JSON.stringify({ "request_image_list": request_data });
                                            $.ajax({
                                                type: "POST",
                                                data: image_list_data,
                                                url: '/api/kgibank/v1/LoanRequest/apply_image_list',
                                                dataType: "json",
                                                success: function (response) {
                                                    let bank_response = response.success ? '成功' : '失敗';
                                                    alert(`凱基送出結果 ： ${bank_response}\n回應內容 ： ${response.error}\n凱基送出圖片總數 ： ${response.total_image_count}\n`);
                                                },
                                                error: function (error) {
                                                    alert(error);
                                                }
                                            });
                                            $("#kgibank_img_send_btn").prop("disabled", false);
                                            $("#kgibank_img_send_btn").text("凱基 圖片送出");
                                        }
                                    });
                                })
                            })
                        }
                    }
                },
                error: function (error) {
                    alert(error);
                }
            });
        });

        //凱基附件上傳完成 API
        $(document).off("click", "#kgibank_approve_send_btn").on("click", "#kgibank_approve_send_btn", function () {
            $("#kgibank_approve_send_btn").prop("disabled", true);
            $("#kgibank_approve_send_btn").text("資料處理中");
            bank = 2;
            let case_no = $('#kgibankCaseNo').text();
            let comp_id = $('#kgibankCompId').text();
            if (case_no && comp_id) {
                getMappingMsgNo(caseId, 'send', 'image_complete', bank, (data) => {
                    msg_data = data;
                    msg_no = msg_data.data.msg_no;

                    if (msg_no) {
                        $.ajax({
                            type: "POST",
                            url: "/api/kgibank/v1/LoanRequest/apply_image_complete",
                            dataType: "json",
                            data: JSON.stringify({
                                'CaseNo': case_no,
                                'CompId': comp_id,
                                'MsgNo': msg_no,
                            }),
                            success: function (response) {
                                let kgibank_response = response.success ? '成功' : '失敗';
                                if (kgibank_response == '失敗') {
                                    $("#kgibank_approve_send_btn").prop("disabled", true);
                                }
                                alert(`送出結果 ： ${kgibank_response}\n回應內容 ： ${response.error}`);
                            }
                        });
                    } else {
                        alert(`凱基發送交易序號生成失敗`);
                    }
                    $("#kgibank_approve_send_btn").text("凱基 通過");
                });
            }
        });

        skbank_text_get(caseId, 1);
        kgibank_text_get(caseId, 2);

        // 取得案件核貸資料
        case_aprove_item = get_default_item(caseId);
        // 最高核貸層級
        let last_mask = 4;
        if(case_aprove_item && case_aprove_item.hasOwnProperty(`basicInfo`) && case_aprove_item.basicInfo.hasOwnProperty(`finalReviewedLevel`)){
            last_mask = case_aprove_item.basicInfo.finalReviewedLevel;
            for(i=1;i<=last_mask;i++){
                $(`#${i}_opinion_mask`).css("display","none");
                if(case_aprove_item.basicInfo.hasOwnProperty(`reviewedLevelList`) && case_aprove_item.basicInfo.reviewedLevelList.hasOwnProperty(`${i-1}`)){
                    let reviewer_name = case_aprove_item.basicInfo.reviewedLevelList[i-1];
                    $(`#${i}_opinion_mask`).text(`${reviewer_name}意見未送出`);
                }
            }
        }
        // 專家調整分數
        if(case_aprove_item && case_aprove_item.hasOwnProperty("creditLineInfo") && case_aprove_item.creditLineInfo.hasOwnProperty("scoringMin") && case_aprove_item.creditLineInfo.hasOwnProperty("scoringMax")){
            $(`.score_range`).text(`${case_aprove_item.creditLineInfo.scoringMin}~${case_aprove_item.creditLineInfo.scoringMax}`);
            $(`#1_score`).attr({
                "max" : case_aprove_item.creditLineInfo.scoringMax,
                "min" : case_aprove_item.creditLineInfo.scoringMin,
                "oninput" : `if(value>=${case_aprove_item.creditLineInfo.scoringMax})value=${case_aprove_item.creditLineInfo.scoringMax}`
            });
            $(`#2_score`).attr({
                "max" : case_aprove_item.creditLineInfo.scoringMax,
                "min" : case_aprove_item.creditLineInfo.scoringMin,
                "oninput" : `if(value>=${case_aprove_item.creditLineInfo.scoringMax})value=${case_aprove_item.creditLineInfo.scoringMax}`
            });
            $(`#3_score`).attr({
                "max" : case_aprove_item.creditLineInfo.scoringMax,
                "min" : case_aprove_item.creditLineInfo.scoringMin,
                "oninput" : `if(value>=${case_aprove_item.creditLineInfo.scoringMax})value=${case_aprove_item.creditLineInfo.scoringMax}`
            });
            $(`#4_score`).attr({
                "max" : case_aprove_item.creditLineInfo.scoringMax,
                "min" : case_aprove_item.creditLineInfo.scoringMin,
                "oninput" : `if(value>=${case_aprove_item.creditLineInfo.scoringMax})value=${case_aprove_item.creditLineInfo.scoringMax}`
            });
        }

        function changeReevaluationLoading(display = true) {
            if (!display) {
                $(".table-reevaluation p").css('background', 'white');
                $(".table-reevaluation p").css('animation-play-state', 'paused');
            } else {
                $(".table-reevaluation p").css('animation-play-state', 'running');
                $(".table-reevaluation p").css('background', 'linear-gradient(to right, #eeeeee 10%, #dddddd 18%, #eeeeee 33%)');
                $(".table-reevaluation p").css('animation-duration', '1.25s');
                $(".table-reevaluation p").css('background-size', '800px 104px');
            }
        }

        function clearCreditInfo(isReEvaluated = false) {
            var prefix = '';
            if (isReEvaluated) prefix = "new-";
            $("#" + prefix + "product-name").text('');
            $("#" + prefix + "credit-level").text('');
            $("#" + prefix + "credit-amount").text('');
            $("#" + prefix + "credit-points").text('');
            $("#" + prefix + "credit-created-at").text('');
            $("#" + prefix + "credit-expired-at").text('');
        }

        function fillCreditInfo(credit, isReEvaluated = false) {
            var prefix = '';
            if(credit.product.id == 1 && credit.product.sub_product_id == 9999){
                $('#credit-evaluation button').attr('disabled',false);
                $('#evaluation-complete [type=submit]').text('通過');
                $('#evaluation-complete [type=submit]').removeClass('btn-warning').addClass('btn-success');
                $('.changeCredit').hide();
            }
            if (isReEvaluated) prefix = "new-";
            $("#" + prefix + "product-name").text(credit.product.name);
            $("#" + prefix + "credit-level").text(credit.level);
            $("#" + prefix + "credit-amount").text(convertNumberSplitedByThousands(credit.amount));
            $("#" + prefix + "credit-points").text(credit.points);
            $("#" + prefix + "credit-created-at").text(credit.getCreatedAtAsDate());
            $("#" + prefix + "credit-expired-at").text(credit.getExpiredAtAsDate());
        }

        // 取得案件核貸資料
        case_aprove_data = get_report_data(caseId);
        if(case_aprove_data){
            Object.keys(case_aprove_data).forEach(function (area_name) {
                Object.keys(case_aprove_data[area_name]).forEach(function (input_title) {
                    if(input_title == 'reviewedInfoList'){
                        stop_flag = false;
                        let total_score = 0;
                        // 資料寫入
                        Object.keys(case_aprove_data[area_name][input_title]).forEach(function (list_key) {
                            $(`#${list_key}_name`).text(case_aprove_data[area_name][input_title][list_key]['name']);
                            $(`#${list_key}_apporvedTime`).text(case_aprove_data[area_name][input_title][list_key]['approvedTime']);
                            $(`#${list_key}_opinion`).val(case_aprove_data[area_name][input_title][list_key]['opinion']);
                            let score = case_aprove_data[area_name][input_title][list_key]['score'] && case_aprove_data[area_name][input_title][list_key]['score'] != '' ? parseInt(case_aprove_data[area_name][input_title][list_key]['score']) : 0;
                            total_score += score;
                            $(`#${list_key}_score`).val(score);
                        })
                        $('#credit_test').val(total_score);
                        // 顯示更改,核可層級解鎖
                        Object.keys(case_aprove_data[area_name][input_title]).forEach(function (list_key) {
                            status_html = get_status_icon('success');
                            $(`#${list_key}_opinion_status`).html(status_html);

                            // 前級審核未通過
                            if(stop_flag && case_aprove_data[area_name][input_title][list_key]['name'] == '' && case_aprove_data[area_name][input_title][list_key]['opinion'] == '' && case_aprove_data[area_name][input_title][list_key]['score'] == ''){
                                status_html = get_status_icon('pending');
                                $(`#${list_key}_opinion_status`).html(status_html);
                                $(`#${list_key}_opinion_mask`).show();
                            }

                            // 當前審核層級解鎖
                            if(!stop_flag && case_aprove_data[area_name][input_title][list_key]['name'] == '' && case_aprove_data[area_name][input_title][list_key]['opinion'] == '' && case_aprove_data[area_name][input_title][list_key]['score'] == ''){
                                status_html = get_status_icon('pending');
                                $(`#${list_key}_opinion_status`).html(status_html);
                                $(`#${list_key}_opinion`).prop('disabled', false);
                                $(`#${list_key}_score`).prop('disabled', false);
                                $(`#${list_key}_opinion_button`).prop('disabled', false);
                                stop_flag = true;
                            }
                        })
                    }
                })
            })
        }

        $( "#1_score,#2_score,#3_score,#4_score" ).change(function() {
            let score_vue = 0;
            for(i=1;i<=last_mask;i++){
                score_vue += parseInt($(`#${i}_score`).val());
            }
            $('#credit_test').val(score_vue);
        });

        function convertNumberSplitedByThousands(value) {
            var convertedNumbers = value;
            try {
                convertedNumbers = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } catch(err) {

            }
            return convertedNumbers;
        }

        $("#credit-evaluation").submit(function(e) {
            e.preventDefault();

            $('#credit-evaluation button').attr('disabled',true);
            if (relatedUserAjaxLock || targetInfoAjaxLock) {
                alert("請等待資料載入完成後，再行試算。");
                $('#credit-evaluation button').attr('disabled',false);
                return;
            }

            var form = $(this);
            var url = form.attr('action');
            var points = form.find('input[name="score"]').val();
            var remark = form.find('input[name="description"]').val();

            $.ajax({
                type: "GET",
                url: url + "?id=" + caseId + "&points=" + points,
                beforeSend: function () {
                    changeReevaluationLoading(true);
                    clearCreditInfo(true);
                },
                complete: function () {
                    changeReevaluationLoading(false);
                },
                success: function (response) {
                    if (response.status.code != 200) {
                        return;
                    }

                    let creditJson = response.response.credits;
                    credit = new Credit(creditJson);
                    fillCreditInfo(credit, true);
                    modifiedPoints = points;
                    $('#credit-evaluation button').attr('disabled',false);
                }
            });
        });
    });

    // 取得新光收件檢核表成功紀錄
    function skbank_text_get(target_id, bank){
        $.ajax({
              type: "GET",
              url: `/admin/target/skbank_text_get?target_id=${target_id}&bank=${bank}`,
              success: function (response) {
                  response = response.response;
                  if(response && response.length != 0){
                      Object.keys(response).forEach( (key) => {
                          $(`#${key}`).text(response[key]);
                          if(key == 'skbankMetaInfo'){
                              if(response[key] == '成功'){
                                  $("#skbank_text_send_btn").prop("disabled", true);
                                  $("#skbank_img_send_btn").prop("disabled", false);
                                  $("#skbank_approve_send_btn").prop("disabled", false);
                              }else{
                                  $("#skbank_img_send_btn").prop("disabled", true);
                                  $("#skbank_approve_send_btn").prop("disabled", true);
                              }
                          }
                      })
                  }else{
                      $("#skbank_img_send_btn").prop("disabled", true);
                      $("#skbank_approve_send_btn").prop("disabled", true);
                  }
              },
              error: function(error) {
                  alert(error);
              }
          });
    }

    // 凱基
    function kgibank_text_get(target_id, bank){
        $.ajax({
            type: "GET",
            url: `/admin/target/skbank_text_get?target_id=${target_id}&bank=${bank}`,
            success: function (response) {
                response = response.response;
                if (response && response.length != 0) {
                    Object.keys(response).forEach((key) => {
                        $(`#${key}`).text(response[key]);
                        if (key == 'kgibankMetaInfo') {
                            if (response[key] == '成功') {
                                $("#kgibank_text_send_btn").prop("disabled", true);
                                $("#kgibank_img_send_btn").prop("disabled", false);
                                $("#skbank_approve_send_btn").prop("disabled", false);
                            } else {
                                $("#kgibank_img_send_btn").prop("disabled", true);
                                $("#kgibank_approve_send_btn").prop("disabled", true);
                            }
                        }
                    })
                } else {
                    $("#kgibank_img_send_btn").prop("disabled", true);
                    $("#kgibank_approve_send_btn").prop("disabled", true);
                }
            },
            error: function (error) {
                alert(error);
            }
        });
    }


    // 授審表評分意見送出
    function send_opinion(target_id = '', group_id = ''){
        let score = $(`#${group_id}_score`).val();
        let opinion = $(`#${group_id}_opinion`).val();
        if(group_id && target_id){
            $.ajax({
                type: "POST",
                url: `/admin/creditmanagement/approve`,
                data: {
                    'target_id' : target_id,
                    'score' : score,
                    'opinion' : opinion,
                    'group' : group_id,
                    'type' : 'person',
                },
                async: false,
                success: function (response) {
                    alert(`${response.response.msg}`);
                },
                error: function(error) {
                    alert(error);
                }
            });
        }
    }

    // 取得授審表設定檔資料
    function get_default_item(target_id,type){
        let report_item = {};
        $.ajax({
            type: "GET",
            url: `/admin/creditmanagement/get_structural_data?target_id=${target_id}&type=company`,
            async: false,
            success: function (response) {
                report_item = response.response;
            },
            error: function(error) {
                alert(error);
            }
        });
        return report_item;
    }

    // 取得授審表案件核貸資料
    function get_report_data(target_id){
        let report_data = {};
        $.ajax({
            type: "GET",
            url: `/admin/creditmanagement/get_reviewed_list?target_id=${target_id}&type=company`,
            async: false,
            success: function (response) {
                report_data = response.response;
            },
            error: function(error) {
                alert(error);
            }
        });
        return report_data;
    }

    // 取得狀態icon
    function get_status_icon(status = 'default'){
        let status_icon = '';
        switch (status) {
            // 成功
            case 'success':
                status_icon = `<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>`;
                break;
            // 等待中
            case 'pending':
                status_icon = `<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>`;
                break;
            // 失敗
            case 'fail':
                status_icon = `<button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>`;
                break;
            // 未啟用
            default:
                status_icon = `<button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-minus"></i></button>`;
        }
        return status_icon;
    }

</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?=$productList[$targetInfo->product_id]['name'] ?> - 二審</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <iframe id="creditManagementTable" src="../creditmanagementtable/waiting_reinspection_report?target_id=<?=$get['target_id']?>&table_type=management" scrolling='no' ></iframe>
                    <iframe id="riskPage" src="../Risk/juridical_person?target_id=<?=$get['target_id']?>&investor=0&company=1" scrolling='no' ></iframe>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                反詐欺管理指標-狀態
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table id="brookesia_results" class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr class="odd list">
                                                    <th width="25%">風險等級</th>
                                                    <th width="30%">事件時間</th>
                                                    <th width="45%">指標內容</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                司法院查詢
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table id="judicial-yuan" class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr class="odd list">
                                                    <th width="70%">裁判案由</th>
                                                    <th width="30%">總數</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                關聯戶
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive center-text">
                                            <table id="related-users" class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr class="odd list">
                                                    <th width="20%">使用者編號</th>
                                                    <th width="50%">關聯原因</th>
                                                    <th width="30%">關聯值</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="odd list">
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static"></p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <button id="load-more" class="btn btn-default">載入更多</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">額度試算</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td class="table-field center-text"><p>產品</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-product-name"></p>
                                            </td>
                                            <td class="table-field center-text"><p>信用等級</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-credit-level"></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-field center-text"><p>信用評分</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-credit-points"></p>
                                            </td>
                                            <td class="table-field center-text"><p>信用額度</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-credit-amount"></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="table-field center-text"><p>核准時間</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-credit-created-at"></p>
                                            </td>
                                            <td class="table-field center-text"><p>有效時間</p></td>
                                            <td class="center-text table-reevaluation">
                                                <p id="new-credit-expired-at"></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form id="credit-evaluation" method="POST" action="/admin/Target/credits">
                                    <div class="col-lg-12 text-center">
                                        <input id="credit_test" type="text" name="score" value="0"/ disabled>
                                        <button class="btn btn-warning" type="submit">額度試算</button>
                                        <button class="btn btn-info"  onclick="window.open('/admin/Enterprise/credit_sheet/<?=isset($targetInfo)?$targetInfo->id:0?>', '_blank')">查看評分表</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">審批</div>
                            <div class="panel-body" id="opinion">
                                <div class="opinion_item">
                                    <div id="1_opinion_mask" class="mask" style="margin: unset;">非核貸最高層級</div>
                                    <div id="1_opinion_status" class="opinion_status">
                                        <button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <div class="opinion_info">
                                        <div>
                                            <span style="width:30%;display:flex;align-items:center;">一審結果：</span>
                                            <span style="width:70%;"><textarea id="1_opinion" type="text" placeholder="請輸入..." value="" disabled></textarea></span>
                                        </div>
                                        <div>
                                <span style="width:30%;">
                                    <span>分數調整</span>
                                    <span class="score_range"></span>
                                    <span>：</span>
                                </span>
                                            <span style="width:70%;"><input id="1_score" type="number" value="0" min="0" step="1" disabled></span>
                                        </div>
                                        <div><span style="width:30%;">姓名：</span><span id="1_name"></span></div>
                                        <div><span style="width:30%;">時間：</span><span id="1_approvedTime"></span></div>
                                    </div>
                                    <div class="opinion_button">
                                        <button id="1_opinion_button" class="btn btn-primary btn-info score" onclick="send_opinion(<?=$_GET['target_id']?>,1)" disabled>送出</button>
                                    </div>
                                </div>
                                <div class="opinion_item">
                                    <div id="2_opinion_mask" class="mask" style="margin: unset;">非核貸最高層級</div>
                                    <div id="2_opinion_status" class="opinion_status">
                                        <button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <div class="opinion_info">
                                        <div>
                                            <span style="width:30%;display:flex;align-items:center;">二審意見：</span>
                                            <span style="width:70%;"><textarea id="2_opinion" type="text" placeholder="請輸入..." value="" disabled></textarea></span>
                                        </div>
                                        <div>
                                <span style="width:30%;">
                                    <span>分數調整</span>
                                    <span class="score_range"></span>
                                    <span>：</span>
                                </span>
                                            <span style="width:70%;"><input id="2_score" type="number" value="0" min="0" step="1" disabled></span>
                                        </div>
                                        <div><span style="width:30%;">姓名：</span><span id="2_name"></span></div>
                                        <div><span style="width:30%;">時間：</span><span id="2_approvedTime"></span></div>
                                    </div>
                                    <div class="opinion_button">
                                        <button id="2_opinion_button" class="btn btn-primary btn-info score" onclick="send_opinion(<?=$_GET['target_id']?>,2)" disabled>送出</button>
                                    </div>
                                </div>
                                <div class="opinion_item">
                                    <div id="3_opinion_mask" class="mask" style="margin: unset;">非核貸最高層級</div>
                                    <div id="3_opinion_status" class="opinion_status">
                                        <button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <div class="opinion_info">
                                        <div>
                                            <span style="width:30%;display:flex;align-items:center;">風控長意見：</span>
                                            <span style="width:70%;"><textarea id="3_opinion" type="text" placeholder="請輸入..." value="" disabled></textarea></span>
                                        </div>
                                        <div>
                                <span style="width:30%;">
                                    <span>分數調整</span>
                                    <span class="score_range"></span>
                                    <span>：</span>
                                </span>
                                            <span style="width:70%;"><input id="3_score" type="number" value="0" min="0" step="1" disabled></span>
                                        </div>
                                        <div><span style="width:30%;">姓名：</span><span id="3_name"></span></div>
                                        <div><span style="width:30%;">時間：</span><span id="3_approvedTime"></span></div>
                                    </div>
                                    <div class="opinion_button">
                                        <button id="3_opinion_button" class="btn btn-primary btn-info score" onclick="send_opinion(<?=$_GET['target_id']?>,3)" disabled>送出</button>
                                    </div>
                                </div>
                                <div class="opinion_item">
                                    <div id="4_opinion_mask" class="mask" style="margin: unset;">非核貸最高層級</div>
                                    <div id="4_opinion_status" class="opinion_status">
                                        <button type="button" class="btn btn-secondary btn-circle"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <div class="opinion_info">
                                        <div>
                                            <span style="width:30%;display:flex;align-items:center;">總經理意見：</span>
                                            <span style="width:70%;"><textarea id="4_opinion" type="text" placeholder="請輸入..." value="" disabled></textarea></span>
                                        </div>
                                        <div>
                                <span style="width:30%;">
                                    <span>分數調整</span>
                                    <span class="score_range"></span>
                                    <span>：</span>
                                </span>
                                            <span style="width:70%;"><input id="4_score" type="number" value="0" min="0" step="1" disabled></span>
                                        </div>
                                        <div><span style="width:30%;">姓名：</span><span id="4_name"></span></div>
                                        <div><span style="width:30%;">時間：</span><span id="4_approvedTime"></span></div>
                                    </div>
                                    <div class="opinion_button">
                                        <button id="4_opinion_button" class="btn btn-primary btn-info score" onclick="send_opinion(<?=$_GET['target_id']?>,4)" disabled>送出</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? if($targetInfo->product_id == 1002){ ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">新光送出回應</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive center-text">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr class="odd list">
                                                    <th width="20%" hidden>統一編號</th>
                                                    <th width="20%">申請序號</th>
                                                    <th width="20%">案件編號</th>
                                                    <th width="20%">送出結果</th>
                                                    <th width="40%">回應內容</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="odd list">
                                                    <td class="center-text fake-fields" hidden>
                                                        <p class="form-control-static" id="skbankCompId"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="skbankMsgNo"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="skbankCaseNo"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="skbankReturn"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="skbankMetaInfo"></p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? } ?>
                     <? if($targetInfo->product_id == 1002){ ?>
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">凱基送出回應</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive center-text">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                <tr class="odd list">
                                                    <th width="20%" hidden>統一編號</th>
                                                    <th width="20%">申請序號</th>
                                                    <th width="20%">案件編號</th>
                                                    <th width="20%">送出結果</th>
                                                    <th width="40%">回應內容</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="odd list">
                                                    <td class="center-text fake-fields" hidden>
                                                        <p class="form-control-static" id="kgibankCompId"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="kgibankMsgNo"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="kgibankCaseNo"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="kgibankReturn"></p>
                                                    </td>
                                                    <td class="center-text fake-fields">
                                                        <p class="form-control-static" id="kgibankMetaInfo"></p>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <? } ?>
                    <div class="targetAction">
                        <? if($active){ ?>
                        <div class="mb-2">
                            <button id="manual_handling" class="btn btn-primary btn-warning" onclick="">轉人工</button>
                            <button id="closeWindow" class="btn btn-primary btn-danger" onclick="">關閉視窗</button>
                        </div>
                        <? } ?>
                        <? if($targetInfo->product_id == 1002){ ?>
                            <div>
                                <div>
                                    <button id="skbank_text_send_btn" class="btn btn-primary btn-info" onclick="">新光 收件檢核表送出</button>
                                    <button id="skbank_img_send_btn" class="btn btn-primary btn-info" onclick="">新光 圖片送出</button>
                                    <button id="skbank_approve_send_btn" class="btn btn-primary btn-primary" onclick="">新光 通過</button>
                                </div>
                                <div class="mt-2">
                                    <button id="kgibank_text_send_btn" class="btn btn-primary btn-info" onclick="">凱基 收件檢核表送出</button>
                                    <button id="kgibank_img_send_btn" class="btn btn-primary btn-info" onclick="">凱基 圖片送出</button>
                                    <button id="kgibank_approve_send_btn" class="btn btn-primary btn-primary" onclick="">凱基 通過</button>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->
