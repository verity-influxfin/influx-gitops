<template>
  <div class="download-card">
    <div class="d-desc">
      <h5>Get APP</h5>
      <hr />
      <p>選擇適合的平台下載APP</p>
      <div class="d-apps">
        <div v-if="$props.isLoan" class="d-loan">
          <p>借款</p>
          <a class="d-link" target="_blank" href="https://event.influxfin.com/R/url?p=webbanner">
            <appleApp type="loan" />
          </a>
          <a class="d-link" target="_blank" href="https://event.influxfin.com/R/url?p=webbanner">
            <googleApp type="loan" />
          </a>
        </div>
        <div v-if="$props.isInvest" class="d-invest">
          <p>投資</p>
          <a class="d-link" target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest">
            <appleApp type="invest" />
          </a>
          <a class="d-link" target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest">
            <googleApp type="invest" />
          </a>
        </div>
      </div>
    </div>
    <div class="img">
      <img :src="'./images/phone.png'" class="img-fluid" />
    </div>
  </div>
</template>

<script>
import googleApp from "../component/svg/googleAppComponent";
import appleApp from "../component/svg/appleAppComponent";
export default {
  components: {
    googleApp,
    appleApp,
  },
  props: ["isLoan","isInvest"],
};
</script>

<style lang="scss">
.download-card {
  position: relative;
  background-image: url("../asset/images/3013240.png");
  background-repeat: no-repeat;
  background-size: 100% 100%;
  overflow: hidden;
  display: flex;

  .d-desc {
    width: 70%;
    padding: 4rem;
    h5 {
      font-size: 36.5px;
      text-align: center;
      color: #ffffff;
    }

    hr {
      border-top: 2px solid #ffffff;
      width: 10%;
      margin: 2rem auto;
    }

    p {
      line-height: 1.8;
      letter-spacing: 1.74px;
      text-align: center;
      color: #ffffff;
    }
  }

  .d-apps {
    display: flex;
    width: 70%;
    margin: 0px auto;

    %font {
      width: 100%;

      p {
        font-size: 18px;
        font-weight: bold;
        line-height: 1.55;
        letter-spacing: 2.02px;
        text-align: center;
      }

      .d-link {
        width: 165px;
        display: block;
        margin: 20px auto;
      }
    }

    .d-loan {
      @extend %font;

      p {
        color: #08deb1;
      }
    }

    .d-invest {
      @extend %font;

      p {
        color: #fbd900;
      }
    }
  }

  .img {
    width: 30%;
    animation: i-float 4s ease-in-out infinite;
    position: relative;

    img {
      transform: translateY(10px);
      position: absolute;
      bottom: 0px;
    }
  }
}

@keyframes i-float {
  0% {
    transform: translatey(0px);
  }

  50% {
    transform: translatey(-10px);
  }

  100% {
    transform: translatey(0px);
  }
}

@media screen and (max-width: 767px) {
  .download-card {
    background-size: auto 100%;

    .d-desc {
      width: 100%;
      padding: 2rem;
    }

    .img {
      display: none;
    }

    .d-apps {
      width: 100%;

      %font {
        width: 100%;

        .d-link {
          width: 80%;
          margin: 10px auto;
        }
      }
    }
  }
}
</style>