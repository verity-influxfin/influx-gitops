<template>
    <div class="空白" :class="{
        '空白_微小': size === 'tiny',
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

.空白.空白_微小 {
    --alesis-space-gap: 3rem;

    @include rwd {
        --alesis-space-gap: 1rem;
    }
}

.空白.空白_小 {
    --alesis-space-gap: 5rem;

    @include rwd {
        --alesis-space-gap: 3rem;
    }
}

.空白.空白_中 {
    --alesis-space-gap: 9rem;

    @include rwd {
        --alesis-space-gap: 7rem;
    }
}

.空白.空白_大 {
    --alesis-space-gap: 13rem;

    @include rwd {
        --alesis-space-gap: 11rem;
    }
}

.空白.空白_巨大 {
    --alesis-space-gap: 17rem;

    @include rwd {
        --alesis-space-gap: 15rem;
    }
}
</style>