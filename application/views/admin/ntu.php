<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header d-flex justify-between">
                <div>台大慈善</div>
                <button class="btn btn-danger" data-toggle="modal" data-target="#newModal">新增</button>
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
                <div class="search-btn">
                    <button class="btn btn-primary" @click="get_list">搜尋</button>
                </div>
            </div>
        </div>
        <div class="p-3">
            <table id="my-table">
                <thead>
                <tr>
                    <th>投資人ID/姓名</th>
                    <th>捐款金額</th>
                    <th>權重</th>
                    <th>排行</th>
                    <th>建立時間</th>
                    <th>最後更新時間</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" @submit.prevent="add_info">
                <div class="modal-header">
                    <button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">新增</h3>
                </div>
                <div class="modal-body p-5">
                    <div class="d-flex mb-2">
                        <div class="col-20 input-require">投資人ID</div>
                        <div class="col">
                            <input type="text" required class="w-100 form-control"
                                   v-model.number="add_info_form.user_id">
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20 input-require">捐款金額</div>
                        <div class="col">
                            <input type="text" required class="w-100 form-control"
                                   v-model.number="add_info_form.amount">
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20 input-require">權重</div>
                        <div class="col">
                            <input type="text" required class="w-100 form-control"
                                   v-model.number="add_info_form.weight"
                                   placeholder="請輸入整數0-10，數字越小，權重越高">
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20">是否排行</div>
                        <div class="col">
                            <input type="checkbox" class=""
                                   v-model="add_info_form.type">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-between mx-5 mb-5">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">送出
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" @submit.prevent="update_info()">
                <div class="modal-header">
                    <button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h3 class="modal-title">編輯</h3>
                </div>
                <div class="modal-body p-5">
                    <div class="d-flex mb-2">
                        <div class="col-20">投資人ID :</div>
                        <div class="col">{{ modal_data.user_id }}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20">投資人姓名 :</div>
                        <div class="col">{{ modal_data.user_name }}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20">捐款金額 :</div>
                        <div class="col">{{ modal_data.formatted_amount }}</div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20">權重</div>
                        <div class="col">
                            <div class="col">{{ modal_data.weight }}</div>
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="col-20">是否排行</div>
                        <div class="col">
                            <div class="col">{{ modal_data.type }}</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-between mx-5 mb-5">
                    <button type="submit" class="btn btn-outline-danger">刪除</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script> -->
<script src="https://unpkg.com/axios@1.0.0/dist/axios.min.js"></script>
<script>
    $(document).ready(function () {
        const t = $('#my-table').DataTable({
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
        v.get_list()
    });
    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                data_list: [],
                search_option: {
                    user_id: null,
                },
                modal_data: {
                    user_id: null,
                    amount: null,
                    formatted_amount: null,
                    status_title: null,
                    source_title: null,
                    weight: null,
                    type: null
                },
                add_info_form: {
                    user_id: null,
                    amount: null,
                    weight: null,
                    type: 0
                },
                update_info_form: {
                    id: null
                }
            }
        },
        methods: {
            set_table_row(item) {
                let edit_btn = '';
                if (item.status === '0') {
                    edit_btn = `<div class="d-flex"><button class="btn btn-primary mr-2" onclick="v.get_info(${item.id})">瀏覽</button></div>`;
                }

                $('#my-table').DataTable().row.add([
                    `${item.user_id}/${item.user_name}`,
                    parseInt(item.amount).toLocaleString('en-US'),
                    item.weight,
                    parseInt(item.type || 0) === 0 ? '是' : '否',
                    item.created_at,
                    item.updated_at,
                    edit_btn
                ]);
            },
            get_list() {
                $('#my-table').DataTable().clear().draw()
                const user_id = this.search_option.user_id
                axios.get('get_list', {
                    params: {
                        user_id
                    }
                }).then(({data}) => {
                    this.data_list = data;
                    this.data_list.forEach(item => this.set_table_row(item))
                    $('#my-table').DataTable().draw()
                })
            },
            get_info(id) {
                axios.get('get_info', {
                    params: {
                        id
                    }
                }).then(({data}) => {
                    $('#updateModal').modal('show')
                    this.modal_data = data;
                    this.modal_data.formatted_amount = parseInt(data.amount).toLocaleString('en-US');
                    this.modal_data.type = parseInt(data.type || 0) === 0 ? '是' : '否';
                }).finally(() => {
                    $('#updateModal').modal('show')
                    this.update_info_form.id = id
                })

            },
            update_info() {
                const {update_info_form} = this
                axios({
                    method: 'post',
                    url: 'update_info',
                    data: {
                        ...update_info_form
                    }
                }).then(({data}) => {
                    if (data.result === 'SUCCESS') {
                        alert('刪除成功');
                        this.get_list();
                        return;
                    }
                    alert(data.msg);
                })
                $('#updateModal').modal('hide')
            },
            add_info() {
                const {add_info_form} = this
                axios({
                    method: 'post',
                    url: 'add_info',
                    data: {
                        ...add_info_form
                    }
                }).then(({data}) => {
                    if (data.result === 'SUCCESS') {
                        $('#newModal').modal('hide')
                        alert('新增成功');
                        this.get_list();
                        return;
                    }
                    alert(data.msg);
                })
                $('#newModal').modal('hide')
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
