<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">申貸總覽</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <form>
                    <table>
                        <tr>
                            <td>申貸區間：</td>
                            <td><a target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
                            <td><a target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
                            <td class="center-text gap">|</td>
                            <td><input id="loan_sdate" name="loan_sdate" type="text" data-toggle="datepicker"/></td>
                            <td>-</td>
                            <td><input id="loan_edate" name="loan_edate" type="text" data-toggle="datepicker"/></td>
                            <td class="center-text gap">|</td>
                        </tr>
                        <tr>
                            <td>轉化區間：</td>
                            <td><a target="_self" class="btn btn-default float-right btn-md" >本日</a></td>
                            <td><a target="_self" class="btn btn-default float-right btn-md" >全部</a></td>
                            <td class="center-text gap">|</td>
                            <td><input id="conversion_sdate" name="conversion_sdate" data-toggle="datepicker"/></td>
                            <td>-</td>
                            <td><input id="conversion_edate" name="conversion_edate" data-toggle="datepicker"/></td>
                            <td class="center-text gap">|</td>
                            <td><a class="btn btn-default float-right btn-md" >查詢</a></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var today = new Date();
        var todayString = today.getFullYear() + "-" + (today.getMonth()+1) + "-" + today.getDate();

        $('input[name=loan_sdate]').val(todayString);
        $('input[name=loan_edate]').val(todayString);
        $('input[name=conversion_sdate]').val(todayString);
        $('input[name=conversion_edate]').val(todayString);
    });
</script>

<style>
    .center-text {
        text-align: center;
    }

    .gap {
        width: 30px;
    }
</style>