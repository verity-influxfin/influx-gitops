<template>
  <div class="end-page">
    <div v-if="type === 'apply'">
      <div class="row">
        <div class="col">
          <div class="go-download">前往APP下載區 立即啟動服務</div>
          <div>
            <router-link to="/borrowLink" target="_blank" class="mr-3">
              <img
                class="get-app"
                src="../../asset/images/get-on-apple.png"
                alt="download app"
              />
            </router-link>
            <router-link to="/borrowLink" target="_blank">
              <img
                src="../../asset/images/get-on-google.png"
                class="get-app"
                alt="download app"
              />
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div v-if="type === 'consult'" class="consult">
      <div>我們已收到您的訊息，</div>
      <div>24小時內將有專人為您服務</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: ''
    },
  },
  mounted() {
    if (!this.type) {
      this.$router.back()
    }
  },
}
</script>

<style lang="scss" scoped>
.end-page {
  min-height: 100vh;
  padding: 150px 30px;
  .consult {
    font-size: 32px;
    text-align: center;
  }
  .go-download {
    font-size: 24px;
    line-height: 1.2;
    margin-bottom: 8px;
  }
}
@media screen and (max-width: 767px) {
  .end-page {
    padding: 100px 15px;
    .consult {
      font-size: 24px;
      margin-top: 120px;
      text-align: center;
    }
    .go-download {
      font-size: 20px;
    }
    .get-app {
      width: 140px;
    }
  }
}
</style>
