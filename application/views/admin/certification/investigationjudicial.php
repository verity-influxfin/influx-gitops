<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    .sk-input {
        width : 100%;
    }
</style>
<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        var sel = $(this).find(':selected');
        $('input#fail').css('display', sel.attr('value') == 'other' ? 'block' : 'none');
        $('input#fail').attr('disabled', sel.attr('value') == 'other' ? false : true);
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>交件方式</label>
                                <p class="form-control-static">
                                    <?php
                                    if (isset($return_type))
                                    {
                                        echo $return_type;
                                    }
                                    else
                                    {
                                        echo '';
                                    }
                                    ?>
                                </p>
                            </div>
                            <form class="form-group" @submit.prevent="doSubmit">
                                <ul class="nav nav-tabs">
                                    <li role="presentation" :class="{'active': tab ==='tab-skbank'}"><a @click="changeTab('tab-skbank')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-kgibank'}"><a @click="changeTab('tab-kgibank')">凱基</a></li>
                                </ul>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-skbank'">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業聯徵查詢日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompJCICQueryDate"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司中期放款餘額-年月</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.MidTermLnYM"
                                                    placeholder="格式:YYYYMM"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司中期放款餘額</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.MidTermLnBal"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司短期放款餘額-年月</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ShortTermLnYM"
                                                    placeholder="格式:YYYYMM"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司短期放款餘額</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ShortTermLnBal"></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業聯徵J02資料年月</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompJCICDataDate"
                                                    placeholder="格式:YYYYMM"></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業信用評分</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompCreditScore"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-kgibank'">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>票債信情形是否異常</span></td>
                                            <td><select v-model="formData.CompJCDebtLog" class="table-input sk-input form-control">
                                                    <option :value="'1'">1:是</option>
                                                    <option :value="'0'">0:否</option>
                                                </select></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業聯徵查詢日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompJCICQueryDate"
                                                       placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業聯徵J02資料年月</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompJCICDataDate"
                                                       placeholder="格式:YYYYMM"></td>
                                        </tr>
                                        <tr>
                                            <td><span>企業信用評分</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompCreditScore"></td>
                                        </tr>
                                        <tr>
                                            <td><span>往來銀行家數</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompJCBankDealingNum"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                              <? isset($ocr['url']) && !is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                              foreach ($ocr['url'] as $key => $value) { ?>
                                  <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                              <? } ?>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?
                                if ($remark) {
                                    if (isset($remark["fail"]) && $remark["fail"]) {
                                        echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?
                                if (isset($sys_check)) {
                                    echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <? foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                        <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= isset($data->id) ? $data->id : ""; ?>">
                                        <input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <? foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option
                                                    <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail"
                                               value="<?= $remark && isset($remark["fail"]) ? $remark["fail"] : ""; ?>"
                                               style="background-color:white!important;display:none" disabled="false">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label>法人聯徵資料</label><br>
                                    <? isset($content['legal_person_mq_image']) && !is_array($content['legal_person_mq_image']) ? $content['legal_person_mq_image'] = array($content['legal_person_mq_image']) : [];
                                    if(!empty($content['legal_person_mq_image'])){
                                        foreach ($content['legal_person_mq_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                    <? isset($content['postal_image']) && !is_array($content['postal_image']) ? $content['postal_image'] = array($content['postal_image']) : [];
                                    if(!empty($content['postal_image'])){
                                        foreach ($content['postal_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>

                                    <?php
                                    $content['receipt_postal_image'] = isset($content['receipt_postal_image'])
                                        ? ! is_array($content['receipt_postal_image'])
                                            ? [$content['receipt_postal_image']]
                                            : $content['receipt_postal_image']
                                        : [];
                                    $content['receipt_jcic_image'] = isset($content['receipt_jcic_image'])
                                        ? ! is_array($content['receipt_jcic_image'])
                                            ? [$content['receipt_jcic_image']]
                                            : $content['receipt_jcic_image']
                                        : [];
                                    $receipt_image = array_merge($content['receipt_postal_image'], $content['receipt_jcic_image']);
                                    foreach ($receipt_image as $key => $value)
                                    { ?>
                                        <a href="<?= $value; ?>" data-fancybox="images">
                                            <img src="<?= $value; ?>" alt="" style='width:30%;max-width:400px'>
                                        </a>
                                    <?php } ?>

                                </div>
                            </fieldset>
                            <? if( ($data->certification_id == 9 || $data->certification_id == 1003 || $data->certification_id == 12) && isset($ocr['upload_page']) ){ ?>
                            <div class="form-group" style="background:#f5f5f5;border-style:double;">
                              <?= isset($ocr['upload_page']) ? $ocr['upload_page'] : ""?>
                            </div>
                            <? } ?>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

<script>
    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                tab: 'tab-skbank',
                pageId: '',
                formData: {
                    CompJCICQueryDate: '',
                    MidTermLnYM: '',
                    MidTermLnBal: '',
                    ShortTermLnYM: '',
                    ShortTermLnBal: '',
                    CompJCICDataDate: '',
                    CompCreditScore: '',
                    CompJCDebtLog: '',
                    CompJCBankDealingNum: '',
                }
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
            },
            doSubmit() {
                return axios.post('/admin/certification/save_company_cert', {
                    ...this.formData,
                    id: this.pageId
                }).then(({ data }) => {
                    alert(data.result)
                    location.reload()
                })
            },
            getData() {
                axios.get('/admin/certification/getSkbank', {
                    params: {
                        id: this.pageId
                    }
                }).then(({ data }) => {
                    mergeDeep(this.formData, data.response)
                })
            }
        },
    })
</script>
