<style>
    td{padding:10px 5px;}
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">資產列表(全部列表v2)</h1>
        </div>
        <!-- /.col-lg-12 -->

    </div>
    <script type="text/javascript">
        function checked_all() {
            $('.investment').prop("checked", true);
            check_checked();
        }

        function check_checked() {
            var ids = "", ctr = $('#amortization_export,#assets_export').parent().find('.btn');
            $('.investment:checked').each(function () {

                if (ids == "") {
                    ids += this.value;
                } else {
                    ids += ',' + this.value;
                }
            });
            if (ids != "") {
                $('#assets_export').val(ids);
                $('#amortization_export').val(ids);
                ctr.prop('disabled', false);
            } else {
                ctr.prop('disabled', true);
            }
        }

        $(document).off("keypress","input[type=text]").on("keypress","input[type=text]" ,  function(e){
            code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13){
                $('.asearch').click();
            }
        });

        $(document).off("click",".asearch").on("click",".asearch" ,  function(){
            var user_id = $('#user_id').val();
            var target_no = $('#target_no').val();
            var status = $('#status :selected').val();
            var trans_status = $('#trans_status :selected').val();
            var dateRange = '&sdate=' + $('#sdate').val() + '&edate=' + $('#edate').val();
            var data = '';
            if (user_id == '' && target_no == '' && status == '') {
                if (confirm(target_no + "即將撈取各狀態分割債權，過程可能需點時間，請勿直接關閉， 確認是否執行？")) {
                    data = 'data=1&all=all' + dateRange;
                }
            }else{
                data = 'data=1&status=' + status + '&trans_status=' + trans_status + '&user_id=' + user_id + '&target_no=' + target_no  + dateRange;
            }

            $.ajax({
                type:'POST',
                url:location.origin+"/admin/Transfer/assets_list",
                timeout:120000,
                data:data,
                beforeSend: function () {
                    $('.investment').prop("checked", false);
                    check_checked();
                    $('.asearch').attr('disabled',true).text('撈取中..');
                },
                success:function(wri){
                    res=$.parseJSON(wri);
                    if(res.result == "SUCCESS"){
                        if(res.data.length != 0){
                            var title = '',content = '';
                            $.each(res.data.title, function(a,b){
                                title+='<td>'+b+ (a==1?'  <a href="javascript:void(0)" onclick="checked_all();" class="btn">全選</a> ':'')+'</td>';
                            });
                            $('.adata thead').html('<tr>'+title+'<td></td></tr>');
                            $.each(res.data.content, function(a,b){
                                cut = a.split('|');
                                var temp = '',selection = '';
                                $.each(b, function(c,d){
                                    selection = c == 1?' <input class="investment" type="checkbox" onclick="check_checked();" value="'+cut[0]+'"':'';
                                    temp += '<td>' + d + selection +'</td>';
                                    c == b.length-1 ? temp += ('<td>' + '<a target="_blank" href="'+location.origin+'/admin/target/edit?id='+cut[1]+'" class="btn btn-default">案件詳情</a>' + '</td>') : '';
                                });
                                content += '<tr>'+ temp +'</tr>';
                            });
                        }
                        else{
                            $('.adata thead').html('<tr><td></td></tr>');
                            content += '<tr><td style="text-align: center;">無資料</td></tr>';
                        }
                        $('.panel-body').show();
                        $('.adata tbody').html('<tr>'+content+'</tr>');
                        $('.asearch').attr('disabled',false).text('查詢');
                    }
                    else{
                        $('.asearch').attr('disabled',false).text('查詢');
                        alert('撈取失敗');
                    }
                },error:function(wri){
                    $('.asearch').attr('disabled',false).text('查詢');
                    alert('撈取時間過長..請與系統確認');
                }
            });
        });
    </script>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table>
                        <tr>
                            <td>投資人ID：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['user_id']) && $_GET['user_id'] != "" ? $_GET['user_id'] : "" ?>"
                                       id="user_id"/></td>
                            <td>案號：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['target_no']) && $_GET['target_no'] != "" ? $_GET['target_no'] : "" ?>"
                                       id="target_no"/></td>
                        </tr>
                        <tr>
                            <td>案件取得時間：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['sdate']) && $_GET['sdate'] != '' ? $_GET['sdate'] : '' ?>"
                                       id="sdate" data-toggle="datepicker" placeholder="不指定區間"/></td>
                            <td>至：</td>
                            <td><input type="text"
                                       value="<?= isset($_GET['edate']) && $_GET['edate'] != '' ? $_GET['edate'] : '' ?>"
                                       id="edate" data-toggle="datepicker" style="width: 182px;" placeholder="不指定區間"/>
                            </td>
                        </tr>
                        <tr>
                            <td>狀態：</td>
                            <td>
                                <select id="status">
                                    <option value="">請選擇</option>
                                    <? foreach ($target_status as $key => $value) {
                                        ?>
                                        <option value="<?= $key ?>" <?= isset($_GET['status']) && $_GET['status'] != "" && intval($_GET['status']) == intval($key) ? "selected" : "" ?>><?= $value ?></option>
                                        <?
                                    } ?>
                                </select>
                            </td>
                            <td>債轉狀態：</td>
                            <td>
                                <select id="trans_status">
                                    <option value="">請選擇</option>
                                        <option value="0" <?= intval($_GET['status']) == 0 ? "selected" : "" ?>>無</option>
                                        <option value="2" <?= intval($_GET['status']) == 2 ? "selected" : "" ?>>已轉出</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td>
                                <a class="btn btn-info asearch">查詢</a>
                            </td>
                            <td>
                                <form action="<?= admin_url('transfer/assets_list') ?>" method="post"
                                      style="display: inline-block">
                                    <input type="submit" class="btn btn-primary float-right" value="債權明細表" disabled/>
                                    <input id="assets_export" type="hidden" name="ids"/>
                                    <input type="hidden" name="type" value="assets"/>
                                </form>
                            </td>
                            <td>
                                <form action="<?= admin_url('transfer/amortization_export') ?>" method="post"
                                      style="display: inline-block">
                                    <input type="submit" class="btn btn-primary float-right" value="本金餘額攤還表" disabled/>
                                    <input id="amortization_export" type="hidden" name="ids"/>
                                </form>
                            </td>
                        </tr>
                    </table>

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover adata" width="100%">
                            <thead>
                            <tr>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
<!-- /#page-wrapper -->