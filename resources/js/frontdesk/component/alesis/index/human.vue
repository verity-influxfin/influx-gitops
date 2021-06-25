<template>
    <div class="item">
        <div class="header">
            <div class="symbol">
                <div class="wrapper">
                    <img class="image" src="/images/累積放款金額-01.svg" alt="">
                </div>
            </div>
            <div class="grid">
                <div class="price">
                    <span class="label">借貸金額</span>
                    <span class="number">50,000 元</span>
                </div>
                <div class="percentage">
                    <span class="label">借貸利率</span>
                    <span class="number">10%</span>
                </div>
                <div class="span">
                    <span class="label">期數</span>
                    <span class="number">24期</span>
                </div>
                <div class="days">
                    <span class="label">媒合時間</span>
                    <span class="number">3天</span>
                </div>
            </div>
        </div>
        <div class="content">
            <slot name="video"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name : "Human",
    props: {
        image : "",
        header: "",
        number: "",
        unit  : "",
    }
};
</script>

<style lang="scss" scoped>
@import "./../alesis";

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

        @include rwd {
            padding: 0.25rem .5rem;
        }

        .symbol {
            position: relative;
            width   : 130px;

            @include rwd {
                width: 80px;
            }

            .wrapper {
                position     : absolute;
                top          : -2.5rem;
                background   : #FFF;
                padding      : .5rem;
                border-radius: 100rem;
                box-shadow   : 0px 0px 5px 0px rgb(50 50 50 / 75%);

                @include rwd {
                    top: -1.5rem;
                }

                .image {
                    width : 90px;
                    height: 90px;

                    @include rwd {
                        width : 60px;
                        height: 60px;
                    }
                }
            }
        }

        .grid {
            display              : grid;
            grid-template-columns: 1fr min-content;
            background           : #b7c6d4;
            gap                  : 1px;
            flex                 : 1;

            > * {
                background : #ecedf1;
                padding    : .5rem 1rem;
                line-height: 1;
                white-space: nowrap;

                @include rwd {
                    padding: .5rem .5rem;
                }
            }

            .label {
                font-size  : 1.2rem;
                font-weight: bold;
                color      : #353482;

                @include rwd {
                    font-size: .8rem;
                }
            }

            .number {
                font-size: 1.2rem;
                color    : #646464;

                @include rwd {
                    font-size: .8rem;
                }
            }
        }
    }

    .content {
        overflow     : hidden;
        border-radius: 0rem 0 20px 20px;

        > div {
            position      : relative;
            width         : 100%;
            height        : 0;
            padding-bottom: 56.25%;
        }

        iframe {
            vertical-align: top;
            position      : absolute;
            width         : 100%;
            height        : 100%;
            left          : 0;
            top           : 0;
        }
    }
}
</style>
