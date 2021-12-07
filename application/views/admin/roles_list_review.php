<style>
	.search-heading {
		display: flex;
		align-items: center;
		gap: 15px;
	}

	.table-row {
		padding: 15px;
	}
</style>
<div id="page-wrapper">
	<div class="row">
		<h1 class="page-header">權限審查</h1>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="search-heading row">
				<div>
					<span class="name">名稱</span>
					<input type="text" id="search" />
				</div>
				<div class="search-btn">
					<button class="btn btn-primary btn-sm">搜尋</button>
				</div>
			</div>
		</div>
		<div class="table-row">
			<table class="display responsive nowrap" width="100%" id="table-roles-setting">
				<thead>
					<tr>
						<th>帳號</th>
						<th>姓名</th>
						<th>部門</th>
						<th>組別</th>
						<th>角色</th>
						<th>修改</th>
					</tr>
				</thead>
				<tbody id="tbody">
				</tbody>
			</table>
		</div>

	</div>
</div>
<script>
	$(document).ready(() => {
		const table = $('#table-roles-setting').DataTable({
			'language': {
				'processing': '處理中...',
				'lengthMenu': '顯示 _MENU_ 項結果',
				'zeroRecords': '目前無資料',
				'info': '顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項',
				'infoEmpty': '顯示第 0 至 0 項結果，共 0 項',
				'infoFiltered': '(從 _MAX_ 項結果過濾)',
				'search': '搜尋結果',
				'paginate': {
					'first': '首頁',
					'previous': '上頁',
					'next': '下頁',
					'last': '尾頁'
				}
			},
		})
		for (let index = 0; index < 30; index++) {
			const enabled = Math.random() > 0.55
			insertDataRow({ table, account: 'aaa', name: 'aaa' + index, part: index, group: 0, role: 0, id: index , enabled})
		}
	});
	function insertDataRow({ table, account, name, part, group, role, id, enabled }) {
		const origin = window.location.origin
		
		const button = `
		<div class="search-heading">
			<button class="btn btn-default" onClick="window.open('${origin}/admin/Admin/role_review_edit?id=${id}')">細節</button>
			<div class="check-item">
				<input type="checkbox" name="create" ${enabled ? 'checked' : ''}>
				<label>啟用</label>
			</div>
		</div>
		`
		table.row.add([account, name, part, group, role, button]).draw()
	}
</script>
