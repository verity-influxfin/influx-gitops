<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12 d-flex justify-between">
            <h1 class="page-header">
                自然人借款端審核
            </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="d-flex">
                <div class="p-2">圖示說明：</div>
            </div>
            <div class="d-flex">
                <div class="p-2">
                    留空 尚未認證
                </div>
                <div class="">
                    <button type="button" class="btn btn-warning btn-circle"><i class="fa fa-refresh"></i></button>
                    資料更新中
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>
                    認證完成
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-check"></i></button>
                    認證過期
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button>
                    未符合授信標準
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-exclamation"></i></button>
                    資料未繳交完全
                </div>
                <div class="p-2">
                    <button type="button" class="btn btn-danger btn-circle"><i class="fa fa-minus"></i></button>
                    資料逾期
                </div>
                <div class="p-2">
                    <a id="fontchange" class="btn btn-default" style="margin-top: 6px;">Font mode</a>
                </div>
            </div>
        </div>
        <div>
            <ul class="nav nav-tabs" id="" role="tablist">
                <li role="presentation" data-stage="0">
                    <a href="" role="tab" data-toggle="tab" aria-controls="" aria-expanded="true">身份驗證</a>
                </li>
                <li role="presentation" data-stage="1">
                    <a href="" role="tab" data-toggle="tab" aria-controls="" aria-expanded="false">收件檢核</a>
                </li>
                <li role="presentation" data-stage="2">
                    <a href="" role="tab" data-toggle="tab" aria-controls="" aria-expanded="false">審核中</a>
                </li>
            </ul>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="" id="">
                <div class="row">
                    <h3 class="page-header m-3"></h3>
                </div>
                <div class="m-2" style="float: right;">
                    <a class="btn btn-default" href="<?= admin_url("Risk/natural_person_export") ?>" target="_blank" id="export">匯出資料</a>
                </div>
                <div class="m-2">
                    <ul class="nav nav-tabs" id="" role="tablist">
                        <li role="presentation" data-product="1">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">學生貸</a>
                        </li>
                        <li role="presentation" data-product="3">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">上班族貸</a>
                        </li>
                        <li role="presentation" data-product="3:9999">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">上班族簡易速貸</a>
                        </li>
                        <li role="presentation" data-product="5:10">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">房產消費貸(購房貸)</a>
                        </li>
                        <li role="presentation" data-product="5:11">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">房產消費貸(房屋裝修款)</a>
                        </li>
                        <li role="presentation" data-product="5:12">
                            <a href="javascript:void(0)" role="tab" data-toggle="tab">房產消費貸(添購傢俱家電)</a>
                        </li>
                    </ul>
                </div>
                <table class="" style="text-align: center;">
                    <thead>
                    <tr></tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!-- /.panel-body -->
    </div>
</div>
<!-- /#page-wrapper -->
<style>
    table.dataTable tbody td {
        vertical-align: middle;
    }

    .d-flex {
        display: flex;
        align-items: center;
    }
</style>
<script>
    $(document).ready(function () {
        const url = new URL(location.href);
        const urlParams = new URLSearchParams(url.search);
        let stage = urlParams.get('stage') || 2;
        let product = urlParams.get('product') || '1';
        const stage_title_list = ['身份驗證', '收件檢核', '審核中'];

        $("ul[role=tablist] li").removeClass("active");
        $(`li[data-stage=${stage}]`).addClass("active");
        $(`li[data-product='${product}']`).addClass("active");
        $(".panel-body .page-header").text(stage_title_list[stage]);

        $("li[role='presentation']").on('click', function () {
            if ($(this).data('stage') !== undefined) {
                stage = $(this).data('stage');
            }
            if ($(this).data('product') !== undefined) {
                product = $(this).data('product');
            }
            location.href = url.pathname + `?stage=${stage}&product=${product}`
        });

        let $export = $('#export');
        let export_url_search = new URLSearchParams({'product': product, 'stage': stage}).toString();
        $export.attr('href', `${$export.attr('href')}?${export_url_search}`);

        drawTable(stage, product);
    });

    // 畫資料表
    function drawTable(stage, product) {
        let selector = $("table");

        $.ajax({
            url: `get_natural_person_list?stage=${stage}&product=${product}`,
            method: 'GET',
            dataType: 'JSON',
            success: function (result) {

                let cols = [];
                $.each(result.cols, function (index, element) {
                    cols.push({id: element['id'], sTitle: element['name'], sType: 'string'})
                });

                if (cols.length === 0) {
                    cols = [{id: '', sTitle: '', sType: ''}];
                }

                var table = selector.DataTable({
                    'ordering': true,
                    'fixedHeader': true,
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
                    'info': false,
                    'aoColumns': cols
                });
                setTableRow(selector, cols, result.list);
                selector.DataTable().draw();
                selector.addClass('table-striped');
            }
        });
    }

    // 塞資料列
    function setTableRow(selector, cols, list) {
        $.each(list, function (list_index, list_element) {
            let dataRow = [];
            $.each(cols, function (col_index, col_element) {
                dataRow.push(list_element[col_element.id]);
            });
            selector.DataTable().row.add(dataRow);
        });
    }

    function success(id, target_no) {
        if (confirm(target_no + " 確認審批上架？")) {
            if (id) {
                $.ajax({
                    url: '<?=admin_url('target/verify_success?id=')?>' + id,
                    type: 'GET',
                    success: function (response) {
                        alert(response);
                        location.reload();
                    }
                });
            }
        }
    }

    function failed(id, target_no) {
        if (confirm(target_no + " 確認退件？案件將自動取消")) {
            if (id) {
                var p = prompt("請輸入退案原因，將自動通知使用者，不通知請按取消", "");
                var remark = "";
                if (p) {
                    remark = encodeURIComponent(p);
                }
                $.ajax({
                    url: '<?=admin_url('target/verify_failed?id=')?>' + id + '&remark=' + remark,
                    type: 'GET',
                    success: function (response) {
                        alert(response);
                        location.reload();
                    }
                });
            }
        }
    }

    // 狀態顯示方式切換：[icon mode] 或 [font mode]
    $(document).off("click", "a#fontchange").on("click", "a#fontchange", function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('.nhide').css('display', 'inherit');
            $('.sword').css('display', 'none');
        } else {
            $(this).addClass('active');
            $('.nhide').css('display', 'none');
            $('.sword').css('display', 'block');
        }
    });
</script>
