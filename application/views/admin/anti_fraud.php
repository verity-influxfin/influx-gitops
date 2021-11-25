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
		grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
	}

	.result-date {
		flex: 0 0 25%;
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
					<input
						type="text"
						data-toggle="datepicker"
						class="form-control input"
					/>
				</div>
				<span class="mx-2">~</span>
				<div class="input-group input">
					<input
						type="text"
						data-toggle="datepicker"
						class="form-control"
						aria-label="Default"
						aria-describedby="inputGroup-sizing-default"
					/>
				</div>
			</div>
			<div class="d-flex align-items-center mt-4">
				<div class="mr-2 head-item-title">產品別：</div>
				<div class="input-group input">
					<select class="form-select">
						<option value="學生貸">學生貸</option>
						<option value="上班族貸">上班族貸</option>
						<option value="工程師貸">工程師貸</option>
						<option value="工程師貸">微企貸</option>
					</select>
				</div>
				<button
					class="btn ml-5 search-btn"
					id="search-btn"
					onclick="doSearch()"
				>
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
			<button class="btn" onclick="insertResultPanel()">命中結果</button>
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
				<div class="data-item" id="rule">
					【設備ID】同一個設備號，有3人以上註冊帳戶，且非內部認證設備
				</div>
			</div>
			<div class="data-item result-date">
				<div class="header-item">日期</div>
				<div class="data-item" id="date">20210101~20210102</div>
			</div>
		</div>
		<button class="btn" onclick="insertResultDataRow({userId:'11111'})">
			test
		</button>
		<div id="result-rows">
			<div class="result-data-row">
				<div class="data-item">
					<div class="header-item">會員ID</div>
					<div class="data-item" id="user-id">12334</div>
				</div>
				<div class="data-item">
					<div class="header-item">日期</div>
					<div class="data-item">20210101~20210102</div>
				</div>
			</div>
		</div>
	</div>
</template>
<template id="result-data-item">
	<div class="data-item">
		<div class="header-item key"></div>
		<div class="data-item value"></div>
	</div>
</template>
<template id="result-data-row">
	<div class="result-data-row">
		<div class="data-item">
			<div class="header-item">會員ID</div>
			<div class="data-item user-id">12334</div>
		</div>
	</div>
</template>

<script>
	let antiFraudData = [1, 3, 2, 4];
	let orderBy = null;
	const faIcons = [];
	const apiUrl = "http://52.68.199.159:9453/";
	async function onLoad() {
		//on load
		insertDefaultPanel();
		// insertData([
		// 	"20210101~20210102",
		// 	"學生貸",
		// 	"【設備ID】同一個設備號，有3人以上註冊帳戶，且非內部認證設備",
		// 	"12%",
		// ]);
		// init faIcons
		faIcons.push(
			document.querySelector("#asc"),
			document.querySelector("#desc"),
			document.querySelector("#default-order")
		);

		//query
		const typeIds = await getRuleAll();
		const ans = await getRuleStatistics({
			typeIds: typeIds.slice(1, 3),
			filter: {},
		});
		console.log(ans);
		const resultData = ans.flatMap((x) => {
			return x.productIdInfo.map((p) => {
				return {
					id: `${x.ruleId}-${p.productId}`,
					rule: `${x.mainDescription},${x.description}`,
					duration: "All",
					productId: p.productId,
					efficiency: p.efficiency,
				};
			});
		});
		console.log(resultData);
		resultData.forEach((x) => insertData(x));
		antiFraudData = [...resultData];
	}
	window.addEventListener("load", onLoad());
	function doSearch() {
		//do search
		console.log("do search");
	}
	function insertData({ id, rule, duration, productId, efficiency }) {
		const template = document.querySelector("template#data-row");
		const dataArray = template.content.querySelectorAll(".data-item");
		//insert
		dataArray[0].textContent = duration;
		dataArray[1].textContent = productId;
		dataArray[2].textContent = rule;
		dataArray[3].textContent = convertEfficiency({ efficiency });
		// dataArray[4].textContent = x;
		const rows = document.querySelector("#rows");
		const clone = document.importNode(template.content, true);
		rows.appendChild(clone);
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
	function insertResultPanel() {
		const parent = document.querySelector("#panel");
		const child = parent.querySelector(".panel.panel-default");
		parent.removeChild(child);
		const template = document.querySelector("template#result-panel");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
	}

	function insertResultDataRow({ userId }) {
		const template = document.querySelector("template#result-data-row");
		const k = template.content.querySelector(".user-id");
		k.textContent = userId;

		const parent = document.querySelector("#result-rows");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
		insertResultDataItem({ key: "test", value: "test2" });
		insertResultDataItem({ key: "test", value: "test2" });
	}
	function insertResultDataItem({ key, value }) {
		// insert to last result-data-row
		const t = document.querySelector("template#result-data-item");
		const k = t.content.querySelector(".key");
		const v = t.content.querySelector(".value");
		k.textContent = key;
		v.textContent = value;

		const parent = document.querySelector(".result-data-row:last-child");
		const clone = document.importNode(t.content, true);
		console.log(parent);
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
		//insert
		// insertDefaultPanel()
		// locData.forEach(x => {
		// 	insertData(x)
		// })
		console.log(locData);
	}
	// convertData
	function converProductId({ productId }) {
		switch (key) {
			case 1:
				break;
			case 2:
				break;
			case 3:
				break;
			case 4:
				break;

			default:
				break;
		}
	}
	function convertEfficiency({ efficiency }) {
		return efficiency.toFixed(2) + "%";
	}

	//apis
	function getRuleAll() {
		return fetch(apiUrl + "/brookesia/api/v1.0/rule/all")
			.then((x) => x.json())
			.then(({ response }) => {
				return response.results.map((x) => x.typeId);
			});
	}
	function getRuleStatistics({ typeIds, filter: { startTime, endTime } }) {
		const fetchRule = ({ typeId, filter: { startTime, endTime } }) => {
			return fetch(
				`${apiUrl}/brookesia/api/v1.0/result/ruleStatistics?typeId=${typeId}&startTime=${startTime}&endTime=${endTime}`
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
		const locStartTime = startTime ?? 0;
		const locEndTime = endTime ?? 999999999999;
		const fetchRules = [];
		typeIds.forEach((typeId) => {
			fetchRules.push(
				fetchRule({
					typeId,
					filter: { startTime: locStartTime, endTime: locEndTime },
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
</script>
