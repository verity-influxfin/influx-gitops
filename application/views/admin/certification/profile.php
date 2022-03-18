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
                                            <a @click="changeSubTab('Spouse')" data-toggle="tab" aria-expanded="false">配偶</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuOne')" data-toggle="tab" aria-expanded="false">保證人甲</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuTwo')" data-toggle="tab" aria-expanded="false">保證人乙</a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive Pr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrStartYear" placeholder="格式:YYYY">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人學歷</span></td>
                                                    <td>
                                                        <select name="PrEduLevel" class="table-input form-control">
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
                                    <div class="table-responsive Spouse">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶是否擔任本案保證人</span></td>
                                                    <td>
                                                        <select name="IsPrSpouseGu" class="table-input sk-input form-control">
                                                            <option value="A">1:是</option>
                                                            <option value="B">2:否</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="OthRealPrRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrStartYear"
                                                            placeholder="格式:YYYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_擔任本公司職務</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrTitle"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_持股比率%</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrSHRatio"></td>
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
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲_任職公司</span></td>
                                                    <td>
                                                        <select name="GuOneCompany" class="table-input sk-input form-control">
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
                                                    <td><span>保證人甲_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="GuOneRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="OthRealPrRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrStartYear"
                                                            placeholder="格式:YYYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_擔任本公司職務</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrTitle"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_持股比率%</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrSHRatio"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive GuTwo">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙_任職公司</span></td>
                                                    <td>
                                                        <select name="GuTwoCompany" class="table-input sk-input form-control">
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
                                                    <td><span>保證人乙_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="GuTwoRelWithPr" class="table-input sk-input form-control">
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
                                            <a @click="changeSubTab('Spouse')" data-toggle="tab" aria-expanded="false">配偶</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuOne')" data-toggle="tab" aria-expanded="false">保證人甲</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuTwo')" data-toggle="tab" aria-expanded="false">保證人乙</a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive Pr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="PrStartYear" placeholder="格式:YYYY">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人學歷</span></td>
                                                    <td>
                                                        <select name="PrEduLevel" class="table-input form-control">
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
                                                    <td><span>costom 2</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.tab2Input">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive Spouse">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="SpouseMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶是否擔任本案保證人</span></td>
                                                    <td>
                                                        <select name="IsPrSpouseGu" class="table-input sk-input form-control">
                                                            <option value="A">1:是</option>
                                                            <option value="B">2:否</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="OthRealPrRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrStartYear"
                                                            placeholder="格式:YYYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_擔任本公司職務</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrTitle"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_持股比率%</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrSHRatio"></td>
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
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuOneMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人甲_任職公司</span></td>
                                                    <td>
                                                        <select name="GuOneCompany" class="table-input sk-input form-control">
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
                                                    <td><span>保證人甲_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="GuOneRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="OthRealPrRelWithPr" class="table-input sk-input form-control">
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
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_從事本行業年度</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrStartYear"
                                                            placeholder="格式:YYYY"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_擔任本公司職務</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrTitle"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>實際負責(經營)人_其他實際負責經營人_持股比率%</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="OthRealPrSHRatio"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive GuTwo">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                                <tr style="text-align: center;">
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                                </tr>
                                                <tr hidden>
                                                    <td><span>徵提資料ID</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="id"
                                                            value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-郵遞區號</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurAddrZip"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-郵遞區號名稱</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurAddrZipName"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙現居地址-非郵遞地址資料</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoCurlAddress"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-區碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelAreaCode"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-電話號碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡電話-分機碼</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoTelExt"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙連絡行動電話</span></td>
                                                    <td><input class="sk-input form-control" type="text" name="GuTwoMobileNo"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人乙_任職公司</span></td>
                                                    <td>
                                                        <select name="GuTwoCompany" class="table-input sk-input form-control">
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
                                                    <td><span>保證人乙_與借戶負責人之關係</span></td>
                                                    <td>
                                                        <select name="GuTwoRelWithPr" class="table-input sk-input form-control">
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
                    PrCurAddrZip: '',
                    PrCurAddrZipName: '',
                    PrCurlAddress: '',
                    PrTelAreaCode: '',
                    PrTelNo: '',
                    PrTelExt: '',
                    PrMobileNo: '',
                    PrStartYear: '',
                    PrEduLevel: '',
                    SpouseCurAddrZip: '',
                    SpouseCurAddrZipName: '',
                    SpouseCurlAddress: '',
                    SpouseTelAreaCode: '',
                    SpouseTelNo: '',
                    SpouseTelExt: '',
                    SpouseMobileNo: '',
                    IsPrSpouseGu: '',
                    OthRealPrRelWithPr: '',
                    OthRealPrStartYear: '',
                    OthRealPrTitle: '',
                    OthRealPrSHRatio: '',
                    GuOneCurAddrZip: '',
                    GuOneCurAddrZipName: '',
                    GuOneCurlAddress: '',
                    GuOneTelAreaCode: '',
                    GuOneTelNo: '',
                    GuOneTelExt: '',
                    GuOneMobileNo: '',
                    GuOneCompany: '',
                    GuOneRelWithPr: '',
                    OthRealPrRelWithPr: '',
                    OthRealPrStartYear: '',
                    OthRealPrTitle: '',
                    OthRealPrSHRatio: '',
                    GuTwoCurAddrZip: '',
                    GuTwoCurAddrZipName: '',
                    GuTwoCurlAddress: '',
                    GuTwoTelAreaCode: '',
                    GuTwoTelNo: '',
                    GuTwoTelExt: '',
                    GuTwoMobileNo: '',
                    GuTwoCompany: '',
                    GuTwoRelWithPr: '',
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
