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
        <h1 class="page-header">權限查詢</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="search-heading row">
                <div>
                    <span class="name">姓名</span>
                    <input type="text" id="search_name"/>
                    <span class="name">部門</span>
                    <input type="text" id="search_division"/>
                </div>
                <div class="search-btn">
                    <button class="btn btn-primary btn-sm">搜尋</button>
                </div>
            </div>
        </div>
        <div class="table-row">
            <table class="display responsive nowrap" width="100%" id="table-roles-setting">
                <thead>
                <tr>
                    <th>帳號</th>
                    <th>姓名</th>
                    <th>部門</th>
                    <th>組別</th>
                    <th>角色</th>
                    <th></th>
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
            let url = new URL(location.href);
            url.search = new URLSearchParams({
                name: $('#search_name').val(),
                division: $('#search_division').val()
            });
            top.location = url.href;
        });

        let params = new URLSearchParams(location.search);
        $('#search_name').val(params.get("name"));
        $('#search_division').val(params.get("division"));
    });

    function insertDataRow({table, account, name, part, group, role, id}) {
        const origin = window.location.origin

        const button = `
            <button class="btn btn-default" onClick="window.open('${origin}/admin/admin/permission_detail?id=${id}','_self')">查看</button>
        `
        table.row.add([account, name, part, group, role, button]).draw()
    }

    function get_dataRow(table) {
        $.ajax({
            url: 'get_permission_list' + window.location.search,
            type: 'GET',
            dataType: 'JSON',
            success: function (response) {
                if (response['list']) {
                    $.each(response['list'], function (index, value) {
                        insertDataRow({
                            table,
                            account: value['email'] ?? '',
                            name: value['name'] ?? '',
                            part: value['division'] ?? '',
                            group: value['department'] ?? '',
                            role: response['position_list'][value['position']],
                            id: value['id']
                        });
                    });
                }
            }
        });
    }
</script>
