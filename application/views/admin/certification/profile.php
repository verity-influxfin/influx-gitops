<style>
    .sk-input form-control {
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
            <h1 class="page-header"><?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ''; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ''; ?>
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
                                                <tr style="text-align: center;"><td colspan="2"><span>普匯微企e秒貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>負責人現居地址-郵遞區號</span></td><td><input class="sk-input zipcode" type="text" name="PrCurAddrZip"></td></tr>
                                                <tr><td><span>負責人現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="PrCurAddrZipName"></td></tr>
                                                <tr><td><span>負責人現居地址-非郵遞地址資料</span></td><td><input class="sk-input address" type="text" name="PrCurlAddress"></td></tr>
                                                <tr><td><span>負責人連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="PrTelAreaCode"></td></tr>
                                                <tr><td><span>負責人連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="PrTelNo"></td></tr>
                                                <tr><td><span>負責人連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="PrTelExt"></td></tr>
                                                <tr><td><span>負責人連絡行動電話</span></td><td><input class="sk-input" type="text" name="PrMobileNo"></td></tr>
                                                <tr><td><span>負責人從事本行業年度</span></td><td><input class="sk-input" type="text" name="PrStartYear" placeholder="格式:YYYY"></td></tr>
                                                <tr><td><span>負責人學歷</span></td><td>
                                                    <select name="PrEduLevel" class="table-input sk-input">
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
                                                <td><span>實際負責人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select v-model="formData.prRelationship" class="table-input sk-input form-control">
                                                        <option :value="'A'">A:配偶</option>
                                                        <option :value="'B'">B:血親</option>
                                                        <option :value="'C'">C:姻親</option>
                                                        <option :value="'D'">D:股東</option>
                                                        <option :value="'E'">E:朋友</option>
                                                        <option :value="'F'">F:本人</option>
                                                        <option :value="'G'">G:其他</option>
                                                        <option :value="'H'">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人從事本行業年度-起始</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人從事本行業年度-結束</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrEndYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人擔任公司職務</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrTitle"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人持股比率(%)</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrSHRatio"
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
                                                    <select v-model="formData.hasGuarantor" class="table-input sk-input form-control">
                                                        <option :value="1">1:是</option>
                                                        <option :value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶是否擔任保證人</span></td>
                                                <td>
                                                    <select v-model="formData.isPrSpouseGu" class="table-input sk-input form-control">
                                                        <option :value="1">1:是</option>
                                                        <option :value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select v-model="formData.guOneRelWithPr" class="table-input sk-input form-control">
                                                        <option :value="'A'">A:配偶</option>
                                                        <option :value="'B'">B:血親</option>
                                                        <option :value="'C'">C:姻親</option>
                                                        <option :value="'D'">D:股東</option>
                                                        <option :value="'E'">E:朋友</option>
                                                        <option :value="'F'">F:本人</option>
                                                        <option :value="'G'">G:其他</option>
                                                        <option :value="'H'">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人任職公司</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneCompany"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人從事本行業年度-起始</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人從事本行業年度-結束</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneEndYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人擔任公司職務</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneTitle"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人持股比率(%)</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneSHRatio"
                                                           placeholder="請輸入數字部分即可"></td>
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
                                                <tr style="text-align: center;"><td colspan="2"><span>普匯微企e秒貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>配偶現居地址-郵遞區號</span></td><td><input class="sk-input zipcode" type="text" name="SpouseCurAddrZip"></td></tr>
                                                <tr><td><span>配偶現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="SpouseCurAddrZipName"></td></tr>
                                                <tr><td><span>配偶現居地址-非郵遞地址資料</span></td><td><input class="sk-input address" type="text" name="SpouseCurlAddress"></td></tr>
                                                <tr><td><span>配偶連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="SpouseTelAreaCode"></td></tr>
                                                <tr><td><span>配偶連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="SpouseTelNo"></td></tr>
                                                <tr><td><span>配偶連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="SpouseTelExt"></td></tr>
                                                <tr><td><span>配偶連絡行動電話</span></td><td><input class="sk-input" type="text" name="SpouseMobileNo"></td></tr>
                                                <tr><td><span>配偶是否擔任本案保證人</span></td><td>
                                                    <select name="IsPrSpouseGu" class="table-input sk-input">
                                                        <option value="1">1:是</option>
                                                        <option value="0">0:否</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>個人不動產設定情形</span></td>
                                                <td>
                                                    <select v-model="formData.realEstateMortgage" class="table-input form-control">
                                                        <option :value="''">請選擇</option>
                                                        <option :value="'1'">1:有</option>
                                                        <option :value="'0'">0:無</option>
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
                                                <tr style="text-align: center;"><td colspan="2"><span>普匯微企e秒貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>保證人甲現居地址-郵遞區號</span></td><td><input class="sk-input zipcode" type="text" name="GuOneCurAddrZip"></td></tr>
                                                <tr><td><span>保證人甲現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="GuOneCurAddrZipName"></td></tr>
                                                <tr><td><span>保證人甲現居地址-非郵遞地址資料</span></td><td><input class="sk-input address" type="text" name="GuOneCurlAddress"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="GuOneTelAreaCode"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="GuOneTelNo"></td></tr>
                                                <tr><td><span>保證人甲連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="GuOneTelExt"></td></tr>
                                                <tr><td><span>保證人甲連絡行動電話</span></td><td><input class="sk-input" type="text" name="GuOneMobileNo"></td></tr>
                                                <tr><td><span>保證人甲_任職公司</span></td><td>
                                                    <select name="GuOneCompany" class="table-input sk-input">
                                                        <option value="A">A:公家機關</option>
                                                        <option value="B">B:上市櫃公司</option>
                                                        <option value="C">C:專業人士</option>
                                                        <option value="D">D:借戶</option>
                                                        <option value="E">E:其他民營企業</option>
                                                        <option value="F">F:無</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>實際負責人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select v-model="formData.prRelationship" class="table-input sk-input form-control">
                                                        <option :value="'A'">A:配偶</option>
                                                        <option :value="'B'">B:血親</option>
                                                        <option :value="'C'">C:姻親</option>
                                                        <option :value="'D'">D:股東</option>
                                                        <option :value="'E'">E:朋友</option>
                                                        <option :value="'F'">F:本人</option>
                                                        <option :value="'G'">G:其他</option>
                                                        <option :value="'H'">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人從事本行業年度</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人從事本行業年度-結束</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrEndYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人擔任公司職務</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrTitle"></td>
                                            </tr>
                                            <tr>
                                                <td><span>其他實際負責人持股比率(%)</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.othRealPrSHRatio"
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
                                                <tr style="text-align: center;"><td colspan="2"><span>普匯微企e秒貸資料確認</span></td></tr>
                                                <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                <tr><td><span>保證人乙現居地址-郵遞區號</span></td><td><input class="sk-input zipcode" type="text" name="GuTwoCurAddrZip"></td></tr>
                                                <tr><td><span>保證人乙現居地址-郵遞區號名稱</span></td><td><input class="sk-input" type="text" name="GuTwoCurAddrZipName"></td></tr>
                                                <tr><td><span>保證人乙現居地址-非郵遞地址資料</span></td><td><input class="sk-input address" type="text" name="GuTwoCurlAddress"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-區碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelAreaCode"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-電話號碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelNo"></td></tr>
                                                <tr><td><span>保證人乙連絡電話-分機碼</span></td><td><input class="sk-input" type="text" name="GuTwoTelExt"></td></tr>
                                                <tr><td><span>保證人乙連絡行動電話</span></td><td><input class="sk-input" type="text" name="GuTwoMobileNo"></td></tr>
                                                <tr><td><span>保證人乙_任職公司</span></td><td>
                                                    <select name="GuTwoCompany" class="table-input sk-input">
                                                        <option value="A">A:公家機關</option>
                                                        <option value="B">B:上市櫃公司</option>
                                                        <option value="C">C:專業人士</option>
                                                        <option value="D">D:借戶</option>
                                                        <option value="E">E:其他民營企業</option>
                                                        <option value="F">F:無</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人與借戶、負責人之關係</span></td>
                                                <td>
                                                    <select v-model="formData.guOneRelWithPr" class="table-input sk-input form-control">
                                                        <option :value="'A'">A:配偶</option>
                                                        <option :value="'B'">B:血親</option>
                                                        <option :value="'C'">C:姻親</option>
                                                        <option :value="'D'">D:股東</option>
                                                        <option :value="'E'">E:朋友</option>
                                                        <option :value="'F'">F:本人</option>
                                                        <option :value="'G'">G:其他</option>
                                                        <option :value="'H'">H:與經營有關之借戶職員</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人任職公司</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneCompany"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人從事本行業年度-起始</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneStartYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人從事本行業年度-結束</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneEndYear"
                                                           placeholder="格式:YYY"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人擔任公司職務</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneTitle"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人持股比率(%)</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guOneSHRatio"
                                                           placeholder="請輸入數字部分即可"></td>
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
                                <?php $fail = '';
                                if ( ! empty($remark['fail']))
                                {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
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
                                            <option value="other" <?= $fail_other ? 'selected' : ''; ?>>其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail"
                                               value="<?= $fail; ?>"
                                               style="background-color:white!important;display:none" disabled>
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset>
                                <div class="form-group">
                                    <label>個人基本資料</label><br>
                                    <?php
                                    if ( ! empty($content['profile_image']))
                                    {
                                        foreach ($content['profile_image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style="width:30%;max-width:400px">
                                            </a>
                                        <?php }
                                    } ?>
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
                    prMobileNo: '',
                    prEmail: '',
                    prInChargeYear: '',
                    prInChargeYearEnd: '',
                    prStartYear: '',
                    prEndYear: '',
                    prEduLevel: '',
                    realEstateOwner: '',
                    realEstateAddress: '',
                    realEstateUsage: '',
                    realEstateMortgage: '',
                    isPrRegister: '',
                    prRelationship: '',
                    othRealPrStartYear: '',
                    othRealPrEndYear: '',
                    othRealPrTitle: '',
                    othRealPrSHRatio: '',
                    hasGuarantor: '',
                    isPrSpouseGu: '',
                    guOneRelWithPr: '',
                    guOneCompany: '',
                    guOneStartYear: '',
                    guOneEndYear: '',
                    guOneSHRatio: '',
                    guOneTitle: '',
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
                $("#app1").find('button').attr('disabled', true).text('資料更新中...');
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

                $.each($("input.address"), function (key, item) {
                    if ($(item).val()) {
                        $(item).trigger("change");
                    }
                })
            }
        },
    })
    $( "#skbank_form_tab :first-child :first-child" ).trigger( "click" );

    $(".address").on("change", function () {
        let address = $(this);
        $.ajax({
            type: 'GET',
            url: `https://zip5.5432.tw/zip5json.py?adrs=${address.val()}`,
            success: function (response) {
                address.parents(".dataTable").find("input.zipcode").val(response.zipcode.substring(0, 3));
            }
        });
    });
});
</script>
