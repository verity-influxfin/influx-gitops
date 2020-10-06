<template>
  <div class="shanghui-banner">
    <img
      :src="'/images/6ac45bc4w.jpg'"
      style="width: 100%; height: auto"
      class="hidden-desktop"
    />
    <img
      :src="'/images/66asc13w.jpg'"
      style="width: 100%; height: auto"
      class="hidden-phone"
    />
    <div class="box">
      <router-link class="btn btn-shanghui" to="shanghaipage"
        >活動詳情</router-link
      >

      <a
        class="btn btn-shanghui float-right"
        href="https://apply.scsb.com.tw/openaccount/client?activeCode=&salesCode=8500INFLUX9Q&openExternalBrowser=1"
        target="_balnk"
        onClick="ga('send', 'event', 'Link', 'Click', 'shanghai');"
        >立即開戶</a
      >
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss">
.shanghui-banner {
  position: relative;

  img {
    height: inherit;
  }

  .box {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1;
    bottom: 11%;
    width: 36%;

    .btn-shanghui {
      background: #ffffff;
      color: red;
      font-weight: bold;
      font-size: 25px;
      padding: 5px 20px;
    }
  }

  @media screen and (max-width: 767px) {
    .box {
      bottom: 43%;
      width: 90%;
    }
  }
}
</style>