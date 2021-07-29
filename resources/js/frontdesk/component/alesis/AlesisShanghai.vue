<template>
    <div class="上海容器">
        <img class="商標" src="/images/shanghai.png">
        <div class="分隔線"></div>
        <div class="表單" v-if="isForm">
            <input class="輸入欄位" type="text" placeholder="姓名">
            <input class="輸入欄位" type="text" placeholder="*手機">
            <input class="輸入欄位" type="text" placeholder="*E-mail">
            <a href="#!" class="按鈕">前往優惠</a>
            <div class="註釋">
                信用貸款總費用年百分率說明：貸款總費用年百分率約2.62%~7.72%。例如：貸款金額：30萬元，貸款期間：5年，貸款年利率：前三個月1.88% (固定利率)，第四個月起2.25%~7.92%，開辦費為新臺幣3,000元，其總費用年百分率約為2.62%~7.72%。(1)本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，以本行提供之產品為準，且每一顧客實際之年百分率仍以其個別貸款產品及授信條件而有所不同。(2)總費用年百分率不等於貸款利率。(3)本總費用年百分率之計算基準日係依據活動專案適用起日之本行貸款定儲指數(G)調整日期訂定之(當期利率請至本行網站查詢)，請詳閱本行官網定儲指數說明。例如：110年04月23日之貸款定儲指數(G)為0.80%。(4)上海商銀保有依申貸人資信條件核定貸款條件、實際核貸金額以及最終核准貸款與否之權利。
            </div>
        </div>
        <template v-if="!isForm">
            <div class="特色清單">
                <div class="項目">1日快速核准</div>
                <div class="項目">前3個月利率1.88%起</div>
                <div class="項目">額度最高達300萬，期間最長7年</div>
            </div>
            <div class="動作群組">
                <a class="動作" href="#!"><alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">了解更多</alesis-button></a>
                <a class="動作" href="#!" @click="() => isForm = true"><alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">取得資格</alesis-button></a>
            </div>
        </template>
    </div>
</template>

<script>
import AlesisButton from "./AlesisButton";

export default {
    name : "AlesisShanghai",
    components: {
        AlesisButton,
    },
    data() {
        return {
            isForm: false,
        }
    }
};
</script>

<style lang="scss" scoped>
.上海容器 {
    display      : inline-block;
    border-radius: 17px;
    border       : 1px solid #2664a5;
    padding      : 1.5rem 1.25rem;
    max-width    : 310px;
    text-align   : justify;
    font-size    : 1.1rem;
}

.上海容器 .商標 {
    margin : 0 auto;
    display: block;
}

.上海容器 .分隔線 {
    height    : 1px;
    background: #2664a5;
    margin    : 1rem 0;
}

.上海容器 .表單 {
    display       : flex;
    flex-direction: column;
}

.上海容器 .表單 .輸入欄位 {
    appearance   : none;
    border       : 1px solid #326398;
    border-radius: .4rem;
    line-height  : 2.2;
    padding      : 0 .5rem;
    outline      : none;
}

.上海容器 .表單 .按鈕 {
    background   : #326398;
    width        : 100%;
    display      : block;
    color        : #FFF;
    padding      : .5rem;
    text-align   : center;
    border-radius: .4rem;
}

.上海容器 .表單 > *:not(:last-child) {
    margin-bottom: 1rem;
}

.上海容器 .表單 .註釋 {
    font-size: .8rem;
}

.上海容器 .特色清單 {
    line-height: 2;
    margin-left: 1rem;
}

.上海容器 .特色清單 .項目 {
    display: list-item;
}

.上海容器 .動作群組 {
    margin-top: 1rem;
    display   : flex;
}

.上海容器 .動作群組 .動作 {
    flex         : 1;
    margin-right : 1rem;
}

.上海容器 .動作群組 .動作 + .動作 {
    margin-right: 0;
}
</style>
