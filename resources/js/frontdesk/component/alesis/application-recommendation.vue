<template>
    <div class="alesis-app">
        <div class="wrapper">
            <div class="content">
                <a href="#!" class="button">{{ action }}</a>
                <div class="divider"></div>
                <div class="description">前往APP下載區 立即啟動服務</div>
                <div class="markets">
                    <a href="#!" class="link"><img src="/images/alesis-app-store.svg" alt=""></a>
                    <a href="#!" class="link"><img src="/images/alesis-google-play.svg" alt=""></a>
                </div>
            </div>
            <div class="demo">
                <img class="phone" src="/images/alesis-phone-lay.svg" alt="">
                <img class="edge" src="/images/alesis-outer-edge-phones.svg" alt="">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ApplicationRecommendation",
    props: {
        action: {
            default: "立即投資",
            type: String,
        }
    }
};
</script>

<style lang="scss" scoped>
@import "./alesis";

.alesis-app {
    .wrapper {
        background-image: url('/images/sshot-1644.png');
        background-size : cover;
        display         : flex;
        align-items     : center;
        justify-content : center;
        min-height      : 34rem;
        position        : relative;

        @include rwd {
            flex-direction: column;
        }

        .content {
            flex      : 1;
            text-align: center;

            @include rwd {
                margin-top   : 3rem;
                margin-bottom: 3rem;
            }

            .button {
                text-align      : center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip : text;
                width           : fit-content;
                color           : rgba(255, 255, 255, 0);
                font-weight     : bolder;
                font-size       : 1.9rem;
                line-height     : 1;
                white-space     : nowrap;
                border-bottom   : 1px solid #2664A5;
                padding         : .25rem 1.5rem;
                border-radius   : 1rem;
                border          : 2px solid #036eb7;
            }

            .divider {
                background: #2f95cb;
                height    : 1px;
                margin    : 2rem auto 2rem;
                width     : 330px;

                @include rwd {
                    width: 270px;
                }
            }

            .description {
                font-size  : 1.1rem;
                font-weight: bolder;
                color      : #112e53;
            }

            .markets {
                    margin-top: 2rem;

                .link {
                    &:first-child {
                        margin-right: 1rem;
                    }
                }

                img {
                    @include rwd {
                        width: 160px;
                    }
                }
            }
        }
        .demo {
            flex: 1;

            .phone {
                @include rwd {
                    width        : 380px;
                    margin-bottom: 2rem;
                }
            }

            .edge {
                position      : absolute;
                top           : 0;
                right         : 0;
                bottom        : 0;
                height        : 100%;
                pointer-events: none;

                @include rwd {
                    opacity: 0.4;
                }
            }
        }
    }
}
</style>
