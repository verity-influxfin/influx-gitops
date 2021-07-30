<template>
    <div class="應用推薦">
        <div class="包裹容器">
            <div class="內容">
                <a class="按鈕">{{ action }}</a>
                <div class="分隔線"></div>
                <div class="註釋">前往APP下載區 立即啟動服務</div>
                <div class="市集應用">
                    <a :href="action == '立即投資' ? 'https://www.influxfin.com/investLink' : 'https://www.influxfin.com/borrowLink'" target="_balnk"><img src="/images/alesis-app-store.svg"></a>
                    <a :href="action == '立即投資' ? 'https://www.influxfin.com/investLink' : 'https://www.influxfin.com/borrowLink'" class="連結"><img src="/images/alesis-google-play.svg"></a>
                </div>
            </div>
            <div class="展示區塊">
                <img class="手機" src="/images/alesis-phone-lay.svg">
                <img class="邊緣手機" src="/images/alesis-outer-edge-phones.svg">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AlesisAppRecommendation",
    props: {
        action: {
            default: "立即投資",
            type   : String,
        }
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.應用推薦 .包裹容器 {
    background-image: url('/images/sshot-1644.png');
    background-size : cover;
    display         : flex;
    align-items     : center;
    justify-content : center;
    min-height      : 30rem;
    position        : relative;

    @include rwd {
        flex-direction: column;
    }
}

.應用推薦 .包裹容器 .內容 {
    flex      : 1;
    text-align: center;

    @include rwd {
        margin-top   : 3rem;
        margin-bottom: 3rem;
    }
}

.應用推薦 .包裹容器 .內容 .按鈕 {
    text-align      : center;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip : text;
    width           : fit-content;
    color           : rgba(255, 255, 255, 0);
    font-weight     : bolder;
    font-size       : 1.9rem;
    line-height     : 1;
    white-space     : nowrap;
    border-bottom   : 1px solid #2664A5;
    padding         : .25rem 1.5rem;
    border-radius   : 1rem;
    border          : 2px solid #036eb7;
}

.應用推薦 .包裹容器 .內容 .分隔線 {
    background: #2f95cb;
    height    : 1px;
    margin    : 2rem auto 2rem;
    width     : 330px;

    @include rwd {
        width: 270px;
    }
}

.應用推薦 .包裹容器 .內容 .註釋 {
    font-size  : 1.1rem;
    font-weight: bolder;
    color      : #112e53;
}

.應用推薦 .包裹容器 .內容 .市集應用 {
    margin-top: 2rem;
}

.應用推薦 .包裹容器 .內容 .市集應用 .連結:first-child {
    margin-right: 1rem;
}

.應用推薦 .包裹容器 .內容 .市集應用 img {
    @include rwd {
        width: 160px;
    }
}

.應用推薦 .包裹容器 .展示區塊 {
    flex      : 1;
    text-align: right;
}

.應用推薦 .包裹容器 .展示區塊 .手機 {
    height: 100%;

    @include rwd {
        width        : 380px;
        margin-bottom: 2rem;
    }
}

.應用推薦 .包裹容器 .展示區塊 .邊緣手機 {
    position      : absolute;
    top           : 0;
    right         : 0;
    bottom        : 0;
    height        : 100%;
    pointer-events: none;

    @include rwd {
        opacity: 0.4;
    }
}
</style>
