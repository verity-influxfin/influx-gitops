<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12 d-flex justify-between">
            <h1 class="page-header">
                自然人借款端審核匯出
            </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="panel panel-default">
        <!-- /.panel-heading -->
        <div class="panel-body">
            <form id="myForm" action="export_natural_person_list" method="post">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                    <tr>
                        <td>
                            <strong>步驟一</strong>
                        </td>
                        <td>
                            <input type="radio" name="product_id" value="<?= PRODUCT_ID_STUDENT ?>"
                                   id="product_student">
                            <label for="product_student">學生貸</label>
                            <input type="radio" name="product_id" value="<?= PRODUCT_ID_SALARY_MAN ?>"
                                   id="product_salary_man">
                            <label for="product_salary_man">上班族貸</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>步驟二</strong>
                        </td>
                        <td>
                            <input type="checkbox" name="export_column[]" value="target_no"
                                   id="export_column_target_no">
                            <label for="export_column_target_no">案號</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="user_id" id="export_column_user_id">
                            <label for="export_column_user_id">會員編號</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="user_name"
                                   id="export_column_user_name">
                            <label for="export_column_user_name">姓名</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="user_phone"
                                   id="export_column_user_phone">
                            <label for="export_column_user_phone">電話</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="product_name"
                                   id="export_column_product_name">
                            <label for="export_column_product_name">產品名稱</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="target_status"
                                   id="export_column_target_status">
                            <label for="export_column_target_status">狀態</label>
                            <br/>
                            <input type="checkbox" name="export_column[]" value="updated_at"
                                   id="export_column_updated_at">
                            <label for="export_column_updated_at">最後更新時間</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_IDENTITY ?>"
                                   id="export_column_cert_<?= CERTIFICATION_IDENTITY ?>">
                            <label for="export_column_cert_<?= CERTIFICATION_IDENTITY ?>">實名認證</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_STUDENT ?>"
                                   id="export_column_cert_<?= CERTIFICATION_STUDENT ?>">
                            <label for="export_column_cert_<?= CERTIFICATION_STUDENT ?>">學生身分認證</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_DEBITCARD ?>"
                                   id="export_column_cert_<?= CERTIFICATION_DEBITCARD ?>">
                            <label for="export_column_cert_<?= CERTIFICATION_DEBITCARD ?>">金融帳號驗證</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_SOCIAL ?>"
                                   id="export_column_cert_<?= CERTIFICATION_SOCIAL ?>">
                            <label for="export_column_cert_<?= CERTIFICATION_SOCIAL ?>">社交帳號</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_EMERGENCY ?>"
                                   id="export_column_cert_<?= CERTIFICATION_EMERGENCY ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_EMERGENCY ?>">緊急聯絡人</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_EMAIL ?>"
                                   id="export_column_cert_<?= CERTIFICATION_EMAIL ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_EMAIL ?>">常用電子信箱</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]"
                                   value="<?= CERTIFICATION_FINANCIALWORKER ?>"
                                   id="export_column_cert_<?= CERTIFICATION_FINANCIALWORKER ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_FINANCIALWORKER ?>">財務訊息資訊</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_DIPLOMA ?>"
                                   id="export_column_cert_<?= CERTIFICATION_DIPLOMA ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_DIPLOMA ?>">最高學歷證明</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]"
                                   value="<?= CERTIFICATION_INVESTIGATION ?>"
                                   id="export_column_cert_<?= CERTIFICATION_INVESTIGATION ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_INVESTIGATION ?>">聯合徵信報告</label>
                            <br/>
                            <input type="checkbox" name="export_column_cert[]" value="<?= CERTIFICATION_JOB ?>"
                                   id="export_column_cert_<?= CERTIFICATION_JOB ?>"">
                            <label for="export_column_cert_<?= CERTIFICATION_JOB ?>">工作收入證明</label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-default" disabled>產出資料</button>
                            <span id="loading" style="display: none">產出中...</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </form>
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
<script type="text/javascript">
    $(document).ready(function () {
        const url = new URL(location.href);
        const urlParams = new URLSearchParams(url.search);
        let product = urlParams.get('product') || '';

        let [product_id, sub_product_id] = product.split(':');
        product_id = product_id | '';

        $(`input[name="product_id"][value="${product_id}"]`).prop('checked', true);
        $('input[name="export_column[]"]').prop('checked', true);
        $('input[name="export_column_cert[]"]').prop('checked', true);

        $('form button[type=submit]').on('click', function () {
            $(this).css('display', 'none');
            $('span#loading').css('display', 'block');
        }).prop('disabled', false);

        document.cookie = 'export_natural_person=0';
        window.setInterval(function () {
            if (document.cookie.split(';').some((item) => item.includes('export_natural_person=1'))) {
                document.cookie = 'export_natural_person=0';
                window.close();
            }
        }, 2000);
    });
</script>
