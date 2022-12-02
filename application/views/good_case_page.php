<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>優良案件列表</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="/assets/admin/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style>
        @charset "UTF-8";

        .單張卡片 {
            border: 1px solid #3770AC;
            background: #FFF;
            border-radius: 20px;
            padding: 0.5rem;
            overflow: hidden;
            font-size: 0.9rem;
            width: 400px;
        }

        .單張卡片.單張卡片_媒合成功 {
            background: #f5f5f5;
        }

        .單張卡片 .標題 {
            display: flex;
            align-items: center;
        }

        .單張卡片 .標題 .階級 {
            margin-right: 1rem;
        }

        .單張卡片 .標題 .個人資料 {
            flex: 0.8;
        }

        .單張卡片 .標題 .個人資料 .階級指數 {
            color: #85ACE3;
        }

        .單張卡片 .標題 .個人資料 .階級指數 .說明 .圖示 {
            width: 17px;
            height: 17px;
        }

        .單張卡片 .標題 .個人身份 {
            border-left: 1px solid #D4DAE4;
            padding-left: 0.8rem;
            width: 50%;
        }

        .單張卡片 .標題 .個人身份 .來自 {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .單張卡片 .原因 {
            font-size: 0.9rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .單張卡片 .分隔線 {
            height: 1px;
            background: #D4DAE4;
        }

        .單張卡片 .資訊 {
            display: flex;
            margin: 1rem 0;
        }

        .單張卡片 .資訊 .項目 {
            flex: 1;
        }

        .單張卡片 .資訊 .項目 .標題 {
            color: #96A0AE;
        }

        .單張卡片 .資訊 .項目 .數值 {
            color: #729EE0;
        }

        .單張卡片 .資訊 .項目 .數值.數值_重要的 {
            color: #EC6869;
        }

        .單張卡片 .目標 {
            display: flex;
        }

        .單張卡片 .目標 .進度 {
            flex: 1;
            display: flex;
            border-radius: 0.5rem;
            background: #686868;
            color: #FFF;
            box-shadow: inset 0px 0px 10px 0px rgba(0, 0, 0, 0.75);
            line-height: 1;
            position: relative;
            font-size: 0.9rem;
            padding: 0.35rem 0.5rem;
            height: auto;
            margin-right: 0.5rem;
        }

        .單張卡片 .目標 .進度.進度_媒合成功 .條 {
            width: 98%;
            background: #E8BB76;
        }

        .單張卡片 .目標 .進度 .標籤 {
            display: flex;
            flex: 1;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .單張卡片 .目標 .進度 .標籤 .種類 {
            flex: 1;
        }

        .單張卡片 .目標 .進度 .標籤 .剩餘 {
            flex: 1;
            text-align: right;
        }

        .單張卡片 .目標 .進度 .條 {
            position: absolute;
            border-radius: 0.5rem;
            top: 0.2rem;
            left: 0.2rem;
            bottom: 0.2rem;
            width: 10%;
            background: #E9A944;
            z-index: 0;
        }

        .單張卡片 .目標 .動作 .項目 {
            display: block;
            padding: 0.7rem 1.5rem;
            background: #E8EBEE;
            color: #2B4669;
            border-radius: 0.4rem;
            line-height: 1;
            font-weight: bold;
        }
    </style>
</head>

<body style="background:#d0ddd0;">
    <div class="container" id="app">
        <div class="row">
            <div class="col-12">
                <h1>優良案件列表</h1>
                <p>點擊案件產生圖片</p>
            </div>
            <div class="col-12 d-flex align-items-center">
                <div class="col-auto mb-3 mx-2">
                    篩選器
                </div>
                <div class="input-group mb-3 w-25">
                    <input class="form-control" placeholder="起始日" type="date" v-model.lazy="filters.start_date" :disabled="isLoading">
                </div>
                <div class="input-group mb-3 w-25">
                    <input class="form-control" placeholder="終止日" type="date" v-model.lazy="filters.end_date" :disabled="isLoading">
                </div>
                <div class="input-group mb-3 w-25">
                    <input type="text" class="form-control" placeholder="特定案件" v-model.lazy="filters.target_no" :disabled="isLoading">
                </div>
                <select class="form-select w-25 mb-3" v-model.lazy="filters.order_by" :disabled="isLoading">
                    <option>Sort by</option>
                    <option :value="'created_at'">案件日期</option>
                    <option :value="'credit_level'">信評</option>
                    <option :value="'loan_amount'">金額</option>
                </select>
            </div>
            <div :class="{'d-none': !isLoading}" class="col-12">
                <div class="text-center">
                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                </div>
            </div>
            <div :class="{'d-flex': !isLoading,'d-none': isLoading}" class="flex-wrap">
                <div class="單張卡片 m-3" :class="{'單張卡片_媒合成功': item.invested >= item.loan_amount}" v-for="item in dataList" :key="item.target_no" :id="item.target_no" @click="imageOutput(item.target_no)">
                    <div class="標題">
                        <div class="階級">
                            <img class="圖片" style="max-width:70px;" v-bind:src="'/assets/credits/credit_' + item.credit_level + '.png'">
                        </div>
                        <div class="個人資料">
                            <div class="象徵">{{ item.user.sex === 'M' ? '男' : '女'}} / {{ item.user.age }}歲</div>
                            <div class="階級指數">
                                {{ item.credit_level }}級
                            </div>
                        </div>
                        <div class="個人身份">
                            <div class="來自">{{ item.user.company_name }}</div>
                            <div class="編號">{{ item.target_no }}</div>
                        </div>
                    </div>
                    <div class="原因">申貸原因：{{ item.reason }}</div>
                    <div class="分隔線"></div>
                    <div class="資訊">
                        <div class="項目">
                            <div class="標題">金額</div>
                            <div class="數值">{{ format(item.loan_amount) }} 元</div>
                        </div>
                        <div class="項目">
                            <div class="標題">期數(本息均攤)</div>
                            <div class="數值">{{ item.instalment }}期</div>
                        </div>
                        <div class="項目">
                            <div class="標題">年化報酬率</div>
                            <div class="數值 數值_重要的">{{ item.interest_rate }}%</div>
                        </div>
                    </div>
                    <div class="目標">
                        <div class="進度" :class="{'進度_媒合成功': item.invested >= item.loan_amount}">
                            <div class="標籤">
                                <div class="種類">{{ item.product_name }}</div>
                                <div class="剩餘">{{ item.invested < item.loan_amount  ? '可投餘額'+format(item.loan_amount - item.invested)+'元' : '媒合成功'}}</div>
                            </div>
                            <div class="條"></div>
                        </div>
                        <div class="動作">
                            <a href="https://event.influxfin.com/R/url?p=webbanner" target="_blank" class="項目">看更多</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="picture-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">使用右鍵另存圖片</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center p-4"></div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://unpkg.com/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://unpkg.com/vue@3.2.31/dist/vue.global.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    const {
        ref,
        reactive,
        onMounted,
        onUnmounted,
        computed,
        watch
    } = Vue;
    Vue.createApp({
        setup() {
            const dataList = ref([]);
            const isLoading = ref(true);
            const format = (data) => {
                data = parseInt(data);
                if (!isNaN(data)) {
                    let l10nEN = new Intl.NumberFormat('en-US');
                    return l10nEN.format(data.toFixed(0));
                }
                return 0;
            }
            const imageOutput = (id) => {
                html2canvas(document.querySelector('#' + id), {
                    backgroundColor: null
                }).then(canvas => {
                    const myModal = new bootstrap.Modal(document.getElementById('picture-modal'))
                    myModal.toggle()
                    //remove all child of #picture-modal .modal-body
                    const modalBody = document.querySelector('#picture-modal .modal-body');
                    while (modalBody.firstChild) {
                        modalBody.removeChild(modalBody.firstChild);
                    }
                    modalBody.appendChild(canvas);
                });
            }
            const filters = reactive({
                start_date: new Date(new Date().setDate(new Date().getDate() - 360)).toISOString().split('T')[0],
                end_date: new Date().toISOString().split('T')[0],
                target_no: '',
                order_by: 'created_at'
            })

            const getData = async () => {
                isLoading.value = true
                return axios({
                    method: 'get',
                    url: '/page/get_good_case',
                    params: {
                        filters
                    }
                }).then(({
                    data
                }) => {
                    isLoading.value = false
                    dataList.value = [...data]
                })
            }

            onMounted(() => {
                getData()
            })

            watch(filters, () => {
                getData()
            })
            return {
                dataList,
                format,
                isLoading,
                imageOutput,
                filters
            }
        }
    }).mount('#app')
</script>

</html>
