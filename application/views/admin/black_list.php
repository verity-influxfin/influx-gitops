<div id="page-wrapper">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="page-header">
				<div>借款 - 黑名單列表</div>
			</h1>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="d-flex">
				<div class="p-2">會員ID</div>
				<div class="p-2">
					<input type="text">
				</div>
				<div class="search-btn">
					<button class="btn btn-primary">搜尋</button>
				</div>
			</div>
		</div>
		<div class="p-3">
			<h4>單一user</h4>
		</div>
		<div class="p-3">
			<table id="search-table">
				<thead>
					<tr>
						<th>會員ID</th>
						<th>更新時間</th>
						<th>更新原因</th>
						<th>符合黑名單規則</th>
						<th>規則細項</th>
						<th>風險等級</th>
						<th>執行狀態</th>
						<th>備註</th>
						<th>會員資訊</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="d-flex">
				<div class="p-2">
					黑名單規則
				</div>
				<div class="p-2">
					<select id="rule-option" class="form-control">
						<option value=""></option>
						<option value="1" label="信用不良紀錄">信用不良紀錄</option>
						<option value="2" label="第三方資料結果">第三方資料結果</option>
						<option value="3" label="反詐欺規則">反詐欺規則</option>
						<option value="4" label="其他 （人為加入）">其他 （人為加入）</option>
						<option value="5" label="授信政策">授信政策</option>
					</select>
				</div>
				<div class="p-2">
					執行狀態
				</div>
				<div class="p-2">
					<select id="status" class="form-control">
						<option value=""></option>
						<option value="1" label="封鎖三個月（中風險）">封鎖三個月（中風險）</option>
						<option value="2" label="封鎖六個月（高風險）">封鎖六個月（高風險）</option>
						<option value="3" label="拒絕">拒絕</option>
					</select>
				</div>
			</div>
		</div>
		<div class="p-3">
			<h4>全部結果</h4>
		</div>
		<div class="p-3">
			<table id="main-table">
				<thead>
					<tr>
						<th>會員ID</th>
						<th>更新時間</th>
						<th>更新原因</th>
						<th>符合黑名單規則</th>
						<th>規則細項</th>
						<th>風險等級</th>
						<th>執行狀態</th>
						<th>備註</th>
						<th>會員資訊</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="modal fade" id="idModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">移除黑名單</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-20 input-require">移除原因：</div>
						<div class="col">
							<input type="text" required class="w-100">
						</div>
					</div>
					<div class="d-flex justify-between mx-3 mb-3">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="submit" class="btn btn-danger">移除</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">調整狀態</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-20 input-require">調整狀態：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" required>
								<option value=""></option>
								<option value="">1</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-20 input-require">調整原因：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" required>
								<option value=""></option>
								<option value="">1</option>
							</select>
						</div>
					</div>
					<div class="d-flex justify-between mx-5 mb-5">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="submit" class="btn btn-primary">送出</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">新增至黑名單</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-30 input-require">符合黑名單規則：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" required>
								<option value=""></option>
								<option value="">1</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-30 input-require">規則細項：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" required>
								<option value=""></option>
								<option value="">1</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col-30">備註:</div>
						<div class="col">
							<textarea rows="7" class="w-100"></textarea>
						</div>
					</div>

				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-info">新增</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	const buttonToID = (id) => {
		return `
		<div class="d-flex">
			<button class="btn btn-default mr-2" data-toggle="modal" data-target="#deductModal">
				<a href="/admin/user/edit?id=${id}" target="_blank">查看</a>
			</button>
		</div>
		`
	}
	const idGroup = (id, status) => {
		if (status === 'disabled') {
			// 已移除
			return `
			<div class="d-flex flex-column">
				<div class="mb-2">${id}</div>
				<button class="btn btn-default mr-2" disabled>
					已移除
				</button>
			</div>
			`
		}
		if (status === 'new') {
			return `
			<div class="d-flex flex-column">
				<div class="mb-2">${id}</div>
				<button class="btn btn-info mr-2" data-toggle="modal" data-target="#newModal">
					新增
				</button>
			</div>
			`
		}
		return `
			<div class="d-flex flex-column">
				<div class="mb-2">${id}</div>
				<button class="btn btn-danger mr-2" data-toggle="modal" data-target="#idModal">
					移除
				</button>
			</div>
		`
	}
	const statusGroup = (text, status) => {
		if (status === 'disabled') {
			return `
			<div class="d-flex flex-column">
				<div class="mb-2">${text}</div>
				<button class="btn btn-default mr-2" disabled>
					已移除
				</button>
			</div>
			`
		}
		return `
			<div class="d-flex flex-column">
				<div class="mb-2">${text}</div>
				<button class="btn btn-primary mr-2" data-toggle="modal" data-target="#statusModal">
					調整
				</button>
			</div>
			`
	}
	$(document).ready(function () {
		const t1 = $('#search-table').DataTable({
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
		const t2 = $('#main-table').DataTable({
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

		$('#rule-option').on('change', function () {
			t2.search(this.selectedOptions[0].label).draw()
		})


		for (let index = 0; index < 3; index++) {
			addRow(t1, [idGroup('14675', 'new'), 2222, 333, '信用不良紀錄', 'rules', '高', '封鎖', '.....'])
		}
		for (let index = 0; index < 15; index++) {
			const rule = Math.random() > 0.5 ? '信用不良紀錄' : '反詐欺規則'
			const status = Math.random() > 0.5 ? '' : 'disabled'
			addRow(t2, [idGroup(index, status), 2222, 333, rule, 'rules', '高', statusGroup('封鎖', status), '.....'])
		}
		t1.draw()
		t2.draw()
	});

	function addRow(table, [itemA, keyB, keyC, keyD, keyE, keyF, itemG, keyH]) {
		table.row.add([itemA, keyB, keyC, keyD, keyE, keyF, itemG, keyH, buttonToID(47346)])
	}
</script>

<style>
	.d-flex {
		display: flex;
		align-items: center;
	}

	.flex-column {
		flex-direction: column;
	}

	.align-start {
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

	.col-20 {
		flex: 0 0 20%;
	}

	.col-30 {
		flex: 0 0 30%;
	}

	.col {
		flex: 1 0 0%;
	}

	.w-100 {
		width: 100%;
	}

	.input-require::after {
		content: '*';
		color: #dc3545;
		margin-left: 4px;
		display: inline-block;
	}

	.orange-hint {
		color: orange;
		font-size: 12px;
	}

	.search-btn {
		display: flex;
		justify-content: flex-end;
		flex: 1 0 auto;
	}
</style>
