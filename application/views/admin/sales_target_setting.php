<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header d-flex justify-between">
                <div>績效統計表</div>
            </h1>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="d-flex">
                <div class="search-btn">
                    <form action="<?=base_url() . 'admin/Sales/goals_export'?>" method="post" style="display: inline-block">
                        &nbsp;&nbsp;
                        選擇匯出月份：
                        <select name="month">
                            <option value="01" <?=($goal_month=='01')?'selected':'';?>>一月</option>
                            <option value="02" <?=($goal_month=='02')?'selected':'';?>>二月</option>
                            <option value="03" <?=($goal_month=='03')?'selected':'';?>>三月</option>
                            <option value="04" <?=($goal_month=='04')?'selected':'';?>>四月</option>
                            <option value="05" <?=($goal_month=='05')?'selected':'';?>>五月</option>
                            <option value="06" <?=($goal_month=='06')?'selected':'';?>>六月</option>
                            <option value="07" <?=($goal_month=='07')?'selected':'';?>>七月</option>
                            <option value="08" <?=($goal_month=='08')?'selected':'';?>>八月</option>
                            <option value="09" <?=($goal_month=='09')?'selected':'';?>>九月</option>
                            <option value="10" <?=($goal_month=='10')?'selected':'';?>>十月</option>
                            <option value="11" <?=($goal_month=='11')?'selected':'';?>>十一月</option>
                            <option value="12" <?=($goal_month=='12')?'selected':'';?>>十二月</option>
                        </select>
                        &nbsp;&nbsp;
                        <input type="submit" class="btn btn-primary float-right" value="下載"/>
                    </form>
                </div>
            </div>
        </div>
        <div class="p-3">
            <table  class="display responsive nowrap" width="100%" id="dataTables-paging">
                <div class="search-btn">
                    <div>
                        <h3>本月目標 - <?php echo (int)$goal_month?>月</h3>
                    </div>
                    <form action="<?=base_url() . 'admin/Sales/set_sale_goals'?>" method="post" style="display: inline-block">
                        
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
