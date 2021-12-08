        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">推薦有賞 - 合作報表匯入</h1>
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
                .cut-text {
                    text-overflow: ellipsis;
                    overflow: hidden;
                    width: 145px;
                    white-space: nowrap;
                }
            </style>
			<script type="text/javascript">
                function showChang(){
                    var collaborator 				= $('#collaborator :selected').val();
                    var dateRange           = '&sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
                    if(collaborator===''){
                        top.location = './promote_import_list?'+dateRange;
                    }
                    else{
                        top.location = './promote_import_list'+(collaborator!==''?'?collaboration_id='+collaborator:'')+dateRange;
                    }
				}
                $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
                    code = (e.keyCode ? e.keyCode : e.which);
                    if (code == 13){
                        showChang();
                    }
                });
                $( document ).ready(function() {
                    $('.custom-file-input').change(function (e) {
                        let inputObj = $('.custom-file-input').get(0);
                        let nextSibling = e.target.nextElementSibling;

                        if(inputObj.files.length > 0) {
                            let name = inputObj.files[0].name;
                            nextSibling.innerText = name;
                            console.log(nextSibling);
                            console.log(name);
                        }else{
                            nextSibling.innerText = '選擇報表檔案';
                        }
                    });

                    $('#importBtn').click(function () {
                        let formData = new FormData();
                        formData.append('file', $('.custom-file-input')[0].files[0]);

                        if(confirm("確認匯入報表嗎？")){
                            Pace.track(() => {
                                $.ajax({
                                    type: 'POST',
                                    url: "<?=admin_url('Sales/promote_import_report')?>",
                                    data : formData,
                                    processData: false,  // tell jQuery not to process the data
                                    contentType: false,  // tell jQuery not to set contentType
                                    success: (rsp) => {
                                        console.log(rsp['response']);
                                        alert(rsp['response']['msg']);
                                        location.reload();
                                    },
                                    error: function (xhr, textStatus, thrownError) {
                                        console.log(xhr, textStatus, thrownError);
                                        alert(textStatus);
                                    }
                                });
                            });
                        }
                    });
                });
			</script>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style=" display: flex;">
                            <div class="col-lg-9">
                                <table>
                                    <tr style="vertical-align: baseline;">
                                        <td>匯入日期：</td>
                                        <td><input type="text" value="<?=isset($_GET['sdate'])&&$_GET['sdate']!=''?$_GET['sdate']:''?>" id="sdate" data-toggle="datepicker" placeholder="不指定區間" /></td>
                                        <td style="">到：</td>
                                        <td><input type="text" value="<?=isset($_GET['edate'])&&$_GET['edate']!=''?$_GET['edate']:''?>" id="edate" data-toggle="datepicker" style="width: 182px;"  placeholder="不指定區間" /></td>
                                        <td colspan="2" style="text-align: right"><a href="javascript:showChang();" class="btn btn-default">查詢</a></td>
                                    </tr>
                                    <tr>
                                        <td>合作對象：</td>
                                        <td colspan="5">
                                            <select id="collaborator">
                                                <? foreach($collaborator_list as $key => $value){ ?>
                                                    <option value="<?=$key?>" <?=isset($_GET['collaboration_id'])&&$_GET['collaboration_id']!=''&&$_GET['collaboration_id']==$key?"selected":''?>><?=$value?></option>
                                                <? } ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="
								display: flex;
								justify-content: space-between;
								align-self: flex-start;
								 "
                                 class="col-lg-3">
                                <button class="btn btn-success button-saved draw" id="importBtn">送出</button>
                                <div>
                                    <input id="files" type="file" class="btn btn-primary custom-file-input" style="display: none;"/>
                                    <label for="files" class="btn btn-primary cut-text">選擇報表檔案</label>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%" id="dataTables-tables"
                                       data-sort-name="date" data-sort-order="desc">
                                    <thead>
                                        <tr>
                                            <th data-field="date">匯入日期</th>
                                            <th>合作對象</th>
                                            <th>成功推薦案件數</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                        $count = 0;
										if(isset($list) && !empty($list)) {
                                            foreach($list as $key => $value) {
                                                $count++;
									?>
                                        <tr class="<?= $count%2==0?"odd":"even"; ?> list <?= $value['id'] ?? '' ?>">
                                            <td><?= $value['created_at']??'' ?></td>
                                            <td><?= $value['collaborator']??'' ?></td>
                                            <td><?= $value['count']??'' ?></td>
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
                <div class="col-lg-12" style="
                    display: flex;
                    align-items: center;
                    justify-content: end;
                ">
                <?= $links ?>
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->
