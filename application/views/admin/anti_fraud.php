<style>
	#rows {
		display: flex;
		flex-direction: column;
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

	.result-item {
		flex: 0 0 22%;
		margin-right: 22px;
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
					<input type="text" data-toggle="datepicker" class="form-control input" />
				</div>
				<span class="mx-2">~</span>
				<div class="input-group input">
					<input type="text" data-toggle="datepicker" class="form-control" aria-label="Default"
						aria-describedby="inputGroup-sizing-default" />
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
				<button class="btn ml-5 search-btn" id="search-btn" onclick="doSearch()">搜尋</button>
			</div>
		</div>
		<div class="panel-body">
			<div>
				<div class="header">
					<div class="header-item">時間</div>
					<div class="header-item">產品別</div>
					<div class="header-item item-full">反詐欺規則</div>
					<div class="header-item">有效性</div>
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
				<div class="data-item" id="rule">【設備ID】同一個設備號，有3人以上註冊帳戶，且非內部認證設備</div>
			</div>
			<div class="data-item result-date">
				<div class="header-item">日期</div>
				<div class="data-item" id="date">20210101~20210102</div>
			</div>
		</div>
		<button class="btn" onclick="insertResultDataItem({key:'test',value:'test2'})">test</button>
		<div class="d-flex mt-4 flex-wrap" id="result-data-row">
			<div class="data-item result-item">
				<div class="header-item">會員ID</div>
				<div class="data-item" id="user-id">12334</div>
			</div>
			<div class="data-item result-item">
				<div class="header-item">日期</div>
				<div class="data-item">20210101~20210102</div>
			</div>
		</div>
	</div>
</template>
<template id="result-data-item">
	<div class="data-item result-item">
		<div class="header-item" id="key"></div>
		<div class="data-item" id="value"></div>
	</div>
</template>

<script>
	function onLoad() {
		//on load
		insertDefaultPanel()
		insertData([
			"20210101~20210102",
			"學生貸",
			"【設備ID】同一個設備號，有3人以上註冊帳戶，且非內部認證設備",
			"12%",
		]);
	}
	window.addEventListener("load", onLoad())
	function doSearch() {
		//do search
		console.log('do search')
	}
	function insertData(data) {
		const template = document.querySelector("template#data-row");
		const dataArray = template.content.querySelectorAll(".data-item");
		data.forEach((x, i) => {
			dataArray[i].textContent = x;
		});
		const rows = document.querySelector("#rows");
		const clone = document.importNode(template.content, true);
		rows.appendChild(clone);
	}
	function insertDefaultPanel() {
		const parent = document.querySelector("#panel");
		const child = parent.querySelector('div')
		if (child) {
			parent.removeChild(child)
		}
		const template = document.querySelector("template#default-panel");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
	}
	function insertResultPanel() {
		const parent = document.querySelector("#panel");
		const child = parent.querySelector('.panel.panel-default')
		parent.removeChild(child)
		const template = document.querySelector("template#result-panel");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
	}
	function insertResultDataItem({ key, value }) {
		const template = document.querySelector('template#result-data-item')
		const k = template.content.querySelector('#key')
		const v = template.content.querySelector('#value')
		k.textContent = key
		v.textContent = value

		const parent = document.querySelector("#result-data-row");
		const clone = document.importNode(template.content, true);
		parent.appendChild(clone);
	}
</script>
