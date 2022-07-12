<template>
  <div class="apply">
    <div>
      <img
        src="@/asset/images/2022_campus_ambassador/2022-campus-apply-top.png"
        alt="2022普匯校園大使報名形象圖"
        class="img-fluid img-top"
      />
    </div>
    <div class="apply-main">
      <router-view></router-view>
    </div>
    <img
      src="@/asset/images/2022_campus_ambassador/2022-campus-apply-bottom.png"
      alt="2022普匯校園大使報名形象圖"
      class="img-fluid img-bottom"
    />
  </div>
</template>

<style lang="scss" scoped>
.apply{
  position: relative;
  .img-top{
    min-height: 600px;
  }
  .apply-main{
    position: relative;
    display: flex;
    justify-content: center;
    top: -150px;
    min-height: 1000px;
  }
  .img-bottom{
    min-height: 320px;
  }
}
</style>
