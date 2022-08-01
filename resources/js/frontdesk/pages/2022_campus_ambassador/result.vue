<template>
  <div class="result-2022">
    <div class="banner">
      <img
        src="@/asset/images/2022_campus_ambassador/2022-campus-result-banner.png"
        class="w-100 img-fluid d-sm-block d-none"
      />
      <img src="@/asset/images/2022_campus_ambassador/2022-campus-result-banner-phone.png" class="w-100 img-fluid d-block d-sm-none" >
    </div>
    <div class="result-main mx-auto">
      <h1 class="h1">普匯明日之星-校園大使</h1>
      <div class="result-table">
        <h2 class="h2">錄取名單</h2>
        <div class="table-item">
          <div class="table-item-title">謝怡樺</div>
          <div>國立中正大學</div>
          <div>哲學系</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">林敬硯</div>
          <div>國立臺北商業大學</div>
          <div>財務金融系(臺北校區)</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">周昀蓉</div>
          <div>國立臺灣科技大學</div>
          <div>企業管理系</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">黃苡晴</div>
          <div>國立臺灣科技大學</div>
          <div>應用外語系</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">柯奕安</div>
          <div>國立臺灣科技大學</div>
          <div>工業管理系</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">王紀璿</div>
          <div>國立交通大學</div>
          <div>資訊管理與財務金融學系財務金融組</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">巫家蓁</div>
          <div>國立政治大學</div>
          <div>企業管理學系</div>
        </div>
        <div class="table-item">
          <div class="table-item-title">張靖玟</div>
          <div>國立彰化師範大學</div>
          <div>財務金融技術學系</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>
.h1 {
  font-style: normal;
  font-weight: 500;
  font-size: 56px;
  line-height: 81px;
  text-align: center;
  color: #393939;
  margin-bottom: 64px;
}
.h2 {
  font-style: normal;
  font-weight: 500;
  font-size: 36px;
  line-height: 52px;
  text-align: center;
  color: #393939;
}
.result-2022 {
  .result-main {
    padding: 100px 150px;
    max-width: 1400px;
    background-image: url("~images/2022_campus_ambassador/2022-campus-benefit-card-bg-2.png"),
      url("~images/2022_campus_ambassador/2022-campus-mission-card-bg-1.png");
    background-repeat: no-repeat;
    background-position: left top, right bottom;
    .result-table {
      padding: 32px 36px;
      box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
      border-radius: 16px;
      .table-item {
        &:nth-child(even) {
          background: #fafbfe;
        }
        &-title {
          color: #036eb7;
        }
        font-style: normal;
        font-weight: 400;
        font-size: 20px;
        line-height: 1.4;
        color: #707070;
        padding: 24px 60px;
        display: grid;
        grid-template-columns: 4fr 7fr 8fr;
        gap: 30px;
      }
    }
  }
}
@media screen and (max-width: 768px) {
  .h1 {
    font-size: 32px;
    line-height: 1.5;
    margin-bottom: 15px;
  }
  .h2 {
    font-size: 24px;
    line-height: 1.4;
  }
  .result-2022 {
    .result-main {
      padding: 30px 15px;
      background-image: none;
      .result-table {
        padding: 18px;
        .table-item {
          &-title {
            color: #036eb7;
          }
          font-size: 14px;
          line-height: 1.4;
          color: #707070;
          padding: 8px 16px;
          display: grid;
          grid-template-columns: auto 1fr 1fr;
          gap: 15px;
        }
      }
    }
  }
}
</style>
