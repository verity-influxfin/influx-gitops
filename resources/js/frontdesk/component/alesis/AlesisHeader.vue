<template>
    <div class="區塊標題">
        <div class="文字之前">
            <div class="點"></div>
            <div class="點"></div>
            <div class="點"></div>
            <div class="點"></div>
        </div>
        <div class="內容">
            <slot></slot>
        </div>
        <div class="文字之後">
            <div class="點"></div>
            <div class="點"></div>
            <div class="點"></div>
            <div class="點"></div>
        </div>
    </div>
</template>

<script>
export default {
    name: "AlesisHeader",
}
</script>


<style scoped lang="scss">
@import "./alesis";

.區塊標題 {
    position   : absolute;
    left       : 50%;
    top        : 0;
    transform  : translate(-50%, var(--alesis-xheader-offset, -50%));
    z-index    : 10;
    text-align : center;
}

.區塊標題_拖尾的 .文字之後 {
    display: none;
}

.區塊標題 .點 {
    width        : 6px;
    height       : 6px;
    background   : #3670d3;
    border-radius: 100rem;
    margin-bottom: .7rem;
    margin-left  : auto;
    margin-right : auto;
}

.區塊標題 .內容 {
    text-align      : center;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip : text;
    width           : 90vw;
    color           : rgba(255, 255, 255, 0);
    margin          : 0px auto;
    font-weight     : bolder;
    font-size       : 2rem;
    line-height     : 1.2;
    margin-top      : 1px;
}

.區塊標題 .內容 .標題 {
    @include rwd {
        font-size  : 1.25rem;
        line-height: 1;
    }
}

.區塊標題 .內容 .標題_外框線的 {
    border       : 1px solid #1a5fa2;
    padding      : 0.5rem;
    line-height  : 1;
    border-radius: 0.4rem;
    width        : fit-content;
    margin       : 1rem auto;
}

.區塊標題 .內容 .標題 + .標題 {
    margin: .3rem auto;
}

.區塊標題 .內容 .註釋 {
    font-size  : 1.5rem;
    line-height: 1.7;
    margin     : .6rem 0;

    @include rwd {
        font-size: .9rem;
    }
}

.區塊標題 .文字之前 {
    margin-bottom: -0.5rem;
    display      : inline-block;
}

.區塊標題 .文字之前 .點:nth-child(1) {
    opacity: 0.2;
    width  : 3px;
    height : 3px;
}

.區塊標題 .文字之前 .點:nth-child(2) {
    opacity: 0.4;
    width  : 4px;
    height : 4px;
}

.區塊標題 .文字之前 .點:nth-child(3) {
    opacity: 0.6;
}

.區塊標題 .文字之前 .點:nth-child(4) {
    opacity: 0.75;
}

.區塊標題 .文字之後 {
    margin-top: .5rem;
}

.區塊標題 .文字之後 .點:nth-child(1) {
    opacity: 0.75;
}

.區塊標題 .文字之後 .點:nth-child(2) {
    opacity: 0.6;
}

.區塊標題 .文字之後 .點:nth-child(3) {
    opacity: 0.4;
    width  : 4px;
    height : 4px;
}

.區塊標題 .文字之後 .點:nth-child(4) {
    opacity: 0.2;
    width  : 3px;
    height : 3px;
}
</style>