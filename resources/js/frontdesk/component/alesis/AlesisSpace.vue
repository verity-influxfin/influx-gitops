<template>
    <div class="空白" :class="{
        '空白_小'  : size === 'small',
        '空白_中'  : size === 'medium',
        '空白_大'  : size === 'large',
        '空白_巨大': size === 'huge'
    }"></div>
</template>

<script>
export default {
    name : "Space",
    props: {
        size: {
            default: "medium"
        }
    }
}
</script>

<style lang="scss" scoped>
@import "./alesis";

.空白 {
    height: var(--alesis-space-gap);
}

.空白.空白_小 {
    --alesis-space-gap: 6rem;

    @include rwd {
        --alesis-space-gap: 4rem;
    }
}

.空白.空白_中 {
    --alesis-space-gap: 10rem;

    @include rwd {
        --alesis-space-gap: 8rem;
    }
}

.空白.空白_大 {
    --alesis-space-gap: 14rem;

    @include rwd {
        --alesis-space-gap: 12rem;
    }
}

.空白.空白_巨大 {
    --alesis-space-gap: 18rem;

    @include rwd {
        --alesis-space-gap: 16rem;
    }
}
</style>