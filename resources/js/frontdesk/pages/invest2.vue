<template>
  <div class="index-wrapper">
      <div class="alesis-slate">
          <div class="wrapper">
              <div class="header">
                  <div class="text">普匯投資</div>
              </div>
              <div class="showcase">
                  <img class="image" src="/images/直手機-14.png" alt="">
              </div>
          </div>
      </div>
  </div>
</template>

<script>
//svg
import routeMap from "../component/svg/routeMapComponent";
import routeMapM from "../component/svg/routeMapMComponent.vue";
//
import indexSection from "../component/alesis/index/section";
import indexSymcard from "../component/alesis/index/symcard";
import indexHuman from "../component/alesis/index/human";

export default {
  components: {
    indexSection,
    indexSymcard,
    indexHuman
    //banner
    // shanghuiBanner,
  },
  data: () => {
  },
};
</script>

<style lang="scss" scoped>
.alesis-slate {
    background-image: url("/images/上班族.png");
    background-size: cover;

    .wrapper {
        display: flex;
        align-items: center;
        position: relative;


        &:before {
  content: ' ';
  display: block;
  position: absolute;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  opacity: 0.6;
  background-image: url("/images/未命名-3.png");
        background-size: cover;
}

        .header {
            flex: 1;

            .text {
                color: #FFF;
    padding-bottom: 0.2rem;
    border-bottom: 1px solid #FFF;
    display: inline-block;
    font-size: 1.8rem;
    font-weight: bolder;
            }
        }
        .showcase {
            flex: 2;
    text-align: center;

            .image {
                height: 520px;
            }
        }
    }
}
</style>
