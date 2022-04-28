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
                        <?=$name?>&nbsp;&nbsp;目標：
                        <input type="number" id="goal_id" value="<?=$number?>" />
                        <button class="btn btn-primary" onclick="set_goals()">更新</button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function set_goals(){
        top.location = "<?=base_url();?>"+'admin/Sales/set_goals/'+<?=$id?>+'?number='+$('#goal_id').val();
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
