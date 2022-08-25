<template>
  <div class="smeg-suit">
    <div class="tabs">
      <div class="tab" @click="tab = 0" :class="{ active: tab === 0 }">
        新創微型企業
      </div>
      <div class="tab" @click="tab = 1" :class="{ active: tab === 1 }">
        電子商務
      </div>
      <div class="tab" @click="tab = 2" :class="{ active: tab === 2 }">
        中小企業 (主)
      </div>
    </div>
    <div class="main-content">
      <div v-show="tab === 0">
        <div class="content-title">新創微型企業</div>
        <div class="content-sub-title">New Ventures</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>新創比氣長，普匯聯手銀行</div>
          <div>信保提供後盾，提供您營運資金需求</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">適用對象：</div>
              <div>Startups、育成中心團隊、新創企業</div>
            </div>
            <div class="article-info">
              <div>根據天下雜誌統計，</div>
              <div>台灣新創企業在五年存活率僅有1%！</div>
              <div class="mb-4">
                「資金不足」與「投資者撤資」，更名列創業失敗的十大原因！
              </div>
              <div>滿腦子的好點子，卻差臨門一腳的資金投入？</div>
              <div>算得精準的創業資金，卻被市場無情磨損？</div>
              <div class="mb-4">創業維艱，當老闆實在不容易。我們都懂！</div>
              <div>現在就找普匯信保專案融資，讓普匯當您溫暖的事業後盾。</div>
            </div>
          </div>
          <div>
            <img src="@/asset/images/bussinessLoan/smeg-suit-conent-1.png" class="img-fluid">
          </div>
        </div>
      </div>
      <div v-show="tab === 1">
        <div class="content-title">電子商務</div>
        <div class="content-sub-title">E-Commerce</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>異軍突起，有伯樂賞識</div>
          <div>更要有銀彈支持</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">適用對象：</div>
              <div>新型態商業模式、KOL網紅經濟、直播業者</div>
            </div>
            <div class="article-info">
              <div>勤業眾信調查報告顯示，「新業務與新產品」是消費產業在</div>
              <div>台灣新創企業在五年存活率僅有1%！</div>
              <div class="mb-4">
                後疫情時代的兩大投資方向，分別占78.6%和60.7%。
              </div>
              <div>新型態商業模式百百款，除了自身競爭力以外，</div>
              <div>您更需要充裕的資金，讓您總能在多變的市場中擁有優勢。</div>
              <div class="mb-4">千里馬覓得伯樂，普匯幫您覓得銀彈。</div>
              <div>現在就找普匯信保專案融資，讓普匯當您溫暖的事業後盾。</div>
            </div>
          </div>
          <div>
            <img src="@/asset/images/bussinessLoan/smeg-suit-conent-2.png" class="img-fluid">
          </div>
        </div>
      </div>
      <div v-show="tab === 2">
        <div class="content-title">中小企業 (主)</div>
        <div class="content-sub-title">Small and Medium Enterprise</div>
        <div class="content-sub-divider"></div>
        <div class="content-sub-title-2">
          <div>解決老闆們營運週轉、資金收付</div>
          <div>展店裝修...等各種資金需求</div>
        </div>
        <div class="article-content">
          <div>
            <div class="article-title">
              <div class="em">適用對象：</div>
              <div>文創業者、工廠主、商店小老闆、一般公司行號</div>
            </div>
            <div class="article-info">
              <div>通膨發酵，生意大受影響？</div>
              <div>經營擴張，需要更多成長資金？</div>
              <div>想要取得更多資金，提高企業在商場叢林中的競爭籌碼嗎？</div>
              <div class="my-4">
                商場上，各種五花八門的需求，都圍繞著資金打轉！
              </div>
              <div>現在就與普匯一起做打拼吧！透過我們申請信保專案貸款，</div>
              <div>讓您事半功倍。</div>
            </div>
          </div>
          <div>
            <img src="@/asset/images/bussinessLoan/smeg-suit-conent-3.png" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="mt-4 text-center">
        <button class="btn btn-outline-primary">立即申辦</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tab: 0
    }
  },
}
</script>

<style lang="scss" scoped>
.tabs {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 6px;
  .tab {
    align-self: end;
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.4;
    text-align: center;
    color: #ffffff;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 16px 16px 0 0;
    padding: 10px;
    cursor: pointer;
    &.active {
      padding: 12px;
      background: #ffffff;
      font-size: 40px;
      color: #036eb7;
    }
  }
}
.main-content {
  padding: 36px 64px;
  background-color: #fff;
  .content {
    &-title {
      margin-bottom: 12px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      text-align: center;
      color: #023d64;
    }
    &-sub-title {
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: #036eb7;
    }
    &-sub-divider {
      margin: 8px auto;
      width: 40px;
      border-bottom: 3px solid #036eb7;
    }
    &-sub-title-2 {
      font-style: normal;
      font-weight: 500;
      font-size: 32px;
      line-height: 1.4;
      text-align: center;
      color: #f29500;
    }
  }
  .article-content {
    margin-top: 32px;
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 30px;
    .article-title {
      font-style: normal;
      font-weight: 400;
      font-size: 28px;
      line-height: 1.4;
      color: #036eb7;
      padding-left: 15px;
      .em {
        &::before {
          display: inline-block;
          content: '•';
          margin-right: 5px;
        }
        font-weight: 700;
        padding-left: 0;
        margin-left: -15px;
      }
    }
    .article-info {
      margin-top: 24px;
      padding-left: 15px;
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 29px;
      color: #707070;
    }
  }
}
.btn-outline-primary {
  &::before {
    content: '';
    position: absolute;
    top: -2.4px;
    left: -2.4px;
    bottom: -2.4px;
    right: -2px;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 12px;
    z-index: -1;
  }
  position: relative;
  padding: 10px 120px;
  color: #036eb7;
  background-color: #fff;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  text-align: center;
  border-radius: 12px;
}
</style>
