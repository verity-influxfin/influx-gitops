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
    #opinion{
        width: 100%;
    }
    #opinion td br {
        display: block;
        padding: 3px 0;
        content: "";
    }
    #opinion td {
        padding: 13px 0px 13px 10px;
        height: 60px;
        border-bottom: 1px solid #bdbdbd;
    }
    #opinion td.icon{
        width: 40px;
        text-align: center;
    }
    #opinion td.title{
        width: 120px!important;
    }
    #opinion td.input{
        padding-left: 0px;
    }
    #opinion td input[type="text"]{
        width: 100%;
    }
    #opinion td input[type="number"]{
        width: 50px;
    }
    #opinion td.updatedTime{
        width: 220px;
    }
    #opinion td.button{
        text-align: right;
        width: 430px;
    }
    #opinion td.button button{
        width: 150px;
        margin: 0 0px 0 12px;
    }
    .targetAction{
        width: 100%;
        text-align: center;
    }
    .targetAction button{
        width: 200px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        var caseId = <?=isset($targetInfo)?$targetInfo->id:''?>;
        var userId = <?=isset($targetInfo)?$targetInfo->user_id:''?>;
        var targetInfoAjaxLock = false;
        var relatedUserAjaxLock = false;

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
                url: "/admin/target/skbank_text_send" + "?target_id=" + caseId,
                success: function (response) {
                    if(response.status.code == 200){
                        $('#skbankCompId').text(response.CompId);
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
                                   $("#skbank_img_send_btn").prop("disabled", true);

                               }
                               alert(`新光送出結果 ： ${skbank_response}\n回應內容 ： ${response.error}\n新光案件編號 ： ${response.case_no}\n新光交易序號 ： ${response.msg_no}\n新光送出資料資訊 ： ${response.meta_info}\n`);
                           },
                           error: function(error) {
                             alert(error);
                           }
                       });
                       $("#skbank_text_send_btn").prop("disabled", false);
                       $("#skbank_text_send_btn").text("收件檢核表送出");
                    }
                }
            });
        });

        // 新光圖片附件送出
        $(document).off("click","#skbank_img_send_btn").on("click","#skbank_img_send_btn" ,  function(){
            $("#skbank_img_send_btn").prop("disabled", true);
            $("#skbank_img_send_btn").text("資料處理中");
            $.ajax({
                type: "GET",
                url: "/admin/target/skbank_image_get" + "?target_id=" + caseId,
                success: function (response) {
                  if(response.status.code == 200){
                      let case_no = $('#skbankMsgNo').text();
                      let comp_id = $('#skbankCompId').text();
                      if(case_no && comp_id){
                          let request_data = [];
                          let data_count = Object.keys(response.response).length;
                          Object.keys(response.response).forEach( (image_type_key) => {
                              Object.keys(response.response[image_type_key]).forEach( (key) => {
                                  console.log(response.response[image_type_key][key]);
                                  let skbank_image_type = image_type_key;
                                  let skbank_image_url = response.response[image_type_key][key];
                                  let image_count = key;
                                  getMappingMsgNo(caseId, 'send', image_type_key,  (data) => {
                                      msg_data = data;
                                      msg_no = msg_data.data.msg_no;
                                      request_data.push({
                                          'MsgNo' : msg_no,
                                          'CompId' : comp_id,
                                          'CaseNo' : case_no,
                                          'DocType' : image_type_key,
                                          'DocSeq' : parseInt(image_count)+1,
                                          'DocFileType' : 4,
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
                                                alert(response);
                                              },
                                              error: function(error) {
                                                alert(error);
                                              }
                                          });
                                          $("#skbank_img_send_btn").prop("disabled", false);
                                          $("#skbank_img_send_btn").text("圖片送出");
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

        function getMappingMsgNo(target_id,action,data_type,result){
      	  $.ajax({
                type: "GET",
                url: `/admin/bankdata/getMappingMsgNo?target_id=${target_id}&action=${action}&data_type=${data_type}`,
                success: function (response) {
                    response = response.response;
                    result(response);
                },
                error: function(error) {
                    alert(error);
                }
            });
        }

    });
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
                    <iframe id="creditManagementTable" src="../creditmanagementtable/report?target_id=<?=$get['target_id']?>&table_type=management" scrolling='no' ></iframe>
                    <iframe id="riskPage" src="../Risk/index?target_id=<?=$get['target_id']?>&investor=0&company=1" scrolling='no' ></iframe>
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
                            <div class="panel-heading">
                                審批
                            </div>
                            <?
                            $target_data = json_decode($targetInfo->target_data);

                            //二審意見
                            $reinspection_opinion = 'reinspection_opinion';
                            $reinspectionOpinion = isset($target_data->reinspection->$reinspection_opinion) ? $target_data->reinspection->$reinspection_opinion : '';
                            $reinspectionStatus = $reinspectionOpinion ? 'success' : 'warning';
                            $reinspectionStatusColor = $reinspectionOpinion ? 'check' : 'refresh';
                            //風控長意見
                            $CRO_opinion = 'CRO_opinion';
                            $CROOpinion = isset($target_data->reinspection->$CRO_opinion) ? $target_data->reinspection->$CRO_opinion : '';
                            $CROOpinionStatus = $CROOpinion ? 'success' : 'warning';
                            $CROOpinionStatusColor = $CROOpinion ? 'check' : 'refresh';
                            //總經理意見
                            $general_manager_opinion = 'general_manager_opinion';
                            $generalManagerOpinion = isset($target_data->reinspection->$general_manager_opinion) ? $target_data->reinspection->$general_manager_opinion : '';
                            $generalManageStatus = $generalManagerOpinion ? 'success' : 'warning';
                            $generalManageStatusColor = $generalManagerOpinion ? 'check' : 'refresh';
                            $active = $targetInfo->status == TARGET_WAITING_VERIFY && $targetInfo->sub_status ==TARGET_SUBSTATUS_SECOND_INSTANCE;
                            ?>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table id="opinion">
                                            <tr>
                                                <td class="icon">
                                                    <button type="button" class="btn btn-<?=$reinspectionStatus?> btn-circle"><i
                                                                class="fa fa-<?=$reinspectionStatusColor?>"></i></button>
                                                </td>
                                                <td class="title">二審意見：</td>
                                                <td><input id="<?= $reinspection_opinion ?>"
                                                           type="text"
                                                           placeholder="請輸入..."
                                                           value="<?= isset($reinspectionOpinion->comment) ? end($reinspectionOpinion->comment) : ""; ?>"<?=!$active?' disabled':''?>/>
                                                </td>
                                                <td class="updatedTime">
                                                    <?= isset($reinspectionOpinion->comment) ? '更新時間：'.date('Y/m/d H:i:s', key($reinspectionOpinion->comment)) : ""; ?></td>
                                                <td class="button">
                                                    <? if($active){ ?>
                                                    <button id="<?= $reinspection_opinion ?>" class="btn btn-primary btn-success submit comment" onclick=""><?echo isset($reinspectionOpinion->comment) ? '更新' : "送出"; ?>意見
                                                    </button>
                                                    <? } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="icon">
                                                    <button type="button" class="btn btn-<?=$CROOpinionStatus?> btn-circle"><i
                                                                class="fa fa-<?=$CROOpinionStatusColor?>"></i></button>
                                                </td>
                                                <td class="title">風控長意見：<br/>專業調整分數：</td>
                                                <td><input id="<?= $CRO_opinion ?>"
                                                           type="text"
                                                           placeholder="請輸入..."
                                                           value="<?= isset($CROOpinion->comment) ? end($CROOpinion->comment) : ""; ?>"<?=!$active?' disabled':''?> /><br/>
                                                    <input id="<?= $CRO_opinion ?>"
                                                           type="number"
                                                           value="<?= isset($CROOpinion->score) ? end($CROOpinion->score) : ""; ?>"
                                                           min="0"
                                                           step="1" max="10"<?=!$active?' disabled':''?> />
                                                </td>
                                                <td class="updatedTime">
                                                    <?= isset($CROOpinion->comment) ? '更新時間：'.date('Y/m/d H:i:s', key($CROOpinion->comment)) : ""; ?>
                                                    <br/><?= isset($CROOpinion->score) ? '更新時間：'.date('Y/m/d H:i:s', key($CROOpinion->score)) : ""; ?>
                                                </td>
                                                <td class="button">
                                                    <? if($active){ ?>
                                                    <button id="<?= $CRO_opinion ?>" class="btn btn-primary btn-info score" onclick=""><?echo isset($CROOpinion->score) ? '更新' : "送出"; ?>評分
                                                    </button>
                                                    <button id="<?= $CRO_opinion ?>" class="btn btn-primary btn-success comment" onclick=""><?echo isset($CROOpinion->comment) ? '更新' : "送出"; ?>意見
                                                    </button>
                                                    <? } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="icon">
                                                    <button type="button" class="btn btn-<?=$generalManageStatus?> btn-circle"><i
                                                                class="fa fa-<?=$generalManageStatusColor?>"></i></button>
                                                </td>
                                                <td class="title">總經理意見：<br/>專業調整分數：</td>
                                                <td class="input"><input id="<?= $general_manager_opinion ?>"
                                                                         type="text"
                                                                         placeholder="請輸入..."
                                                                         value="<?= isset($generalManagerOpinion->comment) ? end($generalManagerOpinion->comment) : ""; ?>"<?=!$active?' disabled':''?> /><br/>
                                                    <input id="<?= $general_manager_opinion ?>"
                                                           type="number"
                                                           value="<?= isset($generalManagerOpinion->score) ? end($generalManagerOpinion->score) : ""; ?>"
                                                           min="0"
                                                           step="1" max="10"<?=!$active?' disabled':''?> />
                                                </td>
                                                <td class="updatedTime">
                                                    <?= isset($generalManagerOpinion->comment) ? '更新時間：'.date('Y/m/d H:i:s', key($generalManagerOpinion->comment)) : ""; ?>
                                                    <br/><?= isset($generalManagerOpinion->score) ? '更新時間：'.date('Y/m/d H:i:s', key($generalManagerOpinion->score)) : ""; ?>
                                                </td>
                                                <td class="button">
                                                    <? if($active){ ?>
                                                    <button id="<?= $general_manager_opinion ?>" class="btn btn-primary btn-info submit score" onclick=""><?echo isset($generalManagerOpinion->score) ? '修改' : "更新"; ?>評分
                                                    </button>
                                                    <button id="<?= $general_manager_opinion ?>" class="btn btn-primary btn-success submit comment" onclick=""><?echo isset($generalManagerOpinion->comment) ? '修改' : "更新"; ?>意見
                                                    </button>
                                                    <? } ?>
                                                </td>
                                            </tr>
                                        </table>
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
                    <div class="targetAction">
                        <? if($active){ ?>
                        <button id="manual_handling" class="btn btn-primary btn-warning" onclick="">轉人工</button>
                        <button id="closeWindow" class="btn btn-primary btn-danger" onclick="">關閉視窗</button>
                        <? } ?>
                        <? if($targetInfo->product_id == 1002){ ?>
                            <button id="skbank_text_send_btn" class="btn btn-primary btn-info" onclick="">收件檢核表送出</button>
                            <button id="skbank_img_send_btn" class="btn btn-primary btn-info" onclick="" disabled>圖片送出</button>
                            <button id="skbank_approve_send_btn" class="btn btn-primary btn-primary" onclick="" disabled>通過</button>
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
