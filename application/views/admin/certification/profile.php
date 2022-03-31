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
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <form class="form-group" @submit.prevent="doSubmit">
                                <ul class="nav nav-tabs nav-justified mb-1">
                                    <li role="presentation" :class="{'active': tab ==='tab-skbank'}"><a @click="changeTab('tab-skbank')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-kgibank'}"><a @click="changeTab('tab-kgibank')">凱基</a></li>
                                </ul>
                                <div id="tab-skbank" v-show="tab==='tab-skbank'">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a @click="changeSubTab('Pr')" data-toggle="tab" aria-expanded="true">負責人</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('OtherRealPr')" data-toggle="tab" aria-expanded="false">其他實際負責人</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuOne')" data-toggle="tab" aria-expanded="false">保證人</a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive Pr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人擔任本企業負責人年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="prInChargeYear"
                                                        placeholder="格式:YYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="prStartYear"
                                                        placeholder="格式:YYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人學歷</span></td>
                                                    <td>
                                                        <select name="prEduLevel" class="table-input form-control">
                                                            <option value="A">A:國小</option>
                                                            <option value="B">B:國中</option>
                                                            <option value="C">C:高中職</option>
                                                            <option value="D">D:專科</option>
                                                            <option value="E">E:大學</option>
                                                            <option value="F">F:碩士</option>
                                                            <option value="G">G:博士</option>
                                                            <option value="H">H:無</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive OtherRealPr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責人是否等於登記負責人</span></td>
                                                    <td>
                                                        <select name="isPrRegister" class="table-input sk-input form-control">
                                                            <option value="1">1:是</option>
                                                            <option value="0">0:否</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責人與借戶、負責人之關係</span></td>
                                                    <td>
                                                        <select name="prRelationship" class="table-input sk-input form-control">
                                                            <option value="A">A:配偶</option>
                                                            <option value="B">B:血親</option>
                                                            <option value="C">C:姻親</option>
                                                            <option value="D">D:股東</option>
                                                            <option value="E">E:朋友</option>
                                                            <option value="F">F:本人</option>
                                                            <option value="G">G:其他</option>
                                                            <option value="H">H:與經營有關之借戶職員</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>其他實際負責人從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="othRealPrStartYear"
                                                               placeholder="格式:YYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>其他實際負責人擔任公司職務</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="othRealPrTitle"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>其他實際負責人持股比率(%)</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="othRealPrSHRatio"
                                                        placeholder="請輸入數字部分即可"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive GuOne">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>是否有徵提保證人</span></td>
                                                    <td>
                                                        <select name="hasGuarantor" class="table-input sk-input form-control">
                                                            <option value="1">1:是</option>
                                                            <option value="0">0:否</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶是否擔任保證人</span></td>
                                                    <td>
                                                        <select name="isPrSpouseGu" class="table-input sk-input form-control">
                                                            <option value="1">1:是</option>
                                                            <option value="0">0:否</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人與借戶、負責人之關係</span></td>
                                                    <td>
                                                        <select name="guOneRelWithPr" class="table-input sk-input form-control">
                                                            <option value="A">A:配偶</option>
                                                            <option value="B">B:血親</option>
                                                            <option value="C">C:姻親</option>
                                                            <option value="D">D:股東</option>
                                                            <option value="E">E:朋友</option>
                                                            <option value="F">F:本人</option>
                                                            <option value="G">G:其他</option>
                                                            <option value="H">H:與經營有關之借戶職員</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人任職公司</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="guCompany"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="tab-kgibank" v-show="tab==='tab-kgibank'">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active">
                                            <a @click="changeSubTab('Pr')" data-toggle="tab" aria-expanded="true">負責人</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('OtherRealPr')" data-toggle="tab" aria-expanded="false">其他實際負責人</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuOne')" data-toggle="tab" aria-expanded="false">保證人</a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive Pr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                            <tr style="text-align: center;">
                                                <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人擔任本企業負責人年度</span></td>
                                                <td><input class="sk-input form-control" type="text" name="prInChargeYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人從事本行業年度</span></td>
                                                <td><input class="sk-input form-control" type="text" name="prStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人學歷</span></td>
                                                <td>
                                                    <select name="prEduLevel" class="table-input form-control">
                                                        <option value="A">A:國小</option>
                                                        <option value="B">B:國中</option>
                                                        <option value="C">C:高中職</option>
                                                        <option value="D">D:專科</option>
                                                        <option value="E">E:大學</option>
                                                        <option value="F">F:碩士</option>
                                                        <option value="G">G:博士</option>
                                                        <option value="H">H:無</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive OtherRealPr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                            <tr style="text-align: center;">
                                                <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>實際負責人是否等於登記負責人</span></td>
                                                <td>
                                                    <select name="isPrRegister" class="table-input sk-input form-control">
                                                        <option value="1">1:是</option>
                                                        <option value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>實際負責人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select name="prRelationship" class="table-input sk-input form-control">
                                                        <option value="A">A:配偶</option>
                                                        <option value="B">B:血親</option>
                                                        <option value="C">C:姻親</option>
                                                        <option value="D">D:股東</option>
                                                        <option value="E">E:朋友</option>
                                                        <option value="F">F:本人</option>
                                                        <option value="G">G:其他</option>
                                                        <option value="H">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人從事本行業年度</span></td>
                                                <td><input class="sk-input form-control" type="text" name="othRealPrStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人擔任公司職務</span></td>
                                                <td><input class="sk-input form-control" type="text" name="othRealPrTitle"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人持股比率(%)</span></td>
                                                <td><input class="sk-input form-control" type="text" name="othRealPrSHRatio"
                                                           placeholder="請輸入數字部分即可"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive GuOne">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                            <tr style="text-align: center;">
                                                <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>是否有徵提保證人</span></td>
                                                <td>
                                                    <select name="hasGuarantor" class="table-input sk-input form-control">
                                                        <option value="1">1:是</option>
                                                        <option value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶是否擔任保證人</span></td>
                                                <td>
                                                    <select name="isPrSpouseGu" class="table-input sk-input form-control">
                                                        <option value="1">1:是</option>
                                                        <option value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select name="guOneRelWithPr" class="table-input sk-input form-control">
                                                        <option value="A">A:配偶</option>
                                                        <option value="B">B:血親</option>
                                                        <option value="C">C:姻親</option>
                                                        <option value="D">D:股東</option>
                                                        <option value="E">E:朋友</option>
                                                        <option value="F">F:本人</option>
                                                        <option value="G">G:其他</option>
                                                        <option value="H">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人任職公司</span></td>
                                                <td><input class="sk-input form-control" type="text" name="guCompany"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
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
                    prInChargeYear: '',
                    prStartYear: '',
                    prEduLevel: '',
                    isPrRegister: '',
                    prRelationship: '',
                    othRealPrStartYear: '',
                    othRealPrTitle: '',
                    othRealPrSHRatio: '',
                    hasGuarantor: '',
                    isPrSpouseGu: '',
                    guOneRelWithPr: '',
                    guCompany: '',
                    tab2Input: '',
                    tab3Input: '',
                }
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.changeTab('tab-skbank')
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
                this.changeSubTab('Pr')
            },
            changeSubTab(show_id) {
                $(".table-responsive").hide()
                $(`#${this.tab} .${show_id}`).show()
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
