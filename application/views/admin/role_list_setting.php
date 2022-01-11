<style>
    .search-heading {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .table-row {
        padding: 15px;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <h1 class="page-header">部門權限管理</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="search-heading row">
                <div>
                    <span class="name">部門名稱</span>
                    <input type="text" id="search"/>
                </div>
                <div class="search-btn">
                    <button class="btn btn-primary btn-sm">搜尋</button>
                </div>
            </div>
        </div>
        <div class="new-role m-4">
            <a class="btn btn-primary" href="<?= admin_url('Admin/role_list_add') ?>" target="_blank">新增角色</a>
        </div>
        <div class="table-row">
            <table class="display responsive nowrap" width="100%" id="table-roles-setting">
                <thead>
                <tr>
                    <th>部門</th>
                    <th>組別</th>
                    <th>角色名稱</th>
                    <th>修改</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    $(document).ready(() => {
        const table = $('#table-roles-setting').DataTable({
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
        })

        get_dataRow(table);

        $('.search-btn button').on('click', function () {
            let division = $('#search').val();
            top.location = `./role_list_setting?division=${division}`;
        });
    });

    function insertDataRow({table, part, group, role, id}) {
        const origin = window.location.origin
        const button = `<button class="btn btn-default" onClick="window.open('${origin}/admin/Admin/role_list_edit?id=${id}')">Edit</button>`
        table.row.add([part, group, role, button]).draw()
    }

    function get_dataRow(table) {
        $.ajax({
            url: 'role_list_setting_get' + window.location.search,
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                if (response['list']) {
                    $.each(response['list'], function (index, value) {
                        insertDataRow({
                            table,
                            part: value['division'], // 部門
                            group: value['department'], // 組別
                            role: response['position_list'][value['position']] || '', // 角色名稱
                            id: value['id']
                        });
                    });
                }
            }
        });
    }
</script>
