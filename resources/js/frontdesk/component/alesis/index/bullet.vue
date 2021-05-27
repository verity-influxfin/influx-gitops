<template>
    <div class="item">
        <div class="symbol">
            <img class="image" :src="image" alt="">
        </div>
        <div class="content">
            <div class="header">{{ header }}</div>
            <div class="description">{{ description }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Bullet",
    props: {
        image      : "",
        header     : "",
        description: "",
    }
};
</script>

<style lang="scss" scoped>
.item {
    display        : flex;
    justify-content: center;
    align-items    : center;

    .symbol {
        margin-right: 2.5rem;

        .image {
            width : 100px;
            height: 100px;
        }
    }

    .content {
        display        : flex;
        flex-direction : column;
        justify-content: center;

        .header {
            color      : #112e53;
            font-size  : 1.2rem;
            font-weight: bold;
            line-height: 2;
        }

        .description {
            font-size: 1.2rem;
            color    : #5d5555;
        }
    }
}
</style>
