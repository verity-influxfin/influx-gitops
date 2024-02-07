<template>
  <div>
    <!-- phone -->
    <div class="d-sm-none d-flex no-gutters flex-wrap mt-4">
      <div class="col-12">
        <img
          :src="imagePathMobile"
          class="d-block mx-auto img-fluid fund-phone"
        />
      </div>
    </div>
    <!-- web -->
    <div class="d-sm-flex d-none no-gutters justify-content-lg-between">
      <div class="col-12">
        <img
          :src="imagePath"
          class="w-100 img-fluid p-1"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default{
  props: ["year", "headerIndex"],
  computed: {
    imagePath(){
      return require(`@/asset/images/index/header/newyear${this.$props.year}0${this.$props.headerIndex}.png`);
    },
    imagePathMobile(){
      return require(`@/asset/images/index/header/newyear${this.$props.year}0${this.$props.headerIndex}-phone.png`);
    }
  },
}
</script>

<style lang="scss" scoped>
.text-bg {
  height: 325px;
  padding-top: 50px;
}
.btn-join {
  border-radius: 12px;
  padding: 10px;
  color: #fff;
  background: #083a6e;
  box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
  width: 250px;
  font-size: 28px;
}
.people-bg {
  top: -35px;
  position: relative;
  padding-right: 75px;
}
@media screen and (max-width: 576px) {
  .text-bg {
    padding-top: 25px;
    height: 100px;
  }
  .people-bg {
    padding-right: 0;
    display: block;
    margin: 65px auto 0;
  }
  .fund-phone{
    max-height: 330px;
  }
}
</style>
