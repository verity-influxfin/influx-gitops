<div id="page-wrapper">
    <div class="row" style="padding-top: 30px;">
        <div class="col-xs-4">
            <form action="/admin/TestScript/mockingTransfer" method="post" onsubmit="return confirm('確認匯款嗎?')">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">匯款</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>user ID</label>
                                    <input type="number" class="form-control" name="user_id" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>身分</label>
                                    <select class="form-control" name="investor" required>
                                        <option value="0" selected>借款人</option>
                                        <option value="1">投資人</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>金額</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="/cron/handle_payment" target="_blank">handle_payment</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary pull-right">匯入</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>