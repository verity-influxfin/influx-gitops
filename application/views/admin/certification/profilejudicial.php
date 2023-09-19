<style>
    .sk-input form-control {
        width: 100%;
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
                                        <td><span>企業聯絡人電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactTel"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業聯絡人分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人職稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContact"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司員工人數</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model.number="formData.employeeNum"></td>
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
                                        <td><span>是否曾有信用瑕疵紀錄</span></td>
                                        <td><select v-model="formData.hasCreditFlaws" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年平均員工人數是否高過200人</span></td>
                                        <td><select v-model="formData.lastOneYearOver200employees" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><span>傳真號碼</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compFax"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerTel"></td>
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
                                        <td><input class="sk-input form-control" type="number" v-model="formData.stockholderNum"></td>
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
                                        <td><select v-model="formData.goPublicPlan" class="table-input sk-input form-control">
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
                                        <td><span>企業專業證照/專利</span></td>
                                        <td><select v-model="formData.hasLicence" class="table-input sk-input form-control">
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
                                        <td><span>實際營業地址是否等於營業登記地址</span></td>
                                        <td><select v-model="formData.isBizAddrEqToBizRegAddr" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddress"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_縣市</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressCity"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_鄉鎮市區</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressArea"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_路街名稱(不含路、街)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoad"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_路 OR 街</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoadType"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_段</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressSec"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_巷</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressLn"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_弄</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressAly"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_號(不含之號)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressNo"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_之號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressNoExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_樓(不含之樓、室)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressFloor"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_之樓</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressFloorExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_室</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoom"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址是否自有</span></td>
                                        <td><select v-model="formData.realBizAddressOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址所有權</span></td>
                                        <td><select v-model="formData.realBizAddrOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:負責人</option>
                                                <option :value="'B'">B:負責人配偶</option>
                                                <option :value="'C'">C:企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址是否自有</span></td>
                                        <td><select v-model="formData.realBizRegAddressOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址所有權</span></td>
                                        <td><select v-model="formData.bizRegAddrOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:負責人</option>
                                                <option :value="'B'">B:負責人配偶</option>
                                                <option :value="'C'">C:企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有關係企業</span></td>
                                        <td><select v-model="formData.hasRelatedCompany" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompAName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompAGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompAType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompARelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompBGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompBName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompBType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompBRelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompCName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompCGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompCType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompCRelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司型態</span></td>
                                        <td><select v-model="formData.compType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'41'">41:獨資</option>
                                                <option :value="'21'">21:中小企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司產業別</span></td>
                                        <td><select v-model="formData.compDuType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'01'">01:水泥</option>
                                                <option :value="'02'">02:食品</option>
                                                <option :value="'03'">03:塑膠</option>
                                                <option :value="'06'">06:電器</option>
                                                <option :value="'07'">07:化學</option>
                                                <option :value="'08'">08:玻璃(陶瓷)</option>
                                                <option :value="'09'">09:通訊</option>
                                                <option :value="'10'">10:鋼鐵</option>
                                                <option :value="'11'">11:橡膠</option>
                                                <option :value="'12'">12:汽車</option>
                                                <option :value="'13'">13:服務業</option>
                                                <option :value="'14'">14:飯店百貨</option>
                                                <option :value="'17'">17:DRAM 製造</option>
                                                <option :value="'18'">18:DRAM 模組</option>
                                                <option :value="'19'">19:IC 設計</option>
                                                <option :value="'20'">20:晶圓代工</option>
                                                <option :value="'21'">21:IC 封測</option>
                                                <option :value="'22'">22:TFT-LCD</option>
                                                <option :value="'23'">23:主機板</option>
                                                <option :value="'24'">24:光碟片</option>
                                                <option :value="'25'">25:光碟機</option>
                                                <option :value="'26'">26:網路通訊</option>
                                                <option :value="'27'">27:連接器</option>
                                                <option :value="'28'">28:伺服器</option>
                                                <option :value="'29'">29:軟體業</option>
                                                <option :value="'30'">30:掃描器</option>
                                                <option :value="'31'">31:印表機</option>
                                                <option :value="'32'">32:機殼業</option>
                                                <option :value="'33'">33:手機業</option>
                                                <option :value="'34'">34:電腦組裝</option>
                                                <option :value="'35'">35:電腦週邊</option>
                                                <option :value="'36'">36:筆記型電腦</option>
                                                <option :value="'37'">37:顯示(監視)器</option>
                                                <option :value="'38'">38:印刷電路板</option>
                                                <option :value="'39'">39:被動元件</option>
                                                <option :value="'40'">40:數位相機</option>
                                                <option :value="'41'">41:電源供應器</option>
                                                <option :value="'42'">42:LED</option>
                                                <option :value="'43'">43:工業電腦</option>
                                                <option :value="'44'">44:IC 通路</option>
                                                <option :value="'45'">45:資訊(3C)通路</option>
                                                <option :value="'46'">46:安全監控</option>
                                                <option :value="'47'">47:FLASH(模組)</option>
                                                <option :value="'48'">48:觸控面板</option>
                                                <option :value="'49'">49:散熱模組</option>
                                                <option :value="'50'">50:背光模組</option>
                                                <option :value="'51'">51:電池模組</option>
                                                <option :value="'52'">52:農業</option>
                                                <option :value="'53'">53:林業</option>
                                                <option :value="'54'">54:漁業</option>
                                                <option :value="'55'">55:畜牧業</option>
                                                <option :value="'56'">56:大宗物資業</option>
                                                <option :value="'57'">57:人造纖維業</option>
                                                <option :value="'58'">58:紡紗業</option>
                                                <option :value="'59'">59:織布業</option>
                                                <option :value="'60'">60:成衣業</option>
                                                <option :value="'61'">61:皮革皮毛業</option>
                                                <option :value="'62'">62:染整業</option>
                                                <option :value="'63'">63:電線電纜業</option>
                                                <option :value="'64'">64:機電機械業</option>
                                                <option :value="'65'">65:工具機</option>
                                                <option :value="'66'">66:非鐵金屬業</option>
                                                <option :value="'67'">67:海運</option>
                                                <option :value="'68'">68:空運</option>
                                                <option :value="'69'">69:陸運</option>
                                                <option :value="'70'">70:倉儲物流業</option>
                                                <option :value="'71'">71:建設(開發)</option>
                                                <option :value="'72'">72:營造(工程)</option>
                                                <option :value="'73'">73:建材業</option>
                                                <option :value="'74'">74:金控(銀行)</option>
                                                <option :value="'75'">75:保險</option>
                                                <option :value="'76'">76:證券</option>
                                                <option :value="'77'">77:投資公司</option>
                                                <option :value="'78'">78:其他金融業</option>
                                                <option :value="'79'">79:太陽能</option>
                                                <option :value="'80'">80:自行車</option>
                                                <option :value="'81'">81:生技醫療</option>
                                                <option :value="'82'">82:鐘錶眼鏡業</option>
                                                <option :value="'83'">83:影音通路</option>
                                                <option :value="'84'">84:電信業</option>
                                                <option :value="'85'">85:印刷業</option>
                                                <option :value="'86'">86:出版業</option>
                                                <option :value="'87'">87:製鞋業</option>
                                                <option :value="'88'">88:油電燃氣業</option>
                                                <option :value="'89'">89:有線電視</option>
                                                <option :value="'90'">90:機車業</option>
                                                <option :value="'91'">91:運動用品業</option>
                                                <option :value="'92'">92:餐飲業</option>
                                                <option :value="'93'">93:觀光旅遊業</option>
                                                <option :value="'94'">94:資源回收業</option>
                                                <option :value="'95'">95:量販、超市、便利商店</option>
                                                <option :value="'96'">96:輪胎業</option>
                                                <option :value="'97'">97:休閒娛樂</option>
                                                <option :value="'98'">98:家具業</option>
                                                <option :value="'99'">99:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人行動電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prMobileNo"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prEmail"></td>
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
                                        <td><span>企業聯絡人電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactTel"></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業聯絡人分機</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContactExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>聯絡人職稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compContact"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司員工人數</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model.number="formData.employeeNum"></td>
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
                                        <td><span>是否曾有信用瑕疵紀錄</span></td>
                                        <td><select v-model="formData.hasCreditFlaws" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年平均員工人數是否高過200人</span></td>
                                        <td><select v-model="formData.lastOneYearOver200employees" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>企業Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><span>傳真號碼</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.compFax"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管姓名</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>財務主管電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.financialOfficerTel"></td>
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
                                        <td><input class="sk-input form-control" type="number" v-model="formData.stockholderNum"></td>
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
                                        <td><select v-model="formData.goPublicPlan" class="table-input sk-input form-control">
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
                                        <td><span>企業專業證照/專利</span></td>
                                        <td><select v-model="formData.hasLicence" class="table-input sk-input form-control">
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
                                        <td><span>實際營業地址是否等於營業登記地址</span></td>
                                        <td><select v-model="formData.isBizAddrEqToBizRegAddr" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddress"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_縣市</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressCity"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_鄉鎮市區</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressArea"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_路街名稱(不含路、街)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoad"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_路 OR 街</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoadType"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_段</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressSec"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_巷</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressLn"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_弄</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressAly"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_號(不含之號)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressNo"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_之號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressNoExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_樓(不含之樓、室)</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressFloor"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_之樓</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressFloorExt"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址_室</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.realBizAddressRoom"></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址是否自有</span></td>
                                        <td><select v-model="formData.realBizAddressOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>實際營業地址所有權</span></td>
                                        <td><select v-model="formData.realBizAddrOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:負責人</option>
                                                <option :value="'B'">B:負責人配偶</option>
                                                <option :value="'C'">C:企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址是否自有</span></td>
                                        <td><select v-model="formData.realBizRegAddressOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>營業登記地址所有權</span></td>
                                        <td><select v-model="formData.bizRegAddrOwner" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:負責人</option>
                                                <option :value="'B'">B:負責人配偶</option>
                                                <option :value="'C'">C:企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>是否有關係企業</span></td>
                                        <td><select v-model="formData.hasRelatedCompany" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'1'">1:是</option>
                                                <option :value="'0'">0:否</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompAName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompAGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompAType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(A)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompARelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompBGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompBName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompBType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(B)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompBRelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)名稱</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompCName"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)統一編號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.relatedCompCGuiNumber"></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)組織型態</span></td>
                                        <td><select v-model="formData.relatedCompCType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:獨資</option>
                                                <option :value="'B'">B:合夥</option>
                                                <option :value="'C'">C:有限公司</option>
                                                <option :value="'D'">D:股份有限公司</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>關係企業(C)與申請人之關係</span></td>
                                        <td><select v-model="formData.relatedCompCRelationship" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'A'">A:有下列關係之一(相同負責人、負責人互為配偶、負責人互為二親等內血親)</option>
                                                <option :value="'B'">B:相同股東出資額均>=40%</option>
                                                <option :value="'C'">C:轉投資之投資額>=40%</option>
                                                <option :value="'D'">D:營業場所相同</option>
                                                <option :value="'E'">E:營業場所有租賃關係</option>
                                                <option :value="'F'">F:相同總經理</option>
                                                <option :value="'G'">G:相同財務主管</option>
                                                <option :value="'H'">H:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司型態</span></td>
                                        <td><select v-model="formData.compType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'41'">41:獨資</option>
                                                <option :value="'21'">21:中小企業</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司產業別</span></td>
                                        <td><select v-model="formData.compDuType" class="table-input sk-input form-control">
                                                <option :value="''"></option>
                                                <option :value="'01'">01:水泥</option>
                                                <option :value="'02'">02:食品</option>
                                                <option :value="'03'">03:塑膠</option>
                                                <option :value="'06'">06:電器</option>
                                                <option :value="'07'">07:化學</option>
                                                <option :value="'08'">08:玻璃(陶瓷)</option>
                                                <option :value="'09'">09:通訊</option>
                                                <option :value="'10'">10:鋼鐵</option>
                                                <option :value="'11'">11:橡膠</option>
                                                <option :value="'12'">12:汽車</option>
                                                <option :value="'13'">13:服務業</option>
                                                <option :value="'14'">14:飯店百貨</option>
                                                <option :value="'17'">17:DRAM 製造</option>
                                                <option :value="'18'">18:DRAM 模組</option>
                                                <option :value="'19'">19:IC 設計</option>
                                                <option :value="'20'">20:晶圓代工</option>
                                                <option :value="'21'">21:IC 封測</option>
                                                <option :value="'22'">22:TFT-LCD</option>
                                                <option :value="'23'">23:主機板</option>
                                                <option :value="'24'">24:光碟片</option>
                                                <option :value="'25'">25:光碟機</option>
                                                <option :value="'26'">26:網路通訊</option>
                                                <option :value="'27'">27:連接器</option>
                                                <option :value="'28'">28:伺服器</option>
                                                <option :value="'29'">29:軟體業</option>
                                                <option :value="'30'">30:掃描器</option>
                                                <option :value="'31'">31:印表機</option>
                                                <option :value="'32'">32:機殼業</option>
                                                <option :value="'33'">33:手機業</option>
                                                <option :value="'34'">34:電腦組裝</option>
                                                <option :value="'35'">35:電腦週邊</option>
                                                <option :value="'36'">36:筆記型電腦</option>
                                                <option :value="'37'">37:顯示(監視)器</option>
                                                <option :value="'38'">38:印刷電路板</option>
                                                <option :value="'39'">39:被動元件</option>
                                                <option :value="'40'">40:數位相機</option>
                                                <option :value="'41'">41:電源供應器</option>
                                                <option :value="'42'">42:LED</option>
                                                <option :value="'43'">43:工業電腦</option>
                                                <option :value="'44'">44:IC 通路</option>
                                                <option :value="'45'">45:資訊(3C)通路</option>
                                                <option :value="'46'">46:安全監控</option>
                                                <option :value="'47'">47:FLASH(模組)</option>
                                                <option :value="'48'">48:觸控面板</option>
                                                <option :value="'49'">49:散熱模組</option>
                                                <option :value="'50'">50:背光模組</option>
                                                <option :value="'51'">51:電池模組</option>
                                                <option :value="'52'">52:農業</option>
                                                <option :value="'53'">53:林業</option>
                                                <option :value="'54'">54:漁業</option>
                                                <option :value="'55'">55:畜牧業</option>
                                                <option :value="'56'">56:大宗物資業</option>
                                                <option :value="'57'">57:人造纖維業</option>
                                                <option :value="'58'">58:紡紗業</option>
                                                <option :value="'59'">59:織布業</option>
                                                <option :value="'60'">60:成衣業</option>
                                                <option :value="'61'">61:皮革皮毛業</option>
                                                <option :value="'62'">62:染整業</option>
                                                <option :value="'63'">63:電線電纜業</option>
                                                <option :value="'64'">64:機電機械業</option>
                                                <option :value="'65'">65:工具機</option>
                                                <option :value="'66'">66:非鐵金屬業</option>
                                                <option :value="'67'">67:海運</option>
                                                <option :value="'68'">68:空運</option>
                                                <option :value="'69'">69:陸運</option>
                                                <option :value="'70'">70:倉儲物流業</option>
                                                <option :value="'71'">71:建設(開發)</option>
                                                <option :value="'72'">72:營造(工程)</option>
                                                <option :value="'73'">73:建材業</option>
                                                <option :value="'74'">74:金控(銀行)</option>
                                                <option :value="'75'">75:保險</option>
                                                <option :value="'76'">76:證券</option>
                                                <option :value="'77'">77:投資公司</option>
                                                <option :value="'78'">78:其他金融業</option>
                                                <option :value="'79'">79:太陽能</option>
                                                <option :value="'80'">80:自行車</option>
                                                <option :value="'81'">81:生技醫療</option>
                                                <option :value="'82'">82:鐘錶眼鏡業</option>
                                                <option :value="'83'">83:影音通路</option>
                                                <option :value="'84'">84:電信業</option>
                                                <option :value="'85'">85:印刷業</option>
                                                <option :value="'86'">86:出版業</option>
                                                <option :value="'87'">87:製鞋業</option>
                                                <option :value="'88'">88:油電燃氣業</option>
                                                <option :value="'89'">89:有線電視</option>
                                                <option :value="'90'">90:機車業</option>
                                                <option :value="'91'">91:運動用品業</option>
                                                <option :value="'92'">92:餐飲業</option>
                                                <option :value="'93'">93:觀光旅遊業</option>
                                                <option :value="'94'">94:資源回收業</option>
                                                <option :value="'95'">95:量販、超市、便利商店</option>
                                                <option :value="'96'">96:輪胎業</option>
                                                <option :value="'97'">97:休閒娛樂</option>
                                                <option :value="'98'">98:家具業</option>
                                                <option :value="'99'">99:其他</option>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人行動電話</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prMobileNo"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負責人Email</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.prEmail"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <label>備註</label>
                                <?php $fail = '';
                                if ( ! empty($remark['fail'])) {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark['fail'] . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
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
                                            <?php } ?>
                                            <option value="other" <?= $fail_other ? 'selected' : ''; ?>>其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail"
                                               value="<?= $fail; ?>"
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
                                    <label>土地所有權狀</label><br>
                                    <? isset($content['BizLandOwnership']) && !is_array($content['BizLandOwnership']) ? $content['BizLandOwnership'] = array($content['BizLandOwnership']) : '';
                                        if(!empty($content['BizLandOwnership'])){
                                            foreach ($content['BizLandOwnership'] as $key => $value) { ?>
                                                <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                    <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                                </a><br>
                                            <? }
                                        }?>
                                    <hr/>
                                    <label>建物所有權狀</label><br>
                                    <? isset($content['BizHouseOwnership']) && !is_array($content['BizHouseOwnership']) ? $content['BizHouseOwnership'] = array($content['BizHouseOwnership']) : '';
                                    if(!empty($content['BizHouseOwnership'])){
                                        foreach ($content['BizHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <?}
                                    }?>
                                    <hr/>
                                    <label>實際土地所有權狀</label><br>
                                    <? isset($content['RealLandOwnership']) && !is_array($content['RealLandOwnership']) ? $content['RealLandOwnership'] = array($content['RealLandOwnership']) : '';
                                    if(!empty($content['RealLandOwnership'])){
                                        foreach ($content['RealLandOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <? }
                                    }?>
                                    <hr/>
                                    <label>實際建物所有權狀</label><br>
                                    <? isset($content['RealHouseOwnership']) && !is_array($content['RealHouseOwnership']) ? $content['RealHouseOwnership'] = array($content['RealHouseOwnership']) : '';
                                    if(!empty($content['RealHouseOwnership'])){
                                        foreach ($content['RealHouseOwnership'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a><br>
                                        <? }
                                    }?>
                                    <hr/>
                                    <label>其它</label><br>
                                    <?php
                                    if ( ! empty($content['other_image']) && is_array($content['other_image']))
                                    {
                                        foreach ($content['other_image'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    } ?>
                                    <br/>
                                    <?php
                                    if ( ! empty($content['pdf']) && is_array($content['pdf']))
                                    {
                                        $index = 0;
                                        foreach ($content['pdf'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" class="btn btn-info">
                                                檔案<?= ++$index; ?>
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
                pageId:'',
                formData: {
                    compContactName: '',
                    compContact: '',
                    compContactTel: '',
                    compEmail: '',
                    compFax: '',
                    compContactExt: '',
                    financialOfficerName: '',
                    financialOfficerTel: '',
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
                    isBizAddrEqToBizRegAddr: '',
                    realBizAddress: '',
                    realBizRegAddressOwner: '',
                    bizRegAddrOwner: '',
                    realBizAddressOwner: '',
                    realBizAddrOwner: '',
                    hasRelatedCompany: '',
                    relatedCompAName: '',
                    relatedCompAGuiNumber: '',
                    relatedCompAType: '',
                    relatedCompARelationship: '',
                    relatedCompBGuiNumber: '',
                    relatedCompBName: '',
                    relatedCompBType: '',
                    relatedCompBRelationship: '',
                    relatedCompCName: '',
                    relatedCompCGuiNumber: '',
                    relatedCompCType: '',
                    relatedCompCRelationship: '',
                    compType: '',
                    compDuType: '',
                    prMobileNo: '',
                    prEmail: '',

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
                let selector = this.$el;
                $(selector).find('button').attr('disabled', true).text('資料更新中...');
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
