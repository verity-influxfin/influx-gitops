<template>
    <div class="子彈項目">
        <div class="象徵">
            <img class="圖示" :src="image">
        </div>
        <div class="內容">
            <div class="標題">{{ header }}</div>
            <div class="註釋">{{ description }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name : "AlesisBullet",
    props: {
        image: {
            default: "",
        },
        header: {
            default: "",
        },
        description: {
            default: "",
        },
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.子彈項目 {
    display        : flex;
    justify-content: center;
    align-items    : center;
}

.子彈項目 .象徵 {
    margin-right: 2.5rem;
}

.子彈項目 .象徵 .圖示 {
    width : 100px;
    height: 100px;

    @include rwd {
        width : 86px;
        height: 86px;
    }
}

.子彈項目 .內容 {
    display        : flex;
    flex-direction : column;
    justify-content: center;
}

.子彈項目 .內容 .標題 {
    color      : #112e53;
    font-size  : 1.2rem;
    font-weight: bold;
    line-height: 2;

    @include rwd {
        font-size: 1rem;
    }
}

.子彈項目 .內容 .註釋 {
    font-size: 1.2rem;
    color    : #5d5555;

    @include rwd {
        font-size: .9rem;
    }
}
</style>
