<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">DD查核</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">DD查核
                </div>
                <div class="panel-body">
                    <div class="row" id="app1">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" :href="user_info_href">
                                    <p>{{ user_id }}</p>
                                </a>
                            </div>
                            <form class="form-group" @submit.prevent="doSubmit">
                                <fieldset>
                                    <div class="form-group">
                                        <label>1. 創業背景</label><br/>
                                        <input type="radio" id="background_1" v-model="formData.background" value="1">
                                        <label for="background_1">自行創業</label>
                                        <input type="radio" id="background_2" v-model="formData.background" value="2">
                                        <label for="background_2">二代接班</label>
                                        <input type="radio" id="background_3" v-model="formData.background" value="3">
                                        <label for="background_3">家族支持</label>
                                        <input type="radio" id="background_4" v-model="formData.background" value="4">
                                        <label for="background_4">股東合資</label>
                                        <input type="radio" id="background_5" v-model="formData.background" value="5">
                                        <label for="background_5">其他</label>
                                    </div>
                                    <div class="form-group">
                                        <label>2. 本業資歷</label><br/>
                                        <input type="radio" id="seniority_1" v-model="formData.seniority" value="1">
                                        <label for="seniority_1">10 年以上</label>
                                        <input type="radio" id="seniority_2" v-model="formData.seniority" value="2">
                                        <label for="seniority_2">5 年以上</label>
                                        <input type="radio" id="seniority_3" v-model="formData.seniority" value="3">
                                        <label for="seniority_3">3 年以上</label>
                                        <input type="radio" id="seniority_4" v-model="formData.seniority" value="4">
                                        <label for="seniority_4">其他</label>
                                    </div>
                                    <div class="form-group">
                                        <label>3. 人力變動狀況 ( 可複選 )</label><br/>
                                        <input type="checkbox" id="changes_1" v-model="formData.changes" value="1">
                                        <label for="changes_1">核心團隊股東人數 > 3 人 ( 不包含負責人配偶 ) </label><br/>
                                        <input type="checkbox" id="changes_2" v-model="formData.changes" value="2">
                                        <label for="changes_2">公司離職率 < 50 %</label><br/>
                                        <input type="checkbox" id="changes_3" v-model="formData.changes" value="3">
                                        <label for="changes_3">平均薪資 > 5 萬</label><br/>
                                        <input type="checkbox" id="changes_4" v-model="formData.changes" value="4">
                                        <label for="changes_4">未提供或提供無效資訊者</label>
                                    </div>
                                    <div class="form-group">
                                        <label>4. 團隊資歷</label><br/>
                                        <input type="radio" id="group_seniority_1" v-model="formData.group_seniority"
                                               value="1">
                                        <label for="group_seniority_1">團隊平均工作年資 > 3 年</label><br/>
                                        <input type="radio" id="group_seniority_2" v-model="formData.group_seniority"
                                               value="2">
                                        <label for="group_seniority_2">專案銷售成功案例提供</label><br/>
                                        <input type="radio" id="group_seniority_3" v-model="formData.group_seniority"
                                               value="3">
                                        <label for="group_seniority_3">未提供或提供無效資訊者</label><br/>
                                    </div>
                                    <div class="form-group">
                                        <label>5. 稼動率 ( = 近三月平均產能 / 近三月最高產能 )</label><br/>
                                        <input type="radio" id="capacity_1" v-model="formData.capacity" value="1">
                                        <label for="capacity_1">>100%</label>
                                        <input type="radio" id="capacity_2" v-model="formData.capacity" value="2">
                                        <label for="capacity_2">70%~100%</label>
                                        <input type="radio" id="capacity_3" v-model="formData.capacity" value="3">
                                        <label for="capacity_3">50%~70%</label>
                                        <input type="radio" id="capacity_4" v-model="formData.capacity" value="4">
                                        <label for="capacity_4">50%以下</label>
                                    </div>
                                    <div class="form-group">
                                        <label>6.
                                            公司、負責人 ( 配偶 ) 資產市值 ( 以下加總 ) <br/>
                                            (1) 不動產市價值<br/>
                                            (2) 上市櫃公開企業股票市價值<br/>
                                            (3) 汽車市價值
                                        </label><br/>
                                        <input type="radio" id="capitalization_1" v-model="formData.capitalization"
                                               value="1">
                                        <label for="capitalization_1"> > 3,000 萬</label>
                                        <input type="radio" id="capitalization_2" v-model="formData.capitalization"
                                               value="2">
                                        <label for="capitalization_2"> 1,000 ~ 3,000萬 ( 含 ) </label>
                                        <input type="radio" id="capitalization_3" v-model="formData.capitalization"
                                               value="3">
                                        <label for="capitalization_3"> 500 ~ 1,000 萬 ( 含 ) </label>
                                        <input type="radio" id="capitalization_4" v-model="formData.capitalization"
                                               value="4">
                                        <label for="capitalization_4"> 0 ~ 500 萬 ( 含 ) </label>
                                        <input type="radio" id="capitalization_5" v-model="formData.capitalization"
                                               value="5">
                                        <label for="capitalization_5">未提供或提供無效資訊者</label>
                                    </div>
                                    <div class="form-group">
                                        <label>7. 增提保人 ( 非同一經濟體之保人，淨資產 > 0 ) </label><br/>
                                        <input type="radio" id="guarantor_1" v-model="formData.guarantor" value="1">
                                        <label for="guarantor_1">公家機關、大專院校講師等級以上</label><br/>
                                        <input type="radio" id="guarantor_2" v-model="formData.guarantor" value="2">
                                        <label for="guarantor_2">專業人士 ( 醫師、會計師、律師、白領主管 ) </label><br/>
                                        <input type="radio" id="guarantor_3" v-model="formData.guarantor" value="3">
                                        <label for="guarantor_3">1000大企業員工</label><br/>
                                        <input type="radio" id="guarantor_4" v-model="formData.guarantor" value="4">
                                        <label for="guarantor_4">具正當職業 ( 需徵勞保卡或扣繳憑單或薪轉存摺或稅額證明 ) </label><br/>
                                        <input type="radio" id="guarantor_5" v-model="formData.guarantor" value="5">
                                        <label for="guarantor_5">無徵提保人</label><br/>
                                    </div>
                                    <button type="submit" class="btn btn-primary">儲存
                                    </button>
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
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    const v = new Vue({
        el: '#app1',
        data() {
            return {
                page_id: '',
                formData: {
                    background: '',
                    seniority: '',
                    changes: [],
                    group_seniority: '',
                    capacity: '',
                    capitalization: '',
                    guarantor: '',
                },
                user_info_href: '',
                user_id: ''
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.page_id = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            doSubmit() {
                let selector = this.$el;
                $(selector).find('button[type=submit]').attr('disabled', true).text('資料更新中...');
                return axios.post('/admin/target/save_meta_info', {
                    meta: {...this.formData},
                    id: this.page_id
                }).then(({data}) => {
                    if (data.status.code !== 200) {
                        alert(data.error.message || '資料更新失敗，請洽工程師');
                    } else {
                        alert('資料更新成功');
                    }
                    location.reload()
                })
            },
            getData() {
                axios.get('/admin/target/get_meta_info', {
                    params: {
                        id: this.page_id
                    }
                }).then(({data}) => {
                    if (data.status.code !== 200) {
                        alert(data.error.message || '查無資料');
                        window.close();
                    }
                    this.user_id = data.response.data.user_id;
                    this.user_info_href = "<?= admin_url('user/display?id=')?>" + data.response.data.user_id;
                    this.formData = {
                        ...data.response.data.meta_info
                    };
                    if (!this.formData.changes) {
                        this.formData.changes = [];
                    }
                })
            }
        },
    })
</script>