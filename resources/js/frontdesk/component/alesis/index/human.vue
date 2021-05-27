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
            <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
            width   : 130px;

            .wrapper {
                position     : absolute;
                top          : -2.5rem;
                background   : #FFF;
                padding      : .5rem;
                border-radius: 100rem;
                box-shadow   : 0px 0px 5px 0px rgb(50 50 50 / 75%);

                .image {
                    width : 90px;
                    height: 90px;
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
            }

            .label {
                font-size  : 1.2rem;
                font-weight: bold;
                color      : #353482;
            }
            .number {
                font-size: 1.2rem;
                color    : #646464;
            }
        }
    }
    .content {
        iframe {

        }
    }
}
</style>
