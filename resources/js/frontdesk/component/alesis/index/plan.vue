<template>
    <div class="box">
        <img class="image" src="/images/累積放款金額-01.svg" alt="">
        <div class="feature">
            <div class="header">
                {{ header }}
            </div>
            <div class="list">
                <div class="item" v-for="(item, index) in targets" :key="index">{{ item }}</div>
                <div class="unready" v-if="unready">敬請期待</div>
            </div>
        </div>
        <div class="list">
            <div class="item" v-for="(item, index) in features" :key="index">{{ item }}</div>
            <div class="unready" v-if="unready">敬請期待</div>
        </div>
        <a class="action" href="#!">{{ action }}</a>
    </div>
</template>

<script>
export default {
    name : "Plan",
    props: {
        header  : "",
        unready : false,
        targets : [],
        action  : "",
        features: [],
    }
};
</script>

<style lang="scss" scoped>
.box {
    padding       : 1rem;
    border        : 1px solid #2664a5;
    border-radius : 24px;
    position      : relative;
    height        : 100%;
    display       : flex;
    flex-direction: column;

    .image {
        position     : absolute;
        top          : 0;
        left         : 50%;
        transform    : translate(-50%, -50%);
        z-index      : 2;
        background   : #f3f3f3;
        border-radius: 3rem;
    }

    .feature {
        display       : flex;
        flex-direction: column;
        align-items   : center;
        margin-top    : 3rem;

        .header {
            font-size  : 1.2rem;
            font-weight: bold;
            color      : #036eb7;
        }

        .list {
            font-size : .9rem;
            min-height: 43px;

            .item {
                display: list-item;
            }

            .unready {
                margin-top: 1rem;
                text-align: center;
            }
        }
    }

    > .list {
        border-top   : 1px solid #2664a5;
        padding-top  : 1rem;
        margin-top   : 1rem;
        font-size    : .9rem;
        line-height  : 2;
        margin-bottom: 1.5rem;

        .item {
            display    : list-item;
            margin-left: 1rem;
        }

        .unready {
            text-align: center
        }
    }

    .action {
        display      : block;
        width        : 100%;
        text-align   : center;
        font-size    : 1.2rem;
        font-weight  : bold;
        color        : #036eb7;
        border-radius: .4rem;
        padding      : .5rem 0;
        border       : 1px solid #2664a5;
        margin-top   : auto;
    }
}
</style>
