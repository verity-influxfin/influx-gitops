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
                            <form class="form-group" @submit.prevent="doSubmit">
                                <!-- navs -->
                                <ul class="nav nav-tabs">
                                    <li role="presentation" :class="{'active': tab ==='tab-1'}"><a @click="changeTab('tab-1')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-2'}"><a @click="changeTab('tab-2')">凱基</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-3'}"><a @click="changeTab('tab-3')">其他</a></li>
                                </ul>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-1'">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司行業別(主計處)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompIdustry"></td>
                                        </tr>
                                        <tr>
                                            <td><span>近一年結算申報書營業收入-年度</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear1" placeholder="格式:YYYYMMDD">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>近一年結算申報書營業收入-營收</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome1"></td>
                                        </tr>
                                        <tr>
                                            <td><span>近二年結算申報書營業收入-年度</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear2" placeholder="格式:YYYYMMDD">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>近二年結算申報書營業收入-營收</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome2"></td>
                                        </tr>
                                        <tr>
                                            <td><span>近三年結算申報書營業收入-年度</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear3" placeholder="格式:YYYYMMDD">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>近三年結算申報書營業收入-營收</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome3"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-2'">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司行業別(主計處)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompIdustry"></td>
                                        </tr>
                                        <tr>
                                            <td><span>custom tab2</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.tab2Input"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-3'">
                                    <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>普匯微企e秒貸資料確認3</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司行業別(主計處)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompIdustry"></td>
                                        </tr>
                                        <tr>
                                            <td><span>custom tab3</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.tab3Input"></td>
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
                                    <label>損益表</label><br>
                                    <? isset($content['income_statement_image']) && !is_array($content['income_statement_image']) ? $content['income_statement_image'] = array($content['income_statement_image']) : '';
                                    if(!empty($content['income_statement_image'])){
                                        foreach ($content['income_statement_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                      <!-- admin form end -->
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
                tab: 'tab-1',
                pageId: '',
                formData: {
                    CompIdustry: '',
                    AnnualIncomeYear1: '',
                    AnnualIncome1: '',
                    AnnualIncomeYear2: '',
                    AnnualIncome2: '',
                    AnnualIncomeYear3: '',
                    AnnualIncome3: '',
                    tab2Input:'',
                    tab3Input:''
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
                return axios.post('/admin/certification/sendSkbank', {
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
