<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header d-flex justify-between">
                <div>編輯績效目標</div>
            </h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="d-flex">
                <div class="search-btn">

                    <div>
                        <h4><?php echo $goal_ym?> 目標</h4>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="p-3">
            <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
                <div class="search-btn">

                    <form action="<?=base_url() . 'admin/Sales/set_monthly_goals/' . $goal_ym?>" method="post" style="display: inline-block">

                            <?php foreach ($goal_items as $key => $value) { ?>
                                <tr><td><?=$value?>&nbsp;:&nbsp;<input type="number" value="<?=$goal_number[$key]['number']?>" name="<?=$goal_number[$key]['id']?>" /></td></tr>
                            <?php } ?>

                        <tr><td><input type="submit" class="btn btn-primary float-right" value="更新本月目標"/></td></tr>
                    </form>

                </div>
            </table>
        </div>
    </div>
</div>
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
