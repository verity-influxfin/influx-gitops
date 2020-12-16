<template>
  <div class="target-card">
    <div class="t-c"><h2>適用對象</h2></div>
    <div class="hr"></div>
    <p v-html="text"></p>
    <div class="i-row">
      <div class="item" v-for="(item, index) in $props.items" :key="index">
        <div class="img"><img :src="item.imgSrc" class="img-fluid" /></div>
        <p>{{ item.text }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["items", "text"],
};
</script>

<style lang="scss">
.target-card {
  background-color: #ffffff;
  padding: 40px 30px;

  p {
    margin: 0.5rem auto;
    font-size: 17px;
    font-weight: 700;
    letter-spacing: 1px;
    text-align: center;
    color: #153a71;
  }

  .i-row {
    display: flex;
    justify-content: space-around;

    .item {
      .img {
        width: 85px;
        border: 3px solid #153a71;
        border-radius: 50%;
        padding: 15px;
        margin: 0px auto;
      }
    }
  }
}

@media (max-width: 767px) {
  .target-card {
    padding: 10px;

    .i-row {
      display: block;
      overflow: hidden;
      .item {
        float: left;
        width: calc(100% / 3);
        .img {
          width: calc(100% - 20px);
          margin: 10px;
        }
      }
    }
  }
}
</style>
