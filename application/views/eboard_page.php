<!doctype html>
<html lang="zh-TW">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<title>電子看板</title>
	<style>
		.table-title {
			color: #fff;
			font-size: 20px;
			text-align: center;
		}

		.table-border {
			border: 2px solid #fff;
			border-radius: 12px;
		}

		.circle-table {
			display: grid;
			margin-top: 35px;
			width: 633px;
			grid-template-columns: 300px 300px;
			grid-template-rows: 300px 50px;
		}

		.qr-table {
			padding: 15px 0;
			display: grid;
			gap: 8px;
			grid-template-columns: repeat(7, 1fr);
			grid-template-rows: repeat(4, 1fr);
		}

		.qr-box {
			padding: 5px;
			color: azure;
			font-size: 16px;
			line-height: 1.2;
		}

		.qr-box-title {
			margin: 4px 0;
		}

		.rank-table {
			height: calc(100% - 67px);
			margin: 15px 0;
		}

		.rank-table-title {
			color: #fff;
			text-align: center;
			margin: 15px 0 10px;
		}

		tbody>tr:nth-last-child(1) {
			border-bottom-style: hidden;
		}
	</style>
</head>

<body id="app" style="background: linear-gradient(357.26deg,#090052 -8.75%, #1177A1 102.56%); height: 100vh;">
	<div class="row m-2 justify-content-between">
		<div class="text-center table-title" style="width: 620px">官網</div>
		<div class="text-center table-title" style="width: 620px">ＡＰＰ</div>
		<div class="text-center table-title" style="width: 620px">產品申貸數</div>
	</div>
	<div class="row m-2 justify-content-between">
		<div class="table-border" id="main" style="width: 620px;height:450px"></div>
		<div class="table-border" id="tb-2" style="width: 620px;height:450px;"></div>
		<div class="table-border" id="tb-3" style="width: 620px;height:450px;"></div>
	</div>
	<div class="row m-2 table-border" style="height: 450px;">
		<div class="circle-table">
			<div id="c-1" style="width: 300px; height: 300px;"></div>
			<div id="c-2" style="width: 300px; height: 300px;"></div>
			<div class="table-title">學生貸</div>
			<div class="table-title">上班族貸</div>
		</div>
		<div class="col-5 qr-table">
			<div class="qr-box table-border" v-for="item in state.qrcode">
				<div class="qr-box-title">{{ item.name }}</div>
				<div class="qr-box-item">學生： {{item.student_count}}</div>
				<div class="qr-box-item">上班族： {{item.salary_man_count}}</div>
			</div>
		</div>
		<div class="col pe-0">
			<div class="rank-table-title">外部排名</div>
			<div class="table-border rank-table">
				<table class="table text-white h-100">
					<thead>
						<tr>
							<th>#</th>
							<th>名字</th>
							<th>學生</th>
							<th>上班族</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item,index) in rankInflux">
							<td>{{ index+1 }}</td>
							<td>{{ item.name }}</td>
							<td>{{ item.student_count }}</td>
							<td>{{ item.salary_man_count }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col">
			<div class="rank-table-title">員工排名</div>
			<div class="table-border rank-table">
				<table class="table text-white h-100">
					<thead>
						<tr>
							<th>#</th>
							<th>名字</th>
							<th>學生</th>
							<th>上班族</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item,index) in rankInflux">
							<td>{{ index+1 }}</td>
							<td>{{ item.name }}</td>
							<td>{{ item.student_count }}</td>
							<td>{{ item.salary_man_count }}</td>
						</tr>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
	crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.0/dist/echarts.js"></script>
<script>
	const { reactive, onMounted, computed } = Vue;
	Vue.createApp({
		setup() {
			const state = reactive({
				data: [],
				qrcode: []
			})
			const basicOption = {
				color: ['#fff', '#42E5F3', '#F29600', '#1edf90', '#e9e54e'],
				textStyle: {
					color: '#fff',
					fontSize: '14px'
				},
				tooltip: {
					trigger: 'axis',
					axisPointer: {
						type: 'shadow'
					}
				},
				legend: {
					bottom: 0,
					icon: 'path://M30.9,53.2C16.8,53.2,5.3,41.7,5.3,27.6S16.8,2,30.9,2C45,2,56.4,13.5,56.4,27.6S45,53.2,30.9,53.2z',
					textStyle: {
						color: '#fff'
					}
				},
				grid: {
					top: '40px',
					left: '5px',
					right: '5px',
					bottom: '40px',
					containLabel: true
				},
			}
			onMounted(() => {
				axios.get("/page/get_eboard_data").then(function ({ data }) {
					state.data = data.data.history.reverse()
					state.qrcode = data.data.qrcode
				}).then(() => {
					drawTable1()
					drawTable2()
					drawTable3()
					drawRound1()
					drawRound2()
				})

			})
			const drawTable1 = () => {
				const { data } = state
				myChart = echarts.init(document.getElementById('main'))
				option = {
					...basicOption,
					xAxis: {
						type: 'category',
						axisLabel: { fontSize: '14px' },
						data: data.map(x => x.date.replace('-', '\n'))
					},
					yAxis: [
						{
							name: '流量',
							type: 'value',
							splitLine: {
								show: false
							},
							axisLine: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								}
							},
							axisLabel: { fontSize: '14px' },
							axisTick: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								},
							}
						},
						{
							name: '會員總數',
							alignTicks: true,
							type: 'value',
							splitLine: {
								show: false
							},
							axisLine: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								}
							},
							axisLabel: { fontSize: '14px' },
							axisTick: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								},
							}
						}
					],
					series: [
						{
							name: '流量',
							type: 'line',
							data: data.map(x => x.official_site_trends)
						},
						{
							name: '新增會員',
							type: 'line',
							data: data.map(x => x.new_member)
						},
						{
							name: '會員總數',
							type: 'line',
							yAxisIndex: 1,
							data: data.map(x => x.total_member)
						}
					]
				}
				myChart.setOption(option)
			}
			const drawTable2 = () => {
				const { data } = state
				myChart = echarts.init(document.getElementById('tb-2'))
				option = {
					...basicOption,
					xAxis: {
						type: 'category',
						axisLabel: { fontSize: '14px' },
						data: data.map(x => x.date.replace('-', '\n'))
					},
					yAxis: [
						{
							name: '下載數',
							type: 'value',
							splitLine: {
								show: false
							},
							axisLabel: { fontSize: '14px' },
							axisLine: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								}
							},
							axisTick: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								},
							},
						}
					],
					series: [
						{
							name: 'APP Android',
							type: 'line',
							data: data.map(x => x.android_downloads)
						},
						{
							name: 'APP IOS',
							type: 'line',
							data: data.map(x => x.ios_downloads)
						}
					]
				}
				myChart.setOption(option)
			}
			const drawTable3 = () => {
				const { data } = state
				myChart = echarts.init(document.getElementById('tb-3'))
				option = {
					...basicOption,
					xAxis: {
						type: 'category',
						axisLabel: { fontSize: '14px' },
						axisLine: {
							show: true,
							lineStyle: {
								color: '#86A0BE',
							}
						},
						axisTick: {
							show: false,
						},
						data: data.map(x => x.date.replace('-', '\n'))
					},
					yAxis: [
						{
							name: '申貸數',
							type: 'value',
							axisLabel: { fontSize: '14px' },
							axisLine: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								}
							},
							splitLine: {
								show: false
							},
							axisTick: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								},
							},
							minInterval: 1
						},
						{
							name: '成交數',
							type: 'value',
							axisLabel: { fontSize: '14px' },
							axisLine: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								}
							},
							splitLine: {
								show: false
							},
							axisTick: {
								show: true,
								lineStyle: {
									color: '#86A0BE',
								},
							},
							minInterval: 1
						}
					],
					series: [
						{
							name: '3S',
							type: 'bar',
							data: data.map(x => x.product_bids['SMART_STUDENT'])
						},
						{
							name: '學生貸',
							type: 'bar',
							data: data.map(x => x.product_bids['STUDENT'])
						},
						{
							name: '上班族貸',
							type: 'bar',
							data: data.map(x => x.product_bids['SALARY_MAN'])
						},
						{
							name: '微企貸',
							type: 'bar',
							data: data.map(x => x.product_bids['SK_MILLION'])
						},
						{
							name: '成交數',
							yAxisIndex: 1,
							type: 'line',
							data: data.map(x => x.deals)
						},

					]
				}
				myChart.setOption(option)
			}
			const drawRound1 = () => {
				myChart = echarts.init(document.getElementById('c-1'))
				option = {
					...basicOption,
					legend: {
						show: false,
					},
					series: [
						{
							type: 'pie',
							symbolSize: 1,
							label: {
								position: 'inner',
								fontSize: 14
							},
							data: state.qrcode.map(x => { return { name: x.name, value: x.student_count } })
						}
					]
				}
				myChart.setOption(option)
			}
			const drawRound2 = () => {
				myChart = echarts.init(document.getElementById('c-2'))
				option = {
					...basicOption,
					legend: {
						show: false,
					},
					series: [
						{
							type: 'pie',
							symbolSize: 1,
							label: {
								position: 'inner',
								fontSize: 14
							},
							data: state.qrcode.map(x => { return { name: x.name, value: x.student_count } })
						}
					]
				}
				myChart.setOption(option)
			}
			const rankInflux = computed(() => {
				return [...state.qrcode].sort((a, b) => {
					return b.full_member_count - a.full_member_count
				}).slice(0, 5)
			})
			return { state, rankInflux };
		}
	}).mount('#app')
</script>

</html>
