<template>
    <div class="alesis-loan-header">
        <div class="background">
            <img src="/images/sshot-1691.png" class="image">
        </div>
        <div class="mask">
            <div class="header">學生貸</div>
            <div class="content">
                快速、安全、隱私<br>
                最受學生信賴的線上借貸平台！<br>
                最高額度12萬、最低利率5%<br>
                幫助年輕人完成夢想！
            </div>
            <div class="action">
                <a href="#!" class="item">立即借款 &gt;</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoanHeader"
};
</script>

<style lang="scss" scoped>
.alesis-loan-header {
    position: relative;

    .background {
        position: absolute;
        top     : 0;
        left    : 0;
        right   : 0;
        bottom  : 0;

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
        padding          : 7rem 1rem 5rem;
        background-size  : contain;
        background-repeat: no-repeat;
        min-width        : 1315px;

        .header {
            font-size  : 2.2rem;
            font-weight: bolder;
            color      : #F2E627;
        }

        .content {
            margin-top : 1rem;
            line-height: 1.8;
        }

        .action {
            margin-top : 3rem;
            margin-left: 7rem;

            .item {
                display        : inline-block;
                color          : #FFF;
                text-decoration: none;
                padding        : .25rem 2rem;
                text-shadow    : none;
                font-weight    : bold;
                border-radius  : 100rem;
                border         : 2px solid #FFF;
            }
        }
    }
}
</style>
