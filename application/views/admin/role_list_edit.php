<style>
	#page-wrapper {
		font-size: 16px;
	}

	.settings-item {
		padding: 25px 0 0 25px;
		display: flex;
		gap: 15px;
	}

	.block-title {
		border-radius: 6px;
		line-height: 34px;
		background-color: #c4c4c4;
		width: 110px;
		text-align: center;
	}

	.input-custom {
		width: 140px;
	}

	.row {
		margin: 25px 0;
		display: flex;
		gap: 15px;
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
		display: grid;
		grid-template-columns: 1fr 1fr;
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
	<div class="settings-item">
		<div class="block-title">部門</div>
		<div class="input-group input-custom">
			<input class="form-control" type="text">
		</div>
	</div>
	<div class="settings-item">
		<div class="block-title">組別</div>
		<div class="input-group input-custom">
			<input class="form-control" type="text">
		</div>
	</div>
	<div class="settings-item">
		<div class="block-title">角色名稱</div>
		<div class="input-group input-custom">
			<select class="form-control">
				<option value="">執行長</option>
				<option value="">主管</option>
				<option value="">經辦</option>
			</select>
		</div>
	</div>
	<div class="settings-item">
		<div class="block-title">權限設定</div>
	</div>
	<div class="row">
		<div class="panel-title">產品管理 :</div>
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
	<div class="row">
		<div class="panel-title">借款管理 :</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="checkbox-group">
					<div class="check-title">全部列表</div>
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
