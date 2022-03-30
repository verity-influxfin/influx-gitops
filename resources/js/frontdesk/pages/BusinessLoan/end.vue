<template>
  <div class="end-page">
    <div v-if="type === 'apply'">
      <div class="row justify-content-center">
        <div class="get-message">
          <div>我們已收到您的訊息</div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-auto">
          <img
            src="../../asset/images/end-apply.png"
            class="end-apply-img"
            alt=""
          />
          <div class="download">
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
  min-height: 50vh;
  padding: 150px 30px;
  background-color: #f5f5f5;
  .get-message {
    font-size: 32px;
    line-height: 1.2;
    color: #326398;
    margin-bottom: 8px;
  }
  .consult {
    color: #326398;
    font-size: 32px;
    text-align: center;
  }
  .download {
    margin-top: 10px;
    padding: 20px;
    align-items: center;
    border-radius: 20px;
    background-color: #fff;
    display: flex;
    justify-content: space-between;
  }
  .go-download {
    font-size: 24px;
    line-height: 1.2;
    color: #1c395f;
    margin-bottom: 8px;
  }
}
@media screen and (max-width: 767px) {
  .end-page {
    padding: 50px 15px;
    .consult {
      font-size: 24px;
      margin-top: 120px;
      color: #326398;
      text-align: center;
    }
    .get-message{
        font-size: 20px;
    }
    .download {
      display: block;
    }
    .end-apply-img {
      max-width: 100%;
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
