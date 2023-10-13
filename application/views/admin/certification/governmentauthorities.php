<style>
    .sk-input {
        width : 100%;
    }
</style>
<script type="text/javascript">
    function check_fail() {
        if ($('#status :selected').val() === '2') {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        if ($(this).find(':selected').val() === 'other') {
            $('input#fail').css('display', 'block').attr('disabled', false);
        } else {
            $('input#fail').css('display', 'none').attr('disabled', true);
        }
    });

    $(document).ready(function () {
        check_fail();
        $('select#fail').trigger('change');
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
                                            <td><span>營業種類</span></td>
                                            <td>
                                                <select v-model="formData.businessTypeCode" class="table-input sk-input form-control">
                                                    <option value=""></option>
                                                    <option value="1">1:製造業 (08~34)</option>
                                                    <option value="2">2:買賣業 (45~48)</option>
                                                    <option value="3">3:服務業 (49~56/58~63/69~76/77~82/86~88/90~93/94~96)</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>公司行業別(主計處)</span></td>
                                            <td>
                                                <select v-model="formData.businessCycleCode" class="table-input sk-input form-control">
                                                    <option value=""></option>
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
                                            <td><span>公司所在地</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compRegAddress"></td>
                                        </tr>
                                        <tr>
                                            <td><span>統一編號</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compId"></td>
                                        </tr>
                                        <tr>
                                            <td><span>戳章日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compDate"></td>
                                        </tr>
                                        <tr>
                                            <td><span>負責人姓名</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.prName"></td>
                                        </tr>
                                        <tr>
                                            <td><span>組織類型</span></td>
                                            <td>
                                                <select v-model="formData.organizationType" class="table-input sk-input form-control">
                                                    <option value=""></option>
                                                    <option value="A">A:獨資</option>
                                                    <option value="B">B:合夥</option>
                                                    <option value="C">C:有限公司</option>
                                                    <option value="D">D:股份有限公司</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>核准設立日期</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.compSetDate"></td>
                                        </tr>
                                        <tr>
                                            <td><span>依法核准情形</span></td>
                                            <td>
                                                <select v-model="formData.registerType" class="table-input sk-input form-control">
                                                    <option value=""></option>
                                                    <option value="A">A:有公司登記與商業登記</option>
                                                    <option value="B">B:取得主管機關核發之營業證照</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>是否公開發行</span></td>
                                            <td>
                                                <select v-model="formData.isPublic" class="table-input sk-input form-control">
                                                    <option value=""></option>
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
                                            <td><input class="sk-input form-control" type="text" v-model="formData.lastPaidInCapitalDate"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddress"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_縣市</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressCity"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_鄉鎮市區</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressArea"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_路街名(不含路、街)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoad"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_路 OR 街</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoadType"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_段</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressSec"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_巷</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressLn"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_弄</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressAly"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_號(不含之號)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressNo"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_之號</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressNoExt"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_樓(不含之樓、室)</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressFloor"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_之樓</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressFloorExt"></td>
                                        </tr>
                                        <tr>
                                            <td><span>營業登記地址_室</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoom"></td>
                                        </tr>
                                        <tr>
                                            <td><span>是否有法人投資</span></td>
                                            <td>
                                                <select v-model="formData.hasJuridicalInvest" class="table-input sk-input form-control">
                                                    <option value=""></option>
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
                                                    <option value=""></option>
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
                                                    <option value=""></option>
                                                    <option value="1">1:是</option>
                                                    <option value="0">0:否</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php for ($i = ord('A'); $i <= ord('G'); $i++)
                                        { ?>
                                            <tr>
                                                <td><span>董監事<?= chr($i); ?>姓名</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.director<?= chr($i); ?>Name"></td>
                                            </tr>
                                            <tr>
                                                <td><span>董監事<?= chr($i); ?>統編</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.director<?= chr($i); ?>Id"></td>
                                            </tr>
                                        <?php } ?>
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
                                        <td><span>營業種類</span></td>
                                        <td>
                                            <select v-model="formData.businessTypeCode" class="table-input sk-input form-control">
                                                <option value=""></option>
                                                <option value="1">1:製造業 (08~34)</option>
                                                <option value="2">2:買賣業 (45~48)</option>
                                                <option value="3">3:服務業 (49~56/58~63/69~76/77~82/86~88/90~93/94~96)</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>公司行業別(主計處)</span></td>
                                        <td>
                                            <select v-model="formData.businessCycleCode" class="table-input sk-input form-control">
                                                <option value=""></option>
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
                                        <td><span>公司所在地</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compRegAddress"></td>
                                    </tr>
                                    <tr>
                                        <td><span>統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compId"></td>
                                    </tr>
                                    <tr>
                                        <td><span>戳章日期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compDate"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>組織類型</span></td>
                                        <td>
                                            <select v-model="formData.organizationType" class="table-input sk-input form-control">
                                                <option value=""></option>
                                                <option value="A">A:獨資</option>
                                                <option value="B">B:合夥</option>
                                                <option value="C">C:有限公司</option>
                                                <option value="D">D:股份有限公司</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>核准設立日期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compSetDate"></td>
                                    </tr>
                                    <tr>
                                        <td><span>依法核准情形</span></td>
                                        <td>
                                            <select v-model="formData.registerType" class="table-input sk-input form-control">
                                                <option value=""></option>
                                                <option value="A">A:有公司登記與商業登記</option>
                                                <option value="B">B:取得主管機關核發之營業證照</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>是否公開發行</span></td>
                                        <td>
                                            <select v-model="formData.isPublic" class="table-input sk-input form-control">
                                                <option value=""></option>
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
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastPaidInCapitalDate"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddress"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_縣市</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressCity"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_鄉鎮市區</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressArea"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_路街名(不含路、街)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoad"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_路 OR 街</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoadType"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_段</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressSec"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_巷</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressLn"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_弄</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressAly"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_號(不含之號)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressNo"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_之號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressNoExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_樓(不含之樓、室)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressFloor"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_之樓</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressFloorExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址_室</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.bizRegAddressRoom"></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有法人投資</span></td>
                                        <td>
                                            <select v-model="formData.hasJuridicalInvest" class="table-input sk-input form-control">
                                                <option value=""></option>
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
                                                <option value=""></option>
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
                                                <option value=""></option>
                                                <option value="1">1:是</option>
                                                <option value="0">0:否</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php for ($i = ord('A'); $i <= ord('G'); $i++)
                                    { ?>
                                        <tr>
                                            <td><span>董監事<?= chr($i); ?>姓名</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.director<?= chr($i); ?>Name"></td>
                                        </tr>
                                        <tr>
                                            <td><span>董監事<?= chr($i); ?>統編</span></td>
                                            <td><input class="sk-input form-control" type="text" v-model="formData.director<?= chr($i); ?>Id"></td>
                                        </tr>
                                    <?php } ?>
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
                                <?php $fail = '';
                                if ( ! empty($remark["fail"]))
                                {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
                                } ?>
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
                                            <?php $fail_other = TRUE;
                                            foreach ($certifications_msg[$data->certification_id] as $key => $value)
                                            {
                                                $this_option_selected = FALSE;
                                                if ($fail == $value)
                                                {
                                                    $fail_other = FALSE;
                                                    $this_option_selected = TRUE;
                                                } ?>
                                                <option
                                                    <?= $this_option_selected ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                            <option value="other" <?= $fail_other && ! empty($fail) ? 'selected' : ''; ?>>其它</option>
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
                            <fieldset>
                                <div class="form-group">
                                    <div class="row" style="width: 100%">
                                        <div class="col-lg-3">
                                            <label>設立(變更)事項登記表</label><br>
                                            <?php isset($content['governmentauthorities_image']) && !is_array($content['governmentauthorities_image']) ? $content['governmentauthorities_image'] = array($content['governmentauthorities_image']) : '';
                                            if(!empty($content['governmentauthorities_image'])){
                                                foreach ($content['governmentauthorities_image'] as $key => $value) { ?>
                                                    <a href="<?= $value ?? "" ?>" data-fancybox="images">
                                                        <img src="<?= $value ?: "" ?>" style='width:100%;'>
                                                    </a>
                                                <?php }
                                            }?>
                                            <hr/>
                                            <label>其它</label><br>
                                            <?php
                                            if ( ! empty($content['pdf']) && is_array($content['pdf']))
                                            {
                                                $index = 0;
                                                foreach ($content['pdf'] as $value)
                                                { ?>
                                                    <a href="<?= $value ?>" class="btn btn-info" style="margin: 1px 1px 1px 1px">
                                                        檔案<?= ++$index; ?>
                                                    </a>
                                                <?php }
                                            } ?>
                                        </div>
                                        <div class="col-lg-9">
                                            <label>商業司資料</label>
                                            <table class="table table-striped table-bordered table-hover dataTable">
                                                <tbody>
                                                <?php
                                                    foreach (($content['scraper']['DepartmentOfCommerce']['firstPageCompanyInfo'] ?? []) as $key => $value)
                                                    {
                                                        if(is_array($value))
                                                            continue;
                                                ?>
                                                        <tr>
                                                            <td><?=$key?></td><td><?=$value?></td>
                                                        </tr>
                                                <?php
                                                    }
                                                ?>
                                                </tbody>
                                            </table>
                                            <label class="mt-3">董監事持股狀況</label>
                                            <table class="table table-striped table-bordered table-hover dataTable">
                                                <tbody>
                                                <?php
                                                foreach (($content['scraper']['DepartmentOfCommerce']['firstPageDirectorInfo'] ?? []) as $key => $value)
                                                {
                                                ?>
                                                    <tr>
                                                        <td>姓名</td><td><?=$value['姓名']?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>職稱</td><td><?=$value['職稱']?></td>
                                                    </tr>
													<?php
													if (isset($value['持有股份數(股)']))
													{
													?>
                                                        <tr>
                                                            <td>持有股份數(股)</td><td><?=$value['持有股份數(股)']?></td>
                                                        </tr>
													<?php
													}
													?>
													<?php
													if (isset($value['出資額(元)']))
													{
													?>
														<tr>
															<td>出資額(元)</td><td><?=$value['出資額(元)']?></td>
														</tr>
                                                    <?php
													}
													?>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                            <label class="mt-3">財政部登記資訊</label>
                                            <?php if(isset($content['scraper']['MinistryOfFinance'])) { ?>
                                            <table class="table table-striped table-bordered table-hover dataTable">
                                                <tbody>
                                                    <tr><td>營業人統一編號</td><td><?=$content['scraper']['MinistryOfFinance']['businessId'] ?? ''?></td></tr>
                                                    <tr><td>營業人名稱</td><td><?=$content['scraper']['MinistryOfFinance']['nameOfBusinessEntity'] ?? ''?></td></tr>
                                                    <tr><td>營業（稅籍）登記地址</td><td><?=$content['scraper']['MinistryOfFinance']['address'] ?? ''?></td></tr>
                                                    <tr><td>資本額(元)</td><td><?=$content['scraper']['MinistryOfFinance']['amountOfCapital'] ?? ''?></td></tr>
                                                    <tr><td>組織種類</td><td><?=$content['scraper']['MinistryOfFinance']['organizationType'] ?? ''?></td></tr>
                                                    <tr><td>設立日期</td><td><?=$content['scraper']['MinistryOfFinance']['dateOfIncorporation'] ?? ''?></td></tr>
                                                    <tr><td>登記營業代碼</td><td><?=$content['scraper']['MinistryOfFinance']['industryCode'] ?? ''?></td></tr>
                                                    <tr><td>登記營業項目</td><td><?=$content['scraper']['MinistryOfFinance']['industryName'] ?? ''?></td></tr>
                                                    <tr><td>是否使用發票</td><td><?=$content['scraper']['MinistryOfFinance']['useOfUniformInvoice'] ?? ''?></td></tr>
                                                </tbody>
                                            </table>
                                            <?php } ?>
                                        </div>
                                    </div>
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
                    businessTypeCode: '',
                    businessCycleCode: '',
                    compName: '',
                    compRegAddress: '',
                    compId: '',
                    compDate: '',
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
                    directorAName: '',
                    directorAId: '',
                    directorBName: '',
                    directorBId: '',
                    directorCName: '',
                    directorCId: '',
                    directorDName: '',
                    directorDId: '',
                    directorEName: '',
                    directorEId: '',
                    directorFName: '',
                    directorFId: '',
                    directorGName: '',
                    directorGId: '',
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
                let selector = this.$el;
                $(selector).find('button').attr('disabled', true).text('資料更新中...');
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
