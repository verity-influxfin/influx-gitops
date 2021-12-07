<style>
	#page-wrapper {
		font-size: 16px;
	}

	.basic-info {
		margin-top: 25px;
		border-spacing: 12px;
		border-collapse: separate;
		width: 100%;
	}

	.settings-item {
		padding: 25px 0 0 25px;
	}

	.block-title {
		line-height: 34px;
		border-radius: 6px;
		background-color: #c4c4c4;
		width: 130px;
		text-align: center;
	}

	.item {
		padding: 0 12px;
		text-align: center;
	}

	.bgc-2 {
		background: #C4C4C455;
	}

	.settings {
		display: grid;
		grid-template-columns: 1fr 1fr;
	}

	.input-custom {
		width: 200px;
	}

	.row {
		margin: 25px 0;
		display: flex;
	}

	.panel-title {
		flex: 1 0 auto;
	}

	.panel {
		flex: 1 1 100%;
		max-width: 100%;
		max-height: 280px;
		overflow: auto;
	}

	.panel-body {
		max-height: 500px;
	}

	.panel-body::before {
		display: none;
	}

	.checkbox-group {
		display: flex;
		gap: 12px;
		padding: 4px;
		margin-bottom: 10px;
	}
</style>

<div id="page-wrapper">
	<table class="basic-info">
		<thead>
			<th class="block-title">姓名</th>
			<th class="block-title">帳號</th>
			<th class="block-title">部門</th>
			<th class="block-title">組別</th>
			<th class="block-title">角色</th>
		</thead>
		<tbody>
			<tr>
				<td class="item">Jack</td>
				<td class="item">jack@influxfin.com</td>
				<td class="item">科技部</td>
				<td class="item">專案管理組</td>
				<td class="item">主管</td>
			</tr>
		</tbody>
	</table>

	<div class="settings">
		<div class="settings-item">
			<div class="block-title bgc-2">權限1</div>
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="checkbox-group">
							<div class="check-title">產品管理</div>
							<div class="check-item">
								<input type="checkbox" name="create">
								<label>增</label>
							</div>
							<div class="check-item">
								<input type="checkbox" name="delete">
								<label>刪</label>
							</div>

							<div class="check-item">
								<input type="checkbox" name="update">
								<label>改</label>
							</div>
							<div class="check-item">
								<input type="checkbox" name="read">
								<label>查</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="settings-item">
			<div class="block-title bgc-2">例外權限</div>
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="checkbox-group">
							<div class="check-title">產品管理</div>
							<div class="check-item">
								<input type="checkbox" name="create">
								<label>增</label>
							</div>
							<div class="check-item">
								<input type="checkbox" name="delete">
								<label>刪</label>
							</div>

							<div class="check-item">
								<input type="checkbox" name="update">
								<label>改</label>
							</div>
							<div class="check-item">
								<input type="checkbox" name="read">
								<label>查</label>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
