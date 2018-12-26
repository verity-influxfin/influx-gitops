<style type="text/css">
body{
	font-family: 微軟正黑體;
}
#bwindow{display:none;}
#bwindow.bshow{display:block;z-index:9998;}
#bwindow,#bpopup.bshow{position:absolute;width:100%;height:100%;}
#bpopup.bshow{z-index:9997;background-color:black;opacity:0.4;}
#bwindow.bshow .bcontent{
  z-index:9999;
  margin:50px auto;
  padding:16px 26px 2px;
  background-color:white;
  border-radius:2px;
  min-width:20px;
  min-height:200px;
  max-width:700px;
  -webkit-box-shadow: 0px 0px 11px 1px rgba(0,0,0,0.75);
  -moz-box-shadow: 0px 0px 11px 1px rgba(0,0,0,0.75);
  box-shadow: 0px 0px 11px 1px rgba(0,0,0,0.75);
}
#bwindow.bshow .bcontent .x{float:right;cursor:pointer;color:#5b554d;font-size: 22px;margin: -7px -14px 2px 0px;}
#bwindow.bshow .bcontent .x:hover{color:#428bca;font-weight:bold;}
.bcontent label {width:16%;}
.bcontent form-control {display: inline-block;}
.bcontent .row{padding-top:22px;}
textarea{height: 300px!important;width: 100%!important;}
.barLink a{
    display: inline-block;
    margin: 19px 9px;
    font-size: 20px;
    font-weight: bold;
    border-bottom: 3px #e2e2e2 solid;
    padding: 2px 5px;
    cursor:pointer;
    text-decoration: none;
    color:black;
}.bcontent label {
    10px;
}

.bcontent form-control {
    display: inline-block;
    width: 80%;
}
.barLink a:hover{border-bottom: 3px #ccc solid;}
.barLink a.active{border-bottom-color: #988977;cursor:auto;}
.panel-heading{font-size: 16px;letter-spacing: 0.5px;font-weight: bold;}
.panel a{margin:0 0 0 12px;}
.r,.r:hover{background-color:whitesmoke;color:#ccc;border-color:#ddd;cursor:auto;}
.empty{width:100%;text-align:center;}
thead{background-color:#428bca;color:white;font-size:16px;}
th{text-align:center;}
tbody td{text-align:center;}
.nr input{border:0px;-webkit-box-shadow: none;box-shadow: none;background-color:white!important;cursor:auto!important;}
#page-wrapper{margin-left:0px;}
</style>
	<script>
	$(window).ready(function() {
		$.each($('.nex,.ent'),function(a, b){$(this).text(cvtime($(this).text()));});
	});
		function form_onsubmit(){
			return true;
		}
		function totimestamp(iso){return new Date(iso+"+0000").getTime().toString().substr(0,10);}
		function isotime(timestamp){return new Date(parseInt(timestamp)).toISOString().substr(0,19);}
		function nowtime(){return $.now()+28800000;}
		function cvtime(timestamp){
			var date=new Date(timestamp*1000),year=date.getFullYear(),month="0"+(date.getMonth()+1),day="0"+date.getDate(),hours="0"+date.getHours(),minutes="0"+date.getMinutes(),seconds="0"+date.getSeconds();
			return  year+'/'+month.substr(-2)+'/'+day.substr(-2)+' '+hours.substr(-2)+':'+minutes.substr(-2)+':'+seconds.substr(-2);
		}
		function bopen(){$("#bpopup,#bwindow").addClass('bshow');$('#next_time').attr('min',isotime(nowtime()));}
		function bclose(){$("#bpopup,#bwindow").removeClass('bshow');}
		$(document).off("click",".bopen").on("click",".bopen" , function(){bopen();$('#submit').hide();$('#next_time').val(isotime(nowtime()));});
		$(document).off("click",".x").on("click",".x" , function(){bclose();});
		$('#submit').hide();
		$(document).off("keyup","textarea").on("keyup","textarea" , function(){
			$('textarea').val()!=""?$('#submit').show():$('#submit').hide();
		});
		$(document).off("click","#submit").on("click","#submit" , function(){
			var arr=[],sta=1;
			$.each($('input, select:not(:disabled),textarea'),function(a, b){
				var input = $(this);
				input.val()==""?(a>3?sta=0:""):arr.push(input.val());
			});
			if(sta==1){$('#submit').hide();
				$.ajax({type:'POST',async:true,url:'<?=admin_url('risk/push_audit_add')."?id=".(isset($id)?$id:"") ?>',data:'&remark='+arr[0]+'&product_level='+arr[1]+'&next_push='+totimestamp(arr[2])+'&result='+arr[3]+'&end_time='+totimestamp(isotime(nowtime())),success: function() {
					window.location= "<?=admin_url('risk/push_audit')."?id=".(isset($id)?$id:"") ?>";
				}});								
			}

		});		
	</script>
	<div id="bpopup"></div><div id="bwindow"><div class="bcontent"><div class="x">X</div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading<?=isset($slist)?" r":"" ?>">新增處理意見</div>
                        <div class="panel-body">
							<form role="form" method="post" onsubmit="return form_onsubmit();" > 
								<div class="form-group" data-type="t">
									<label>意見備註表</label>
									<textarea class="form-control" rows="3" maxlength="2000" placeholder="最多可填寫2000中文字"></textarea>
								</div>
								<div class="form-group" data-type="s">
									<label>資產等級</label>
									<select class="form-control">
									<? foreach($product_level as $key => $value){ ?>
										<option value="<?=$key?>"><?=$value?></option>
									<? } ?>
									</select>
								</div>
								<div class="wr form-group nr" data-type="i">
									<label>系統標準</label>
									<input class="form-control" value="" disabled />
								</div>
								<div class="form-group" data-type="i">
									<label>下次催收時間</label>
									<input id="next_time" class="form-control" type="datetime-local" class="form-control" value="" />
								</div>
								<div class="form-group" data-type="s">
									<label>處理結果</label>
									<select class="form-control">
										<option>同意</option>
									</select>
								</div>
							<div style="width:100%;text-align:center"><div id="submit" class="btn btn-default">提交</div></div>
							</form>
                        </div>
                    </div>
                </div>
            </div>
	</div></div>
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="barLink">
					<a href="<? echo admin_url('target/edit')."?risk=1&id=".$id.(isset($slist)?"&slist=1":"") ?>">標的資訊</a>
					<a href="<? echo admin_url('risk/push_info')."?id=".$id.(isset($slist)?"&slist=1":"") ?>">催收資訊</a>
					<a class="active">催收審批</a>
				</div>
			</div>
		</div>
		<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							處理意見跟進表<a class="btn btn-default bopen<?=isset($slist)?" r":"" ?>">新增處理意見</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>處理人</th>
                                            <th>角色</th>
                                            <th>收到請求時間</th>
                                            <th>處理完畢時間</th>
                                            <th>資產等級人工調整</th>
											<th>下次催收時間</th>
                                            <th>處理結果</th>
                                            <th style="width: 50%;">意見備註</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 						
									if($list!=null){
										foreach($list as $key => $value){
									?>
                                        <tr>
                                            <td><?=isset($value->admin_name)?$value->admin_name:"" ?></td>
                                            <td><?=isset($role_name[$value->role_id])?$role_name[$value->role_id]:"" ?></td>
                                           	<td><?=isset($value->start_time)?$value->start_time:"" ?></td>
                                           	<td class="ent" time="<?=isset($value->end_time)?$value->end_time:"" ?>"><?=isset($value->end_time)?$value->end_time-28800:"" ?></td>
                                           	<td><?=isset($value->product_level)?$product_level[$value->product_level]:"" ?></td>
                                           	<td class="nex" time="<?=isset($value->next_push)?$value->next_push:"" ?>"><?=isset($value->next_push)?$value->next_push-28800:"" ?></td>
                                           	<td><?=isset($value->result)?$value->result:"" ?></td>
                                           	<td><?=isset($value->remark)?$value->remark:"" ?></td>
                                        </tr>                                        
									<?php 
										}
									}
									else{
										echo "<tr class='empty'><td colspan='8'>目前沒有任何審批資訊</td></tr>";
									}
									?>		
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
    </div>
