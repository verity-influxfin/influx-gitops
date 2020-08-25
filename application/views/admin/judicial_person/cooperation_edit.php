<script>
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }
    function build(id){
        if(id){
            cut = id.split('|');
            top.location = '../certification/user_certification_edit?cid=1006&user_id='+cut[0]+'&selltype='+cut[1];
        }
    }
    function create(id){
        var create = $('.create');
        if(id){
            create.attr('disabled',true);
            $.ajax({
                url: '../judicialperson/cooperation_edit',
                data: 'id='+id+'&create_taishin=1',
                type: 'POST',
                success: function (e) {
                    var res = JSON.parse(e);
                    alert(res.msg);
                    window.location.href = res.redirect;
                }
            });
        }
    }
</script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">經銷商申請資料</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>法人 User ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->company_user_id) ?>">
                                    <p><?= isset($data->company_user_id) ? $data->company_user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>申請人 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>申請人/負責人</label>
                                <p class="form-control-static"><?= isset($user_info->name) ? $user_info->name : "" ?></p>
                            </div>
                            <div class="form-group">
                                <label>銷售類別</label>
                                <p class="form-control-static"><?=isset($data->selling_type)?$selling_type[$data->selling_type]:"" ?></p>
                                <?
                                if($data->no_taishin && $data->selling_type == 2){
                                    echo '<p class="form-control-static">尚未建立台新帳號</p><button class="btn btn-danger create" style="width: 80px;" onclick="create('.(isset($data->id) ? $data->id : "").')">建立</button>';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>公司名稱</label>
                                <p class="form-control-static"><?= isset($data->company) ? $data->company : "" ?></p>
                            </div>
                             <div class="form-group">
                                <label>統一編號</label>
                                <p class="form-control-static"><?= isset($data->tax_id) ? $data->tax_id : "" ?></p>
                            </div>
                            <?
                                if($data->no_taishin && $selling_type[$data->selling_type] == 2){?>
                            <div class="form-group">
                                <label>信用評估表</label><br />
                                <? $company_user_id = isset($data->company_user_id)?$data->company_user_id:"";
                                $sellingType = isset($data->selling_type)?'|'.$data->selling_type:"";
                                if(!$data->cerCreditJudicial || $data->cerCreditJudicial->status == 0){
                                    echo '<button class="btn btn-danger" style="width: 80px;" onclick="build(\''.$company_user_id.$sellingType.'\')">填寫</button>';
                                }
                                else{
                                    echo '<a target="_blank" class="btn btn-info" style="width: 80px;" href="'.admin_url('certification/user_certification_edit?id=').$data->cerCreditJudicial->id.'">檢閱</a>';
                                }
                                ?>
                        </div>
                        <?}
                        ?>
                            <div class="form-group">
                                <label>備註</label>
                                <p class="form-control-static"><?= isset($data->remark) ? $data->remark : "" ?></p>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="cooperation" name="cooperation" class="form-control"
                                                onchange="check_fail();">
                                            <? foreach ($cooperation_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                        <?= $data->cooperation == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= isset($data->id) ? $data->id : ""; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>商業司資料</h1>
                            <div class="form-group">
                                <? if ($company_data) { ?>
                                    <table class="table table-bordered table-hover table-striped">
                                        <tbody>
                                        <tr>
                                            <td><p class="form-control-static">公司統一編號</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Business_Accounting_NO']) ? $company_data['Business_Accounting_NO'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">公司狀況描述</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Company_Status_Desc']) ? $company_data['Company_Status_Desc'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">公司名稱</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Company_Name']) ? $company_data['Company_Name'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">資本總額(元)</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Capital_Stock_Amount']) ? number_format($company_data['Capital_Stock_Amount']) : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">實收資本額(元)</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Paid_In_Capital_Amount']) ? number_format($company_data['Paid_In_Capital_Amount']) : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">代表人姓名</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Responsible_Name']) ? $company_data['Responsible_Name'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">公司登記地址</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Company_Location']) ? $company_data['Company_Location'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">登記機關名稱</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Register_Organization_Desc']) ? $company_data['Register_Organization_Desc'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">核准設立日期</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Company_Setup_Date']) ? $company_data['Company_Setup_Date'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">最後核准變更日期</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Change_Of_Approval_Data']) ? $company_data['Change_Of_Approval_Data'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">撤銷日期</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Revoke_App_Date']) ? $company_data['Revoke_App_Date'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">停復業狀況</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Case_Status']) ? $company_data['Case_Status'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">停復業狀況描述</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Case_Status_Desc']) ? $company_data['Case_Status_Desc'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">停業核准日期</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Sus_App_Date']) ? $company_data['Sus_App_Date'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">停業/延展期間(起)</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Sus_Beg_Date']) ? $company_data['Sus_Beg_Date'] : '' ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><p class="form-control-static">停業/延展期間(迄)</p></td>
                                            <td>
                                                <p class="form-control-static"><?= isset($company_data['Sus_End_Date']) ? $company_data['Sus_End_Date'] : '' ?></p>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <? } ?>
                            </div>
                            <div class="form-group">
                                <? if ($shareholders) {
                                    ?>
                                    <table class="table table-bordered table-hover table-striped">
                                        <tbody>
                                        <? foreach ($shareholders as $key => $value) { ?>
                                            <tr>
                                                <td><p class="form-control-static">職稱名稱</p></td>
                                                <td>
                                                    <p class="form-control-static"><?= isset($value['Person_Position_Name']) ? $value['Person_Position_Name'] : '' ?></p>
                                                </td>
                                                <td><p class="form-control-static">董監事股東姓名</p></td>
                                                <td>
                                                    <p class="form-control-static"><?= isset($value['Person_Name']) ? $value['Person_Name'] : '' ?></p>
                                                </td>
                                                <td><p class="form-control-static">所代表法人</p></td>
                                                <td>
                                                    <p class="form-control-static"><?= isset($value['Juristic_Person_Name']) ? $value['Juristic_Person_Name'] : '' ?></p>
                                                </td>
                                                <td><p class="form-control-static">持有股份數</p></td>
                                                <td>
                                                    <p class="form-control-static"><?= isset($value['Person_Shareholding']) ? number_format($value['Person_Shareholding']) : '' ?></p>
                                                </td>
                                            </tr>
                                        <? } ?>
                                        </tbody>
                                    </table>
                                <? } ?>
                            </div>
                            <div class="col-lg-6">
                                <h1>圖片</h1>
                                <div class="form-group">
                                    <label for="disabledSelect">店門正面照</label>
                                    <? if (isset($content['facade_image'])) { ?>
                                        <a href="<?= isset($content['facade_image']) ? $content['facade_image'] : "" ?>"
                                           data-fancybox="images">
                                            <img src="<?= isset($content['facade_image']) ? $content['facade_image'] : "" ?>"
                                                 style='width:100%;max-width:300px'>
                                        </a>
                                    <? } else {
                                        echo "未上傳";
                                    } ?>
                                </div>
                                <? if ($data->selling_type == 0 && isset($content['store_image'])) { ?>
                                    <div class="form-group">
									<? if($content['store_image']!=0){ ?>
											<label for="disabledSelect">店內正面照</label>
											<? foreach($content['store_image'] as $key => $value){ ?>
												<a href="<?=isset($value)?$value:""?>" data-fancybox="images">
													<img src="<?=$value?$value:""?>" style='width:100%;max-width:300px'>
												</a>
											<? } ?>
									<? } ?>
                                <? } ?>
                                <? if(isset($content['store_lease_image'])){ ?>
                                    <div class="form-group">
                                        <label for="disabledSelect">租約照片(外匯車經銷商)</label>
                                        <a href="<?=isset($content['store_lease_image'])?$content['store_lease_image']:""?>" data-fancybox="images">
                                            <img src="<?=isset($content['store_lease_image'])?$content['store_lease_image']:""?>" style='width:100%;max-width:300px'>
                                        </a>
                                    </div>
                                <? } ?>
                                <? if(isset($content['store_sign_image'])){ ?>
                                    <div class="form-group">
                                        <label for="disabledSelect">招牌照片(外匯車經銷商)</label>
                                        <a href="<?=isset($content['store_sign_image'])?$content['store_sign_image']:""?>" data-fancybox="images">
                                            <img src="<?=isset($content['store_sign_image'])?$content['store_sign_image']:""?>" style='width:100%;max-width:300px'>
                                        </a>
                                    </div>
                                <? } ?>
                                    <div class="form-group">
                                        <label for="disabledSelect">銀行流水帳內頁</label>
                                        <? if(isset($content['passbook_image'])){
                                            foreach($content['passbook_image'] as $key => $value){ ?>
                                            <a href="<?=isset($value)?$value:""?>" data-fancybox="images">
                                                <img src="<?=$value?$value:""?>" style='width:100%;max-width:300px'>
                                            </a>
                                        <? }} ?>
                                    </div>
                                <? if(isset($content['front_image'])){ ?>
                                    <div class="form-group">
                                        <label for="disabledSelect">銀行流水帳正面(經銷商選填)</label>
                                        <a href="<?=isset($content['front_image'])?$content['front_image']:""?>" data-fancybox="images">
                                            <img src="<?=isset($content['front_image'])?$content['front_image']:""?>" style='width:100%;max-width:300px'>
                                        </a>
                                    </div>
                                    <? } ?>
                                <? if(isset($content['passbook_dealer_image'])){ ?>
                                        <div class="form-group">
                                        <label for="disabledSelect">銀行流水帳內頁(經銷商選填)</label>
                                    <? foreach($content['passbook_dealer_image'] as $key => $value){ ?>
                                        <a href="<?=isset($value)?$value:""?>" data-fancybox="images">
                                            <img src="<?=$value?$value:""?>" style='width:100%;max-width:300px'>
                                        </a>
                                    <? } ?>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="disabledSelect">存摺封面</label>
                                            <? if (isset($bankaccount_id)) { ?>
                                                <a href="../certification/user_bankaccount_edit?id=<?= isset($bankaccount_id) ? $bankaccount_id : "" ?>" target="_blank">金融帳號頁面</a>
                                            <? } else {
                                                echo "未上傳";
                                            } ?>
                                        </div>
                                        <? if(isset($content['form_401_image'])){ ?>
                                            <div class="form-group">
                                                <label for="disabledSelect">401表格</label>
                                                <? foreach($content['form_401_image'] as $key => $value){ ?>
                                                    <a href="<?=$value ?>" data-fancybox="images">
                                                        <img src="<?=$value ?>" style='width:100%;max-width:300px'>
                                                    </a>
                                                <? } ?>
                                            </div>
                                        <? } ?>
                                    <? } ?>
                                    </div>
                                </div>
							</div>
						</div>
						<!-- /.row (nested) -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
