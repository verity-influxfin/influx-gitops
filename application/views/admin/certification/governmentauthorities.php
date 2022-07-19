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
                            <form id="app1" class="form-group" @submit.prevent="doSubmit">
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
                                            <td><span>產業別</span></td>
                                            <td>
                                                <select v-model="formData.businessTypeCode" class="table-input sk-input form-control">
                                                    <option value="1">1:製造業 (08~34)</option>
                                                    <option value="2">2:買賣業 (45~48)</option>
                                                    <option value="3">3:服務業 (49~56/58~63/69~76/77~82/86~88/90~93/94~96)</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>產業週期</span></td>
                                            <td>
                                                <select v-model="formData.businessCycleCode" class="table-input sk-input form-control">
                                                    <option value="A">A (01~03)</option>
                                                    <option value="B">B (05~06)</option>
                                                    <option value="C">C (08~34)</option>
                                                    <option value="D">D (35)</option>
                                                    <option value="E">E (36~39)</option>
                                                    <option value="F">F (41~43)</option>
                                                    <option value="G">G (45~48)</option>
                                                    <option value="H">H (49~54)</option>
                                                    <option value="I">I (55~56)</option>
                                                    <option value="J">J (58~63)</option>
                                                    <option value="L">L (67~68)</option>
                                                    <option value="M">M (69~76)</option>
                                                    <option value="N">N (77~82)</option>
                                                    <option value="P">P (85)</option>
                                                    <option value="Q">Q (86~88)</option>
                                                    <option value="R">R (90~93)</option>
                                                    <option value="S">S (94~96)</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>公司名稱</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>統一編號</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>戳章日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.stampDate"></td>
                                        </tr>
                                        <tr>
                                            <td><span>負責人姓名</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.prName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>組織類型</span></td>
                                            <td>
                                                <select v-model="formData.organizationType" class="table-input sk-input form-control">
                                                    <option value="A">A:獨資</option>
                                                    <option value="B">B:合夥</option>
                                                    <option value="C">C:有限公司</option>
                                                    <option value="D">D:股份有限公司</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>核准設立日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compSetDate"
                                                placeholder="格式:YYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>依法核准情形</span></td>
                                            <td>
                                                <select v-model="formData.registerType" class="table-input sk-input form-control">
                                                    <option value="A">A:有公司登記與商業登記</option>
                                                    <option value="B">B:取得主管機關核發之營業證照</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>是否公開發行</span></td>
                                            <td>
                                                <select v-model="formData.isPublic" class="table-input sk-input form-control">
                                                    <option value="1">1:是</option>
                                                    <option value="0">0:否</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>實收資本額</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compCapital"></td>
                                        </tr>
                                        <tr>
                                            <td><span>實收資本額最後變異日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.lastPaidInCapitalDate"
                                                placeholder="格式:YYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddress"></td>
                                        </tr>
                                        <tr>
                                            <td><span>是否有法人投資</span></td>
                                            <td>
                                                <select v-model="formData.hasJuridicalInvest" class="table-input sk-input form-control">
                                                    <option value="1">1:是</option>
                                                    <option value="0">0:否</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>法人投資佔總股份(%)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.juridicalInvestRate"
                                                placeholder="請輸入數字部分即可"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業稅申報方式</span></td>
                                            <td>
                                                <select v-model="formData.bizTaxFileWay" class="table-input sk-input form-control">
                                                    <option value="A">A:使用統一發票</option>
                                                    <option value="B">B:免用統一發票核定繳納營業稅</option>
                                                    <option value="C">C:未達課稅起徵點</option>
                                                    <option value="D">D:免徵營業稅或執行業務</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>營業種類標準代碼</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.businessType"></td>
                                        </tr>
                                        <tr>
                                            <td><span>是否屬於製造業、營造業或礦業或土石採集業</span></td>
                                            <td>
                                                <select v-model="formData.isManufacturing" class="table-input sk-input form-control">
                                                    <option value="1">1:是</option>
                                                    <option value="0">0:否</option>
                                                </select>
                                            </td>
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
                                        <td><span>產業別</span></td>
                                        <td>
                                            <select v-model="formData.businessTypeCode" class="table-input sk-input form-control">
                                                <option value="1">1:製造業 (08~34)</option>
                                                <option value="2">2:買賣業 (45~48)</option>
                                                <option value="3">3:服務業 (49~56/58~63/69~76/77~82/86~88/90~93/94~96)</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>產業週期</span></td>
                                        <td>
                                            <select v-model="formData.businessCycleCode" class="table-input sk-input form-control">
                                                <option value="A">A (01~03)</option>
                                                <option value="B">B (05~06)</option>
                                                <option value="C">C (08~34)</option>
                                                <option value="D">D (35)</option>
                                                <option value="E">E (36~39)</option>
                                                <option value="F">F (41~43)</option>
                                                <option value="G">G (45~48)</option>
                                                <option value="H">H (49~54)</option>
                                                <option value="I">I (55~56)</option>
                                                <option value="J">J (58~63)</option>
                                                <option value="L">L (67~68)</option>
                                                <option value="M">M (69~76)</option>
                                                <option value="N">N (77~82)</option>
                                                <option value="P">P (85)</option>
                                                <option value="Q">Q (86~88)</option>
                                                <option value="R">R (90~93)</option>
                                                <option value="S">S (94~96)</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>公司名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compId"></td>
                                    </tr>
                                    <tr>
                                        <td><span>戳章日期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.stampDate"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>組織類型</span></td>
                                        <td>
                                            <select v-model="formData.organizationType" class="table-input sk-input form-control">
                                                <option value="A">A:獨資</option>
                                                <option value="B">B:合夥</option>
                                                <option value="C">C:有限公司</option>
                                                <option value="D">D:股份有限公司</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>核准設立日期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compSetDate"
                                                   placeholder="格式:YYYMMDD"></td>
                                    </tr>
                                    <tr>
                                        <td><span>依法核准情形</span></td>
                                        <td>
                                            <select v-model="formData.registerType" class="table-input sk-input form-control">
                                                <option value="A">A:有公司登記與商業登記</option>
                                                <option value="B">B:取得主管機關核發之營業證照</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>是否公開發行</span></td>
                                        <td>
                                            <select v-model="formData.isPublic" class="table-input sk-input form-control">
                                                <option value="1">1:是</option>
                                                <option value="0">0:否</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>實收資本額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compCapital"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實收資本額最後變異日期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastPaidInCapitalDate"
                                                   placeholder="格式:YYYMMDD"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddress"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有法人投資</span></td>
                                        <td>
                                            <select v-model="formData.hasJuridicalInvest" class="table-input sk-input form-control">
                                                <option value="1">1:是</option>
                                                <option value="0">0:否</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>法人投資佔總股份(%)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.juridicalInvestRate"
                                                   placeholder="請輸入數字部分即可"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業稅申報方式</span></td>
                                        <td>
                                            <select v-model="formData.bizTaxFileWay" class="table-input sk-input form-control">
                                                <option value="A">A:使用統一發票</option>
                                                <option value="B">B:免用統一發票核定繳納營業稅</option>
                                                <option value="C">C:未達課稅起徵點</option>
                                                <option value="D">D:免徵營業稅或執行業務</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>營業種類標準代碼</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.businessType"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否屬於製造業、營造業或礦業或土石採集業</span></td>
                                        <td>
                                            <select v-model="formData.isManufacturing" class="table-input sk-input form-control">
                                                <option value="1">1:是</option>
                                                <option value="0">0:否</option>
                                            </select>
                                        </td>
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
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
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
                                    <label>事業變更登記表</label><br>
                                    <? isset($content['governmentauthorities_image']) && !is_array($content['governmentauthorities_image']) ? $content['governmentauthorities_image'] = array($content['governmentauthorities_image']) : '';
                                    if(!empty($content['governmentauthorities_image'])){
                                        foreach ($content['governmentauthorities_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                </div>
                            </fieldset>
                            <?php if ( ! empty($ocr['upload_page']))
                            {
                                ?>
                                <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                    <?= $ocr['upload_page']; ?>
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
                pageId: '',
                formData: {
                    compName: '',
                    compId: '',
                    stampDate: '',
                    prName: '',
                    organizationType: '',
                    compSetDate: '',
                    registerType: '',
                    isPublic: '',
                    compCapital: '',
                    lastPaidInCapitalDate: '',
                    bizRegAddress: '',
                    hasJuridicalInvest: '',
                    juridicalInvestRate: '',
                    bizTaxFileWay: '',
                    businessType: '',
                    isManufacturing: '',
                    tab2Input: '',
                    tab3Input: '',
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
