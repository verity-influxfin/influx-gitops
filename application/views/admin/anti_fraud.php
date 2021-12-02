<style>
	#rows {
		display: flex;
		flex-direction: column;
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

	.head-item-title {
		flex: 0 0 60px;
	}

	.input {
		width: 140px;
	}

	.form-select {
		width: 100%;
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
		display: grid;
		gap: 0 10px;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
				<h1 class="page-header">反詐欺管理</h1>
			</div>
		</div>
		<div class="row" id="panel"></div>
	</div>
</div>
<template id="default-panel">
	<div class="panel panel-default">
		<div class="panel-heading p-4">
			<div class="d-flex align-items-center">
				<div class="mr-2 head-item-title">時間：</div>
				<div class="input-group input">
					<input type="text" data-toggle="datepicker" class="form-control input" id="start-time" />
				</div>
				<span class="mx-2">~</span>
				<div class="input-group input">
					<input type="text" data-toggle="datepicker" class="form-control" id="end-time" aria-label="Default"
						aria-describedby="inputGroup-sizing-default" />
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
	<div class="data-item">
		<div class="header-item key"></div>
		<div class="data-item value"></div>
	</div>
</template>
<template id="result-data-row">
	<div class="result-data-row"></div>
</template>
<template id="loader">
	<div class="loader"></div>
</template>

<script>
	let antiFraudData = [1, 3, 2, 4];
	let orderBy = null;
	const faIcons = [];
	const apiUrl = "http://52.68.199.159:9453/";
	let typeIds = [];
	let configs = []
	let prevStartTime = 0
	let prevEndTime = 999999999999
	async function onLoad() {
		insertDefaultPanel()
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
		insertSearchOption()

		const ids = await getRuleAll();
		typeIds = ids
	}
	window.addEventListener("load", onLoad());
	async function doSearch() {
		// remove last result
		removeDatas()
		toggleLoading()
		//do search
		const productId = document.querySelector('#search-option').value
		const startTimeObj = document.querySelector('#start-time').value
		const endTimeObj = document.querySelector('#end-time').value
		let startTime = 0
		let endTime = 999999999999
		if (startTimeObj) {
			startTime = new Date(startTime).valueOf()
			prevStartTime = startTime
		}
		if (endTimeObj) {
			endTime = new Date(startTime).valueOf()
			prevEndTime = endTime
		}
		const ans = await getRuleStatistics({
			typeIds,
			productId,
			filter: {
				startTime,
				endTime
			},
		})
		const resultData = ans.map(x => {
			let duration = 'All'
			if (startTimeObj) {
				duration = duration.replace('All', `${startTimeObj} ~ Now`)
			}
			if (endTimeObj) {
				duration = duration.replace('Now', endTimeObj)
			}
			return {
				id: `${x.typeId}-${x.productId}`,
				rule: `${x.mainDescription},${x.description}`,
				duration,
				ruleId: x.ruleId,
				productId: x.productId,
				efficiency: x.efficiency,
			}
		})
		toggleLoading()
		//inset result
		resultData.forEach((x) => insertData(x))
		antiFraudData = [...resultData]
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
	}
	async function insertResultPanel(e) {
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

		// insert data to template
		ans.forEach(x => {
			insertResultDataRow(x)
		})

	}

	function insertResultDataRow(datas) {
		const template = document.querySelector("template#result-data-row");

		const parent = document.querySelector("#result-rows");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
		// console.log(datas)
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
			if (label.includes('時間')) {
				const d = new Date(value * 1000)
				v.textContent = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()
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

	//apis
	function getProductConfig() {
		return fetch('/api/v2/AntiFraud/product_config')
			.then(x => x.json())
			.then(({ data }) => {
				return data
			})
	}

	function getRuleAll() {
		return fetch(apiUrl + "/brookesia/api/v1.0/rule/all")
			.then((x) => x.json())
			.then(({ response }) => {
				return response.results.map((x) => x.typeId);
			});
	}

	function getRuleStatistics({ typeIds, productId, filter: { startTime, endTime } }) {
		const fetchRule = ({ typeId, productId, filter: { startTime, endTime } }) => {
			return fetch(
				`${apiUrl}/brookesia/api/v1.0/result/ruleStatistics?typeId=${typeId}&productId=${productId}&startTime=${startTime}&endTime=${endTime}`
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
				fetchRule({
					typeId,
					productId,
					filter: { startTime, endTime },
				})
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
			return fetch(`${apiUrl}/brookesia/api/v1.0/result/ruleResults?ruleId=${ruleId}&startTime=${prevStartTime}&endTime=${prevEndTime}`)
				.then(x => x.json())
				.then(({ response }) => {
					return response.results
				})
		}
		return fetchResult()
	}
</script>
