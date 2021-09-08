<template>
    <div class="貸款標頭">
        <div class="背景">
            <img :src="image" class="圖片">
        </div>
        <div class="遮罩">
            <div class="標題">{{ header }}</div>
            <div class="內容" v-html="content"></div>
            <div class="動作 hidden-phone header_app_dl_link_mobile">
                <a href="https://event.influxfin.com/R/url?p=webbanner">
                    <img src="/images/android_download_link_mobile.svg">
                </a>
                <a href="https://event.influxfin.com/R/url?p=webbanner" target="_balnk">
                    <img src="/images/ios_download_link_mobile.svg">
                </a>
            </div>

            <div class="動作 hidden-desktop header_app_dl_link">
                <a href="https://event.influxfin.com/R/url?p=webbanner" target="_balnk">
                    <img src="/images/ios_download_link_desktop.png">
                </a>
                <a href="https://event.influxfin.com/R/url?p=webbanner">
                    <img src="/images/android_download_link_desktop.png">
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisLoanHeader",
    props: {
        header : {
            default: "",
        },
        content: {
            default: "",
        },
        image  : {
            default: "",
        },
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.貸款標頭 {
    position  : relative;
    height    : 33rem;
    overflow-x: hidden;

    @include rwd {
        height: clamp(33rem, 155vw, 155vw);
    }
}

.貸款標頭 .背景 {
    position: absolute;
    top     : 0;
    left    : 31rem;
    right   : 0;
    bottom  : 0;

    @include rwd {
        top : 53vw;
        left: 0;
    }
}

.貸款標頭 .背景 .圖片 {
    height    : 100%;
    width     : 100%;
    object-fit: cover;
    object-position: top;
}

.貸款標頭 .遮罩 {
    background-image : url("/images/alesis-shell-mask.svg");
    position         : relative;
    z-index          : 1;
    text-shadow      : 1px 1px 4px black;
    color            : #FFF;
    font-size        : 1.1rem;
    padding          : 7rem 1rem 5rem 4rem;
    background-size  : contain;
    background-repeat: no-repeat;
    min-width        : 1485px;
    height           : 100%;

    @include rwd {
        background-image: url("/images/alesis-wave-bg-rotated.svg");
        background-size : 100vw;
        justify-content : initial;
        padding         : 3vw 6vw;
        min-width       : 100vw;
    }
}

.貸款標頭 .遮罩 .標題 {
    font-size  : 2.2rem;
    font-weight: bolder;
    color      : #F2E627;

    @include rwd {
        font-size: 6vw;
    }
}

.貸款標頭 .遮罩 .內容 {
    margin-top : 1rem;
    line-height: 1.8;
    font-size  : 1.3rem;

    @include rwd {
        font-size: 3vw;
    }
}

.貸款標頭 .遮罩 .動作 {
    margin-top : 3rem;
    margin-left: 7rem;

    @include rwd {
        margin-top : 0;
        margin-left: 52vw;
    }
}

.貸款標頭 .遮罩 .動作 .項目 {
    display        : inline-block;
    color          : #FFF;
    text-decoration: none;
    padding        : .25rem 2rem;
    text-shadow    : none;
    font-weight    : bold;
    border-radius  : 100rem;
    border         : 2px solid #FFF;
    font-size      : 1.3rem;

    @include rwd {
        border-radius: .4rem;
        font-size    : 3.5vw;
    }
}
</style>
