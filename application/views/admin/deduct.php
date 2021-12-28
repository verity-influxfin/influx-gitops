<div id="page-wrapper">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="page-header d-flex justify-between">
				<div>法催扣款</div>
				<button class="btn btn-danger">新增代支紀錄</button>
			</h1>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="d-flex">
				<div class="p-2">投資人ID</div>
				<div class="p-2">
					<input type="text">
				</div>
				<div class="p-2">
					代支日期
				</div>
				<div class="p-2">
					<input type="text" data-toggle="datepicker">
				</div>
				<div class="p-2">
					-
				</div>

				<div class="p-2">
					<input type="text" data-toggle="datepicker">
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

</div>

<script>
	const buttonGroup = `
		<div class="d-flex">
			<button class="btn btn-primary mr-2">扣繳</button>
			<button class="btn btn-outline-danger">註銷</button>
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

	.search-btn {
		display: flex;
		justify-content: flex-end;
		flex: 1 0 auto;
	}
</style>
