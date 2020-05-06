<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    function requestVerdictStatuses() {
          var data = {
              'user_id' : '<?=isset($data->company_user_id)?$data->company_user_id:"" ?>',
          }

          var url = '/admin/certification/verdict_statuses';

          $.ajax({
              type: "GET",
              url: url,
              data: data,
              success: function(response) {
                  if (!response) {
                      alert('爬蟲狀態請求未成功送出');
                      return;
                  }
                  if (response.status.code == 400) {
                      alert('參數錯誤，爬蟲狀態請求未成功送出');
                      return;
                  }

                  if (response.status.code == 204) {
                      html = '<tr><td>狀態</td><td>請求未被收到</td></tr>';
                      $('#verdict_list').html(html);
                      $('.run-scraper-tr').show();
                      return;
                  }
                  if (response.status.code == 200) {
                      var data_arry = response.response.verdict_statuses.response;
                      var data_date = new Date( data_arry.updatedAt * 1000);

                      html = '<tr><td>名字</td><td>'+ data_arry.query +'</td></tr><tr><td>戶籍地</td><td>'+ data_arry.location +'</td></tr><tr><td>狀態</td><td>'
                      + data_arry.status + '</td></tr><tr><td>最後更新時間</td><td>'+ data_date +'</td></tr>';
                      $("#verdict_list").html(html);

                      if(data_arry.status=='爬蟲正在執行中' || data_arry.status=='爬蟲尚未開始'){
                        setTimeout(function() { requestVerdictStatuses()}, 5000 );
                      }
                      if(data_arry.status=='爬蟲執行完成'){
                        requestVerdictCount();
                        if(new Date($.now()-604800000) > data_date){
                          $('#run-scraper-btn').text('重新執行爬蟲');
                          $('.run-scraper-tr').show();
                        }
                      }
                      return;
                  }
              },
              error: function() {
                  alert('爬蟲狀態請求未成功送出');
              }
          });
      }

      function requestVerdictCount() {
            var data = {
                'name' : '<?=isset($data->company)?$data->company:"" ?>',
            }

            var url = '/admin/certification/verdict_count';

            $.ajax({
                type: "GET",
                url: url,
                data: data,
                success: function(response) {
                    if (!response) {
                        alert('案件資訊請求未成功送出');
                        return;
                    }
                    if (response.status.code == 400) {
                        alert('參數錯誤，案件資訊請求未成功送出');
                        return;
                    }

                    if (response.status.code == 204) {
                      html = '<tr><td colspan="2" style="text-align: -webkit-center;">無案件資料</td></tr>';
                        $("#case_list").html(html);
                        return;
                    }

                    if (response.status.code == 200) {
                        var case_list = response.response.verdict_count.response.verdict_count;
                        case_list.forEach(function(case_list){
                          list_head = '<tr><td ';
                          list_style = 'style="color:red;"';
                          list_foot = '>' + case_list.name +'</td><td><a target="_blank" href="/admin/Certification/judicial_yuan_case?name=<?=isset($data->company)?$data->company:"" ?>&case='+ case_list.name +'&page=1&count='+ case_list.count +'"  >' + case_list.count + '</a></td></tr>';
                          if(case_list.name =='本票裁定' || case_list.name =='支付命令' || case_list.name =='消債之前置協商認可事件' || case_list.name =='詐欺' || case_list.name =='侵佔'){
                                  list = list_head + list_style + list_foot;
                                }else{
                                  list = list_head + list_foot;
                                }
                                $("#case_list").prepend(list);
                            return;

                        });
                    }
                },
                error: function() {
                    alert('案件資訊請求未成功送出');
                }
            });
        }

        function requestVerdict() {
              var data = {
                  'name' : '<?=isset($data->company)?$data->company:"" ?>',
                  'address' : '<?=isset($content['id_card_place'])?$content['id_card_place']:"新北市"?>',
                  'user_id' : '<?=isset($data->company_user_id)?$data->company_user_id:"" ?>',
              }

              var url = '/admin/certification/verdict';

              $.ajax({
                  type: "GET",
                  url: url,
                  data: data,
                  success: function(response) {
                      if (!response) {
                          alert('爬蟲執行請求未成功送出');
                          return;
                      }
                      if (response.status.code == 400) {
                          alert('參數錯誤，爬蟲執行請求未成功送出');
                          return;
                      }

                      if (response.status.code == 200) {
                          alert('爬蟲執行請求成功送出');
                          setTimeout(requestVerdictStatuses(),5000);
                              return;
                      }
                  },
                  error: function() {
                      alert('爬蟲執行請求未成功送出');
                  }
              });
          }
    $(document).ready(function(){
      requestVerdictStatuses();
      $( '#run-scraper-btn' ).click(function() {
        requestVerdict();
        $( ".run-scraper-tr" ).hide();
      });
    });
</script>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">法人帳號申請</h1>
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
								<label>申請人 ID</label>
								<a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
									<p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
								</a>
							</div>
							<div class="form-group">
								<label>申請人</label>
								<p class="form-control-static"><?= isset($data->user_name) ? $data->user_name : "" ?></p>
							</div>
							<div class="form-group">
								<label>法人 User ID</label><p>
								<? echo $data->cooperation == 1 ? '<a class="fancyframe" href="' . admin_url('User/display?id=' . $data->company_user_id) . '"</a>' : '尚未開通經銷商' ?></p>

							</div>
							<div class="form-group">
								<label>統一編號</label>
								<p class="form-control-static"><?= isset($data->tax_id) ? $data->tax_id : "" ?></p>
							</div>
							<div class="form-group">
								<label>公司類型</label>
								<p class="form-control-static"><?= isset($company_type[$data->company_type]) ? $company_type[$data->company_type] : "" ?></p>
							</div>
							<div class="form-group">
								<label>公司名稱</label>
								<p class="form-control-static"><?= isset($data->company) ? $data->company : "" ?></p>
							</div>
                            <? if(!empty($data->cooperation_address)){ ?>
                                <div class="form-group">
                                    <label>公司地址</label>
                                    <p class="form-control-static"><?=$data->cooperation_address ?></p>
                                </div>
                            <? } ?>
                            <? if(!empty($data->cooperation_phone)){ ?>
                                <div class="form-group">
                                    <label>公司電話</label>
                                    <p class="form-control-static"><?=$data->cooperation_phone ?></p>
                                </div>
                            <? } ?>
                            <? if(!empty($data->cooperation_contact)){ ?>
                                <div class="form-group">
                                    <label>聯絡人</label>
                                    <p class="form-control-static"><?=$data->cooperation_contact ?></p>
                                </div>
                            <? } ?>
                            <div class="form-group">
                                <label>營運模式</label>
                                <p class="form-control-static"><?echo
                                    $data->cooperation == 1
                                        ? $data->business_model == 0
                                            ? '線下'
                                            : '線上'
                                        : "尚未開通經銷商" ?></p>
                            </div>
                            <div class="form-group">
                                <label>商品類型</label>
                                <p class="form-control-static"><? echo $data->cooperation == 1 ? $this->config->item('selling_type')[$data->selling_type] : "尚未開通經銷商" ?></p>
                            </div>
							<div class="form-group">
								<label>備註</label>
								<p class="form-control-static"><?= isset($data->remark) ? $data->remark : "" ?></p>
							</div>
							<? if ($data->status == 0) { ?>
								<h1>上傳對保影片</h1>
								<div class="form-group">
									<form action="<?= admin_url('Judicialperson/media_upload') ?>" method="post" enctype="multipart/form-data">
										檔案名稱:<input type="file" name="media" /><br />
										<input type="hidden" name="user_id" value="<?= isset($data->user_id) ? $data->user_id : ""; ?>">
										<input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : ""; ?>">
										<input type="submit" class="btn btn-primary" value="上傳檔案" />
									</form>
								</div>
							<? } ?>
							<div class="form-group">
								<h1>對保人員上傳：</h1>
								<? if (!empty($media_list['judi_admin_video'])) { ?>
									<? foreach ($media_list['judi_admin_video'] as $value) { ?>
										<video width="400" controls>
											<source src="<?= isset($value) ? $value : "" ?>" type="video/mp4">
											<source src="<?= isset($value) ? $value : "" ?>" type="video/mov">
										</video>
									<? } ?>
								<? } ?>
							</div>
							<div class="form-group">
								<h1>負責人上傳：</h1>
								<? if (!empty($media_list['judi_user_video'])) { ?>
									<? foreach ($media_list['judi_user_video'] as $value) { ?>
										<video width="400" controls>
											<source src="<?= isset($value) ? $value : "" ?>" type="video/mp4">
											<source src="<?= isset($value) ? $value : "" ?>" type="video/mov">
										</video>
									<? } ?>
								<? } ?>
							</div>
							<h4>審核</h4>
							<form role="form" method="post">
								<fieldset>
									<div class="form-group">
										<select id="status" name="status" class="form-control" onchange="check_fail();">
											<? foreach ($status_list as $key => $value) { ?>
												<option value="<?= $key ?>" <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
											<? } ?>
										</select>
										<input type="hidden" name="id" value="<?= isset($data->id) ? $data->id : ""; ?>">
									</div>
									<button type="submit" class="btn btn-primary">送出</button>
								</fieldset>
							</form>
						</div>
						<div class="col-lg-6">
							<h1>商業司資料</h1>
							<? if ($company_data && $search_type == 0) { ?>
								<h3>公司登記</h3>
								<div class="form-group">
									<table class="table table-bordered table-hover table-striped">
										<tbody>
											<tr>
												<td>
													<p class="form-control-static">公司統一編號</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Business_Accounting_NO']) ? $company_data['Business_Accounting_NO'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">公司狀況描述</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Company_Status_Desc']) ? $company_data['Company_Status_Desc'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">公司名稱</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Company_Name']) ? $company_data['Company_Name'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">資本總額(元)</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Capital_Stock_Amount']) ? number_format($company_data['Capital_Stock_Amount']) : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">實收資本額(元)</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Paid_In_Capital_Amount']) ? number_format($company_data['Paid_In_Capital_Amount']) : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">代表人姓名</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Responsible_Name']) ? $company_data['Responsible_Name'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">公司登記地址</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Company_Location']) ? $company_data['Company_Location'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">登記機關名稱</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Register_Organization_Desc']) ? $company_data['Register_Organization_Desc'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">核准設立日期</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Company_Setup_Date']) ? $company_data['Company_Setup_Date'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">最後核准變更日期</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Change_Of_Approval_Data']) ? $company_data['Change_Of_Approval_Data'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">撤銷日期</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Revoke_App_Date']) ? $company_data['Revoke_App_Date'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">停復業狀況</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Case_Status']) ? $company_data['Case_Status'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">停復業狀況描述</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Case_Status_Desc']) ? $company_data['Case_Status_Desc'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">停業核准日期</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Sus_App_Date']) ? $company_data['Sus_App_Date'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">停業/延展期間(起)</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Sus_Beg_Date']) ? $company_data['Sus_Beg_Date'] : '' ?></p>
												</td>
											</tr>
											<tr>
												<td>
													<p class="form-control-static">停業/延展期間(迄)</p>
												</td>
												<td>
													<p class="form-control-static"><?= isset($company_data['Sus_End_Date']) ? $company_data['Sus_End_Date'] : '' ?></p>
												</td>
											</tr>
										</tbody>
									</table>
								<? } elseif ($company_data) { ?>
									<h3>商業登記</h3>
									<div class="form-group">
										<table class="table table-bordered table-hover table-striped">
											<tbody>
												<tr>
													<td>
														<p class="form-control-static">商業統一編號</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['President_No']) ? $company_data['President_No'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">商業名稱</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Name']) ? $company_data['Business_Name'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">商業狀態</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Current_Status']) ? $company_data['Business_Current_Status'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">商業狀態描述</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Current_Status_Desc']) ? $company_data['Business_Current_Status_Desc'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">資本額</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Register_Funds']) ? $company_data['Business_Register_Funds'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">代表人姓名</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Responsible_Name']) ? $company_data['Responsible_Name'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">組織型態</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Organization_Type']) ? $company_data['Business_Organization_Type'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">組織型態名稱</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Organization_Type_Desc']) ? $company_data['Business_Organization_Type_Desc'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">登記機關</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Agency']) ? $company_data['Agency'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">商業登記地址</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Address']) ? $company_data['Business_Address'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">登記機關名稱</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Agency_Desc']) ? $company_data['Agency_Desc'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">核准設立日期</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Setup_Approve_Date']) ? $company_data['Business_Setup_Approve_Date'] : '' ?></p>
													</td>
												</tr>
												<tr>
													<td>
														<p class="form-control-static">變更日期</p>
													</td>
													<td>
														<p class="form-control-static"><?= isset($company_data['Business_Last_Change_Date']) ? $company_data['Business_Last_Change_Date'] : '' ?></p>
													</td>
												</tr>

											</tbody>
										</table>
									<? } else { ?>
										<div class="form-group">系統查無登記資料，線上商業司<a href="https://findbiz.nat.gov.tw/fts/query/QueryBar/queryInit.do">查詢</a>如有資料警請回報IT工程師</div>
									<? } ?>
									</div>
									<div class="form-group">
										<? if ($shareholders) {
										?>
											<table class="table table-bordered table-hover table-striped">
												<tbody>
													<? foreach ($shareholders as $key => $value) { ?>
														<tr>
															<td>
																<p class="form-control-static">職稱名稱</p>
															</td>
															<td>
																<p class="form-control-static"><?= isset($value['Person_Position_Name']) ? $value['Person_Position_Name'] : '' ?></p>
															</td>
															<td>
																<p class="form-control-static">董監事股東姓名</p>
															</td>
															<td>
																<p class="form-control-static"><?= isset($value['Person_Name']) ? $value['Person_Name'] : '' ?></p>
															</td>
															<td>
																<p class="form-control-static">所代表法人</p>
															</td>
															<td>
																<p class="form-control-static"><?= isset($value['Juristic_Person_Name']) ? $value['Juristic_Person_Name'] : '' ?></p>
															</td>
															<td>
																<p class="form-control-static">持有股份數</p>
															</td>
															<td>
																<p class="form-control-static"><?= isset($value['Person_Shareholding']) ? number_format($value['Person_Shareholding']) : '' ?></p>
															</td>
														</tr>
													<? } ?>
												</tbody>
											</table>
										<? } ?>
									</div>

                  <div class="form-group">
                    <label>爬蟲資訊資訊</label>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">資訊名稱</th><th scope="col">相關資訊</th>
                        </tr>
                      </thead>
                      <tbody id="verdict_list">
                      </tbody>
                      <tbody>
                        <tr class="run-scraper-tr" style="display:none;" ><td colspan="2" style="text-align: -webkit-center;" ><button id="run-scraper-btn">執行爬蟲按鈕</button></td></tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="form-group">
                      <label>案件資訊</label>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">裁判案由</th><th scope="col">總數</th>
                          </tr>
                        </thead>
                        <tbody id="case_list">
                        </tbody>
                      </table>
                  </div>

									<div class="col-lg-6">
										<h1>圖片</h1>
										<div class="form-group">
											<label for="disabledSelect">營利事業變更登記表正本</label>
											<? if (isset($content['enterprise_registration_image'])) {
												foreach ($content['enterprise_registration_image'] as $key => $value) {
											?>
													<a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
														<img src="<?= $value ? $value : "" ?>" style='width:100%;max-width:300px'>
													</a>
											<?
												}
											} else {
												echo "未上傳";
											} ?>
										</div>
										<div class="form-group">
											<label for="disabledSelect">存摺封面</label>
											<? if (isset($bankaccount_id)) { ?>
												<a href="../certification/user_bankaccount_edit?id=<?= isset($bankaccount_id) ? $bankaccount_id : "" ?>" target="_blank">金融帳號認證頁面</a>
											<? }
											if (isset($bankbook)) {
												foreach ($bankbook as $key => $value) {
											?>
													<a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
														<img src="<?= $value ? $value : "" ?>" style='width:100%;max-width:300px'>
													</a>
											<?
												}
											}?>
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
