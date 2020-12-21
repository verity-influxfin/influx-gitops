<template>
  <div class="download-card">
    <img src="../asset/images/3013240.jpg" class="img-fluid" />
    <div class="abs">
      <div class="d-desc">
        <div class="t-c">
          <h2>Get APP</h2>
        </div>
        <hr />
        <p>前往APP下載區 立即啟動服務</p>
        <div class="d-apps">
          <div v-if="$props.isLoan" class="d-loan">
            <a
              class="d-link"
              target="_blank"
              href="https://event.influxfin.com/R/url?p=webbanner"
            >
              我想借款
            </a>
          </div>
          <div v-if="$props.isInvest" class="d-invest">
            <a
              class="d-link"
              target="_blank"
              href="https://event.influxfin.com/r/iurl?p=webinvest"
            >
              我想投資
            </a>
          </div>
        </div>
      </div>
      <div class="img">
        <img :src="'/images/phone.png'" class="img-fluid" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["isLoan", "isInvest"],
};
</script>

<style lang="scss">
.download-card {
  position: relative;
  overflow: hidden;

  .abs {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    display: flex;
    transform: translate(0px, -50%);
  }

  .d-desc {
    width: 55%;
    padding: 4rem;

    hr {
      border-top: 2px solid #16559b;
      width: 300px;
      margin: 2rem auto;
    }

    p {
      font-size: 20px;
      font-weight: 700;
      text-align: center;
      color: #1c2a54;
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
          display: block;
          margin: 20px auto;
          padding: 2px 18px 3px;
          border-radius: 23.5px;
          backdrop-filter: blur(10px);
          border: solid 2px #1f55a0;
          background-color: rgba(216, 216, 216, 0.1);
          font-size: 20px;
          font-weight: 800;
          color: #1f55a0;
          text-align: center;
          width: fit-content;

          &:hover {
            text-decoration: none;
          }
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
  }

  .img {
    width: 40%;
    animation: i-float 4s ease-in-out infinite;
    position: relative;
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
    img {
      height: 270px;
      max-width: initial;
      position: absolute;
      top: 0;
      z-index: 0;
    }
    
    .abs {
      position: relative;
      transform: inherit;

      .d-desc {
        width: 100%;
        padding: 2rem;
      }
    }

    .img {
      display: none;
    }
  }
}
</style>
