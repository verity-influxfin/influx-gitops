<style>
	#rows {
		display: flex;
		flex-direction: column;
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


	.result-date {
		flex: 0 0 25%;
	}

	.loader {
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
					<input type="text" id="user-id" />
				</div>
				<div class="mx-2 head-item-title">指標項目:</div>
				<select class="form-select" id="target-option">
					<option value="">請選擇</option>
				</select>
				<div class="mx-2 head-item-title">風險：</div>
				<select class="form-select" id="risk-option">
					<option value="">請選擇</option>
					<option value="高">高</option>
					<option value="中">中</option>
					<option value="低">低</option>
					<option value="拒絕">拒絕</option>
				</select>
				<button class="btn ml-5 search-btn" id="search-btn" onclick="doSearch()">
					搜尋
				</button>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					反詐欺指標
				</div>
				<div class="panel-body">
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
				</div>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					狀態
				</div>
				<div class="panel-body">
					<div class="d-flex align-items-center">
						<div class="head-item-title mx-2">現在狀態: </div>
						<div class="input-group input">
							<input type="text" class="form-control input" id="status-now" value="封鎖六個月" disabled />
						</div>
						<div class="mx-2 head-item-title">調整:</div>
						<select class="form-select" id="status-change">
							<option value="-1">請選擇</option>
						</select>
						<div class="mx-2 head-item-title">註記:</div>
						<div class="input-group input">
							<input type="text" class="form-control input" id="status-mark" />
						</div>
					</div>
					<div class="d-flex justify-end mt-4">
						<button class="btn btn-primary" onclick="">
							送出
						</button>
					</div>
				</div>
			</div>
			<div class="panel panel-default mt-4">
				<div class="panel-heading p-4">
					新增風險等級
				</div>
				<div class="panel-body">
					<div class="result-data-row">
						<div class="result-data-item">
							<div class="result-header-item key">項目</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-1">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">資料來源</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-2">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">歸類</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-3">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">內容</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-4">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">風險</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-5">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
						<div class="result-data-item">
							<div class="result-header-item key">解決方式</div>
							<div class="result-value-item value">
								<select class="form-select w-100" id="new-risk-6">
									<option value="-1">請選擇</option>
								</select>
							</div>
						</div>
					</div>
					<div class="d-flex justify-end mt-4">
						<button class="btn btn-primary" onclick="">
							送出
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<template id="default-panel">
	<div class="panel panel-default">
		<div class="panel-heading p-4">
			<div class="d-flex align-items-center">
				<div class="mr-2 head-item-title">時間：</div>
				<div class="input-group input">
					<input type="text" data-toggle="datepicker" class="datepicker form-control input" id="start-time" />
				</div>
				<span class="mx-2">~</span>
				<div class="input-group input">
					<input type="text" data-toggle="datepicker" class="datepicker form-control" id="end-time"
						aria-label="Default" aria-describedby="inputGroup-sizing-default" />
				</div>
			</div>
			<div class="d-flex align-items-center mt-4">
				<div class="mr-2 head-item-title">產品別：</div>
				<div class="input-group input">
					<select class="form-select" id="search-option">
					</select>
				</div>
				<button class="btn ml-5 search-btn" id="search-btn" onclick="doSearch()">
					搜尋
				</button>
			</div>
		</div>
		<div class="panel-body">
			<div>
				<div class="header">
					<div class="header-item">時間</div>
					<div class="header-item">產品別</div>
					<div class="header-item item-full">反詐欺規則</div>
					<div class="header-item sortable" onclick="onSort()">
						<span class="mr-2">有效性</span>
						<i class="fa fa-sort" aria-hidden="true" id="default-order"></i>
						<i class="fa fa-sort-desc d-none" aria-hidden="true" id="desc"></i>
						<i class="fa fa-sort-asc d-none" aria-hidden="true" id="asc"></i>
					</div>
					<div class="header-item btn-item"></div>
				</div>
			</div>
			<div id="rows"></div>
		</div>
	</div>
</template>
<template id="data-row">
	<div class="data-row">
		<div class="data-item"></div>
		<div class="data-item"></div>
		<div class="data-item item-full"></div>
		<div class="data-item"></div>
		<div class="data-item btn-item">
			<button class="btn" onclick="insertResultPanel(event)">命中結果</button>
		</div>
	</div>
</template>
<template id="result-panel">
	<div class="result">
		<div>
			<button class="btn m-2" onclick="onLoad()">回上頁</button>
		</div>
		<div class="d-flex">
			<div class="data-item item-full">
				<div class="header-item">反詐欺規則</div>
				<div class="data-item" id="rule"></div>
			</div>
			<div class="data-item result-date">
				<div class="header-item">日期</div>
				<div class="data-item" id="date"></div>
			</div>
		</div>
		<div id="result-rows"></div>
	</div>
</template>
<template id="result-data-item">
	<div class="result-data-item">
		<div class="result-header-item key"></div>
		<div class="result-value-item value"></div>
	</div>
</template>
<template id="result-data-row">
	<div class="result-data-row"></div>
</template>
<template id="loader">
	<div class="loader"></div>
</template>

<script>
	let searchWay = ''
	let antiFraudData = [];
	let colMap = []
	let ruleAll = []
	let orderBy = null;
	let searching = false
	let loading = false
	const faIcons = [];
	const apiUrl = "/api/v2/anti_fraud/";
	let typeIds = [];
	let configs = []
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
				'search': '搜尋結果',
				'paginate': {
					'first': '首頁',
					'previous': '上頁',
					'next': '下頁',
					'last': '尾頁'
				}
			},
			"info": false
		});
		$('#risk-option').on('change', function () {
			if (searchWay === 'userId') {
				t.column(0).search(this.value).draw()
			}

		})
		$('#target-option').on('change', function () {
			if (searchWay === 'userId') {
				t.column(2).search(this.value).draw()
			}
		})
	});
	async function onLoad() {
		// insertDefaultPanel()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		faIcons.push(
			document.querySelector("#asc"),
			document.querySelector("#desc"),
			document.querySelector("#default-order")
		);

		//query
		const res = await getProductConfig()
		configs = Object.keys(res).map(x => {
			return {
				name: res[x].name,
				value: x
			}
		})
		// insert options
		// insertSearchOption()
		colMap = await getColumnMap()
		ruleAll = await getRuleAll()
		document.querySelector('#search-btn').toggleAttribute('disabled')
		insertTargetOption()
		console.log(colMap, ruleAll)
	}
	window.addEventListener("load", onLoad());
	async function doSearch() {
		searchWay = ''
		const userId = document.querySelector('#user-id').value
		const target = document.querySelector('#target-option').value
		const risk = document.querySelector('#risk-option').value
		const table = $('#andtfraud').DataTable()
		table.clear()
		if (userId) {
			searchWay = 'userId'
			// userid 1st
			const ans = await getResultByuserId(userId)
			console.log(ans)
			const aa2 = ans.map(x => {
				const update = x.find(x => x.key === 'updatedAt').value
				const rule = x.filter(item1 => {
					return item1.key === 'typeId' || item1.key === 'ruleId'
				})
				// console.log(rule)
				const typeid = rule[0].key === 'typeId' ? rule[0] : rule[1]
				const ruleid = rule[1].key === 'ruleId' ? rule[1] : rule[0]
				const find = ruleAll.find(a => {
					return a.typeId === typeid.value
				})
				const find2 = find.rules.find(a => {
					return a.id === ruleid.value
				})
				const da = find.description === find2.description ? find2.description : find.description + ' ' + find2.description
				const [first, ...sec] = [...da.split('】')]
				return [find2.risk, converDate(update), first + '】', sec.join('】')]
			})
			aa2.forEach(x => {
				table.row.add(x)
			})
			// table.row.add([1, 1, 1, 1])
			table.draw()
			return
		}
		if (target) {
			//only target 2nd
			return
		}
		return
		// disabled only risk
		const item = await getRiskMap(risk)

		const res = await getRuleTypeId(item)
		res.forEach(x=>{
			const update = x.find(item=>{
				return item.key === 'updatedAt' 
			}).value
			const typeId = x.find(item => {
				return item.key === 'typeId'
			}).value
			const ruleId = x.find(item => {
				return item.key === 'ruleId'
			}).value

			const find = ruleAll.find(a => {
				return a.typeId === typeId
			})
			const find2 = find.rules.find(a => {
				return a.id === ruleId
			})
			console.log(x,typeId, ruleId)
			console.log(find)
			console.log(find2)
			const da = find.description === find2.description ? find2.description : find.description + ' ' + find2.description
			const [first, ...sec] = [...da.split('】')]
			table.row.add([risk,converDate(x), first + '】', sec.join('】')])
		})
		table.draw()
		// no userid and target
	}

	function insertTargetOption() {
		const parent = document.querySelector('#target-option')
		while (parent.firstChild) {
			parent.removeChild(parent.firstChild)
		}
		const map = new Map()
		colMap.forEach(x => {
			const key = Object.keys(x)[0]
			x[key].forEach(item => {
				map.set(item.key, item.value)
			})

		})
		keys = Array.from(map.keys())
		parent.insertAdjacentHTML('beforeend',
			`<option value="" key="">請選擇</option>`
		)
		keys.forEach((x) => {
			parent.insertAdjacentHTML('beforeend',
				`<option value="${map.get(x)}" key="${x}">${map.get(x)}</option>`
			)
		})
	}
	function insertData({ id, rule, duration, ruleId, productId, efficiency }) {
		const template = document.querySelector("template#data-row");
		const dataArray = template.content.querySelectorAll(".data-item");
		const button = template.content.querySelector(".btn");
		//insert
		dataArray[0].textContent = duration;
		dataArray[1].textContent = converProductId({ productId });
		dataArray[2].textContent = rule;
		dataArray[3].textContent = convertEfficiency({ efficiency });
		button.setAttribute('rule-id', ruleId)
		button.setAttribute('rule', rule)
		button.setAttribute('duration', duration)
		// dataArray[4].textContent = x;
		const rows = document.querySelector("#rows");
		const clone = document.importNode(template.content, true);
		rows.appendChild(clone);
	}
	function removeDatas() {
		const parent = document.querySelector('#rows')
		while (parent.firstChild) {
			parent.removeChild(parent.firstChild)
		}
	}
	function toggleLoading(params) {
		const parent = document.querySelector('#rows')
		if (parent.querySelector('.loader')) {
			parent.removeChild(parent.querySelector('.loader'))
		} else {
			const template = document.querySelector("template#loader");
			const clone = document.importNode(template.content, true);
			parent.appendChild(clone);
		}
	}
	function insertSearchOption() {
		const parent = document.querySelector('#search-option')
		configs.forEach(({ name, value }) => {
			parent.insertAdjacentHTML('beforeend',
				`<option value="${value}">${name}</option>`
			)
		})
	}
	function insertDefaultPanel() {
		const parent = document.querySelector("#panel");
		const child = parent.querySelector("div");
		if (child) {
			parent.removeChild(child);
		}
		const template = document.querySelector("template#default-panel");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
		// trigger datepicker
		if ($('.datepicker').datepicker) {
			$('.datepicker').datepicker()
		}
	}
	async function insertResultPanel(e) {
		if (searching) {
			return
		}
		searching = true
		antiFraudData = []
		const parent = document.querySelector("#panel");
		const child = parent.querySelector(".panel.panel-default");
		const template = document.querySelector("template#result-panel");
		ruleId = e.target.getAttribute('rule-id')
		duration = e.target.getAttribute('duration')
		rule = e.target.getAttribute('rule')
		const ans = await getResult({ ruleId })
		// insert header 
		const tRule = template.content.querySelector('#rule')
		const tDate = template.content.querySelector('#date')
		tRule.textContent = rule
		tDate.textContent = duration
		// inset template to page
		parent.removeChild(child);
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
		if (ans.length === 0) {
			// no data
			const data = document.querySelector("#result-rows");
			data.insertAdjacentHTML('beforeend', `
				<div class="text-center">查無資料</div>
			`
			)
		}

		// insert data to template
		ans.forEach(x => {
			insertResultDataRow(x)
		})
		searching = false
	}

	function insertResultDataRow(datas) {
		const template = document.querySelector("template#result-data-row");

		const parent = document.querySelector("#result-rows");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
		datas.forEach(({ key, label, value }) => {
			if (label) {
				insertResultDataItem({ label, value });
			}
		})
	}
	function insertResultDataItem({ label, value }) {
		// insert to last result-data-row
		const t = document.querySelector("template#result-data-item")
		const k = t.content.querySelector(".key")
		const v = t.content.querySelector(".value")
		k.textContent = label
		v.textContent = ''
		if (Array.isArray(value)) {
			value.forEach(x => {
				if (typeof x === "object") {
					let s = ''
					Object.keys(x).forEach(item => {
						s += (x[item] + ' ')
					})
					v.insertAdjacentHTML('beforeend', `
						<div>${s}</div>
					`)
				} else {
					v.insertAdjacentHTML('beforeend', `
						<span>${x}</span>
					`)
				}
			})

		} else {
			if (label.includes('時間') || label.includes('是否有關聯規則')) {
				return
			} else {
				v.textContent = value.toString()
			}
		}

		const parent = document.querySelector(".result-data-row:last-child");
		const clone = document.importNode(t.content, true);
		parent.appendChild(clone);
	}
	function onSort() {
		//type = 'desc','asc',null
		let locData = [...antiFraudData];
		if (locData.length < 1) {
			return
		}
		// display:none faIcons
		faIcons.forEach((x) => {
			x.classList.remove("d-none");
			x.classList.add("d-none");
		});
		if (orderBy === null) {
			//null
			orderBy = "desc";
			faIcons[0].classList.toggle("d-none");
		} else if (orderBy === "desc") {
			orderBy = "asc";
			faIcons[1].classList.toggle("d-none");
		} else {
			orderBy = null;
			faIcons[2].classList.toggle("d-none");
		}
		//do sort
		if (orderBy === "desc") {
			locData.sort((a, b) => a.efficiency - b.efficiency);
		}
		if (orderBy === "asc") {
			locData.sort((a, b) => b.efficiency - a.efficiency);
		}
		removeDatas()
		locData.forEach((x) => insertData(x))
		//insert
		// insertDefaultPanel()
		// locData.forEach(x => {
		// 	insertData(x)
		// })
	}
	// convertData
	function converProductId({ productId }) {
		const configMap = new Map()
		configs.forEach(({ name, value }) => {
			configMap.set(value.toString(), name)
		})
		return configMap.get(productId.toString()) ? configMap.get(productId.toString()) : 'unknown'
	}
	function convertEfficiency({ efficiency }) {
		return efficiency.toFixed(2) + "%";
	}
	function converDate(date) {
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
				// return response.results.map((x) => x.typeId);
			});
	}

	function getRuleStatistics({ typeIds }) {
		const fetchRule = ({ typeId }) => {
			return fetch(
				`${apiUrl}/rule_statistics?typeId=${typeId}`
			)
				.then((res) => {
					if (res.ok) {
						return res.json();
					} else {
						throw new Error(res.statusText);
					}
				})
				.then(({ response }) => {
					return response.results;
				})
				.catch((err) => {
					return Promise.reject(err);
				});
		};
		const fetchRules = [];
		typeIds.forEach((typeId) => {
			fetchRules.push(
				fetchRule({ typeId })
			);
		});
		return Promise.allSettled(fetchRules)
			.then((x) => {
				const ans = x.filter((res) => {
					return res.status == "fulfilled";
				});
				return ans.flatMap((x) => x.value);
			})
			.catch((err) => console.error(err));
	}
	function getRuleTypeId(data) {
			const fetchRule = ({ typeId,ruleId }) => {
				return fetch(
					`${apiUrl}/typeId?typeId=${typeId}&ruleId=${ruleId}`
				)
					.then((res) => {
						if (res.ok) {
							return res.json();
						} else {
							throw new Error(res.statusText);
						}
					})
					.then(({ response }) => {
						return response.results;
					})
					.catch((err) => {
						return Promise.reject(err);
					});
			};
			const fetchRules = [];
			data.forEach(({typeId,ruleId}) => {
				fetchRules.push(
					fetchRule({ typeId , ruleId})
				);
			});
			return Promise.allSettled(fetchRules)
				.then((x) => {
					const ans = x.filter((res) => {
						return res.status == "fulfilled";
					});
					return ans.flatMap((x) => x.value);
				})
				.catch((err) => console.error(err));
		}

	function getResult({ ruleId }) {
		const fetchResult = () => {
			return fetch(`${apiUrl}/rule_results?ruleId=${ruleId}&startTime=${prevStartTime}&endTime=${prevEndTime}`)
				.then(x => x.json())
				.then(({ response }) => {
					return response.results
				})
		}
		return fetchResult()
	}
</script>
