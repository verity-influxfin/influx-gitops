<template>
    <div class="單張卡片">
        <div class="標題">
            <div class="階級">
                <img class="圖片" src="/images/sshot-1591.png">
            </div>
            <div class="個人資料">
                <div class="象徵">{{ user.sex === 'M' ? '男' : '女'}} / {{ user.age }}歲</div>
                <div class="階級指數">
                    {{ credit_level }}級
                    <a class="說明" href="#!">
                        <img class="圖示" src="/images/sshot-1592.png">
                    </a>
                </div>
            </div>
            <div class="個人身份">
                <div class="來自">{{ user.company_name }}</div>
                <div class="編號">{{ target_no }}</div>
            </div>
        </div>
        <div class="原因">申貸原因：{{ reason }}</div>
        <div class="分隔線"></div>
        <div class="資訊">
            <div class="項目">
                <div class="標題">金額</div>
                <div class="數值">{{ loan_amount | format }} 元</div>
            </div>
            <div class="項目">
                <div class="標題">期數(本息均攤)</div>
                <div class="數值">{{ instalment }}期</div>
            </div>
            <div class="項目">
                <div class="標題">年化報酬率</div>
                <div class="數值 數值_重要的">{{ interest_rate }}%</div>
            </div>
        </div>
        <div class="目標">
            <div class="進度" :class="{'進度_媒合成功': invested >= loan_amount}">
                <div class="標籤">
                    <div class="種類">{{ product_name }}</div>
                    <div class="剩餘">{{ invested < loan_amount  ? '可投餘額'+format(loan_amount-invested)+'元' : '媒合成功'}}</div>
                </div>
                <div class="條"></div>
            </div>
            <div class="動作">
                <a href="#!" class="項目">看更多</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AlesisProject",
    props: {
        credit_level: {
            default: "",
        },
        user: {
            default: {
                sex: "M",
                age: 0,
                company_name: "",
            }
        },
        target_no: {
            default: "",
        },
        reason: {
            default: "",
        },
        loan_amount: {
            default: "",
        },
        instalment: {
            default: "",
        },
        interest_rate: {
            default: "",
        },
        product_name: {
            default: "",
        },
        invested: {
            default: 0,
        },
        loan_amount: {
            default: 0,
        }
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
.單張卡片 {
    border       : 1px solid #3770AC;
    background   : #FFF;
    border-radius: 20px;
    padding      : .5rem;
    overflow     : hidden;
    font-size    : .9rem;
    min-width    : 400px;
}

.單張卡片 .標題 {
    display    : flex;
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
    width : 28px;
    height: 28px;
}

.單張卡片 .標題 .個人身份 {
    border-left : 1px solid #D4DAE4;
    padding-left: .8rem;
}

.單張卡片 .原因 {
    font-size: .9rem;
}

.單張卡片 .分隔線 {
    height    : 1px;
    background: #D4DAE4;
}

.單張卡片 .資訊 {
    display: flex;
    margin : 1rem 0;
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

.單張卡片 .目標 .進度.進度_媒合成功 .條 {
    width: 98%;
    background: #E8BB76;
}

.單張卡片 .目標 .進度 .標籤 {
    display    : flex;
    flex       : 1;
    align-items: center;
    position   : relative;
    z-index    : 1;
}

.單張卡片 .目標 .進度 .標籤 .種類 {
    flex: 1;
}

.單張卡片 .目標 .進度 .標籤 .剩餘 {
    flex      : 1;
    text-align: right;
}

.單張卡片 .目標 .進度 .條 {
    position     : absolute;
    border-radius: .5rem;
    top          : .2rem;
    left         : .2rem;
    bottom       : .2rem;
    width        : 10%;
    background   : #E9A944;
    z-index      : 0;
}

.單張卡片 .目標 .動作 .項目 {
    display      : block;
    padding      : .7rem 1.5rem;
    background   : #E8EBEE;
    color        : #2B4669;
    border-radius: .4rem;
    line-height  : 1;
    font-weight  : bold;
}
</style>
