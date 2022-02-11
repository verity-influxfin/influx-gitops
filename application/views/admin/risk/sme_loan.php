<div id="page-wrapper">
	<div id="sme-loan">
		<div class="row">
			<div class="col-12">
				<h1 class="page-header">普匯微企e秒貸</h1>
			</div>
		</div>
		<div class="panel panel-default mt-4">
			<div class="panel-heading p-4">
				普匯微企e秒貸
			</div>
			<div class="panel-body">
				<table id="sme-table">
					<thead>
						<tr>
							<th>產品案號</th>
							<th>名稱</th>
							<th>id</th>
							<th>授信審核表</th>
							<th>送件審核表</th>
							<th>備註</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								#11111
							</td>
							<td>
								名稱1
							</td>
							<td>
								33333
							</td>
							<td>
								<a href="">
									<button class="btn btn-info">
										查看授信審核表
									</button>
								</a>

							</td>
							<td>
								<a href="">
									<button class="btn btn-info">
										查看送件審核表
									</button>
								</a>
							</td>
							<td>
								轉人工原因：未符合標準
							</td>
							<td>
								<button class="btn btn-primary">轉換為一般企業融資</button>
								<button class="btn btn-danger">退件</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		const t = $('#sme-table').DataTable({
			'ordering': false,
			"paging": false,
			"info": false,
			'language': {
				'processing': '處理中...',
				'lengthMenu': '顯示 _MENU_ 項結果',
				'zeroRecords': '目前無資料',
				'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
				'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
				'infoFiltered': '(從 _MAX_ 項結果過濾)',
				'search': '使用本次搜尋結果快速搜尋',
				'paginate': {
					'first': '首頁',
					'previous': '上頁',
					'next': '下頁',
					'last': '尾頁'
				}
			},
			"info": false
		})
	})
</script>
