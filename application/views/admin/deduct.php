<div id="page-wrapper">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="page-header d-flex justify-between">
				<div>法催扣款</div>
				<button class="btn btn-danger" data-toggle="modal" data-target="#newModal">新增代支紀錄</button>
			</h1>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="d-flex">
				<div class="p-2">投資人ID</div>
				<div class="p-2">
					<input type="text" class="form-control">
				</div>
				<div class="p-2">
					代支日期
				</div>
				<div class="p-2">
					<input type="text" data-toggle="datepicker" class="form-control">
				</div>
				<div class="p-2">
					-
				</div>

				<div class="p-2">
					<input type="text" data-toggle="datepicker" class="form-control">
				</div>
				<div class="search-btn">
					<button class="btn btn-primary">搜尋</button>
				</div>
			</div>
		</div>
		<div class="p-3">
			<table id="deduct-table">
				<thead>
					<tr>
						<th>代支日期</th>
						<th>投資人ID</th>
						<th>金額</th>
						<th>事由</th>
						<th>經辦人</th>
						<th>狀態</th>
						<th></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">新增代支紀錄</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<div class="col-20 input-require">投資人ID</div>
						<div class="col">
							<input type="text" required class="w-100 form-control">
						</div>
					</div>
					<div class="d-flex mb-2">
						<div class="orange-hint"></div>
					</div>
					<div class="d-flex mb-2">
						<div class="col-20 input-require">金額</div>
						<div class="col">
							<input type="text" required class="w-100 form-control">
						</div>
					</div>
					<div class="d-flex align-start mb-2">
						<div class="col-20 input-require">事由</div>
						<div class="col">
							<textarea type="text" required class="w-100 form-control" rows="4"></textarea>
						</div>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary">送出</button>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="deductModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">扣繳投資人虛擬帳戶餘額</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<h4 class="col">投資人ID: 22222</h4>
					</div>
					<div class="d-flex mb-2">
						<div class="orange-hint">
							張○豐 虛擬帳戶餘額: $29,302
						</div>
					</div>
					<div class="d-flex mb-2">
						<h4 class="col">代支規費: $750</h4>
					</div>
					<div class="d-flex align-start mb-2">
						<h4 class="col">確定扣繳嗎?</h4>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary">送出</button>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">註銷代支紀錄</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<div class="col input-require">註銷原因</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col">
							<textarea rows="7" required class="w-100 form-control"></textarea>
						</div>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-danger">註銷</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	const buttonGroup = `
		<div class="d-flex">
			<button class="btn btn-primary mr-2" data-toggle="modal" data-target="#deductModal">扣繳</button>
			<button class="btn btn-outline-danger" data-toggle="modal" data-target="#cancelModal">註銷</button>
		</div>
	`
	$(document).ready(function () {
		const t = $('#deduct-table').DataTable({
			'ordering': false,
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
		});
		for (let index = 0; index < 5; index++) {
			addRow([index, 2222, 333, 'test', 'kevin', 'success', ''])
		}
		$('#deduct-table').DataTable().draw()
	});

	function addRow([keyA, keyB, keyC, keyD, keyE, keyF, keyG]) {
		const t = $('#deduct-table').DataTable()
		if (!keyG) {
			// push buttons

			t.row.add([keyA, keyB, keyC, keyD, keyE, keyF, buttonGroup])
			return
		}
		t.row.add([keyA, keyB, keyC, keyD, keyE, keyF, keyG])
	}
</script>

<style>
	.d-flex {
		display: flex;
		align-items: center;
	}
	.align-start{
		align-items: start;
	}

	.btn-outline-danger {
		color: #dc3545;
		background-color: #fff;
		border-color: #dc3545;
	}

	.btn-outline-danger:hover {
		color: #fff;
		background-color: #dc3545;
	}

	.justify-between {
		justify-content: space-between;
	}
	.col-20{
		flex: 0 0 20%;
	}
	.col{
		flex: 1 0 0%;
	}
	.w-100{
		width: 100%;
	}
	.input-require::after{
		content: '*';
		color: #dc3545;
		margin-left: 4px;
		display: inline-block;
	}
	.orange-hint{
		color: orange;
		font-size: 12px;
	}

	.search-btn {
		display: flex;
		justify-content: flex-end;
		flex: 1 0 auto;
	}
</style>
