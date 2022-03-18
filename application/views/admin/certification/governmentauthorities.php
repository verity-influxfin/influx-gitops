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
                                            <td><span>公司統一編號(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司戶名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司核准設立日期(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompSetDate"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司實收資本額(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompCapital"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司型態(商業司)</span></td>
                                            <td>
                                                <select v-model="formData.CompType" class="table-input sk-input form-control">
                                                    <option value="41">41:獨資</option>
                                                    <option value="21">21:中小企業</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>公司登記地址-郵遞區號(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompRegAddrZip"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司登記地址-郵遞區號名稱(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompRegAddrZipName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司登記地址-非郵遞地址資料(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompRegAddress"></td>
                                        </tr>
                                        <tr>
                                            <td><span>現任負責人擔任公司起日-日期(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrOnboardDay"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>現任負責人擔任公司起日-姓名(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrOnboardName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>前任負責人擔任公司起日-日期(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ExPrOnboardDay"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>前任負責人擔任公司起日-姓名(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ExPrOnboardName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>前二任負責人擔任公司起日-日期(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ExPrOnboardDay2"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>前二任負責人擔任公司起日-姓名(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.ExPrOnboardName2"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_選擇縣市(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrCityName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_選擇鄉鎮市區(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrAreaName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_路街名稱(不含路、街)(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrRoadName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_路 OR 街(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrRoadType"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_段(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrSec"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_巷(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrLn"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_弄(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrAly"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_號(不含之號)(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrNo"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_之號(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrNoExt"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_樓(不含之樓、室)(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrFloor"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_之樓(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrFloorExt"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_室(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrRoom"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_其他備註(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.BizRegAddrOtherMemo"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司最後核准變更實收資本額日期(商業司)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.LastPaidInCapitalDate"
                                                    placeholder="格式:YYYYMMDD"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 A 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorAName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 A 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorAId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 B 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorBName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 B 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorBId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 C 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorCName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 C 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorCId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 D 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorDName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 D 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorDId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 E 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorEName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 E 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorEId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 F 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorFName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 F 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorFId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 G 姓名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorGName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司董監事 G 統編(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.DirectorGId"></td>
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
                                            <td><span>公司統一編號(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>公司戶名(變卡)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.CompName"></td>
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
                    CompId: '',
                    CompName: '',
                    CompSetDate: '',
                    CompCapital: '',
                    CompType: '',
                    CompRegAddrZip: '',
                    CompRegAddrZipName: '',
                    CompRegAddress: '',
                    PrOnboardDay: '',
                    PrOnboardName: '',
                    ExPrOnboardDay: '',
                    ExPrOnboardName: '',
                    ExPrOnboardDay2: '',
                    ExPrOnboardName2: '',
                    BizRegAddrCityName: '',
                    BizRegAddrAreaName: '',
                    BizRegAddrRoadName: '',
                    BizRegAddrRoadType: '',
                    BizRegAddrSec: '',
                    BizRegAddrLn: '',
                    BizRegAddrAly: '',
                    BizRegAddrNo: '',
                    BizRegAddrNoExt: '',
                    BizRegAddrFloor: '',
                    BizRegAddrFloorExt: '',
                    BizRegAddrRoom: '',
                    BizRegAddrOtherMemo: '',
                    LastPaidInCapitalDate: '',
                    DirectorAName: '',
                    DirectorAId: '',
                    DirectorBName: '',
                    DirectorBId: '',
                    DirectorCName: '',
                    DirectorCId: '',
                    DirectorDName: '',
                    DirectorDId: '',
                    DirectorEName: '',
                    DirectorEId: '',
                    DirectorFName: '',
                    DirectorFId: '',
                    DirectorGName: '',
                    DirectorGId: '',
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
