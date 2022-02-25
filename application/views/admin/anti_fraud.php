<style>
	#rows {
		display: flex;
		flex-direction: column;
	}

	.popover-content {
		padding: 10px;
		white-space: pre-line;
	}

	.panel-heading {
		height: 64px;
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
		width: 100% !important;
	}

	.d-none {
		display: none !important;
	}

	.d-flex {
		display: flex !important;
	}

	.flex-wrap {
		flex-wrap: wrap;
	}

	.align-items-center {
		align-items: center !important;
	}

	.justify-end {
		justify-content: flex-end;
	}

	.head-item-title {
		flex: 0 0 60px;
	}

	.input {
		width: 140px;
	}

	.form-select {
		width: 140px;
		height: 33px;
		padding: 0 8px;
	}

	.search-btn {
		padding: 6px 18px;
		background-color: #c4c4c4;
	}

	.header {
		display: flex;
		justify-content: space-evenly;
	}

	.header-item {
		text-align: center;
		padding: 6px 0;
		background: rgba(196, 196, 196, 0.5);
		flex: 0 0 16%;
	}

	.sortable {
		cursor: pointer;
	}

	.sortable:hover {
		background: #d3d3d3;
	}

	.data-row {
		margin: 12px 0;
		display: flex;
		justify-content: space-evenly;
		align-items: center;
	}

	.data-item {
		text-align: center;
		padding: 6px 0;
		flex: 0 0 16%;
		overflow-wrap: anywhere;
	}

	.data-item.value {
		padding: 0 15px;
		max-height: 80px;
		overflow: auto;
	}

	.btn-item {
		flex: 0 0 100px;
	}

	.item-full {
		max-width: 100%;
		flex: 1 0 0;
	}

	.result-data-row {
		margin-top: 18px;
		display: flex;
	}

	.result-data-item {
		flex: 1 0 auto;
		max-width: 30%;
	}

	.result-header-item {
		text-align: center;
		padding: 6px 0;
		background: rgba(196, 196, 196, 0.5);
		flex: 0 0 16%;
		padding: 5px 12px;
	}

	.result-value-item {
		padding: 5px 12px;
		text-align: center;
		max-height: 80px;
		overflow: auto;
		overflow-wrap: anywhere;
	}

	.input-require::after {
		content: '*';
		color: #dc3545;
		margin-left: 4px;
		display: inline-block;
	}


	.result-date {
		flex: 0 0 25%;
	}

	.panel {
		position: relative;
	}

	.mask {
		position: absolute;
		width: 100%;
		height: calc(100% - 64px);
		background-color: #c3c3c388;
		z-index: 5;
	}

	.loader {
		position: relative;
		left: 24vw;
		margin: 20px auto;
		border: 16px solid #f3f3f3;
		/* Light grey */
		border-top: 16px solid #3498db;
		/* Blue */
		border-radius: 50%;
		width: 120px;
		height: 120px;
		animation: spin 2s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>

<div id="page-wrapper">
	<div id="anti-fraud-app">
		<div class="row">
			<div class="col-12">
				<h1 class="page-header">反詐欺與授信政策管理指標</h1>
			</div>
		</div>
		<div class="row" id="panel">
			<div class="d-flex align-items-center">
				<div class="mr-2 head-item-title">會員ID:</div>
				<div class="input-group input">
					<input type="text" v-model="searchParam.userId" />
				</div>
				<div class="mx-2 head-item-title">指標項目:</div>
				<select class="form-select" id="target-option" v-model="searchParam.index">
					<option value="">請選擇</option>
					<option :value="item" v-for="item in options.index" :key="item">{{item}}</option>
				</select>
				<div class="mx-2 head-item-title">風險：</div>
				<select class="form-select" id="risk-option" v-model="searchParam.risk">
					<option value="">請選擇</option>
					<option :value="item" v-for="item in options.risk" :key="item">{{item}}</option>
				</select>
				<button class="btn ml-5 search-btn" @click="doSearch({page:1})">
					搜尋
				</button>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					反詐欺指標
				</div>
				<div class="panel-body">
					<div class="d-flex">
						<div>每頁顯示：</div>
						<select v-model="pagination.per_page" @change="doSearch({page:1})">
							<option :value="5">5</option>
							<option :value="10">10</option>
							<option :value="20">20</option>
							<option :value="50">50</option>
						</select>
					</div>
					<table id="andtfraud">
						<thead>
							<tr>
								<th>風險等級</th>
								<th>事件時間</th>
								<th>指標項目</th>
								<th>指標內容</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div class="d-flex justify-end">
						<v-page :data="pagination" @change_page="onChangePage"></v-page>
					</div>
				</div>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					黑名單狀態
					<div style="display: inline-block;" class="ml-2">
						<a class="btn btn-default" :href="'/admin/Risk/black_list?id='+searchUserId" target="_blank"
							:disabled="!searchUserIdStatus">查看黑名單資訊</a>
					</div>
				</div>
				<div class="mask" v-show="!searchUserIdStatus"></div>
				<div class="panel-body">
					<table id="status">
						<thead>
							<tr>
								<th>會員ID</th>
								<th>更新時間</th>
								<th>更新原因</th>
								<th>符合黑名單規則</th>
								<th>規則細項</th>
								<th>風險等級</th>
								<th>執行狀態</th>
								<th>到期時間</th>
								<th style="width: 110px;">備註</th>
								<th>會員資訊</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					新增風險等級
					<button class="btn btn-info ml-5" type="button" @click="openManualAddModal">
						＋手動新增反詐欺規則
					</button>
				</div>
				<div class="mask" v-show="!searchUserIdStatus"></div>
				<form class="panel-body" @submit.prevent="openNewRisk">
					<div class="result-data-row">
						<div class="result-data-item">
							<div class="result-header-item key">項目</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-1" v-model="riskTreeSelect.node1"
									required>
									<option value="-1">請選擇</option>
									<option :value="item" v-for="item in riskTree" :key="item.id">
										{{ item.name }}
									</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">資料來源</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-2" v-model="riskTreeSelect.node2"
									required>
									<option value="-1">請選擇</option>
									<option :value="item" v-for="item in riskTreeSelect.node1.children" :key="item.id">
										{{ item.name }}
									</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">歸類</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-3" v-model="riskTreeSelect.node3"
									required>
									<option value="-1">請選擇</option>
									<option :value="item" v-for="item in riskTreeSelect.node2.children" :key="item.id">
										{{ item.name }}
									</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">內容</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-4" v-model="riskTreeSelect.node4"
									required>
									<option value="">請選擇</option>
									<option :value="item" v-for="item in riskTreeSelect.node3.children" :key="item.id">
										{{ item.description }}
									</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">風險</div>
							<div class="result-value-item value">
								{{riskTreeSelect.node4.risk}}
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">解決方式</div>
							<div class="result-value-item value">
								{{riskTreeSelect.node4.block}}
							</div>
						</div>
					</div>
					<div class="d-flex justify-end mt-4">
						<button class="btn btn-primary" :disabled="!riskNext">
							下一步
						</button>
					</div>
				</form>
			</div>
		</div>
		<div class="modal fade" id="riskModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form class="modal-content" @submit.prevent="submitRisk">
					<div class="modal-header">
						<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h3 class="modal-title">新增風險等級</h3>
					</div>
					<div class="modal-body p-5" v-if="riskTreeSelect.node4">
						<div class="d-flex mb-4" v-for="item in riskTreeSelect.node4.columnMap" :key="item.key">
							<div class="col-20 input-require">{{item.label}}</div>
							<div class="col">
								<input type="text" required class="w-100 form-control" v-model="item.value">
							</div>
						</div>
						<div class="d-flex justify-between mx-3 mb-3">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
							<button type="submit" class="btn btn-primary">送出</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="modal fade" id="manualAddModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form class="modal-content" @submit.prevent="submitManualAdd">
					<div class="modal-header">
						<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h3 class="modal-title">手動新增風險等級</h3>
					</div>
					<div class="modal-body p-5">
						<div class="d-flex mb-4">
							<div class="col-20 input-require">內容</div>
							<div class="col">
								<input type="text" v-model="manualAddParam.description" required
									class="w-100 form-control">
							</div>
						</div>
						<div class="d-flex mb-4">
							<div class="col-20 input-require">風險</div>
							<div class="col">
								<select class="form-control" v-model="manualAddParam.risk" required>
									<option value="">請選擇</option>
									<option :value="item" v-for="item in options.risk" :key="item">{{item}}</option>
								</select>
							</div>
						</div>
						<div class="d-flex mb-4">
							<div class="col-20 input-require">解決方式</div>
							<div class="col">
								<select class="form-control" v-model="manualAddParam.block_text" required>
									<option value="">請選擇</option>
									<option :value="item" v-for="item in options.block_text" :key="item">{{item}}
									</option>
								</select>
							</div>
						</div>
						<div class="d-flex justify-between mx-3 mb-3">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
							<button type="submit" class="btn btn-primary">送出</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script> -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/assets/admin/js/vue-components.js"></script>
<script>
	const apiUrl = "/api/v2/anti_fraud"
	$(document).ready(function () {
		const t = $('#andtfraud').DataTable({
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
		const t2 = $('#status').DataTable({
			'ordering': false,
			"paging": false,
			"info": false,
			"searching": false,
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
		v.onReady()
	})

	const v = new Vue({
		el: '#page-wrapper',
		data() {
			return {
				searchParam: {
					userId: null,
					index: null,
					risk: null
				},
				manualAddParam: {
					description: null,
					block_text: null,
					risk: null
				},
				options: {
					block_rule: [],
					block_text: [],
					index: [],
					risk: [],
					manually_add_type_id: ''
				},
				pagination: {
					current_page: 1,
					last_page: 1,
					per_page: 10
				},
				antiTable: [],
				riskTree: [],
				riskTreeSelect: {
					node1: {},
					node2: {},
					node3: {},
					node4: {},
				},
				searchUserId: null,
				searchUserIdStatus: false
			}
		},
		computed: {
			riskNext() {
				return Object.keys(this.riskTreeSelect.node4).length > 0
			}
		},
		methods: {
			convertDate(n) {
				return new Date(n * 1000).toLocaleString()
			},
			onReady() {
				this.doSearch({})
				this.getOption()
				this.getNewTree()
			},
			onChangePage(page) {
				this.doSearch({ page })
			},
			openNewRisk() {
				$('#riskModal').modal('toggle')
			},
			openManualAddModal() {
				$('#manualAddModal').modal('toggle')
			},
			getOption() {
				axios.get(`/api/v2/black_list/get_option`)
					.then(({ data }) => {
						if (!data.results) {
							alert(data.message)
							return
						}
						this.options = { ...data.results }
					})
			},
			doSearch({ page }) {
				const { searchParam, pagination } = this
				this.searchUserIdStatus = false
				this.searchUserId = null
				const table = $('#status').DataTable()
				table.clear().draw()
				const pageParam = page ?? this.pagination.current_page
				axios.get(`${apiUrl}/get_anti_list`, {
					params: {
						...searchParam,
						page: pageParam,
						count: pagination.per_page
					}
				}).then(({ data }) => {
					if (!data.results) {
						alert(data.message)
						return
					}
					if (searchParam.userId) {
						this.searchUserIdStatus = true
						this.searchUserId = searchParam.userId
					}
					this.antiTable = data.results
					this.pagination = {
						current_page: data.pagination.page,
						last_page: data.pagination.last_page,
						per_page: data.pagination.count
					}
				})
				// other axios 
				if (searchParam.userId) {
					// get 
					axios.get(`/api/v2/black_list/get_all_block_users`, {
						params: {
							userId: searchParam.userId
						}
					}).then(({ data }) => {
						// draw table
						const table = $('#status').DataTable()
						table.clear()
						const reason = (text) => {
							return `<div style="white-space:pre-wrap;">${text}</div>`
						}
						const buttonToID = (id) => {
							return `<div class="d-flex">
								<button class="btn btn-default mr-2">
									<a href="/admin/user/edit?id=${id}" target="_blank">查看</a>
								</button>
							</div>`
						}
						const idGroup = (id, status) => {
							let s = ''
							if (status === 0) {
								s = '停用'
							}
							if (status === 1) {
								s = '封鎖中'
							}
							if (status === 2) {
								s = '已過期'
							}
							return `<div>
								<div>${id}</div>
								<div>${s}</div>
							</div>`
						}
						data.results.forEach(item => {
							const endDate = item.blockInfo.endAt > 0 ? this.convertDate(item.blockInfo.endAt) : '永久'
							table.row.add([
								idGroup(item.userId, item.status),
								this.convertDate(item.updatedAt),
								item.updateReason,
								item.blockRule,
								item.blockDescription,
								item.blockRisk,
								item.blockInfo.blockTimeText,
								endDate,
								reason(item.blockRemark),
								buttonToID(item.userId),
							])
						})
						table.draw()
					})
				}
			},
			getNewTree() {
				axios.get(`${apiUrl}/get_new_tree`)
					.then(({ data }) => {
						if (!data.results) {
							alert(data.message)
							return
						}
						this.riskTree = data.results
					})
			},
			submitRisk() {
				axios.post(`${apiUrl}/new_risk`, {
					userId: this.searchParam.userId,
					...this.riskTreeSelect.node4
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
						return
					}
					$('#riskModal').modal('hide')
					this.doSearch({})
				})
			},
			submitManualAdd() {
				axios.post(`${apiUrl}/manual_add`, {
					'manually_add_type_id': this.options.manually_add_type_id,
					...this.manualAddParam
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
						return
					}
					$('#manualAddModal').modal('hide')
					this.doSearch({})
					this.getNewTree()
				})
			},
		},
		watch: {
			antiTable(data) {
				// draw table
				const table = $('#andtfraud').DataTable()
				table.clear()
				const pop = (s) => {
					return `
				<button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="${s}">
					 <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
				</button>`
				}
				data.forEach(e => {
					let popText = '指標：\n'
					for (const text of e.index) {
						popText += text + ', '
					}
					popText = popText.substr(0, popText.length - 2)
					popText += '\n\n'
					for (const obj of e.columnMap) {
						let s = ''
						if (typeof obj.value === 'object') {
							for (const iterator of obj.value) {
								if (typeof iterator === 'object') {
									for (const [k, v] of Object.entries(iterator)) {
										s += `${v} - `
									}
									s = s.substr(0, s.length - 3)
									s += '\n'
								} else {
									s += iterator + ', '
								}
							}
						} else {
							s += obj.value
						}
						popText += `${obj.label}： \n ${s} \n\n`
					}
					const t = [
						`${e.risk} - ${e.userId}`,
						this.convertDate(e.updatedAt),
						e.mainDescription,
						e.description,
						pop(popText)
					]

					table.row.add(t)
				})
				table.draw()
				$('[data-toggle="popover"]').popover()
			},
			"riskTreeSelect.node1"() {
				this.riskTreeSelect.node2 = {}
				this.riskTreeSelect.node3 = {}
				this.riskTreeSelect.node4 = {}
			},
			"riskTreeSelect.node2"(data) {
				this.riskTreeSelect.node3 = {}
				this.riskTreeSelect.node4 = {}
			},
		},
	})

</script>
