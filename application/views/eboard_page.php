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
		#app {
			height: 100vh;
			background-image: url('/assets/eboard/bg.jpg');
			background-position: center;
			background-size: cover;
			background-repeat: no-repeat;
		}

		.table-title {
			position: absolute;
			color: #fff;
			padding: 8px 16px;
			font-size: 24px;
			letter-spacing: 8px;
		}

		.target {
			position: absolute;
		}

		.table-border {
			border: 2px solid rgba(255, 255, 255, 0.2);
			background: linear-gradient(180deg, rgba(1, 81, 124, 0.5) 0%, rgba(5, 50, 92, 0.5) 100%);
			border-radius: 12px;
		}

		.weather {
			color: #fff;
			padding: 8px 16px;
			font-size: 20px;
			display: grid;
			text-align: center;
			grid-template-columns: 1fr 40px 1fr 40px 1fr 40px 1fr;
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

		.history {
			padding: 9px 14px;
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			grid-template-rows: 30px 1fr;
			grid-template-areas:
				"history-title history-title history-title"
				". . .";
		}

		.history-title {
			grid-area: history-title;
			color: #fff;
			font-size: 24px;
			line-height: 1.2;
		}

		.num-group {
			padding: 12px;
			text-align: center;
		}

		.num-group .num {
			color: rgb(248, 182, 45);
			font-size: 44px;
			line-height: 1.5;
		}

		.num-group .num-title {
			color: #fff;
			font-size: 20px;
		}

		.rank-content {
			margin: 60px 16px 16px 16px;
			height: 240px;
			overflow: hidden;
			display: flex;
			justify-content: center;
			align-items: center;
			background-image: url('/assets/eboard/firework1.gif');
			background-repeat: no-repeat;
			background-position: center center;
		}

		.rank-item {
			color: #fff;
			font-size: 20px;
			margin: 8px;
			text-align: center;
		}

		@keyframes marquee {
			0% {
				top: 250%;
				transform: translateY(0%);
			}

			100% {
				top: 0;
				transform: translateY(-100%);
			}
		}


		tbody>tr:nth-last-child(1) {
			border-bottom-style: hidden;
		}
	</style>
</head>

<body id="app">
	<div class="row m-3">
		<div class="col-4">
			<div class="d-flex table-border position-relative">
				<div class="table-title">官網 </div>
				<div id="main" class="mx-auto" style="height: 300px; width: 560px;"></div>
			</div>
			<div class="d-flex table-border position-relative my-3">
				<div class="table-title">ＡＰＰ</div>
				<div id="tb-2" class="mx-auto" style="height: 300px; width: 560px;"></div>
			</div>
			<div class="d-flex table-border position-relative">
				<div class="table-title">產品</div>
				<div id="tb-3" class="mx-auto" style="height: 300px; width: 560px;"></div>
			</div>
		</div>
		<div class="col-4">
			<div class="table-border position-relative">
				<div class="table-title">成交案件數分佈圖</div>
				<div id="map-1" class="mx-auto" style="height: 620px;"></div>
			</div>
			<div class="table-border position-relative mt-3">
				<div class="table-title">即時成交動態</div>
				<div id="real-1" style="height: 300px; width: 560px;"></div>
			</div>
		</div>
		<div class="col-4">
			<div class="weather" style="height: 50px;">
				<div>{{ state.time.showDate }}</div>
				<div>|</div>
				<div>{{ state.time.time }}</div>
				<div>|</div>
				<div>星期{{ state.time.day }}</div>
				<div>|</div>
				<div v-if="state.weather">
					<img :src="`https://www.metaweather.com/static/img/weather/${state.weather}.svg`"
						style="height: 30px;margin-bottom: 5px;">
				</div>
			</div>
			<div class="table-border">
				<div class="history" style="height: 180px;">
					<div class="history-title">
						最高成交筆數
					</div>
					<div class="num-group">
						<div class="num">{{ covertNum(state.platform_statistic.daily_highest_count) }}</div>
						<div class="num-title">每日筆數</div>
					</div>
					<div class="num-group">
						<div class="num">{{ covertNum(state.platform_statistic.total_investment_count) }}</div>
						<div class="num-title">累積筆數</div>
					</div>
					<div class="num-group">
						<div class="num">{{ covertNum(state.platform_statistic.monthly_highest_count) }}</div>
						<div class="num-title">每月筆數</div>
					</div>
				</div>
			</div>
			<div class="table-border mt-3 position-relative">
				<div class="table-title">績效榜</div>
				<div class="rank-content table-border">
					<div class="rank-board">
						<div class="rank-item" v-for="(item, i) in state.rank">
							恭喜 {{item.name}} 取得推薦有賞績效第{{i+1}}名
						</div>
					</div>
				</div>
				<div class="table-title">推薦有賞績效</div>
				<div id="qr-1" style="height: 374px;"></div>
			</div>
		</div>
	</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
	integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
	crossorigin="anonymous"></script>
<script src="https://unpkg.com/vue@3.2.31/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.0/dist/echarts.js"></script>
<script>
	const { reactive, ref, onMounted, onUnmounted, computed, watch } = Vue;
	Vue.createApp({
		setup() {
			const state = reactive({
				data: [],
				date: '',
				geoJson: {},
				loan_statistic: [],
				loan_distribution: [],
				qrcode: [],
				rank: [],
				real: [],
				time: {
					showDate: '',
					time: '',
					day: ''
				},
				platform_statistic: {
					daily_highest_count: 0,
					monthly_highest_count: 0,
					total_investment_count: 0
				},
				weather: ''
			})
			const renderQr = ref([])
			const intervals = reactive({
				chart: 0,
				api: 0
			})
			const charts = reactive({
				flow: {},
				app: {},
				prod: {},
				geoMap: {},
				real: {},
				qr: {},
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
					top: '80px',
					left: '5px',
					right: '5px',
					bottom: '40px',
					containLabel: true
				},
			}
			onMounted(() => {
				// init chart

				charts.flow = echarts.init(document.getElementById('main'))
				charts.app = echarts.init(document.getElementById('tb-2'))
				charts.prod = echarts.init(document.getElementById('tb-3'))
				charts.geoMap = echarts.init(document.getElementById('map-1'))
				charts.real = echarts.init(document.getElementById('real-1'))
				charts.qr = echarts.init(document.getElementById('qr-1'))
				getData()
			})

			onUnmounted(() => {
				clearInterval(intervals.api)
				clearInterval(intervals.chart)
			})

			const hours = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00',
				'07:00', '08:00', '09:00', '10:00', '11:00', '12:00',
				'13:00', '14:00', '15:00', '16:00', '17:00', '18:00',
				'19:00', '20:00', '21:00', '22:00', '23:00'
			]

			const covertNum = (num) => {
				return num.toLocaleString()
			}

			const getData = () => {
				showTime()
				state.date =
					axios.get('/assets/eboard/taiwan.json').then(function ({ data }) {
						state.geoJson = data
					})
				axios.get("/page/get_eboard_data").then(function ({ data }) {
					state.data = data.data.history.reverse()
					state.weather = data.data.weather
					state.qrcode = data.data.qrcode.map(item => {
						return [item.salary_man_count, item.student_count, item.name]
					})
					state.rank = [...data.data.qrcode].sort((a, b) => { b.full_member_count - a.full_member_count }).slice(0, 3)
					setStatisticData(data.data.loan_statistic)
					state.loan_distribution = data.data.loan_distribution
					state.platform_statistic = data.data.platform_statistic
				}).then(() => {
					drawTable1()
					drawTable2()
					drawTable3()
					drawReal()
					nextQrData()
					drawGeo()
					intervals.chart = setInterval(function () {
						nextQrData()
					}, 4000)
					setTimeout(getData, 300000)
				})
			}

			const drawTable1 = () => {
				const { data } = state
				option = {
					...basicOption,
					xAxis: {
						type: 'category',
						axisLabel: { fontSize: '14px' },
						data: data.map(x => x.date.replace('/', '\n'))
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
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.official_site_trends)
						},
						{
							name: '新增會員',
							type: 'line',
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.new_member)
						},
						{
							name: '會員總數',
							type: 'line',
							yAxisIndex: 1,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.total_member)
						}
					]
				}
				charts.flow.setOption(option)
			}
			const drawTable2 = () => {
				const { data } = state
				option = {
					...basicOption,
					xAxis: {
						type: 'category',
						axisLabel: { fontSize: '14px' },
						data: data.map(x => x.date.replace('/', '\n'))
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
							minInterval: 1
						}
					],
					series: [
						{
							name: 'APP Android',
							type: 'line',
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.android_downloads)
						},
						{
							name: 'APP IOS',
							type: 'line',
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.ios_downloads)
						}
					]
				}
				charts.app.setOption(option)
			}
			const drawTable3 = () => {
				const { data } = state
				option = {
					...basicOption,
					color: [
						{
							type: 'linear',
							x: 0,
							y: 0,
							x2: 0,
							y2: 1,
							colorStops: [{
								offset: 0, color: '#11B4DB '
							}, {
								offset: 1, color: '#00527C'
							}],
							global: false
						},
						{
							type: 'linear',
							x: 0,
							y: 0,
							x2: 0,
							y2: 1,
							colorStops: [{
								offset: 0, color: '#2C90DF'
							}, {
								offset: 1, color: '#06427A'
							}],
							global: false
						},
						{
							type: 'linear',
							x: 0,
							y: 0,
							x2: 0,
							y2: 1,
							colorStops: [{
								offset: 0, color: '#7AECEA'
							}, {
								offset: 1, color: '#0E4470'
							}],
							global: false
						},
						{
							type: 'linear',
							x: 0,
							y: 0,
							x2: 0,
							y2: 1,
							colorStops: [{
								offset: 0, color: '#B19556'
							}, {
								offset: 1, color: '#4D5E60'
							}],
							global: false
						},
						'#e9e54e'
					],
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
						data: data.map(x => x.date.replace('/', '\n'))
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
							barWidth: 7,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							data: data.map(x => x.product_bids['SMART_STUDENT'])
						},
						{
							name: '學生貸',
							type: 'bar',
							barWidth: 7,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							data: data.map(x => x.product_bids['STUDENT'])
						},
						{
							name: '上班族貸',
							type: 'bar',
							barWidth: 7,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							data: data.map(x => x.product_bids['SALARY_MAN'])
						},
						{
							name: '微企貸',
							type: 'bar',
							barWidth: 7,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							data: data.map(x => x.product_bids['SK_MILLION'])
						},
						{
							name: '成交數',
							yAxisIndex: 1,
							type: 'line',
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							data: data.map(x => x.deals)
						},

					]
				}
				charts.prod.setOption(option)
			}
			const drawGeo = () => {
				const { geoJson } = state
				echarts.registerMap('taiwan', { geoJSON: geoJson });
				const project = (point) => [point[0] / 180 * Math.PI, -Math.log(Math.tan((Math.PI / 2 + point[1] / 180 * Math.PI) / 2))]
				option = {
					visualMap: {
						min: Math.min(...state.loan_distribution.map(x => x.value)),
						max: Math.max(...state.loan_distribution.map(x => x.value)),
						left: 24,
						bottom: 18,
						padding: 4.5,
						calculable: true,
						text: ['Max', 'Min'],
						textStyle: {
							color: '#fff',
							fontSize: 14,
						},
						inRange: {
							color: ['#05D4FF', '#0379F6', '#004DF4']
						}
					},
					series: [
						{
							type: 'map',
							projection: {
								project: (point) => project(point),
								unproject: (point) => [point[0] * 180 / Math.PI, 2 * 180 / Math.PI * Math.atan(Math.exp(point[1])) - 90]
							},
							roam: true,
							map: 'taiwan',
							zoom: 5.2,
							center: project([120.58, 23.58]),
							// center: [120.58, 23.58],
							data: state.loan_distribution
						}
					],
				}
				charts.geoMap.setOption(option)
			}
			const drawReal = () => {
				const days = [...Array(8)].map((_, i) => {
					const d = new Date()
					d.setDate(d.getDate() - i)
					return `${(d.getMonth() + 1).toString().padStart(2, 0)}/${d.getDate().toString().padStart(2, 0)}`
				}).concat('').reverse()
				option = {
					...basicOption,
					color: ['#05D4FF'],
					grid: {
						top: '50px',
						left: '40px',
						right: '40px',
						bottom: '20px',
						containLabel: true
					},
					xAxis: {
						type: 'category',
						data: days,
						boundaryGap: false,
						splitLine: {
							show: true
						},
						axisLine: {
							show: false
						}
					},
					yAxis: {
						data: hours,
					},
					legend: {
						show: false,
					},
					series: [
						{
							type: 'scatter',
							symbolSize: (val) => val[2] * 4,
							data: state.loan_statistic
						}
					]
				}
				charts.real.setOption(option)
			}
			const drawQr = () => {
				option = {
					...basicOption,
					grid: {
						top: '80px',
						left: '6%',
						right: '6%',
						bottom: '30px',
						containLabel: true
					},
					dataset: {
						source: [
							['salary_man_count', 'student_count', 'product'],
							...renderQr.value

						]
					},
					xAxis: {
						type: 'category',
						boundaryGap: [0, 0.01]
					},
					yAxis: {
						type: 'value',
						splitLine: {
							show: true,
							lineStyle: {
								color: '#63656E'
							}
						},
					},
					series: [
						{
							name: '上班族貸',
							type: 'bar',
							barWidth: 10,
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							encode: {
								y: 'salary_man_count',
								x: 'product'
							}
						},
						{
							name: '學生貸',
							type: 'bar',
							barWidth: 10,
							itemStyle: {
								borderRadius: [4, 4, 0, 0],
							},
							label: {
								show: true,
								position: 'top',
								fontSize: '10',
								color: '#fff',
								formatter: (x) => {
									return x.value > 0 ? x.value : ''
								}
							},
							encode: {
								y: 'student_count',
								x: 'product'
							}
						}
					]
				}
				charts.qr.setOption(option)
			}
			const showTime = () => {
				var date = new Date();
				var h = date.getHours(); // 0 - 23
				var m = date.getMinutes(); // 0 - 59
				var s = date.getSeconds(); // 0 - 59
				if (h == 0) {
					h = 12;
				}
				h = (h < 10) ? "0" + h : h
				m = (m < 10) ? "0" + m : m
				s = (s < 10) ? "0" + s : s
				const time = h + ":" + m + ":" + s
				const showDate = date.getFullYear() + '.' + (date.getMonth() + 1).toString().padStart(2, 0) + '.' + (date.getDate()).toString().padStart(2, 0)
				const day = ['', '一', '二', '三', '四', '五', '六', '日'].at(date.getDay())
				state.time = { showDate, time, day }
				setTimeout(showTime, 1000);
			}
			const nextQrData = () => {
				state.qrcode.push(state.qrcode.shift())
				renderQr.value = state.qrcode.slice(0, 7)
				drawQr()
			}
			const setStatisticData = (data) => {
				const days = [...Array(8)].map((_, i) => {
					const d = new Date()
					d.setDate(d.getDate() - i)
					return `${d.getFullYear()}/${(d.getMonth() + 1).toString().padStart(2, 0)}/${d.getDate().toString().padStart(2, 0)}`
				}).concat('').reverse()
				let ans = []
				days.forEach((x, i) => {
					if (i != 0) {
						const dataMap = new Map()
						data.filter(item => item.date === x).forEach(item => dataMap.set(item.time, item.value))
						hours.forEach((hour, index) => {
							if (dataMap.has(hour)) {
								ans.push([i, index, dataMap.get(hour)])
							}
						})
					}
				})
				state.loan_statistic = ans
			}
			return { state, covertNum };
		}
	}).mount('#app')
</script>

</html>
