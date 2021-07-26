<template>
    <div class="item">
        <div class="header">
            <div class="symbol">
                <div class="wrapper">
                    <img class="image" :src="symbol" alt="">
                </div>
            </div>
            <div class="text">
                <div class="header">{{ header }}</div>
                <div class="line"></div>
                <div v-if="subheader" class="subheader">{{ subheader }}</div>
            </div>
        </div>
        <div class="content">
            <img class="image" :src="image" alt="">
            <div class="description">
                {{ description }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Symcard",
    props: {
        symbol     : "",
        image      : "",
        header     : "",
        subheader  : "",
        description: "",
    }
};
</script>

<style lang="scss" scoped>
.item {
    box-shadow   : 0px 4px 6px 0px rgb(0 0 0 / 30%);
    flex         : 1;
    display: flex;
    flex-direction: column;
    border-radius: 20px;

    > .header {
        background   : #ecedf1;
        display      : flex;
        padding      : .5rem 1rem;
        box-sizing   : border-box;
        border-radius: 20px 20px 0px 0;
        box-shadow: 0px 6px 6px 0px rgb(0 0 0 / 13%);
        z-index: 1;
        position: relative;
        min-height: 78px;
        align-items: center;

        .symbol {
            position: relative;
            width   : 110px;

            .wrapper {
                position     : absolute;
                top          : -4.5rem;
                left: .5rem;
                background   : #FFF;
                padding      : .5rem;
                border-radius: 100rem;
                box-shadow   : 0px 4px 6px 0px rgb(0 0 0 / 20%);
                z-index: 1;

                .image {
                    width : 80px;
                    height: 80px;
                }
            }
        }

        .text {
            position: relative;
            flex: 1;

            .header {
                text-align: center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip: text;
                width: fit-content;
                color: rgba(255, 255, 255, 0);
                margin: 0px auto;
                font-weight: bolder;
                font-size: 1.4rem;
                line-height: 1.8;
            }

            .subheader {
                text-align: center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip: text;
                width: fit-content;
                color: rgba(255, 255, 255, 0);
                margin: 0px auto;
                font-weight: bolder;
                font-size: .9rem;
                line-height: 1.7;
            }

            .line {
                height: 1px;
                background: #2f95cb;
                width: 40%;
                transform: translateX(-50%);
                left: 50%;
                position: relative;
            }
        }

    }
    .content {
        position     : relative;
        overflow     : hidden;
        border-radius: 0px 0px 20px 20px;
        flex: 1;

        .image {
            vertical-align: top;
            width         : 100%;
            height        : 190px;
            object-fit    : cover;
        }

        .description {
            position: absolute;
            bottom  : 0;
            left    : 0;
            right   : 0;
            background: rgb(0 0 0 / 70%);
            color: #FFF;
            padding: 1rem;
        }
    }
}
</style>
