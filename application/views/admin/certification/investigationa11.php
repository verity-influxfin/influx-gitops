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
                                        if(defined('return_type')){
                                            echo $return_type;
                                        }elseif(isset($content['return_type'])){
                                            if($content['return_type'] == 1){
                                                echo '電子郵件';
                                            }else{
                                                echo '紙本';
                                            }
                                            echo $content['return_type'];
                                        }else{
                                            echo '';
                                        }
                                    ?>
                                </p>
                            </div>
                            <form id="app1" class="form-group" @submit.prevent="doSubmit">
                                <ul class="nav nav-tabs nav-justified mb-1">
                                    <li role="presentation" :class="{'active': tab ==='tab-skbank'}"><a @click="changeTab('tab-skbank')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-kgibank'}"><a @click="changeTab('tab-kgibank')">凱基</a></li>
                                </ul>
                                <div id="tab-skbank" v-show="tab==='tab-skbank'">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" :class="{'active': subTab === 'Pr'}">
                                            <a @click="changeSubTab('Pr')" data-toggle="tab" aria-expanded="true">負責人</a>
                                        </li>
                                        <li role="presentation" :class="{'active': subTab === 'Spouse'}">
                                            <a @click="changeSubTab('Spouse')" data-toggle="tab" aria-expanded="false">配偶</a>
                                        </li>
                                        <li role="presentation" :class="{'active': subTab === 'GuOne'}">
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
                                                    <td><span>負責人聯徵-票債信情形是否異常</span></td>
                                                    <td><select v-model="formData.prDebtLog" class="table-input sk-input form-control">
                                                            <option :value="'1'">1:是</option>
                                                            <option :value="'0'">0:否</option>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-聯徵J02資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prJ02YM"
                                                        placeholder="格式:YYYYMM"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCreditPoint">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-授信總餘額</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCreditTotalAmount"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-現金卡張數</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardQty"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-現金卡可動用額度</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardAvailLimit"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-現金卡餘額合計</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardTotalBalance"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-最近一週或一個月還款有無延遲紀錄</span></td>
                                                    <td><select v-model="formData.prHasWeekMonthDelay" class="table-input sk-input form-control">
                                                            <option :value="'1'">1:有</option>
                                                            <option :value="'0'">0:無</option>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-信用卡張數</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardQty"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-信用卡可動用額度</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardAvailAmount"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-信用卡餘額合計</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardTotalBalance"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-最近三個月內還款有無延遲紀錄</span></td>
                                                    <td><select v-model="formData.prHasLastThreeMonthDelay" class="table-input sk-input form-control">
                                                            <option :value="'1'">1:有</option>
                                                            <option :value="'0'">0:無</option>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵-擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.prBeingOthCompPrId"></td>
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
                                                <tr>
                                                    <td><span>配偶聯徵-票債信情形是否異常</span></td>
                                                    <td><select v-model="formData.spouseDebtLog" class="table-input sk-input form-control">
                                                            <option :value="'1'">1:是</option>
                                                            <option :value="'0'">0:否</option>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵-聯徵J02資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.spouseJ02YM"
                                                               placeholder="格式:YYYYMM"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵-信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.spouseCreditPoint">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵-授信總餘額</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.spouseCreditTotalAmount"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵-信用卡餘額合計</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.spouseCreditCardTotalBalance"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵-擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.spouseBeingOthCompPrId">
                                                    </td>
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
                                                <td><span>保證人聯徵-票債信情形是否異常</span></td>
                                                <td><select v-model="formData.guarantorDebtLog" class="table-input sk-input form-control">
                                                        <option :value="'1'">1:是</option>
                                                        <option :value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人聯徵-聯徵J02資料年月</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guarantorJ02YM"
                                                           placeholder="格式:YYYYMM"></td>
                                            </tr>
                                            <tr>
                                                <td><span>保證人聯徵-信用評分</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.guarantorCreditPoint">
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
                                        <li role="presentation" :class="{'active': subTab === 'Pr'}">
                                            <a @click="changeSubTab('Pr')" data-toggle="tab" aria-expanded="true">負責人</a>
                                        </li>
                                        <li role="presentation" :class="{'active': subTab === 'Spouse'}">
                                            <a @click="changeSubTab('Spouse')" data-toggle="tab" aria-expanded="false">配偶</a>
                                        </li>
                                        <li role="presentation">
                                            <a @click="changeSubTab('GuOne')" data-toggle="tab" aria-expanded="false">保證人</a>
                                        </li>
                                    </ul>
                                    <div class="table-responsive Pr">
                                        <table class="table table-striped table-bordered table-hover dataTable">
                                            <tbody>
                                            <tr style="text-align: center;">
                                                <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-票債信情形是否異常</span></td>
                                                <td><select v-model="formData.prDebtLog" class="table-input sk-input form-control">
                                                        <option :value="'1'">1:是</option>
                                                        <option :value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-聯徵J02資料年月</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prJ02YM"
                                                           placeholder="格式:YYYYMM"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-信用評分</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCreditPoint">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-授信總餘額</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCreditTotalAmount"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-現金卡張數</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardQty"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-現金卡可動用額度</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardAvailLimit"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-現金卡餘額合計</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCashCardTotalBalance"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-最近一週或一個月還款有無延遲紀錄</span></td>
                                                <td><select v-model="formData.prHasWeekMonthDelay" class="table-input sk-input form-control">
                                                        <option :value="'1'">1:有</option>
                                                        <option :value="'0'">0:無</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-信用卡張數</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardQty"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-信用卡可動用額度</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardAvailAmount"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-信用卡餘額合計</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prCreditCardTotalBalance"></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-最近三個月內還款有無延遲紀錄</span></td>
                                                <td><select v-model="formData.prHasLastThreeMonthDelay" class="table-input sk-input form-control">
                                                        <option :value="'1'">1:有</option>
                                                        <option :value="'0'">0:無</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>負責人聯徵-擔任其他企業負責人之企業統編</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.prBeingOthCompPrId"></td>
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
                                                <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶聯徵-票債信情形是否異常</span></td>
                                                <td><select v-model="formData.spouseDebtLog" class="table-input sk-input form-control">
                                                        <option :value="'1'">1:是</option>
                                                        <option :value="'0'">0:否</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶聯徵-聯徵J02資料年月</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.spouseJ02YM"
                                                           placeholder="格式:YYYYMM"></td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶聯徵-信用評分</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.spouseCreditPoint">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><span>配偶聯徵-擔任其他企業負責人之企業統編</span></td>
                                                <td><input class="sk-input form-control" type="text" v-model="formData.spouseBeingOthCompPrId">
                                                </td>
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
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人聯徵-票債信情形是否異常</span></td>
                                                    <td><select v-model="formData.guarantorDebtLog" class="table-input sk-input form-control">
                                                            <option :value="'1'">1:是</option>
                                                            <option :value="'0'">0:否</option>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人聯徵-聯徵J02資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.guarantorJ02YM"
                                                               placeholder="格式:YYYYMM"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>保證人聯徵-信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.guarantorCreditPoint">
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
                                        <select id="status" name="status" v-model="formData.status" class="form-control" onchange="check_fail();">
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
                                    <button id="status-submit" type="submit" class="btn btn-primary" style="display: none;">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片/文件</h1>
                            <fieldset>
                                <div class="form-group">
                                    <label>聯徵資料+A11</label><br>
                                    <? isset($content['person_mq_image']) && !is_array($content['person_mq_image']) ? $content['person_mq_image'] = array($content['person_mq_image']) : '';
                                    if(!empty($content['person_mq_image'])){
                                        foreach ($content['person_mq_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    } ?>
                                    <hr/>
                                    <label>其它</label><br>
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
    const data_status = '<?php echo $data->status ?? 0; ?>';
    const cert_status_spouse_associate = '<?php echo CERTIFICATION_STATUS_PENDING_SPOUSE_ASSOCIATE; ?>';
    if (data_status === cert_status_spouse_associate)
    {
        $('#status').attr('disabled', true);
    } else {
        $(`#status option[value=${cert_status_spouse_associate}]`).attr('disabled', true);
        $('#status-submit').css('display', '');
    }

    const v = new Vue({
        el: '#app1',
        data() {
            return {
                tab: 'tab-skbank',
                subTab: data_status === cert_status_spouse_associate ? 'Spouse' : 'Pr',
                pageId: '',
                formData: {
                    prDebtLog: '',
                    prJ02YM: '',
                    prCreditPoint: '',
                    prCreditTotalAmount: '',
                    prCashCardQty: '',
                    prCashCardAvailLimit: '',
                    prCashCardTotalBalance: '',
                    prHasWeekMonthDelay: '',
                    prCreditCardQty: '',
                    prCreditCardAvailAmount: '',
                    prCreditCardTotalBalance: '',
                    prHasLastThreeMonthDelay: '',
                    prBeingOthCompPrId: '',
                    spouseDebtLog: '',
                    spouseJ02YM: '',
                    spouseCreditPoint: '',
                    spouseBeingOthCompPrId: '',
                    guarantorDebtLog: '',
                    guarantorJ02YM: '',
                    guarantorCreditPoint: '',
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
                this.changeSubTab(this.subTab)
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
