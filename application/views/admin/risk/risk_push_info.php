<style type="text/css">
body{
	font-family: 微軟正黑體;
}
th:last-child{
  width:226px;
}
#bwindow{display:none;}
#bwindow.brshow{display:block;z-index:9998;}
#bwindow,#brpopup.brshow{position:absolute;width:100%;height:100%;}
#brpopup.brshow{z-index:9997;background-color:black;opacity:0.4;}
#bwindow.brshow .brcontent{
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
#bwindow.brshow .brcontent .x{float:right;cursor:pointer;color:#5b554d;font-size: 22px;margin: -7px -14px 2px 0px;}
#bwindow.brshow .brcontent .x:hover{color:#428bca;font-weight:bold;}
textarea{height:160px!important;}
.brcontent label {width:16%;}
.brcontent form-control {display: inline-block;}
.brcontent .row{padding-top:22px;}
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
}
.barLink a:hover{border-bottom: 3px #ccc solid;}
.barLink a.active{border-bottom-color: #988977;cursor:auto;}
.panel-heading{font-size: 16px;letter-spacing: 0.5px;font-weight: bold;}
.panel a{margin:0 0 0 12px;}
.nr input{border:0px;-webkit-box-shadow: none;box-shadow: none;background-color:white!important;cursor:auto!important;}
.r,.r:hover{background-color:whitesmoke;color:#ccc;border-color:#ddd;cursor:auto;}
td{vertical-align:middle!important;}
th{text-align:center;}
tbody td{text-align:center;}
.empty{width:100%;text-align:center;}
thead{background-color:#555;color:white;font-size:16px;}
#page-wrapper{margin-left:0px;}
</style>
<script>
$(window).ready(function() {
	$.each($('#stt,#ent'),function(a, b){$(this).text(cvtime($(this).text()));});
});
	function form_onsubmit(){
		return true;
	}
	function totimestamp(iso){return new Date(iso+"+0000").getTime().toString().substr(0,10);}
	function isotime(timestamp){return new Date(parseInt(timestamp)).toISOString().substr(0,19);}
	function nowtime(){return $.now()+28800000;}
	function cvtime(timestamp){
		var date=new Date(timestamp*1000),year=date.getFullYear(),month="0"+(date.getMonth()+1),day="0"+date.getDate(),hours=date.getHours(),minutes="0"+date.getMinutes(),seconds="0"+date.getSeconds();
		return  year+'/'+month.substr(-2)+'/'+day.substr(-2)+' '+hours+':'+minutes.substr(-2)+':'+seconds.substr(-2);
	}
	function bclose(){
		$("#brpopup,#bwindow").removeClass('brshow');
	}		
	function bopen(to){
		var id=to.attr('id'),item=$('select,input,textarea');
		$('.brcontent .panel-heading').text((id=="create"?"新增":(id=="check"?"查看":"編輯"))+"處理過程");
		id=="check"?item.attr('disabled',true):(item.attr('disabled',false),$('.wr,.ws,.form-group').show());
		$(":selected").attr('selected', false);$('input,textarea').val('');$('#submit').hide();$('#start_time,#end_time').attr("readonly", true);
		$('#start_time').attr('min',isotime(nowtime()));
		if(id!="create"){
			$('.form-group:not(.wr)').each(function(a,b){
				var type=$(this).data('type'),
					data=$('[data-id='+to.closest('tr').data('id')+'] td'),
					name=$(this).find('option:contains("'+data.eq(1).text()+'")'),
					phone=$(this).find('option:contains("'+data.eq(2).text()+'")'),
					result=$(this).find('option:contains("'+data.eq(3).text()+'")'),
					push_by=$(this).find('option:contains("'+data.eq(4).text()+'")'),
					wri=data.eq(a+1).text();
				a==0?(name.length!=0?(name.attr('selected', true),$('.wr input').eq(0).val(''),$('.wr').eq(0).hide(),$('.form-group').eq(0).show()):($('.wr input').eq(0).val(data.eq(1).text()),$('.wr').eq(0).show(),$('.form-group').eq(0).hide(),$('.ws').eq(0).hide(),$('.form-group select').eq(0).attr('disabled',true))):"";
				a==1?(phone.length!=0?(phone.attr('selected', true),$('.wr input').eq(1).val(''),$('.wr').eq(1).hide(),$('.form-group').eq(2).show()):($('.wr input').eq(1).val(data.eq(2).text()),$('.wr').eq(1).show(),$('.form-group').eq(2).hide(),$('.ws').eq(1).hide(),$('.form-group select').eq(1).attr('disabled',true))):"";
				a==2?(result.length!=0?result.attr('selected', true):""):"";
				a==3?(push_by.length!=0?push_by.attr('selected', true):""):"";
				a==5||a==6?$(this).find('input').val(isotime(data.eq(a).attr('time')+"000")).attr("readonly", false):"";
				if(type=="i"&&a<4){$(this).find('input').val(wri);}
				else if(type=="t"&&a==4){$(this).find('textarea').val(data.eq(7).text());}
			});
			id=="edit"?($('.ws,.form-group').show(),$('#submit').show(),$('.brcontent .panel-heading').attr('id',to.closest('tr').data('id'))):"";
		}
		else{
			$('#submit').hide();
			$('#start_time').val(isotime(nowtime()));
		}
		$("#brpopup,#bwindow").addClass('brshow');
	}
	$(document).off("click",".bopen:not(.r)").on("click",".bopen:not(.r)" , function(){bopen($(this));});
	$(document).off("click",".x").on("click",".x" , function(){bclose();});

	$(document).off("keydown","input").on("keydown","input" , function(){!$(this).parent().prev().find('select').hasClass('nos')?$(this).parent().prev().find('select').attr("disabled",true):"";}); 
	$(document).off("keyup","input:not(.nr),textarea").on("keyup","input:not(.nr),textarea" , function(){
		$(this).val()==""?$(this).parent().prev().find('select').attr("disabled",false):"";
		$('textarea').val()!=""?$('#submit').show():$('#submit').hide();
	});
	$(document).off("click","#submit").on("click","#submit" , function(){
		var arr=[],sta=1;
		$('#end_time').val(isotime(nowtime()));
		$('#submit').hide();
		$.each($('input, select:not(:disabled),textarea'),function(a, b){
			var input = $(this);
			input.val()==""?(a==4?sta=0:""):arr.push(input.val());
		});
		if(sta==1){
			if($('.brcontent .panel-heading').text().indexOf('新增')!=-1){	
				$.ajax({type:'POST',async:true,url:'<?=admin_url('risk/push_info_add')."?id=".(isset($list[0]->target_id)?$list[0]->target_id:"") ?>&a='+arr[0]+'&b='+arr[1]+'&c='+arr[2]+'&d='+arr[3]+'&e='+arr[4]+'&f='+totimestamp(arr[5])+'&g='+totimestamp(arr[6]),success: function() {
					window.location= "<?=admin_url('risk/push_info')."?id=".(isset($list[0]->target_id)?$list[0]->target_id:"") ?>";
				}});								
			}
			else{
				$.ajax({type:'POST',async:true,url:'<?=admin_url('risk/push_info_update')."?id=" ?>'+$(".brcontent .panel-heading").attr('id')+'&a='+arr[0]+'&b='+arr[1]+'&c='+arr[2]+'&d='+arr[3]+'&e='+arr[4]+'&f='+totimestamp(arr[5])+'&g='+totimestamp(arr[6]),success: function() {
					window.location= "<?=admin_url('risk/push_info')."?id=".(isset($list[0]->target_id)?$list[0]->target_id:"") ?>";
				}});					
			}
		}

	});
	$(document).off("click",".del:not(.r)").on("click",".del:not(.r)" , function(){
		var r = confirm("是否要刪除該筆資料?");
		  if (r == true) {
			$('.del').attr('disabled',true);
			$.ajax({type:'POST',async:true,url:'<?=admin_url('risk/push_info_remove')."?id=" ?>'+$(this).closest('tr').attr('data-id'),success: function() {
				window.location= "<?=admin_url('risk/push_info')."?id=".(isset($list[0]->target_id)?$list[0]->target_id:"") ?>";
			}});
		  }
	});	
</script>
	<div id="brpopup"></div>
	<div id="bwindow"><div class="brcontent"><div class="x">X</div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
							<form role="form" method="post" onsubmit="return form_onsubmit();" > 
								<div class="form-group" data-type="s">
									<label>聯絡人</label>
									<select class="form-control">
										<option><?=isset($data->name)?$data->name:"";?></option>
										<option><?=isset($meta["emergency_name"])?$meta["emergency_name"]:"" ?></option>
									</select>
								</div>
								<div class="wr form-group" data-type="i">
									<label>聯絡人<span class="ws">填寫</span></label>
									<input class="form-control" max="25" />
								</div>
								<div class="form-group" data-type="s">
									<label>聯絡電話</label>
									<select class="form-control">
										<option><?=isset($data->phone)?$data->phone:"";?></option>
										<option><?=isset($meta["emergency_phone"])?$meta["emergency_phone"]:"" ?></option>
									</select>
								</div>
								<div class="wr form-group" data-type="i">
									<label>聯絡電話<span class="ws">填寫</span></label>
									<input class="form-control" maxlength="25" onkeyup="value=value.replace(/[^\d]/g,'') " />
								</div>
								<div class="form-group" data-type="s">
									<label>聯絡結果</label>
									<select class="form-control">
									<? foreach($result_status_list as $key => $value){ ?>
										<option value="<?=$key?>"><?=$value?></option>
									<? } ?>
									</select>
								</div>
								<div class="form-group" data-type="s">
									<label>跟進方式</label>
									<select class="form-control nos">
									<? foreach($push_by_status_list as $key => $value){ ?>
										<option value="<?=$key?>"><?=$value?></option>
									<? } ?>
									</select>
								</div>
								<div class="form-group" data-type="t">
									<label>意見備註表</label>
									<textarea class="form-control" rows="3" maxlength="200" placeholder="最多可填寫200中文字"></textarea>
								</div>
								<div class="form-group nr" data-type="i">
									<label>開始時間</label>
									<input id="start_time" class="form-control" type="datetime-local" value="" readonly />
								</div>
								<div class="form-group nr" data-type="i">
									<label>結束時間</label>
									<input id="end_time" class="form-control" type="datetime-local" value="" readonly />
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
					<a href="<? echo admin_url('risk/push_target')."?id=".$list[0]->target_id.(isset($slist)?"&slist=1":"") ?>">標的資訊</a>
					<a class="active">催收資訊</a>
					<a href="<? echo admin_url('risk/push_audit')."?id=".$list[0]->target_id.(isset($slist)?"&slist=1":"") ?>">催收審批</a>
				</div>
			</div>
		</div>
		<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">貸後臨時意見跟進表<a id="create" class="btn btn-default bopen<?=isset($slist)?" r":"" ?>">新增處理過程</a></div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>處理人</th>
                                            <th>聯絡人</th>
                                            <th>聯絡電話</th>
                                            <th>聯絡結果</th>
                                            <th>跟進方式</th>
											<th>開始時間</th>
                                            <th>結束時間</th>
                                            <th>意見備註</th>
                                            <th>操作</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
									if($list!=null){
										foreach($list as $key => $value){
									?>
                                        <tr data-id="<?=isset($value->id)?$value->id:"" ?>">
                                            <td><?=isset($value->admin_name)?$value->admin_name:"" ?></td>
                                            <td><?=isset($value->contact_person)?$value->contact_person:"" ?></td>
                                           	<td><?=isset($value->contact_phone)?$value->contact_phone:"" ?></td>
                                           	<td><?=isset($value->result)?$result_status_list[$value->result]:"" ?></td>
                                           	<td><?=isset($value->push_by)?$push_by_status_list[$value->push_by]:"" ?></td>
                                           	<td id="stt" time="<?=isset($value->start_time)?$value->start_time:"" ?>"><?=isset($value->start_time)?$value->start_time-28800:"" ?></td>
                                           	<td id="ent" time="<?=isset($value->end_time)?$value->end_time:"" ?>"><?=isset($value->end_time)?$value->end_time-28800:"" ?></td>
                                           	<td class="results"><?=isset($value->remark)?$value->remark:"" ?></td>
											<td><a id="check" class="btn btn-default bopen">查看</a><a id="edit" class="btn btn-default bopen<?=isset($slist)?" r":"" ?>">編輯</a><a class="btn btn-default del<?=isset($slist)?" r":"" ?>">刪除</a></td> 
                                        </tr>                                        
									<?php 
										}
									}
									else{
										echo "<tr class='empty'><td colspan='9'>目前沒有任何催收資訊</td></tr>";
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
