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
                                                <tr>
                                                    <td><span>負責人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_CreditCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_ShortTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_LongTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_LongTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBeingOthCompPrId"></td>
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
                                                    <td><span>配偶聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_MidTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text"
                                                            v-model="formData.SpouseBal_LongTermLnGuar"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBeingOthCompPrId">
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
                                                    <td><span>甲保證人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_LongTermLnGuar">
                                                    </td>
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
                                                <tr>
                                                    <td><span>乙保證人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_LongTermLnGuar">
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
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_CreditCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_ShortTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_LongTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBal_LongTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>負責人擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.PrBeingOthCompPrId"></td>
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
                                                    <td><span>配偶聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_MidTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text"
                                                            v-model="formData.SpouseBal_LongTermLnGuar"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>配偶擔任其他企業負責人之企業統編</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.SpouseBeingOthCompPrId">
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
                                                    <td><span>甲保證人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>甲保證人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuOneBal_LongTermLnGuar">
                                                    </td>
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
                                                    <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人聯徵查詢日期</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoJCICQueryDate"
                                                            placeholder="格式:YYYYMMDD">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人聯徵信用評分</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoCreditScore"
                                                            placeholder="顯示「此次暫時無法評分」，則傳入 0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人聯徵J01資料年月</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoJCICDataDate"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(現金卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_CashCard"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(信用卡)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_CreditCard">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(短放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_ShortTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(中放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_MidTermLn"></td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(長放)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_LongTermLn">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(短擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_ShortTermGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(中擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_MidTermLnGuar">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span>乙保證人銀行借款餘額(長擔)</span></td>
                                                    <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoBal_LongTermLnGuar">
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
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" v-model="formData.status" class="form-control" onchange="check_fail();">
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
                                    <label>聯徵資料+A11</label><br>
                                    <? isset($content['person_mq_image']) && !is_array($content['person_mq_image']) ? $content['person_mq_image'] = array($content['person_mq_image']) : '';
                                    if(!empty($content['person_mq_image'])){
                                        foreach ($content['person_mq_image'] as $key => $value) { ?>
                                            <a href="<?= isset($value) ? $value : "" ?>" data-fancybox="images">
                                                <img src="<?= $value ? $value : "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }?>
                                </div>
                            </fieldset>
                            <? if( ($data->certification_id == 9 || $data->certification_id == 1003 || $data->certification_id == 12) && isset($ocr['upload_page']) ){ ?>
                            <div class="form-group" style="background:#f5f5f5;border-style:double;">
                              <?= isset($ocr['upload_page']) ? $ocr['upload_page'] : ""?>
                            </div>
                            <? } ?>
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
                    PrJCICQueryDate: '',
                    PrCreditScore: '',
                    PrJCICDataDate: '',
                    PrBal_CashCard: '',
                    PrBal_CreditCard: '',
                    PrBal_ShortTermLn: '',
                    PrBal_MidTermLn: '',
                    PrBal_LongTermLn: '',
                    PrBal_ShortTermGuar: '',
                    PrBal_MidTermLnGuar: '',
                    PrBal_LongTermLnGuar: '',
                    PrBeingOthCompPrId: '',
                    SpouseJCICQueryDate: '',
                    SpouseCreditScore: '',
                    SpouseJCICDataDate: '',
                    SpouseBal_CashCard: '',
                    SpouseBal_CreditCard: '',
                    SpouseBal_ShortTermLn: '',
                    SpouseBal_MidTermLn: '',
                    SpouseBal_LongTermLn: '',
                    SpouseBal_ShortTermGuar: '',
                    SpouseBal_MidTermLnGuar: '',
                    SpouseBal_LongTermLnGuar: '',
                    SpouseBeingOthCompPrId: '',
                    GuOneJCICQueryDate: '',
                    GuOneCreditScore: '',
                    GuOneJCICDataDate: '',
                    GuOneBal_CashCard: '',
                    GuOneBal_CreditCard: '',
                    GuOneBal_ShortTermLn: '',
                    GuOneBal_MidTermLn: '',
                    GuOneBal_LongTermLn: '',
                    GuOneBal_ShortTermGuar: '',
                    GuOneBal_MidTermLnGuar: '',
                    GuOneBal_LongTermLnGuar: '',
                    GuTwoJCICQueryDate: '',
                    GuTwoCreditScore: '',
                    GuTwoJCICDataDate: '',
                    GuTwoBal_CashCard: '',
                    GuTwoBal_CreditCard: '',
                    GuTwoBal_ShortTermLn: '',
                    GuTwoBal_MidTermLn: '',
                    GuTwoBal_LongTermLn: '',
                    GuTwoBal_ShortTermGuar: '',
                    GuTwoBal_MidTermLnGuar: '',
                    GuTwoBal_LongTermLnGuar: '',
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
