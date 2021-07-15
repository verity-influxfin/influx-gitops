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
        description: "",
    }
};
</script>

<style lang="scss" scoped>
.item {
    box-shadow   : 0px 0px 5px 0px rgb(50 50 50 / 75%);
    flex         : 1;
    border-radius: 20px;

    .header {
        background   : #ecedf1;
        display      : flex;
        padding      : .5rem 1rem;
        box-sizing   : border-box;
        border-radius: 20px 20px 0px 0;

        .symbol {
            position: relative;
            width   : 110px;

            .wrapper {
                position     : absolute;
                top          : -3rem;
                left: .5rem;
                background   : #FFF;
                padding      : .5rem;
                border-radius: 100rem;
                box-shadow   : 0px 0px 5px 0px rgb(50 50 50 / 75%);
                z-index: 1;

                .image {
                    width : 70px;
                    height: 70px;
                }
            }
        }

        .text {
            position: relative;

            .header {
                text-align: center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip: text;
                width: fit-content;
                color: rgba(255, 255, 255, 0);
                margin: 0px auto;
                font-weight: bolder;
                font-size: 1.3rem;
                line-height: 1.2;
            }

            .line {
                position:absolute;
                height: 1px;
                background: #2f95cb;
                left: 30%;
                right: 30%;
                bottom: 1px;
            }
        }

    }
    .content {
        position     : relative;
        overflow     : hidden;
        border-radius: 0px 0px 10px 10px;

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
