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
		<h1 class="page-header">後台權限設置</h1>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="search-heading row">
				<div>
					<span class="name">部門名稱</span>
					<input type="text" id="search" />
				</div>
				<div class="search-btn">
					<button class="btn btn-primary btn-sm">搜尋</button>
				</div>
			</div>
		</div>
		<div class="new-role m-4">
			<a class="btn btn-primary" href="<?=admin_url('Admin/role_list_edit') ?>" target="_blank">新增角色</a>
		</div>
		<div class="table-row">
			<table class="display responsive nowrap" width="100%" id="table-roles-setting">
				<thead>
					<tr>
						<th>部門</th>
						<th>組別</th>
						<th>角色名稱</th>
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
			language: {
				search: '搜尋欄位',
			},
		})
		for (let index = 0; index < 30; index++) {
			insertDataRow({ table, part: index, group: 0, role: 0, id: index })
		}
	});
	function insertDataRow({ table, part, group, role, id }) {
		const origin = window.location.origin
		const button = `<button class="btn btn-default" onClick="window.open('http://localhost:8080/admin/Admin/role_list_edit?id=${id}')">Edit</button>`
		table.row.add([part, group, role, button]).draw()
	}
</script>
