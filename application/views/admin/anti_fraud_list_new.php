<style>
	.text-left {
		text-align: left;
	}

</style>
<div id="page-wrapper">
	<div>
		<div>
			<h1 class="page-header">反詐欺統計資訊</h1>
		</div>
	</div>
	<div id="panel">
		<div class="panel panel-default mt-4">
			<div class="panel-heading">
				<h4>規則總覽</h4>
			</div>
			<div class="panel-body">
				<div class="d-flex mb-2">
					<input type="text" class="form-control" v-model="search" placeholder="快速搜尋">
					<button type="button" class="btn btn-default ml-2" @click="doSearch">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>
				<div class="panel-group" role="tablist" aria-multiselectable="true">
				<div class="panel" v-for="(item,index) in rules" :class="panelClass(item)">
						<button class="panel-heading btn w-100 text-left" role="tab" id="headingOne"
							data-toggle="collapse" data-parent="#accordion" :href="'#collapse-'+index">
							<h5 class="panel-title">
								{{ item.typeId }} - {{ item.description }}
							</h5>
						</button>
						<div :id="'collapse-'+index" class="panel-collapse in" role="tabpanel"
							aria-labelledby="headingOne">
							<div class="panel-body">
								<table class="table">
									<thead>
										<tr>
											<th>子規則詳情</th>
											<th>執行狀態</th>
											<th>啟用中</th>
											<th>借款總戶數</th>
											<th>命中人數</th>
											<th title='命中人數/借款總戶數'>命中率</th>
											<th>命中且曾逾期人數</th>
											<th title='命中且曾逾期人數/命中人數'>命中該規則之違約率</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="rule in item.rules">
											<td width="550px">{{rule.description}}</td>
											<td>{{rule.block}}</td>
											<td>{{rule.enabled === true ? '是' : '否'}}</td>
											<td v-for="value in rule.info">
												{{value}}
											</td>
										</tr>
									<tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	const v = new Vue({
		el: '#page-wrapper',
		data() {
			return {
				rules: [],
				originalRules: [],
				search: '',
			}
		},
		computed: {
			apiUrl() {
				return '/api/v2/anti_fraud'
			}
		},
		async mounted() {
			this.originalRules = await this.getAll();
			this.rules = this.originalRules
		},
		methods: {
			async getAll() {
				return await axios.get(this.apiUrl + '/rule_info').then(({ data }) => {
					if (data.status === 200) {
						return data.response.results
					}
				})
			},
			doSearch() {
				this.rules = this.originalRules.filter(x => {
					let text = x.description + x.typeId
					for (const rule of x.rules) {
						text += (rule.block + rule.risk + rule.enabled + rule.description)
					}
					return text.includes(this.search)
				}).map(x => {
					// filter 子項目
					const filterRules = x.rules.filter(rule => {
						const rt = rule.block + rule.risk + rule.enabled + rule.description + x.typeId
						return rt.includes(this.search)
					})
					return {...x, rules: filterRules}
				})
			},
			panelClass(item){
				if(item.isJuridicalRule){
					return 'panel-warning'
				}
				else if(item.typeId === '999999'){
					return 'panel-info'
				}
				return 'panel-default'
			}
		},
		watch: {
			rules() {
				this.$nextTick(function () {
					$('[data-toggle="popover"]').popover('hide')
				})
			}
		},
	})
</script>
