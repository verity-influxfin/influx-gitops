<template>
    <div class="button" :class="{'-fluid': fluid}">
        <img class="background" src="/images/alesis-button-lines.svg" alt="">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name : "Button",
    props: {
        text    : "",
        size    : {
            default: "1.1rem",
        },
        fluid   : {
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
        this.$el.style.setProperty('--x-padding', this.xPadding)
        this.$el.style.setProperty('--y-padding', this.yPadding)
    }
};
</script>

<style lang="scss" scoped>
.button {
    position       : relative;
    padding        : var(--y-padding) var(--x-padding);
    display        : inline-flex;
    align-items    : center;
    justify-content: center;
    line-height    : 1;
    font-size      : var(--font-size);
    font-weight    : bold;
    color          : #036eb7;

    &.-fluid {
        width: 100%;
    }

    .background {
        position: absolute;
        top     : 0;
        right   : 0;
        bottom  : 0;
        left    : 0;
        height  : 100%;
        width   : 100%;
    }
}
</style>
