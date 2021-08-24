<template>
    <div class="推薦介紹">
        <div class="標題">
            <div class="象徵">
                <div class="包裹容器">
                    <img class="圖示" :src="image">
                </div>
            </div>
            <div class="網格系統">
                <div class="價格">
                    <span class="標籤">借貸金額</span>
                    <span class="數值">{{ format(amount) }} 元</span>
                </div>
                <div class="利率">
                    <span class="標籤">借貸利率</span>
                    <span class="數值">{{interest}}%</span>
                </div>
                <div class="期數">
                    <span class="標籤">期數</span>
                    <span class="數值">{{instalment}}期</span>
                </div>
                <div class="天數">
                    <span class="標籤">媒合時間</span>
                    <span class="數值">{{spend}}天</span>
                </div>
            </div>
        </div>
        <div class="內容">
            <slot name="video"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisHuman",
    data() {
        return {
            image: ""
        }
    },
    props: {
        header: {
            default: "",
        },
        number: {
            default: "",
        },
        unit  : {
            default: "",
        },
        amount  : {
            default: 50000,
        },
        interest  : {
            default: 10,
        },
        instalment  : {
            default: 24,
        },
        spend:{
            default: 3,
        },
        rank: {
            default: "student"
        }
    },
    mounted() {
        switch (this.rank) {
            case "officeWorker":
                this.image = "/images/alesis-human-work-symbol.svg";
                break
            case "student":
            default:
                this.image = "/images/alesis-human-student-symbol.svg";
                break
        }
    },
    methods: {
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
@import "./alesis";

.推薦介紹 {
    box-shadow   : 0 3px 6px 0 rgb(0 0 0 / 30%);
    flex         : 1;
    border-radius: 20px;
}

.推薦介紹 .標題 {
    background   : #ecedf1;
    display      : flex;
    padding      : .5rem 1rem;
    box-sizing   : border-box;
    border-radius: 20px 20px 0px 0;

    @include rwd {
        padding: 0.25rem .5rem;
    }
}

.推薦介紹 .標題 .象徵 {
    position: relative;
    width   : 130px;

    @include rwd {
        width: 80px;
    }
}

.推薦介紹 .標題 .象徵 .包裹容器 {
    position     : absolute;
    top          : -2.5rem;
    background   : #FFF;
    padding      : .5rem;
    border-radius: 100rem;
    box-shadow   : 0 3px 6px 0 rgb(0 0 0 / 30%);

    @include rwd {
        top: -1.5rem;
    }
}

.推薦介紹 .標題 .象徵 .包裹容器 .圖示 {
    width : 90px;
    height: 90px;

    @include rwd {
        width : 60px;
        height: 60px;
    }
}

.推薦介紹 .標題 .網格系統 {
    display              : grid;
    grid-template-columns: 1fr min-content;
    background           : #b7c6d4;
    gap                  : 1px;
    flex                 : 1;
}

.推薦介紹 .標題 .網格系統 .價格,
.推薦介紹 .標題 .網格系統 .利率,
.推薦介紹 .標題 .網格系統 .期數,
.推薦介紹 .標題 .網格系統 .天數 {
    background : #ecedf1;
    padding    : .5rem 1rem;
    line-height: 1;
    white-space: nowrap;

    @include rwd {
        padding: .5rem .5rem;
    }
}

.推薦介紹 .標題 .網格系統 .標籤 {
    font-size  : 1.2rem;
    font-weight: bold;
    color      : #353482;

    @include rwd {
        font-size: .8rem;
    }
}

.推薦介紹 .標題 .網格系統 .數值 {
    font-size: 1.2rem;
    color    : #646464;

    @include rwd {
        font-size: .8rem;
    }
}

.推薦介紹 .內容 {
    overflow     : hidden;
    border-radius: 0rem 0 20px 20px;
    -webkit-mask-image: -webkit-radial-gradient(white, black);
}

.推薦介紹 .內容 > div {
    position      : relative;
    width         : 100%;
    height        : 0;
    padding-bottom: 56.25%;
}

.推薦介紹 .內容 iframe {
    vertical-align: top;
    position      : absolute;
    width         : 100%;
    height        : 100%;
    left          : 0;
    top           : 0;
}
</style>
