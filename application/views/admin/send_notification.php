<?php header('Access-Control-Allow-Origin: *'); ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							發送推播
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<form role="form" method="post" onsubmit="return form_onsubmit('確認發送此推播？');" >
										<div class="align-items-start">
											<div class="form-group mr-3">
												<label>發送對象：</label><br/>
												<!--<input type="checkbox" name="loan" value="1">
												<label for="loan">借款</label>-->
												<input type="checkbox" name="investment" value="1">
												<label for="investment">投資</label>
											</div>
											<div class="form-group mr-3">
												<label>平台：</label><br/>
												<input type="checkbox" name="android" value="1">
												<label for="android">Android</label>
												<input type="checkbox" name="ios" value="1">
												<label for="ios">IOS</label>
											</div>
											<div class="form-group mr-3">
												<label>性別：</label><br/>
												<input type="radio" name="gender" value="M">
												<label for="male">男</label>
												<input type="radio" name="gender" value="F">
												<label for="female">女</label>
											</div>
											<div class="form-group">
												<label>年齡：</label><br/>
												<input type="text" name="age_range_start">
												－
												<input type="text" name="age_range_end">
											</div>
										</div>
										<hr/>
										<div class="form-group">
											<label>會員id（請用逗點隔開，或以-指定範圍）：</label><br/>
											<input type="text" name="user_ids" placeholder="1,2,3,20-35,...">
										</div>
										<hr/>
										<div class="form-group">
											<label>標題</label>
											<input id="title" name="title" class="form-control" placeholder="輸入標題" >
										</div>
										<div class="form-group">
											<label>內容</label>
											<textarea id="content" name="content" class="form-control" rows="5"></textarea>
										</div>
										<hr>
										<div class="form-group">
											<label>跳轉到標的：(選填，僅供投資端app使用)</label>
											<input id="target" name="target" class="form-control" placeholder="請輸入案件編號(STN000000000)" >
										</div>
										<div class="form-group">
											<label>發送時間：</label>
											<div class='input-group date' id='send_datetime'>
												<input type='text' class="form-control" name="send_date"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
										</div>
										<input type="hidden" name="notification" value="1">
										<div class="form-group">
											<div class="align-items-start col-md-6">
												<button type="submit" class="btn btn-default">送出按鈕</button>
											</div>
											<div class="align-items-end col-md-6">
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#notificationModal">
													查詢送出紀錄
												</button>
											</div>

										</div>
									</form>
								</div>


							</div>
							<!-- /.col-lg-6 (nested) -->
						</div>
						<!-- /.row (nested) -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->

			<!-- Modal -->
			<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog" role="document" style="width: 95%;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h5 class="modal-title" id="exampleModalLongTitle">推播歷史紀錄查詢</h5>
						</div>
						<div class="modal-body">
							<table id="table"
								   data-toggle="table"
								   data-search="true"
								   data-filter-control="true"
								   data-click-to-select="true"
								   class="table-responsive">
								<thead>
								<tr>
									<!--<th data-field="state" data-checkbox="true"></th>-->
									<th data-field="date" data-filter-control="input" data-sortable="true">傳送時間</th>
									<th data-field="title" data-filter-control="input" data-sortable="true">標題</th>
									<th data-field="content" data-filter-control="input" data-sortable="true">內文</th>
									<th data-field="target" data-filter-control="select" data-sortable="true">跳轉目標案號</th>
									<th data-field="category" data-filter-control="select" data-sortable="true">借款/投資</th>
									<th data-field="platform" data-filter-control="select" data-sortable="true">接收平台</th>
									<th data-field="amount" data-sortable="true">發送數量</th>
									<th data-field="sender" data-filter-control="select" data-sortable="true">發送者</th>
									<th data-field="status" data-sortable="true">狀態</th>
								</tr>
								</thead>
								<tbody>
								<? foreach($data as $record) {?>
								<tr data-id="<?= $record['_id'] ?>">
									<!--<td class="bs-checkbox "><input data-index="0" name="btSelectItem" type="checkbox"></td>-->
									<td><?= $record['send_at'] ?></td>
									<td><?= $record['notification']['title'] ?></td>
									<td><?= $record['notification']['body'] ?></td>
									<td><?= isset($record['data']['targetNo'])?$record['data']['targetNo']:'' ?></td>
									<td><?= $record['target_category'] ?></td>
									<td><?= $record['target_platform'] ?></td>
									<td><?= $record['number_of_tokens']?></td>
									<td><?= $record['sender_name'] ?></td>
									<td class="col-md-2">
										<? if($record['status'] == 0 && $permission) { ?>
											<button type="button" class="btn btn-danger check" data-action="2" onclick="check_notification(this)">拒絕</button>
											<button type="button" class="btn btn-primary check" data-action="1" onclick="check_notification(this)">核可</button>
										<? } else { ?>
											<?= $this->config->item('notification')['status'][$record['status']] ?>
										<? } ?>
										<? if($record['status'] == 1) { ?>
											<button type="button" class="btn btn-warning check" data-action="4" onclick="check_notification(this)">取消</button>
										<? } ?>
									</td>
								</tr>
								<? } ?>
								</tbody>
							</table>
						</div>
						<!--<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary">Save changes</button>
						</div>-->
					</div>
				</div>
			</div>

	<script type="text/javascript">
		let $table = $('#table');

		function check_notification(ele) {
			let action = parseInt($(ele).data('action'), 10);
			let id = $(ele).closest('tr').data('id');
			let data = {id: id, action: action};
			$.ajax({
				type: 'PUT',
				contentType: 'application/json',
				url: "<?=admin_url('contact/update_notificaion')?>",
				data: data,
				success: (json) => {
					let status = <?= json_encode($this->config->item('notification')['status']) ?>;
					let statusCell = $(ele).closest('td');
					statusCell.html(status[data['action']]);
					if(data['action'] === 1) {
						statusCell.append('<button type="button" class="btn btn-warning check" data-action="4" onclick="check_notification(this)">取消</button>');
					}
				},
				error: function (xhr, textStatus, thrownError) {
					alert(textStatus);
				}
			});
		}


		$(function () {
			$('#send_datetime').datetimepicker({
				useCurrent: false,
				defaultDate: moment(new Date(), "YYYY-MM-DD HH:mm"),
				sideBySide: true
			});

			$('#notificationModal').on('shown.bs.modal', function () {
				$('#myInput').trigger('focus')
			});

			$('#toolbar').find('select').change(function () {
				$table.bootstrapTable('refreshOptions', {
					exportDataType: $(this).val()
				});
			});
		});

		// let trBoldBlue = $("table");
		//
		// $(trBoldBlue).on("click", "tr", function (){
		// 	$(this).toggleClass("bold-blue");
		// });
	</script>
