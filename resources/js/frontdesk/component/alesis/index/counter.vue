<template>
    <div class="item">
        <img class="symbol" :src="image">
        <div class="content">
            <div class="header">{{ header }}</div>
            <div class="counter">
                <span class="number">{{ number }}</span>
                <span class="unit">{{ unit }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Counter",
    props: {
        image : "",
        header: "",
        number: "",
        unit  : "",
    }
};
</script>

<style lang="scss" scoped>
.items {
    display        : flex;
    justify-content: center;
}
.item {
    flex           : 1;
    text-align     : center;
    display        : flex;
    align-items    : center;
    justify-content: center;

    .symbol {
        margin-right: 1rem;
    }

    .content {
        display        : flex;
        align-items    : flex-start;
        flex-direction : column;
        justify-content: center;

        .header {
            font-size  : 1.2rem;
            font-weight: bold;
            color      : #353482;
        }

        .counter {
            font-size: 1.2rem;

            .number {
                color: #646464;
            }

            .unit {
                font-size  : 1.2rem;
                font-weight: bold;
                color      : #353482;
            }
        }
    }
}
</style>
