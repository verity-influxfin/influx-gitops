<template>
  <div class="case-overview">
    <div class="case-overview-title">
      {{ caseOverview.title }}
    </div>
    <div class="case-overview-item">產品：{{ caseOverview.productTitle }}</div>
    <div class="case-overview-item">案件編號：{{ caseOverview.caseNum }}</div>
    <div class="case-overview-item">申貸日期：{{ caseOverview.startDate }}</div>
    <div class="case-overview-item">
      申貸金額：${{ caseOverview.loanMoney }}
    </div>
    <div class="case-overview-item">借款期間：{{ caseOverview.loanDur }}</div>
  </div>
</template>

<script>
export default {
  props: {
    caseOverview: {
      type: Object,
      default() {
        return {}
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.case-overview {
  padding: 30px;
  background: #ffffff;
  border-radius: 20px;
  .case-overview-title {
    color: #393939;
    font-style: normal;
    font-weight: 500;
    font-size: 24px;
    line-height: 35px;
    margin-bottom: 15px;
  }
  .case-overview-item {
    color: #707070;
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 26px;
  }
}
</style>
