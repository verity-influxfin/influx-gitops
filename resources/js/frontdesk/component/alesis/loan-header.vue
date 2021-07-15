<template>
    <div class="alesis-loan-header">
        <div class="background">
            <img :src="image" class="image">
        </div>
        <div class="mask">
            <div class="header">{{ header }}</div>
            <div class="content" v-html="content"></div>
            <div class="action">
                <a href="#!" class="item">立即借款 &gt;</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "LoanHeader",
    props: {
        header : "",
        content: "",
        image  : "",
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.alesis-loan-header {
    position  : relative;
    height    : 33rem;
    overflow-x: hidden;

    @include rwd {
        height: clamp(33rem, 155vw, 155vw);
    }

    .background {
        position: absolute;
        top     : 0;
        left    : 31rem;
        right   : 0;
        bottom  : 0;

        @include rwd {
            top : 42vw;
            left: 0;
        }

        .image {
            height    : 100%;
            width     : 100%;
            object-fit: cover;
        }
    }

    .mask {
        background-image : url("/images/alesis-shell-mask.svg");
        position         : relative;
        z-index          : 1;
        text-shadow      : 1px 1px 4px black;
        color            : #FFF;
        font-size        : 1.1rem;
        padding          : 7rem 1rem 5rem 4rem;
        background-size  : contain;
        background-repeat: no-repeat;
        min-width        : 1485px;
        height           : 100%;

        @include rwd {
            background-image: url("/images/alesis-wave-bg-rotated.svg");
            background-size : 100vw;
            justify-content : initial;
            padding         : 3vw 6vw;
            min-width       : 100vw;
        }

        .header {
            font-size  : 2.2rem;
            font-weight: bolder;
            color      : #F2E627;

            @include rwd {
                font-size: 6vw;
            }
        }

        .content {
            margin-top : 1rem;
            line-height: 1.8;
            font-size  : 1.3rem;

            @include rwd {
                font-size: 3vw;
            }
        }

        .action {
            margin-top : 3rem;
            margin-left: 7rem;

            @include rwd {
                margin-top : 0;
                margin-left: 52vw;
            }

            .item {
                display        : inline-block;
                color          : #FFF;
                text-decoration: none;
                padding        : .25rem 2rem;
                text-shadow    : none;
                font-weight    : bold;
                border-radius  : 100rem;
                border         : 2px solid #FFF;
                font-size      : 1.3rem;

                @include rwd {
                    border-radius: .4rem;
                    font-size    : 3.5vw;
                }
            }
        }
    }
}
</style>
