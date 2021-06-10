<template>
    <div class="box">
        <img class="logo" src="/images/shanghai.png" alt="">
        <div class="divider"></div>
        <div class="form" v-if="isForm">
            <input class="input" type="text" placeholder="姓名">
            <input class="input" type="text" placeholder="*手機">
            <input class="input" type="text" placeholder="*E-mail">
            <a href="#!" class="button">前往優惠</a>
            <div class="description">
                信用貸款總費用年百分率說明：貸款總費用年百分率約2.62%~7.72%。例如：貸款金額：30萬元，貸款期間：5年，貸款年利率：前三個月1.88% (固定利率)，第四個月起2.25%~7.92%，開辦費為新臺幣3,000元，其總費用年百分率約為2.62%~7.72%。(1)本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，以本行提供之產品為準，且每一顧客實際之年百分率仍以其個別貸款產品及授信條件而有所不同。(2)總費用年百分率不等於貸款利率。(3)本總費用年百分率之計算基準日係依據活動專案適用起日之本行貸款定儲指數(G)調整日期訂定之(當期利率請至本行網站查詢)，請詳閱本行官網定儲指數說明。例如：110年04月23日之貸款定儲指數(G)為0.80%。(4)上海商銀保有依申貸人資信條件核定貸款條件、實際核貸金額以及最終核准貸款與否之權利。
            </div>
        </div>
        <template v-if="!isForm">
            <div class="list">
                <div class="item">1日快速核准</div>
                <div class="item">前3個月利率1.88%起</div>
                <div class="item">額度最高達300萬，期間最長7年</div>
            </div>
            <div class="buttons">
                <a class="button" href="#!"><index-button :fluid="true" yPadding=".4rem" size="1.1rem">了解更多</index-button></a>
                <a class="button" href="#!" @click="() => isForm = true"><index-button :fluid="true" yPadding=".4rem" size="1.1rem">取得資格</index-button></a>
            </div>
        </template>
    </div>
</template>

<script>
import indexButton from "./button";

export default {
    name : "Shanghai",
    components: {
        indexButton,
    },
    data() {
        return {
            isForm: false,
        }
    }
};
</script>

<style lang="scss" scoped>
.box {
    display      : inline-block;
    border-radius: 17px;
    border       : 1px solid #2664a5;
    padding      : 1.5rem 1rem;
    max-width    : 290px;
    text-align   : justify;

    .logo {
        margin : 0 auto;
        display: block;
    }

    .form {
        display       : flex;
        flex-direction: column;

        .input {
            appearance   : none;
            border       : 1px solid #326398;
            border-radius: .4rem;
            line-height  : 2.2;
            padding      : 0 .5rem;
        }

        .button {
            background   : #326398;
            width        : 100%;
            display      : block;
            color        : #FFF;
            padding      : .5rem;
            text-align   : center;
            border-radius: .4rem;
        }

        > *:not(:last-child) {
            margin-bottom: 1rem;
        }

        .description {
            font-size: .8rem;
        }
    }

    .divider {
        height    : 1px;
        background: #2664a5;
        margin    : 1rem 0;
    }

    .list {
        line-height: 2;
        margin-left: 1rem;

        .item {
            display: list-item;
        }
    }

    .buttons {
        margin-top: 1rem;
        display   : flex;

        > .button {
            flex         : 1;
            margin-right : 1rem;

            & + .button {
                margin-right: 0;
            }
        }
    }
}
</style>
