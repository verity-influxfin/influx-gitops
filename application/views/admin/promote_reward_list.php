        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">推薦有賞 - 放款列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <style>
                .panel-heading td {
                    height: 30px;
                    vertical-align: middle;
                    padding-left: 5px;
                }
                .tsearch input {
                    width: 640px;
                }
            </style>
			<script type="text/javascript">
                let allMode = false;
                $( document ).ready(function() {
                    $('#select_all').click(function () {
                        allMode = !allMode;
                        $('.promote_code').each(function() {
                            $(this).attr('checked', allMode);
                        });
                    });
                });

                function toloan(){
                    let ids = [];
                    let totalAmount = 0;
                    $('.promote_code:checked').each(function() {
                        ids.push(this.value);
                        totalAmount += parseInt($(this).parent().siblings('.amount').text());
                    });
                    if(!ids || ids.length === 0){
                        alert("請先選擇欲放款的獎勵紀錄");
                        return false;
                    }
                    if(confirm("確認要放款 "+ids.length+" 筆資料 (共"+totalAmount+"元)？")){
                        if(ids){
                            Pace.track(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?=admin_url('Sales/promote_reward_loan')?>",
                                    data: {ids: ids},
                                    success: (rsp) => {
                                        console.log(rsp['response']);
                                        alert(rsp['response']['msg']);
                                        location.reload();
                                    },
                                    error: function (xhr, textStatus, thrownError) {
                                        alert(textStatus);
                                    }
                                });
                            });
                        }
                    }
                }

                function showChang(){
                    var tsearch 			= $('#tsearch').val();
                    var alias 				= $('#alias :selected').val();
                    var dateRange           = '&sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    if(tsearch===''&&alias===''){
                        top.location = './promote_reward_list?'+dateRange;
                    }
                    else{
                        top.location = './promote_reward_list?tsearch='+tsearch+(alias!==''?'&alias='+alias:'')+dateRange;
                    }
				}
                $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
                    code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13){
                        showChang();
                    }
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: flex-end;">
							<table>
								<tr>
									<td>搜尋：</td>
									<td class="tsearch" colspan="7"><input type="text" value="<?=isset($_GET['tsearch'])&&$_GET['tsearch']!=''?$_GET['tsearch']:''?>" id="tsearch" placeholder="使用者代號(UserID) / 姓名 / 身份證字號 / 推薦碼" /></td>
								</tr>
								<tr>
                                    <td>狀態：</td>
                                    <td colspan="5">
                                        <select id="alias">
                                            <? foreach($alias_list as $key => $value){ ?>
                                            <option value="<?=$key?>" <?=isset($_GET['alias'])&&$_GET['alias']!=''&&$_GET['alias']==$key?"selected":''?>><?=$value?></option>
                                            <? } ?>
                                        </select>
                                    </td>
								</tr>
                                <tr style="vertical-align: baseline;">
                                    <td>從：</td>
                                    <td><input type="text" value="<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
                                    <td style="">到：</td>
                                    <td><input type="text" value="<?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
                                    <td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
                                </tr>
                            </table>
                            <div>

                                <a href="javascript:toloan();" class="btn btn-primary">放款</a>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables">
                                    <thead>
                                        <tr>
                                            <th><a id='select_all' class="btn btn-default mr-3">全選</a>編號</th>
                                            <th>會員 ID</th>
                                            <th>邀請碼</th>
                                            <th>待放款金額</th>
                                            <th>結算起始時間</th>
                                            <th>結算結束時間</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                        $count = 0;
										if(isset($list) && !empty($list)) {
                                            foreach($list as $value) {
                                                $count++;
									?>
                                        <tr class="<?= $count%2==0?"odd":"even"; ?> list <?= $value['id'] ?? '' ?>">
                                            <td>
                                               <input class="promote_code" type="checkbox" data-id="<?=$value['id']?>" value="<?=$value['id'] ?>" />
                                                &nbsp;<?=$value['id']?>
                                            </td>
                                            <td><?= $value['user_id']??'' ?></td>
                                            <td><?= $value['promote_code']??'' ?></td>
                                            <td class="amount" ><?= $value['amount']??'' ?></td>
                                            <td><?= $value['start_time']??'' ?></td>
                                            <td><?= $value['end_time']??'' ?></td>
                                        </tr>
									<?php
									}}
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
        <!-- /#page-wrapper -->
