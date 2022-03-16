<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    .sk-input {
        width : 100%;
    }
</style>
        <script type="text/javascript">
            function check_fail(){
                var status = $('#status :selected').val();
                if(status==2){
                    $('#fail_div').show();
                }else{
                    $('#fail_div').hide();
                }
            }
            $(document).off("change","select#fail").on("change","select#fail" ,  function(){
                var sel=$(this).find(':selected');
                $('input#fail').css('display',sel.attr('value')=='other'?'block':'none');
                $('input#fail').attr('disabled',sel.attr('value')=='other'?false:true);
            });
        </script>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">	
                                    <div class="form-group">
                                        <label>會員 ID</label>
                                        <a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
                                            <p><?=isset($data->user_id)?$data->user_id:"" ?></p>
                                        </a>
                                    </div>
                                    <form class="form-group" @submit.prevent="doSubmit">
                                        <ul class="nav nav-tabs nav-justified mb-1">
                                            <li role="presentation" :class="{'active': tab ==='tab-1'}"><a @click="changeTab('tab-1')">新光</a></li>
                                            <li role="presentation" :class="{'active': tab ==='tab-2'}"><a @click="changeTab('tab-2')">凱基</a></li>
                                            <li role="presentation" :class="{'active': tab ==='tab-3'}"><a @click="changeTab('tab-3')">其他</a></li>
                                        </ul>
                                        <div id="tab-1" v-show="tab==='tab-1'">
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
                                                            <td><span>負責人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>負責人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborInsSalary"></td>
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
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborInsSalary">
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
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborInsSalary">
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
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborInsSalary">
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
                                        <div id="tab-2" v-show="tab==='tab-2'">
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
                                                            <td><span>負責人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>負責人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborInsSalary"></td>
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
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborInsSalary">
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
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborInsSalary">
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
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborInsSalary">
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
                                        <div id="tab-3" v-show="tab==='tab-3'">
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
                                                            <td colspan="2"><span>普匯微企e秒貸資料確認3</span></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>負責人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>負責人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.PrLaborInsSalary"></td>
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
                                                            <td colspan="2"><span>普匯微企e秒貸資料確認3</span></td>
                                                        </tr>
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>配偶-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.SpouseLaborInsSalary">
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
                                                            <td colspan="2"><span>普匯微企e秒貸資料確認3</span></td>
                                                        </tr>
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>甲保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuOneLaborInsSalary">
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
                                            <div class="table-responsive GuTwo">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;">
                                                            <td colspan="2"><span>普匯微企e秒貸資料確認3</span></td>
                                                        </tr>
                                                        <tr hidden>
                                                            <td><span>徵提資料ID</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.id"
                                                                    value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢日期</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborQryDate"
                                                                    placeholder="格式:YYYYMMDD">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>乙保證人-被保險人勞保異動查詢-最近期投保薪資</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.GuTwoLaborInsSalary">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><span>costom 3</span></td>
                                                            <td><input class="sk-input form-control" type="text" v-model="formData.tab3Input">
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
                                    <? if(isset($content['labor_type'])){?>
                                        <div class="form-group">
                                            <label>勞保卡</label><br>
                                            <p><?= isset($content['labor_type']) && $content['labor_type'] ? '電子郵件交件' : '紙本交件' ?></p>
                                            <? if($content['labor_type']==1){?>
                                                    <? if (!empty($content['pdf_file'])) { ?>
                                                        <a href="<?= isset($content['pdf_file']) ? $content['pdf_file'] : ""?>" target="_blank">下載</a>
                                                    <? }else{?>
                                                        <p>尚未收到回信PDF</p>
                                            <?}?>
                                        </div>
                                        <?}} ?>
                                    <br />
                                    <div class="form-group">
                                        <label>備註</label>
                                    </div>
                                    <h4>審核</h4>
                                    <form role="form" method="post">
                                    <fieldset>
                                               <div class="form-group">
                                                <select id="status" name="status" class="form-control" onchange="check_fail();" >
                                                    <? foreach($status_list as $key => $value){ ?>
                                                    <option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                </select>
                                                <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                                <input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
                                            </div>
                                            <div class="form-group" id="fail_div" style="display:none">
                                                <label>失敗原因</label>
                                                <select id="fail" name="fail" class="form-control">
                                                    <option value="" disabled selected>選擇回覆內容</option>
                                                    <? foreach($certifications_msg[501] as $key => $value){ ?>
                                                        <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                    <option value="other">其它</option>
                                                </select>
                                                <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
                                            </div>
                                            <button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <h1>圖片</h1>
                                    <fieldset disabled>
                                        <? if (isset($content['labor_image'])) {
                                            echo '<h4>【勞保異動明細】</h4><div class="form-group"><label for="disabledSelect">勞保異動明細</label><br>';
                                            foreach($content['labor_image'] as $key => $value){
                                                echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                            }
                                            echo '</div><br /><br /><br />';
                                        }?>
                                    </fieldset>
                                    <? if( $data->certification_id == 501 && isset($ocr['upload_page']) ){ ?>
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
                tab: 'tab-1',
                pageId: '',
                formData: {
                    PrLaborQryDate: '',
                    PrLaborInsSalary: '',
                    SpouseLaborQryDate: '',
                    SpouseLaborInsSalary: '',
                    GuOneLaborQryDate: '',
                    GuOneLaborInsSalary: '',
                    GuTwoLaborQryDate: '',
                    GuTwoLaborInsSalary: '',
                    tab2Input: '',
                    tab3Input: ''
                }
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.changeTab('tab-1')
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
                this.changeSubTab('Pr')
            },
            changeSubTab(show_id){
                $(".table-responsive").hide()
                $(`#${this.tab} .${show_id}`).show()
            },
            doSubmit() {
                return axios.post('/admin/certification/sendSkbank', {
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
