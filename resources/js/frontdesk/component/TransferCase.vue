<template>
    <div class="single-page">
        <div class="標題">
            <div class="階級">
                <img class="圖片" style="max-width:70px;" src="/images/icon_unlock.png">
            </div>
            <div class="個人資料">
                <div class="象徵">{{ target.user.sex === 'M' ? '男' : '女'}} / {{ target.user.age }}歲</div>
                <div class="階級指數">
                    {{ target.credit_level }}級
                    <a class="說明" href="#!">
                        <img class="圖示" src="/images/sshot-1592.png">
                    </a>
                </div>
            </div>
            <div class="個人身份">
                <div class="來自">{{ '出售金額： ' + format(amount) + '元' }}</div>
                <div class="編號">{{ '應收款項： ' + format(accounts_receivable) + '元' }}</div>
            </div>
        </div>
        <div class="分隔線"></div>
        <div class="資訊">
            <div class="項目">
                <div class="標題">預期收益</div>
                <div class="數值">{{ accounts_receivable - amount | format }} 元</div>
            </div>
            <div class="項目">
                <div class="標題">剩餘帳期</div>
                <div class="數值">{{ instalment }}期(共{{ target.instalment }}期)</div>
            </div>
        </div>
        <div class="目標">
            <div class="進度">
                <div class="標籤">
                    <div class="種類">{{ target.product_name }}</div>
                </div>
            </div>
            <div class="動作">
                <a href="#!" class="項目">看更多</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TransferCase",
    props: {
        amount:{
            default: Math.random()*1000,
        },
        principal:{
            default: Math.random()*1000 + Math.random()*100,
        },
        accounts_receivable:{
            default: Math.random()*1000 + Math.random()*100,
        },
        target:{
            user: {
                sex:{
                    default:"M",
                },
                age:{
                    default:23,
                },
                credit_level:{
                    default:Math.random()*10,
                },
                product_name:{
                    default:"上班族貸",
                }
            },
            instalment:{
                default: 24,
            }
        },
        target_no: {
            default: "STN2020122218828",
        },
        loan_amount: {
            default: 73000,
        },
        instalment: {
            default: 24,
        },
        interest_rate: {
            default: 14,
        },
        product_name: {
            default: "學生貸",
        },
        invested: {
            default: 0,
        },
    },
    methods: {
        // format 會格式化數值成為有千分逗號的格式。
        format(data) {
            data = parseInt(data);
            if (!isNaN(data)) {
                let l10nEN = new Intl.NumberFormat("en-US");
                return l10nEN.format(data.toFixed(0));
            }
            return 0;
        },
    }
};
</script>

<style lang="scss" scoped>
.single-page {
    border       : 1px solid #3770AC;
    background   : #FFF;
    border-radius: 20px;
    padding      : .5rem;
    overflow     : hidden;
    font-size    : .9rem;
    min-width    : 400px;
}

.single-page .標題 {
    display    : flex;
    align-items: center;
}

.single-page .標題 .階級 {
    margin-right: 1rem;
}

.single-page .標題 .個人資料 {
    flex: 0.8;
}

.single-page .標題 .個人資料 .階級指數 {
    color: #85ACE3;
}

.single-page .標題 .個人資料 .階級指數 .說明 .圖示 {
    width : 28px;
    height: 28px;
}

.single-page .標題 .個人身份 {
    border-left : 1px solid #D4DAE4;
    padding-left: .8rem;
}

.single-page .原因 {
    font-size: .9rem;
}

.single-page .分隔線 {
    height    : 1px;
    background: #D4DAE4;
}

.single-page .資訊 {
    display: flex;
    margin : 1rem 0;
}

.single-page .資訊 .項目 {
    flex: 1;
}

.single-page .資訊 .項目 .標題 {
    color: #96A0AE;
}

.single-page .資訊 .項目 .數值 {
    color: #729EE0;
}

.single-page .資訊 .項目 .數值.數值_重要的 {
    color: #EC6869;
}

.single-page .目標 {
    display: flex;
}

.single-page .目標 .進度 {
    flex         : 1;
    display      : flex;
    border-radius: .5rem;
    background   : #686868;
    color        : #FFF;
    box-shadow   : inset 0px 0px 10px 0px rgb(0 0 0 / 75%);
    line-height  : 1;
    position     : relative;
    font-size    : .9rem;
    padding      : .35rem .5rem;
    height       : auto;
    margin-right : .5rem;
}

.single-page .目標 .進度.進度_媒合成功 .條 {
    width: 98%;
    background: #E8BB76;
}

.single-page .目標 .進度 .標籤 {
    display    : flex;
    flex       : 1;
    align-items: center;
    position   : relative;
    z-index    : 1;
}

.single-page .目標 .進度 .標籤 .種類 {
    flex: 1;
}

.single-page .目標 .進度 .標籤 .剩餘 {
    flex      : 1;
    text-align: right;
}

.single-page .目標 .進度 .條 {
    position     : absolute;
    border-radius: .5rem;
    top          : .2rem;
    left         : .2rem;
    bottom       : .2rem;
    width        : 10%;
    background   : #E9A944;
    z-index      : 0;
}

.single-page .目標 .動作 .項目 {
    display      : block;
    padding      : .7rem 1.5rem;
    background   : #E8EBEE;
    color        : #2B4669;
    border-radius: .4rem;
    line-height  : 1;
    font-weight  : bold;
}
</style>
