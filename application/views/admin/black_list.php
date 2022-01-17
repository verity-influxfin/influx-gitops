<div id="page-wrapper">
	<div class="row">
		<div class="col-xs-12">
			<h1 class="page-header">
				<div>借款 - 黑名單列表</div>
			</h1>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<form class="d-flex" @submit.prevent="getAllBlockUsers">
				<div class="p-2">會員ID</div>
				<div class="p-2">
					<input type="text" class="form-control" v-model="allBlockUserParam.userId">
				</div>
				<div class="p-2">
					黑名單規則
				</div>
				<div class="p-2">
					<select id="rule-option" class="form-control" v-model="allBlockUserParam.blockRule">
						<option value=""></option>
						<option value="第三方資料結果" label="第三方資料結果">第三方資料結果</option>
						<option value="反詐欺規則" label="反詐欺規則">反詐欺規則</option>
						<option value="其他(人為加入)" label="其他(人為加入)">其他(人為加入)</option>
						<option value="授信政策" label="授信政策">授信政策</option>
					</select>
				</div>
				<div class="p-2">
					執行狀態
				</div>
				<div class="p-2">
					<select id="status" class="form-control" v-model="allBlockUserParam.blockTimeText">
						<option value=""></option>
						<option value="封鎖一個月" label="封鎖一個月">封鎖一個月</option>
						<option value="封鎖三個月" label="封鎖三個月">封鎖三個月</option>
						<option value="封鎖六個月" label="封鎖六個月">封鎖六個月</option>
						<option value="永久封鎖" label="永久封鎖">永久封鎖</option>
                        <option value="轉二審" label="轉二審">轉二審</option>
					</select>
				</div>
				<div class="search-btn">
					<button class="btn btn-primary" type="submit">搜尋</button>
				</div>
                <div class="add-btn">
                    <button class="btn btn-info mr-2" type="button" data-toggle="modal" data-target="#newModal">
                        新增
                    </button>
                </div>

			</form>
		</div>
		<div class="p-3">
			<table id="main-table">
				<thead>
					<tr>
						<th>會員ID</th>
						<th>更新時間</th>
						<th>更新原因</th>
						<th>符合黑名單規則</th>
						<th>規則細項</th>
						<th>風險等級</th>
						<th>執行狀態</th>
						<th style="width: 110px;">備註</th>
						<th>會員資訊</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class="modal fade" id="idModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="blockDisable">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">移除黑名單</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-20 input-require">移除原因：</div>
						<div class="col">
							<input type="text" required class="w-100 form-control" v-model="disableForm.blockRemark">
						</div>
					</div>
					<div class="d-flex justify-between mx-3 mb-3">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="submit" class="btn btn-danger">移除</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="updateStatus">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">調整狀態</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-20 input-require">調整狀態：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" v-model="updateStatusForm.blockTimeText"
								required>
								<option value=""></option>
								<option value="封鎖一個月" label="封鎖一個月">封鎖一個月</option>
								<option value="封鎖三個月" label="封鎖三個月">封鎖三個月</option>
								<option value="封鎖六個月" label="封鎖六個月">封鎖六個月</option>
								<option value="永久封鎖" label="永久封鎖">永久封鎖</option>
                                <option value="轉二審" label="轉二審">轉二審</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-20 input-require">調整原因：</div>
						<div class="col">
							<input class="w-100 form-control" v-model="updateStatusForm.blockRemark" required>
						</div>
					</div>
					<div class="d-flex justify-between mx-5 mb-5">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="submit" class="btn btn-primary">送出</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="blockUserAdd">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">將使用者新增至黑名單</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-30 input-require">使用者ID</div>
						<div class="col">
							<input type="text" class="w-100 form-control" v-model="blockUserAddForm.userId" required>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-30 input-require">符合黑名單規則：</div>
						<div class="col">
							<select name="" id="" class="w-100 form-control" v-model="blockUserAddForm.blockRule"
								required>
								<option value=""></option>
								<option value="第三方資料結果">第三方資料結果</option>
								<option value="反詐欺規則">反詐欺規則</option>
								<option value="其他(人為加入)">其他(人為加入)</option>
								<option value="授信政策">授信政策</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-30 input-require">規則細項：</div>
						<div class="col">
							<input type="text" class="w-100 form-control" v-model="blockUserAddForm.blockDescription"
								required>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-30 input-require">風險等級：</div>
						<div class="col">
							<select class="form-control" v-model="blockUserAddForm.blockRisk" required>
								<option value=""></option>
								<option value="低風險">低風險</option>
								<option value="中風險">中風險</option>
								<option value="高風險">高風險</option>
                                <option value="拒絕">拒絕</option>
								<option value="追蹤分析">追蹤分析</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-4">
						<div class="col-30 input-require">執行狀態：</div>
						<div class="col">
							<select id="status" class="form-control" v-model="blockUserAddForm.blockTimeText" required>
								<option value=""></option>
								<option value="封鎖一個月" label="封鎖一個月">封鎖一個月</option>
								<option value="封鎖三個月" label="封鎖三個月">封鎖三個月</option>
								<option value="封鎖六個月" label="封鎖六個月">封鎖六個月</option>
								<option value="轉二審" label="轉二審">轉二審</option>
								<option value="永久封鎖" label="永久封鎖">永久封鎖</option>
							</select>
						</div>
					</div>
					<div class="d-flex mb-2">
						<div class="col-30 input-require">備註:</div>
						<div class="col">
							<textarea rows="7" class="w-100 form-control" v-model="blockUserAddForm.blockRemark"
								required></textarea>
						</div>
					</div>

				</div>
				<div class="d-flex justify-between mx-5 mb-5">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-info">新增</button>
				</div>
			</form>
		</div>
	</div>
	<div class="modal fade" id="rejoinModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form class="modal-content" @submit.prevent="blockEnable">
				<div class="modal-header">
					<button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title">重新加入黑名單</h3>
				</div>
				<div class="modal-body p-5">
					<div class="d-flex mb-4">
						<div class="col-30 input-require">重新加入原因：</div>
						<div class="col">
							<input type="text" required class="w-100 form-control" v-model="enableForm.blockRemark">
						</div>
					</div>
					<div class="d-flex justify-between mx-3 mb-3">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
						<button type="submit" class="btn btn-primary">加入</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script> -->
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    const apiUrl = "/api/v2/black_list"

	$(document).ready(function () {
		const t2 = $('#main-table').DataTable({
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
		// mounted 因須確保jquery能用
		v.getAllBlockUsers()
	});
	const v = new Vue({
		el: '#page-wrapper',
		data() {
			return {
				allBlockUsers: [],
				allBlockUserParam: {
					userId: null,
					blockRule: null,
					blockTimeText: null
				},
				blockUserAddForm: {
					userId: null,
					blockRule: null,
					blockDescription: null,
					blockRemark: null,
					blockRisk: null,
					blockTimeText: null,
				},
				updateStatusForm: {
					userId: null,
					blockRemark: null,
					blockTimeText: null,
				},
				disableForm: {
					userId: null,
					blockRemark: null,
				},
				enableForm: {
					userId: null,
					blockRemark: null,
				}
			}
		},
		methods: {
			convertTime(time) {
				d = new Date(time * 1000)
				return d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()
			},
			setMainTableRow(item) {
				const buttonToID = (id) => {
					return `<div class="d-flex">
								<button class="btn btn-default mr-2" data-toggle="modal" data-target="#deductModal">
									<a href="/admin/user/edit?id=${id}" target="_blank">查看</a>
								</button>
							</div>`
				}
				const idGroup = (id, status) => {
					if (status === 0) {
						// 取消封鎖
						return `<div class="d-flex flex-column">
									<div class="mb-2">${id}</div>
									<button class="btn btn-default mr-2" data-toggle="modal" data-target="#rejoinModal" onclick="v.$data.enableForm.userId=${id}">
										已移除
									</button>
								</div>`
					}
					if (status === 1) {
						// 封鎖中
						return `<div class="d-flex flex-column">
							<div class="mb-2">${id}</div>
							<button class="btn btn-danger mr-2" data-toggle="modal" data-target="#idModal" onclick="v.$data.disableForm.userId=${id}">
								移除
							</button>
						</div>`
					}
					// 封鎖時間已過
					return `<div class="d-flex flex-column">
								<div class="mb-2">${id}</div>
							<button class="btn btn-warning mr-2" data-toggle="modal" data-target="#newModal">
								已過期
							</button>
						</div>`

				}
				const statusGroup = (text, status, id) => {
					if (status === 'disabled') {
						return `<div class="d-flex flex-column">
								<div class="mb-2">${text}</div>
								<button class="btn btn-default mr-2" disabled>
									已移除
								</button>
							</div>`
					}
					return `<div class="d-flex flex-column">
							<div class="mb-2" style="white-space:pre-wrap;">${text}</div>
							<button class="btn btn-primary mr-2" data-toggle="modal" data-target="#statusModal" onclick="v.$data.updateStatusForm.userId = ${id}">
								調整
							</button>
						</div>`
				}
				const reason = (text) => {
					return `<div style="white-space:pre-wrap;">${text}</div>`
				}

				const updateTime = ({updatedAt,updatedBy}) =>{
				    return `<div>
				                <div class="mb-2">${this.convertTime(updatedAt)}</div>
                                <div>${updatedBy==0?'(系統)':'(人工) '+updatedBy}</div>
                            </div>
				            `
                }


				$('#main-table').DataTable().row.add([
					idGroup(item.userId, item.status),
                    updateTime({updatedAt:item.updatedAt,updatedBy:item.updatedBy}),
					item.updateReason, item.blockRule,
					item.blockDescription, item.blockRisk,
					statusGroup(item.blockInfo.blockTimeText, item.status, item.userId),
					reason(item.blockRemark),
					buttonToID(item.userId)
				])
			},
			getAllBlockUsers() {
				const { allBlockUserParam } = this
				axios.get(`${apiUrl}/get_all_block_users`, {
					params: {
						...allBlockUserParam
					}
				}).then(({ data }) => {
                    if (!data.results) {
                        alert(data.message)
                    }
					$('#main-table').DataTable().clear()
					this.allBlockUsers = data.results
					this.allBlockUsers.forEach(item => this.setMainTableRow(item))
					$('#main-table').DataTable().draw()
				})
				// this.allBlockUsers.forEach(item => this.setMainTableRow(item))
				// $('#main-table').DataTable().draw()
			},
			blockUserAdd() {
				const { blockUserAddForm } = this
				axios.post(`${apiUrl}/block_add`, {
					...blockUserAddForm
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
					}
				}).finally(() => {
					$('#newModal').modal('hide')
					this.getAllBlockUsers()
				})
			},
			updateStatus() {
				const { updateStatusForm } = this
				axios.post(`${apiUrl}/block_update`, {
					...updateStatusForm
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
					}
				}).finally(() => {
					$('#statusModal').modal('hide')
					this.getAllBlockUsers()
				})
			},
			blockDisable() {
				const { disableForm } = this
				axios.post(`${apiUrl}/block_disable`, {
                    ...disableForm
				}).then(({ data }) => {
					if (data.status !== 200) {
						alert(data.message)
					}
				}).finally(() => {
					$('#idModal').modal('hide')
					this.getAllBlockUsers()
				})
			},
			blockEnable() {
				const { enableForm } = this
				axios.post(`${apiUrl}/block_enable`, {
					...enableForm
				}).then(({ data }) => {
					console.log(data)
					if (data.status !== 200) {
						alert(data.message)
					}
				}).finally(() => {
					$('#rejoinModal').modal('hide')
					this.getAllBlockUsers()
				})
			}
		},
	})

</script>
<style>
	.d-flex {
		display: flex;
		align-items: center;
	}

	.flex-column {
		flex-direction: column;
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

	.col-30 {
		flex: 0 0 30%;
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
		flex: 0.2 0 auto;
	}

    .add-btn {
        display: flex;
        justify-content: flex-end;
        flex: 0.2 0 auto;
    }
</style>
