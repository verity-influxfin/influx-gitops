<template>
  <div class="progress-overview">
    <div class="progress-overview-title">
      {{ progressOverview.title }}
    </div>
    <div class="progress-overview-item bb">
      <div
        class="item-title"
        :class="{ none: progressOverview.principal.process.now === 0 }"
      >
        {{ progressOverview.principal.title }}
      </div>
      <div class="item-progress d-flex">
        <div class="item-bar-group col">
          <span
            class="bar-left"
            :style="{ width: progressOverview.principal.process.percentage }"
          ></span>
          <span
            class="bar-dot"
            v-show="progressOverview.principal.process.now > 0"
          ></span>
          <span
            class="bar-right"
            :class="{ col: progressOverview.principal.process.now !== 6 }"
          ></span>
        </div>
        <div class="item-progress-text col-auto">
          {{ progressOverview.principal.process.now }} /
          {{ progressOverview.principal.process.all }}
        </div>
      </div>
    </div>
    <div class="progress-overview-item bb">
      <div
        class="item-title"
        :class="{ none: progressOverview.company.process.now === 0 }"
      >
        {{ progressOverview.company.title }}
      </div>
      <div class="item-progress d-flex">
        <div class="item-bar-group col">
          <span
            class="bar-left"
            :style="{ width: progressOverview.company.process.percentage }"
          ></span>
          <span
            class="bar-dot"
            v-show="progressOverview.company.process.now > 0"
          ></span>
          <span
            class="bar-right"
            :class="{ col: progressOverview.company.process.now !== 8 }"
          ></span>
        </div>
        <div class="item-progress-text col-auto">
          {{ progressOverview.company.process.now }} /
          {{ progressOverview.company.process.all }}
        </div>
      </div>
    </div>
    <div class="progress-overview-item">
      <div
        class="item-title"
        :class="{ none: progressOverview.guarantor.process.now === 0 }"
      >
        {{ progressOverview.guarantor.title }}
      </div>
      <div class="item-progress d-flex">
        <div class="item-bar-group col">
          <span
            class="bar-left"
            :style="{ width: progressOverview.guarantor.process.percentage }"
          ></span>
          <span
            class="bar-dot"
            v-show="progressOverview.guarantor.process.now > 0"
          ></span>
          <span class="bar-right w-100"></span>
        </div>
        <div class="item-progress-text col-auto">
          {{ progressOverview.guarantor.process.now }} /
          {{ progressOverview.guarantor.process.all }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    progressOverview() {
      return this.$store.state.enterprise.progressOverview
    }
  },
}
</script>

<style lang="scss" scoped>
.progress-overview {
  padding: 30px;
  background: #ffffff;
  border-radius: 20px;
  .progress-overview-title {
    color: #393939;
    font-style: normal;
    font-weight: 500;
    font-size: 24px;
    line-height: 35px;
    margin-bottom: 15px;
  }
  .progress-overview-item {
    color: #707070;
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 26px;
    padding-bottom: 10px;
    margin-bottom: 15px;
    &.bb {
      border-bottom: 1.5px solid #f3f3f3;
    }
    .item-title {
      font-style: normal;
      font-weight: 500;
      font-size: 14px;
      line-height: 20px;
      padding: 4px 13px;
      border-radius: 6px;
      width: fit-content;
      margin-bottom: 15px;
      color: #036eb7;
      border: 1.5px solid #036eb7;
      &.none {
        border: 1.5px solid #f3f3f3;
        color: #797979;
      }
    }
    .item-bar-group {
      padding: 0;
      display: flex;
      align-items: center;
      border-radius: 3px;
      .bar-left {
        background: #f29600;
        height: 5px;
        border-radius: 3px;
      }
      .bar-dot {
        margin-left: -4px;
        background: #f29600;
        width: 12px;
        height: 12px;
        border-radius: 50%;
      }
      .bar-right {
        height: 5px;
        background: #f3f3f3;
        border-radius: 3px;
      }
    }
    .item-progress-text {
      color: #393939;
      font-style: normal;
      font-weight: 500;
      font-size: 12px;
      line-height: 17px;
    }
  }
}
</style>
