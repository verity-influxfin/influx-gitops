<div id="page-wrapper">
    <div class="row">
        <div class="col-md-3">
            <h1 class="page-header">交易日報表</h1>
        </div>
        <div class="col-md-9">
            <button 
                class="headerButton" 
                @click="exportExcel()"
            >匯出excel</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <table>
                        <tr style="vertical-align: baseline;">
                            <td style="padding: 14px 0;"> 
                                查詢日 從： 
                            </td>
                            <td>
                                <input
                                    type="date" 
                                    v-model="sdate"
                                    min="2023-01-01" 
                                    max="2030-12-31"
                                    placeholder="不指定區間"
                                >
                            </td>
                            <td style="padding-left: 10px;">  
                                到： 
                            </td>
                            <td>
                                <input
                                    type="date" 
                                    v-model="edate"
                                    min="2023-01-01" 
                                    max="2030-12-31"
                                    placeholder="不指定區間"
                                >
                            </td>
                            <td>
                                <button class="btn btn-default ml-5" @click="goSearch()">查詢</button>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                            <label>交易日期 : {{ sdate }} 00:00:00 ~ {{ edate }} 23:59:59</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                            <table>
                                <tr>
                                    <th colspan="3">一. 交易項目</td>
                                </tr>
                                <tr>
                                    <th>交易種類</th>
                                    <th>筆數</th>
                                    <th>金額</th>
                                </tr>
                                <tr>
                                    <td>代收交易</td>
                                    <td>{{ tradingData.recharge.count }}</td>
                                    <td>{{ tradingData.recharge.amount }}</td>
                                </tr>
                                <tr>
                                    <td>放款交易</td>
                                    <td>{{ tradingData.lending.count }}</td>
                                    <td>{{ tradingData.lending.amount }}</td>
                                </tr>
                                <tr>
                                    <td>還款</td>
                                    <td>{{ tradingData.payment.count }}</td>
                                    <td>{{ tradingData.payment.amount }}</td>
                                </tr>
                                <tr>
                                    <td>提前還款</td>
                                    <td>{{ tradingData.prepayment.count }}</td>
                                    <td>{{ tradingData.prepayment.amount }}</td>
                                </tr>
                                <tr>
                                    <td>逾期還款</td>
                                    <td>{{ tradingData.delay_payment.count }}</td>
                                    <td>{{ tradingData.delay_payment.amount }}</td>
                                </tr>
                                <tr>
                                    <td>提領交易</td>
                                    <td>{{ tradingData.withdraw.count }}</td>
                                    <td>{{ tradingData.withdraw.amount }}</td>
                                </tr>
                                <tr>
                                    <td>產品轉換放款</td>
                                    <td>{{ tradingData.subloan.count }}</td>
                                    <td>{{ tradingData.subloan.amount }}</td>
                                </tr>
                                <tr>
                                    <td>債權轉讓交易</td>
                                    <td>{{ tradingData.tansfer.count }}</td>
                                    <td>{{ tradingData.tansfer.amount }}</td>
                                </tr>
                                <tr>
                                    <td>推薦有賞</td>
                                    <td>{{ tradingData.promote_reward.count }}</td>
                                    <td>{{ tradingData.promote_reward.amount }}</td>
                                </tr>
                                <tr>
                                    <td>平台驗證費</td>
                                    <td>{{ tradingData.verify_fee.count }}</td>
                                    <td>{{ tradingData.verify_fee.amount }}</td>
                                </tr>
                                <tr>
                                    <td>慈善捐款</td>
                                    <td>{{ tradingData.charity.count }}</td>
                                    <td>{{ tradingData.charity.amount }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">上述交易核對無誤</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12" style="text-align: center;">
                            <table>
                                <tr>
                                    <th colspan="2">二. 交易收入</td>
                                </tr>
                                <tr>
                                    <th colspan="2">平台收益虛擬帳戶交易收支統計表</td>
                                </tr>
                                <tr>
                                    <th>科目</th>
                                    <th>收入金額</th>
                                </tr>
                                <tr>
                                    <td>平台服務費</td>
                                    <td>{{ tradingData.platform_fee }}</td>
                                </tr>
                                <tr>
                                    <td>轉換產品服務費</td>
                                    <td>{{ tradingData.subloan_fee }}</td>
                                </tr>
                                <tr>
                                    <td>債權轉讓服務費</td>
                                    <td>{{ tradingData.transfer_fee }}</td>
                                </tr>
                                <tr>
                                    <td>提還違約金</td>
                                    <td>{{ tradingData.prepayment_fee }}</td>
                                </tr>
                                <tr>
                                    <td>已還違約金</td>
                                    <td>{{ tradingData.damage }}</td>
                                </tr>
                                <tr>
                                    <td>法催執行費</td>
                                    <td>{{ tradingData.law_fee }}</td>
                                </tr>
                                <tr>
                                    <td>合計</td>
                                    <td>{{ sumSecondary }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12" style="text-align: center;">
                            <table>
                                <tr>
                                    <th colspan="3">三. 帳務金流(虛擬帳戶)</td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th>系統帳務</th>
                                    <th>銀行帳務</th>
                                </tr>
                                <tr>
                                    <td>期初餘額(475)</td>
                                    <td>0</td>
                                    <td>
                                        <input
                                            type="text" 
                                            class="insertInput"
                                            v-model="bank_balance"
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td>1.會員虛擬帳戶</td>
                                    <td>{{ tradingData.passbook_amount }}</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>2.不明來源資金</td>
                                    <td>{{ tradingData.unknown_funds }}</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>3.平台收益虛擬帳戶</td>
                                    <td>{{ tradingData.virtual_balance }}</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>4.次日記帳</td>
                                    <td>
                                        <input
                                            type="text" 
                                            class="insertInput" 
                                            v-model="secondary_journal"
                                        >
                                    </td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td>合計</td>
                                    <td>{{ sumSystemAccounts }}</td>
                                    <td>{{ sumBankAccounts }}</td>
                                </tr>
                                <tr>
                                    <td>差異</td>
                                    <td>{{ accountsDifferent }}</td>
                                    <td>0</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <span>{{ tradingData.info }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
var p2p_orm_host = '<?php print_r(getenv('ENV_P2P_ORM_HTTPS_HOST'))?>';

const v = new Vue({
    el: '#page-wrapper',
    data() {
        return {
            sdate: null,
            edate: null,
            tradingData: {
                recharge: {
                    count: 0,
                    amount: 0
                },
                lending: {
                    count: 0,
                    amount: 0
                },
                payment: {
                    count: 0,
                    amount: 0
                },
                prepayment: {
                    count: 0,
                    amount: 0
                },
                delay_payment: {
                    count: 0,
                    amount: 0
                },
                withdraw: {
                    count: 0,
                    amount: 0
                },
                subloan: {
                    count: 0,
                    amount: 0
                },
                tansfer: {
                    count: 0,
                    amount: 0
                },
                promote_reward: {
                    count: 0,
                    amount: 0
                },
                verify_fee: {
                    count: 0,
                    amount: 0
                },
                charity: {
                    count: 0,
                    amount: 0
                },
                platform_fee: 0,
                subloan_fee: 0,
                transfer_fee: 0,
                prepayment_fee: 0,
                damage: 0,
                law_fee: 0,
                passbook_amount: 0,
                unknown_funds: 0,
                virtual_balance: 0,
                bank_balance: 0,
                secondary_journal: 0,
                total_promote_reward: 0,
                promote_reward_info: [],
                info: '備註:'
                    
            },
            bank_balance: 0,
            secondary_journal: 0
        }
    },
    computed: {
        sumSecondary() {
            return parseInt(this.tradingData.platform_fee) + parseInt(this.tradingData.subloan_fee) + parseInt(this.tradingData.transfer_fee) + parseInt(this.tradingData.prepayment_fee) + parseInt(this.tradingData.damage) + parseInt(this.tradingData.law_fee);
        },
        sumSystemAccounts() {
            return parseInt(this.tradingData.passbook_amount) + parseInt(this.tradingData.unknown_funds) + parseInt(this.tradingData.virtual_balance) + parseInt(this.secondary_journal);
        },
        sumBankAccounts() {
            return parseInt(this.bank_balance);
        },
        accountsDifferent() {
            return parseInt(this.sumSystemAccounts) - parseInt(this.sumBankAccounts);
        }
    },
    methods: {
        goSearch() {
            if (['', 0, null].includes(this.sdate) || ['', 0, null].includes(this.edate)) {
                alert('開始與結束時間為必選欄位');
            } else {
                // alert(p2p_orm_host);
                axios.get(`${p2p_orm_host}/daily_financial_report?sdate=${this.sdate}&edate=${this.edate}&bank_balance=${this.bank_balance}&secondary_journal=${this.secondary_journal}`)
                .then((res) => {
                    this.tradingData = res.data;
                }) 
                .catch((err) => {
                    console.log(err);
                });
                // this.bank_balance = 0;
                // this.secondary_journal = 0;
            }
        },
        exportExcel() {
            if (['', 0, null].includes(this.bank_balance)) {
                alert('銀行帳戶初期餘額，為必填欄位。')
            } else if (['', 0, null].includes(this.sdate) || ['', 0, null].includes(this.edate)) {
                alert('開始與結束時間為必選欄位');
            } else {
                axios.get(`${p2p_orm_host}/daily_financial_report/excel?sdate=${this.sdate}&edate=${this.edate}&bank_balance=${this.bank_balance}&secondary_journal=${this.secondary_journal}`, { responseType: 'blob' })
                .then((res) => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', '交易日報表.xlsx'); 
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
                .catch((err) => {
                    console.log(err);
                });
            }
        }
    }
});
</script>
<style>
.headerButton {
    margin: 40px 0 20px;
    color: white;
    border-color: white;
    border-radius: 16px;
    height: 44px;
    box-shadow: 2px 4px 14px rgba(219, 47, 47, 0.08), 0px 4px 8px rgba(219, 47, 47, 0.16);
    width: 100px;
    background: #036FB7;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 30%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    text-align: center;
}

tr:nth-child(even) {
    background-color: #dddddd;
}

.insertInput {
    width: 60%;
    border-radius: 8px;
}
</style>