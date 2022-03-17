<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header d-flex justify-between">
                <div>慈善專區</div>
            </h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="d-flex">
                <div>
                    <button class="btn btn-primary" onclick="get_list()">搜尋</button>
                </div>
                <div class="search-btn">
                    <form action="<?=base_url() . 'admin/Charity/export'?>" method="post" style="display: inline-block">
                        &nbsp;&nbsp;
                        捐款日期：
                        <input type="text" value="<?=isset($sdate) && $sdate ? $sdate : ''?>" id="sdate" name="sdate" data-toggle="datepicker"  />
                        &nbsp;~&nbsp;
                        <input type="text" value="<?=isset($edate) && $edate ? $edate : ''?>" id="edate" name="edate" data-toggle="datepicker" />
                        &nbsp;&nbsp;
                        <input type="submit" class="btn btn-primary float-right" value="匯出Excel"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-3">
            <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
                <thead>
                <tr>
                    <th>捐款日</th>
                    <th>User ID</th>
                    <th>身份證</th>
                    <th>捐款人</th>
                    <th>捐款方式</th>
                    <th>收據開立</th>
                    <th>捐款金額</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($list as $key => $value)
                    { ?>
                    <tr>
                        <td><?=explode(' ', $value['tx_datetime'])[0]?></td>
                        <td><?=$value['user_id']?></td>
                        <td><?=$value['id_number']?></td>
                        <td><?=$value['name']?></td>
                        <td>普匯APP</td>
                        <td><?=$value['receipt_type']?></td>
                        <td><?=(int) $value['amount']?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function get_list(){
        top.location = "<?=base_url();?>"+'admin/Charity/index?sdate='+$('#sdate').val()+'&edate='+$('#edate').val();
    }
</script>
<style>
    .d-flex {
        display: flex;
        align-items: center;
    }

    .justify-between {
        justify-content: space-between;
    }

    .search-btn {
        display: flex;
        justify-content: flex-start;
        flex: 1 0 auto;
    }
</style>
