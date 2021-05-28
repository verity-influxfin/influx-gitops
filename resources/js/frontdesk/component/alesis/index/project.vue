<template>
    <div class="index-card">
        <div class="header">
            <div class="level">
                <img class="image" src="/images/sshot-1591.png">
            </div>
            <div class="personal">
                <div class="character">男 / 24歲</div>
                <div class="level">
                    4級
                    <a class="help" href="#!">
                        <img class="image" src="/images/sshot-1592.png">
                    </a>
                </div>
            </div>
            <div class="identifier">
                <div class="from">國立臺中科技大學</div>
                <div class="serial">STN2021031875864</div>
            </div>
        </div>
        <div class="reason">申貸原因：理財 – 跟朋友合作投資需要大筆資金投入</div>
        <div class="divider"></div>
        <div class="information">
            <div class="item">
                <div class="header">金額</div>
                <div class="value">73,000 元</div>
            </div>
            <div class="item">
                <div class="header">期數(本息均攤)</div>
                <div class="value">24期</div>
            </div>
            <div class="item">
                <div class="header">年化報酬率</div>
                <div class="value">14.5%</div>
            </div>
        </div>
        <div class="target">
            <div class="progress">
                <div class="label">
                    <div class="type">學生貸</div>
                    <div class="remaining">可投餘額72,000元</div>
                </div>
                <div class="bar"></div>
            </div>
            <div class="action">
                <a href="#!" class="item">看更多</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name : "Project",
    props: {
    },
};
</script>

<style lang="scss" scoped>
.index-card {
    border       : 1px solid #000;
    background   : #FFF;
    border-radius: 20px;
    padding      : .5rem;
    overflow     : hidden;
    font-size    : .9rem;

    .header {
        display    : flex;
        align-items: center;

        .level {
            margin-right: 1rem;

            .image {

            }
        }

        .personal {
            flex: 1;
            .character {

            }
            .level {
                .help {
                    .image {
                        width : 28px;
                        height: 28px;
                    }
                }
            }
        }

        .identifier {
            border-left : 1px solid #000;
            padding-left: .8rem;

            .from {

            }

            .serial {

            }
        }
    }

    .reason {
        font-size: .9rem;
    }

    .divider {
        height    : 1px;
        background: #000;
    }

    .information {
        display: flex;
        margin : 1rem 0;

        .item {
            flex: 1;

            .header {

            }
            .value {

            }
        }
    }

    .target {
        display: flex;

        .progress {
            flex         : 1;
            display      : flex;
            border-radius: .5rem;
            background   : #686868;
            color        : #FFF;
            box-shadow   : inset 0px 0px 10px 0px rgb(0 0 0 / 75%);
            line-height  : 1;
            position     : relative;
            font-size    : .9rem;
            padding      : .35rem .5rem;
            height       : auto;
            margin-right : .5rem;

            .label {
                display    : flex;
                flex       : 1;
                align-items: center;
                position   : relative;
                z-index    : 1;

                .type {
                    flex: 1;
                }

                .remaining {
                    flex      : 1;
                    text-align: right;
                }
            }
        }

        .bar {
            position     : absolute;
            border-radius: .5rem;
            top          : .2rem;
            left         : .2rem;
            bottom       : .2rem;
            width        : 10%;
            background   : #829036;
            z-index      : 0;
        }

        .action {
            .item {
                display      : block;
                padding      : .7rem 1.5rem;
                background   : #d7d7d7;
                color        : #000;
                border-radius: .4rem;
                line-height  : 1;
            }
        }
    }
}
</style>
