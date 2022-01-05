<div id="page-wrapper">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="page-header d-flex justify-between">
				<div>法催扣款</div>
				<button class="btn btn-danger" data-toggle="modal" data-target="#newModal">新增代支紀錄</button>
			</h1>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="d-flex">
				<div class="p-2">投資人ID</div>
				<div class="p-2">
					<input type="text" class="form-control" v-model="search_option.user_id">
				</div>
				<div class="p-2">
					代支日期
				</div>
				<div class="p-2">
					<input type="text" data-toggle="datepicker" class="form-control" id="created_at_s">
				</div>
				<div class="p-2">
					-
				</div>
				<div class="p-2">
					<input type="text" data-toggle="datepicker" class="form-control" id="created_at_e">
				</div>
				<div class="search-btn">
					<button class="btn btn-primary" @click="get_deduct_list">搜尋</button>
				</div>
			</div>
		</div>
		<div class="p-3">
			<table id="deduct-table">
				<thead>
					<tr>
						<th>代支日期</th>
						<th>投資人ID</th>
						<th>金額</th>
						<th>事由</th>
						<th>經辦人</th>
						<th>狀態</th>
						<th></th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="add_deduct_info">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">新增代支紀錄</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<div class="col-20 input-require">投資人ID</div>
						<div class="col">
							<input type="text" required class="w-100 form-control" v-model.number.lazy="add_form_id">
						</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col-20"></div>
						<div class="orange-hint" v-if="deduct_hint.result === 'SUCCESS'">
							{{ deduct_hint.user_name }} 虛擬帳戶餘額: ${{ deduct_hint.account_amount_formatted }}
						</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col-20 input-require">金額</div>
						<div class="col">
							<input type="text" required class="w-100 form-control"
								v-model.number="add_deduct_info_form.amount">
						</div>
					</div>
					<div class="d-flex align-start mb-2">
						<div class="col-20 input-require">事由</div>
						<div class="col">
							<textarea type="text" required class="w-100 form-control" rows="4"
								v-model="add_deduct_info_form.reason"></textarea>
						</div>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary"
						:disabled="add_deduct_info_form.amount - deduct_hint.account_amount > 0">送出</button>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="deductModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="update_deduct_info(2)">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">扣繳投資人虛擬帳戶餘額</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<h4 class="col">投資人ID: {{ deduct_modal_data.user_id }}</h4>
					</div>
					<div class="d-flex mb-2">
						<div class="orange-hint">
							{{ deduct_modal_data.user_name }} 虛擬帳戶餘額: ${{ deduct_modal_data.account_amount_formatted }}
						</div>
					</div>
					<div class="d-flex mb-2">
						<h4 class="col">{{ deduct_modal_data.deduct_reason }}: ${{ deduct_modal_data.deduct_amount_formatted }}
						</h4>
					</div>
					<div class="d-flex align-start mb-2">
						<h4 class="col">確定扣繳嗎?</h4>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary"
						:disabled="deduct_modal_data.account_amount < deduct_modal_data.deduct_amount">送出</button>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="update_deduct_info(3)">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">註銷代支紀錄</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-2">
						<div class="col input-require">註銷原因</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col">
							<textarea rows="7" required class="w-100 form-control"
								v-model="update_deduct_info_form.cancel_reason"></textarea>
						</div>
					</div>
				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-danger">註銷</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script> -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
	$(document).ready(function () {
		const t = $('#deduct-table').DataTable({
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
		});
		v.get_deduct_list()
	});
	const v = new Vue({
		el: '#page-wrapper',
		data() {
			return {
				deduct_list: [],
				search_option: {
					user_id: null,
				},
				deduct_item: {
					'id': 11,
					'created_at': '2021-12-30 10:00:00',
					'user_id': 24398,
					'amount': 12345,
					'reason': '代支規費1',
					'admin': 'Joanne',
					'status': {
						'code': 1,
						'name': '應付',
						'updated_at': '2021-12-30 10:00:00'
					}
				},
				add_form_id: null,
				add_deduct_info_form: {
					'user_id': null,
					'amount': null,
					'reason': ''
				},
				deduct_hint: {
					result: '',
					user_name: '',
					account_amount: 0
				},
				deduct_modal_data: {
					'id': 31,
					'user_id': 24398,
					'user_name': '王O明',
					'account_amount': 29302,
					'deduct_reason': '代支規費',
					'deduct_amount': 750
				},
				update_deduct_info_form: {
					id: null,
					action: null,
					cancel_reason: '',
				}
			}
		},
		watch: {
			add_form_id(v) {
				this.add_deduct_info_form.user_id = v
				this.get_deduct_user_info(v)
			}
		},
		methods: {
			set_table_row(item) {
				let status = ''
				switch (item.status.code) {
					case 1:
						// 應付 
						status = `<div class="d-flex">
									<button class="btn btn-primary mr-2" onclick="v.get_deduct_info(${item.id})">扣繳</button>
									<button class="btn btn-outline-danger" onclick="v.open_cancel_modal(${item.id})" >註銷</button >
								  </div >`

						break;
					case 2:
						status = `<div>扣繳日期</div>
									  <div>${item.status.updated_at}</div>`
						break;
					case 3:
						status = `<div>註銷日期</div>
								  <div>${item.status.updated_at}</div>
								  <div>原因: ${item.status.cancel_reason}</div>`
						break;
					default:
						status = ''
						break;
				}
				$('#deduct-table').DataTable().row.add([item.created_at, item.user_id, item.amount, item.reason, item.admin, item.status.name, status])
			},
			get_deduct_list() {
				$('#deduct-table').DataTable().clear().draw()
				const user_id = this.search_option.user_id
				const created_at_s = document.querySelector('#created_at_s').value ? document.querySelector('#created_at_s').value : null
				const created_at_e = document.querySelector('#created_at_e').value ? document.querySelector('#created_at_e').value : null
				axios.get('get_deduct_list', {
					params: {
						user_id,
						created_at_s,
						created_at_e
					}
				}).then(({ data }) => {
					this.deduct_list = data
					this.deduct_list.forEach(item => this.set_table_row(item))
					$('#deduct-table').DataTable().draw()
				})
			},
			add_deduct_info() {
				const { add_deduct_info_form } = this
				axios({
					method: 'post',
					url: 'add_deduct_info',
					data: {
						...add_deduct_info_form
					}
				}).then(({ data }) => {
					if (data.result === 'ERROR') {
						alert(data.msg)
						return
					}
					this.get_deduct_list()
					$('#newModal').modal('hide')
				})
				$('#newModal').modal('hide')
			},
			get_deduct_user_info() {
				const { user_id } = this.add_deduct_info_form
				this.deduct_hint = {
					result: '',
					user_name: '',
					account_amount: 0
				}
				axios.get('get_deduct_user_info', {
					params: {
						user_id
					}
				}).then(({ data }) => {
					this.deduct_hint = {
						result: data.result,
						...data.data
					}
				})
			},
			get_deduct_info(id) {
				axios.get('get_deduct_info', {
					params: {
						id
					}
				}).then(({ data }) => {
					if (data.result === 'SUCCESS') {
						$('#deductModal').modal('show')
						this.deduct_modal_data = {
							result: data.result,
							...data.data
						}
					}
				}).finally(() => {
					$('#deductModal').modal('show')
					this.update_deduct_info_form.id = id
				})

			},
			open_cancel_modal(id) {
				this.update_deduct_info_form.id = id
				$('#cancelModal').modal('show')

			},
			update_deduct_info(action) {
				this.update_deduct_info_form.action = action
				const { update_deduct_info_form } = this
				axios({
					method: 'post',
					url: 'update_deduct_info',
					data: {
						...update_deduct_info_form
					}
				}).then(({ data }) => {
					if (data.result === 'ERROR') {
						alert(data.msg)
						return
					}
					this.get_deduct_list()
					$('#cancelModal').modal('hide')
				})
				$('#cancelModal').modal('hide')
				$('#deductModal').modal('hide')
			},
		},
	})
</script>

<style>
	.d-flex {
		display: flex;
		align-items: center;
	}

	.align-start {
		align-items: start;
	}

	.btn-outline-danger {
		color: #dc3545;
		background-color: #fff;
		border-color: #dc3545;
	}

	.btn-outline-danger:hover {
		color: #fff;
		background-color: #dc3545;
	}

	.justify-between {
		justify-content: space-between;
	}

	.col-20 {
		flex: 0 0 20%;
	}

	.col {
		flex: 1 0 0%;
	}

	.w-100 {
		width: 100%;
	}

	.input-require::after {
		content: '*';
		color: #dc3545;
		margin-left: 4px;
		display: inline-block;
	}

	.orange-hint {
		color: orange;
		font-size: 12px;
	}

	.search-btn {
		display: flex;
		justify-content: flex-end;
		flex: 1 0 auto;
	}
</style>
