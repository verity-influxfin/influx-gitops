<style>
	#rows {
		display: flex;
		flex-direction: column;
	}
	
	.panel-heading{
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
								<input type="text" v-model="manualAddParam.description" required class="w-100 form-control">
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
						const idGroup = (id) => {
							return `<div>
								<div>${id}</div>
								<button class="btn btn-default mr-2">
									<a href="/admin/Risk/black_list?id=${id}" target="_blank">查看黑名單資訊</a>
								</button>
							</div>`
						}
						data.results.forEach(item => {
							const endDate = item.blockInfo.endAt > 0 ? this.convertDate(item.blockInfo.endAt) : '永久'
							table.row.add([
								idGroup(item.userId),
								this.convertDate(item.updatedAt),
								item.updateReason,
								item.blockRule,
								item.blockDescription,
								item.blockRisk,
								item.status,
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
			submitManualAdd(){
				axios.post(`${apiUrl}/manual_add`, {
					...this.manualAddParam
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
						return
					}
					$('#manualAddModal').modal('hide')
					this.doSearch({})
				})
			},
		},
		watch: {
			antiTable(data) {
				// draw table
				const table = $('#andtfraud').DataTable()
				table.clear()
				data.forEach(e => {
					const t = [
						`${e.risk} - ${e.userId}`,
						this.convertDate(e.updatedAt),
						e.mainDescription,
						e.description
					]
					table.row.add(t)
				})
				table.draw()
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
<!-- <script>
	let searchWay = ''
	let antiFraudData = []
	let colMap = []
	let ruleAll = []
	let orderBy = null
	let searching = false
	let loading = false
	const faIcons = []
	const apiUrl = "/api/v2/anti_fraud/"
	let typeIds = []
	let prevStartTime = 0
	let prevEndTime = 999999999999

	$(document).ready(function () {
		const t = $('#andtfraud').DataTable({
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
		})
		$('#risk-option').on('change', function () {
			t.column(0).search(this.value).draw()
		})
		$('#target-option').on('change', function () {
			if (searchWay !== 'target') {
				t.column(2).search(this.value).draw()
			} else {
				t.column(2).search('').draw()
			}

		})
	})
	async function onLoad() {
		// insertDefaultPanel()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		faIcons.push(
			document.querySelector("#asc"),
			document.querySelector("#desc"),
			document.querySelector("#default-order")
		)
		// insert options
		colMap = await getColumnMap()
		ruleAll = await getRuleAll()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		insertTargetOption()
	}
	window.addEventListener("load", onLoad())
	async function doSearch() {
		if (searching) {
			return
		}
		searchWay = ''
		const userId = document.querySelector('#user-id').value
		const target = document.querySelector('#target-option').value
		const risk = document.querySelector('#risk-option').value
		const table = $('#andtfraud').DataTable()
		// clear table and set searching status
		table.clear()
		table.column(0).search('')
		table.column(2).search('')
		table.row.add(['<div class="loader"></div>', '', '', '']).draw()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		searching = true
		if (userId) {
			searchWay = 'userId'
			// userid 1st
			const ans = await getResultByuserId(userId)
			table.clear()
			const tablsRows = ans.map(x => {
				const mydata = {}
				x.forEach(e => {
					mydata[e.key] = { ...e }
				})
				const risk = mydata?.risk.value
				const updatedAt = mydata?.updatedAt.value
				const description = mydata?.description.value === mydata?.mainDescription.value ? mydata?.mainDescription.value : mydata?.mainDescription.value + ' ' + mydata?.description.value
				const [first, ...sec] = [...description.split('】')]
				return [risk, convertDate(updatedAt), first + '】', sec.join('】')]
			}).filter(x => {
				// filter by target and risk in search
				if (target) {
					if (risk) {
						// 有target and risk
						return x[0].includes(risk) && (x[2] + x[3]).includes(target)
					}
					return (x[2] + x[3]).includes(target)
				}
				if (risk) {
					return x[0].includes(risk)
				}
				return true
			}).forEach(x => table.row.add(x))
			table.draw()
			document.querySelector('#search-btn').toggleAttribute('disabled')
			searching = false
			return
		}
		if (target) {
			//only target 2nd
			searchWay = 'target'
			const myMap = new Map()
			colMap.forEach(x => {
				myMap.set(x.label, x.result)
			})
			const ans = myMap.get(target)
			if (ans) {
				const res = await getRuleTypeId(ans)
				table.clear()
				res.map(x => {
					const mydata = {}
					x.forEach(e => {
						mydata[e.key] = { ...e }
					})
					const risk = mydata?.risk.value + `  會員ID：${mydata?.userId.value}`
					const updatedAt = mydata?.updatedAt.value
					const description = mydata?.description.value === mydata?.mainDescription.value ? mydata?.mainDescription.value : mydata?.mainDescription.value + ' ' + mydata?.description.value
					const [first, ...sec] = [...description.split('】')]
					return [risk, convertDate(updatedAt), first + '】', sec.join('】')]
				}).filter(item => {
					if (risk) {
						return item[0].includes(risk)
					}
					return true
				}).forEach(item => table.row.add(item))
			}
			table.column(2).search('').draw()
			document.querySelector('#search-btn').toggleAttribute('disabled')
			searching = false
			return
		}
		// no userid and target
		searchWay = 'risk'
		const item = await getRiskMap(risk)
		const res = await getRuleRuleId(item)
		table.clear()
		res.forEach(x => {
			const mydata = {}
			x.forEach(e => {
				mydata[e.key] = { ...e }
			})
			const risk = mydata?.risk.value + `  會員ID：${mydata?.userId.value}`
			const updatedAt = mydata?.updatedAt.value
			const description = mydata?.description.value === mydata?.mainDescription.value ? mydata?.mainDescription.value : mydata?.mainDescription.value + ' ' + mydata?.description.value
			const [first, ...sec] = [...description.split('】')]
			table.row.add([risk, convertDate(updatedAt), first + '】', sec.join('】')])
		})
		table.draw()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		searching = false
		return

	}

	function insertTargetOption() {
		const parent = document.querySelector('#target-option')
		while (parent.firstChild) {
			parent.removeChild(parent.firstChild)
		}
		const map = new Map()
		colMap.forEach(({ key, ...o }) => {
			map.set(key, o)
		})
		keys = Array.from(map.keys())
		parent.insertAdjacentHTML('beforeend',
			`<option value="" key="">請選擇</option>`
		)
		keys.forEach((x) => {
			parent.insertAdjacentHTML('beforeend',
				`<option result="${map.get(x).result}" value="${map.get(x).label}" key="${x}">${map.get(x).label}</option>`
			)
		})
	}
	// converts
	function convertDate(date) {
		const d = new Date(date * 1000)
		return d.getFullYear() + '-' + Number(d.getMonth() + 1) + '-' + d.getDate()
	}
	//apis
	function getColumnMap() {
		return fetch(`${apiUrl}/column_map`).then(x => x.json()).then(({ response }) => {
			return response.results
		})
	}

	function getProductConfig() {
		return fetch('/api/v2/anti_fraud/product_config')
			.then(x => x.json())
			.then(({ data }) => {
				return data
			})
	}

	function getResultByuserId(userId) {
		return fetch(apiUrl + "/user_id?userId=" + userId)
			.then(x => x.json())
			.then(({ response }) => {
				return response.results
			})
	}
	function getRiskMap(risk) {
		return fetch(apiUrl + "/risk_map?risk=" + risk)
			.then(x => x.json())
			.then(({ response }) => {
				return response.results
			})
	}

	function getRuleAll() {
		return fetch(apiUrl + "/rule_all")
			.then((x) => x.json())
			.then(({ response }) => {
				return response.results
				// return response.results.map((x) => x.typeId)
			})
	}
	function getRuleRuleId(data) {
		const fetchRule = ({ typeId, ruleId }) => {
			return fetch(
				`${apiUrl}/ruleId?typeId=${typeId}&ruleId=${ruleId}`
			)
				.then((res) => {
					if (res.ok) {
						return res.json()
					} else {
						throw new Error(res.statusText)
					}
				})
				.then(({ response }) => {
					return response.results
				})
				.catch((err) => {
					return Promise.reject(err)
				})
		}
		const fetchRules = []
		data.forEach(({ typeId, ruleId }) => {
			fetchRules.push(
				fetchRule({ typeId, ruleId })
			)
		})
		return Promise.allSettled(fetchRules)
			.then((x) => {
				const ans = x.filter((res) => {
					return res.status == "fulfilled"
				})
				return ans.flatMap((x) => x.value)
			})
			.catch((err) => console.error(err))
	}

	function getRuleTypeId(data) {
		const fetchRule = (typeId) => {
			return fetch(
				`${apiUrl}/typeId?typeId=${typeId}`
			)
				.then((res) => {
					if (res.ok) {
						return res.json()
					} else {
						throw new Error(res.statusText)
					}
				})
				.then(({ response }) => {
					return response.results
				})
				.catch((err) => {
					return Promise.reject(err)
				})
		}
		const fetchRules = []
		data.forEach(typeId => {
			fetchRules.push(
				fetchRule(typeId)
			)
		})
		return Promise.allSettled(fetchRules)
			.then((x) => {
				const ans = x.filter((res) => {
					return res.status == "fulfilled"
				})
				return ans.flatMap((x) => x.value)
			})
			.catch((err) => console.error(err))
	}
</script> -->
