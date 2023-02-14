<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
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
        if ($(this).find(':selected') === 'other') {
            $('input#fail').css('display', 'block').attr('disabled', false);
        } else {
            $('input#fail').css('display', 'none').attr('disabled', true);
        }
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
                                <!-- navs -->
                                <ul class="nav nav-tabs">
                                    <li role="presentation" :class="{'active': tab ==='tab-skbank'}"><a @click="changeTab('tab-skbank')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-kgibank'}"><a @click="changeTab('tab-kgibank')">凱基</a></li>
                                </ul>
                                <table class="table table-striped table-bordered table-hover dataTable"
                                       v-show="tab==='tab-skbank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近01個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM1" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近01個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured1"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近02個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM2" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近02個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近03個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM3" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近03個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured3"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近04個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM4" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近04個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近05個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM5" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近05個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured5"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近06個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM6" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近06個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近07個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM7" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近07個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured7"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近08個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM8" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近08個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近09個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM9" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近09個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured9"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近10個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM10" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近10個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近11個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM11" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近11個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured11"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近12個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text"
                                                   v-model="formData.NumOfInsuredYM12" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近12個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="number"
                                                   v-model="formData.NumOfInsured12"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <button type="submit" class="btn btn-primary" style="margin:0 45%;">送出
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-kgibank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近01個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM1" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近01個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured1"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近02個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM2" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近02個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近03個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM3" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近03個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured3"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近04個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM4" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近04個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近05個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM5" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近05個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured5"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近06個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM6" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近06個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近07個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM7" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近07個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured7"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近08個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM8" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近08個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近09個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM9" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近09個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured9"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近10個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM10" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近10個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近11個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM11" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近11個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured11"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近12個月投保人數-年月</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsuredYM12" placeholder="格式:YYYMM"></td>
                                    </tr>
                                    <tr>
                                        <td><span>公司近12個月投保人數-人數</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.NumOfInsured12"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <?php if ( ! isset($content['affidavit_image'])) { ?>
                                    <?php isset($ocr['url']) && ! is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                                    foreach ($ocr['url'] as $key => $value)
                                    { ?>
                                        <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                                    <?php } ?>
                                <?php } ?>
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
                                            <?php foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option
                                                    <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                            <?php } ?>
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
                            <h1>圖片/文件</h1>
                            <fieldset>
                                <div class="form-group">
                                    <?php if ( ! empty($content['affidavit_image'])) {
                                        echo '<label>具結書</label><br>';
                                        $content['affidavit_image'] = ! is_array($content['affidavit_image'])
                                            ? array($content['affidavit_image'])
                                            : $content['affidavit_image'];
                                        foreach ($content['affidavit_image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    elseif ( ! empty($content['employeeinsurancelist_image']))
                                    {
                                        echo '<label>員工投保人數資料</label><br>';
                                        $content['employeeinsurancelist_image'] = ! is_array($content['employeeinsurancelist_image'])
                                            ? array($content['employeeinsurancelist_image'])
                                            : $content['employeeinsurancelist_image'];
                                        foreach ($content['employeeinsurancelist_image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    if ( ! empty($content['file_list']['image']))
                                    {
                                        foreach ($content['file_list']['image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value['url'] ?>" data-fancybox="images">
                                                <img src="<?= $value['url'] ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    if ( ! empty($content['file_list']['file']))
                                    {
                                        foreach ($content['file_list']['file'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value['url'] ?>">
                                                <i class="fa fa-file"> <?= $value['file_name'] ?? '檔案' ?></i>
                                            </a>
                                        <?php }
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
    const v = new Vue({
        el: '#app1',
        data() {
            return {
                tab: 'tab-skbank',
                pageId: '',
                formData: {
                    NumOfInsuredYM1: '',
                    NumOfInsured1: '',
                    NumOfInsuredYM2: '',
                    NumOfInsured2: '',
                    NumOfInsuredYM3: '',
                    NumOfInsured3: '',
                    NumOfInsuredYM4: '',
                    NumOfInsured4: '',
                    NumOfInsuredYM5: '',
                    NumOfInsured5: '',
                    NumOfInsuredYM6: '',
                    NumOfInsured6: '',
                    NumOfInsuredYM7: '',
                    NumOfInsured7: '',
                    NumOfInsuredYM8: '',
                    NumOfInsured8: '',
                    NumOfInsuredYM9: '',
                    NumOfInsured9: '',
                    NumOfInsuredYM10: '',
                    NumOfInsured10: '',
                    NumOfInsuredYM11: '',
                    NumOfInsured11: '',
                    NumOfInsuredYM12: '',
                    NumOfInsured12: ''
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
