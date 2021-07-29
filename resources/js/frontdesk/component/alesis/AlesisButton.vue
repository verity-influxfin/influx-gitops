<template>
    <div class="按鈕" :class="{'按鈕_流動的': fluid}">
        <img class="背景" src="/images/alesis-button-lines.svg">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name : "AlesisButton",
    props: {
        text : {
            default: "",
        },
        size : {
            default: "1.1rem",
        },
        fluid: {
            default: false,
        },
        yPadding: {
            default: "0",
        },
        xPadding: {
            default: "0",
        },
    },
    mounted() {
        this.$el.style.setProperty('--font-size', this.size)
        if (this.xPadding !== "0") {
            this.$el.style.setProperty('--x-padding', this.xPadding)
        }
        if (this.yPadding !== "0") {
            this.$el.style.setProperty('--y-padding', this.yPadding)
        }
    }
};
</script>

<style lang="scss" scoped>
.按鈕 {
    position       : relative;
    padding        : var(--y-padding) var(--x-padding);
    display        : inline-flex;
    align-items    : center;
    justify-content: center;
    line-height    : 1;
    font-size      : var(--font-size);
    font-weight    : bold;
    color          : #036eb7;
}

.按鈕.按鈕_流動的 {
    width: 100%;
}

.按鈕 .背景 {
    position: absolute;
    top     : 0;
    right   : 0;
    bottom  : 0;
    left    : 0;
    height  : 100%;
    width   : 100%;
}
</style>
