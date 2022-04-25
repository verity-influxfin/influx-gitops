<template>
  <div class="enterprise-overview">
    <div class="function-tab">
      <div
        class="tab col"
        :class="{
          active: $route.path === '/enterprise-upload/overview/principal',
        }"
        @click="
          changeTab(
            $route.path,
            '/enterprise-upload/overview/principal?case-id=' + caseId
          )
        "
      >
        負責人資料更新
      </div>
      <div
        class="tab col"
        :class="{
          active: $route.path === '/enterprise-upload/overview/company',
        }"
        @click="
          changeTab(
            $route.path,
            '/enterprise-upload/overview/company?case-id=' + caseId
          )
        "
      >
        公司資料更新
      </div>
    </div>
    <transition name="ease">
      <router-view></router-view>
    </transition>
  </div>
</template>

<script>
export default {
  methods: {
    changeTab(from, tab) {
      if (from !== tab) {
        this.$router.push(tab)
      }
    }
  },
  computed: {
    caseId() {
      return this.$route.query['case-id'] ?? ''
    }
  }
}
</script>

<style lang="scss" scoped>
.function-tab {
  display: flex;
  height: 200px;
  gap: 50px;
  margin-bottom: 50px;
  .tab {
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 20px;
    color: #707070;
    background: #fff;
    font-size: 36px;
    font-style: normal;
    font-weight: 500;
    line-height: 52px;
    transition: ease-in 0.5s;
    &.active {
      background-color: #036eb7;
      color: #fff;
    }
  }
}
// .ease-enter-active {
//   transition: ease-in 0.5s;
// }
// .ease-leave-active {
//   transition: ease-out 0.5s;
// }
</style>
