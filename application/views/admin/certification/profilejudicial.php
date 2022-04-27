<style>
    .sk-input form-control {
        width: 100%;
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
                                    <p><?= $data->user_id ?? "" ?></p>
                                </a>
                            </div>
                            <form id="app1" class="form-group" @submit.prevent="doSubmit">
                                <!-- navs -->
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
                                        <td><span>聯絡人姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人職稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContact"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactTel"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><span>傳真號碼</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compFax"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否曾變更負責人</span></td>
                                        <td><select v-model="formData.changeOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人時間-起始</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerYearStart"></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人時間-結束</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerYearEnd"></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人原因</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerReason"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司股東人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.stockholderNum"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司員工人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.employeeNum"></td>
                                    </tr>
                                    <tr>
                                        <td><span>屬於受嚴重特殊傳染性肺炎影響之企業</span></td>
                                        <td><select v-model="formData.isCovidAffected" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>受上述影響致財務困難，支票存款戶經票據交換所註記為「紓困」</span></td>
                                        <td><select v-model="formData.getRelief" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否公開發行</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>有公開發行計畫</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>預計公開發行年份</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.goPublicYear"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有海外投資</span></td>
                                        <td><select v-model="formData.hasForeignInvestment" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業專業證照/專利</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業專業證照/專利名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.licenceName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業自有品牌</span></td>
                                        <td><select v-model="formData.hasOwnBrand" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業自有品牌名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.ownBrandName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業是否從事衍生性金融商品操作</span></td>
                                        <td><select v-model="formData.hasDerivative" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-kgibank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人職稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContact"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactTel"></td>
                                    </tr>
                                    <tr>
                                        <td><span>Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><span>傳真號碼</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compFax"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否曾變更負責人</span></td>
                                        <td><select v-model="formData.changeOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人時間-起始</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerYearStart"></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人時間-結束</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerYearEnd"></td>
                                    </tr>
                                    <tr>
                                        <td><span>變更負責人原因</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.changeOwnerReason"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司股東人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.stockholderNum"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司員工人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.employeeNum"></td>
                                    </tr>
                                    <tr>
                                        <td><span>屬於受嚴重特殊傳染性肺炎影響之企業</span></td>
                                        <td><select v-model="formData.isCovidAffected" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>受上述影響致財務困難，支票存款戶經票據交換所註記為「紓困」</span></td>
                                        <td><select v-model="formData.getRelief" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否公開發行</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>有公開發行計畫</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>預計公開發行年份</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.goPublicYear"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有海外投資</span></td>
                                        <td><select v-model="formData.hasForeignInvestment" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業專業證照/專利</span></td>
                                        <td><select v-model="formData.goPublic" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業專業證照/專利名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.licenceName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業自有品牌</span></td>
                                        <td><select v-model="formData.hasOwnBrand" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:有</option>
                                                <option :value="'0'">0:無</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業自有品牌名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.ownBrandName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業是否從事衍生性金融商品操作</span></td>
                                        <td><select v-model="formData.hasDerivative" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <label>備註</label>
                                <?php
                                if ( ! empty($remark['fail'])) {
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark['fail'] . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <?php foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                    <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= isset($data->id) ? $data->id : ""; ?>">
                                        <input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <?php foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option
                                                    <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                            <?php } ?>
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
                            <h1>圖片/文件</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <?php if ( ! empty($content['others_image']))
                                    {
                                        if ( ! is_array($content['others_image']))
                                        {
                                            $content['others_image'] = array($content['others_image']);
                                        }
                                        foreach ($content['others_image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value ?? '' ?>" data-fancybox="images">
                                                <img alt="" src="<?= $value ?: '' ?>" style="width:30%; max-width:400px;">
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                            </fieldset>
                            <?php if ($data->certification_id == CERTIFICATION_PROFILEJUDICIAL && isset($ocr['upload_page'])) { ?>
                                <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                    <?= $ocr['upload_page'] ?>
                                </div>
                            <?php } ?>
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
        el: '#app1',
        data() {
            return {
                tab: 'tab-skbank',
                pageId:'',
                formData: {
                    compContactName: '',
                    compContact: '',
                    compContactTel: '',
                    compEmail: '',
                    compFax: '',
                    compContactExt: '',
                    financialOfficerName: '',
                    financialOfficerExt: '',
                    changeOwner: '',
                    changeOwnerYearStart: '',
                    changeOwnerYearEnd: '',
                    changeOwnerReason: '',
                    stockholderNum: '',
                    employeeNum: '',
                    isCovidAffected: '',
                    getRelief: '',
                    goPublic: '',
                    goPublicPlan: '',
                    goPublicYear: '',
                    hasForeignInvestment: '',
                    hasLicence: '',
                    licenceName: '',
                    hasOwnBrand: '',
                    ownBrandName: '',
                    hasDerivative: '',
                }
            }
        },
        mounted () {
            const url = new URL(location.href);
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
            },
            doSubmit(){
                return axios.post('/admin/certification/save_company_cert',{
                    ...this.formData,
                    id: this.pageId
                }).then(({data})=>{
                    alert(data.result)
                    location.reload()
                })
            },
            getData(){
                axios.get('/admin/certification/getSkbank',{
                    params:{
                        id: this.pageId
                    }
                }).then(({data})=>{
                    mergeDeep(this.formData, data.response)
                })
            }
        },
    })
</script>
