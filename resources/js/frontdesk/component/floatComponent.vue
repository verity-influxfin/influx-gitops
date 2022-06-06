<template>
  <div class="blog-quiklink" ref="quiklink">
      <a v-if="floatType === 'line'" href="https://line.me/R/ti/p/%40kvd1654s" target="_blank" class="link-line">
        洽專人服務
      </a>
    <a v-else class="icon" href="https://line.me/R/ti/p/%40kvd1654s" target="_blank"
      ><img src="/images/alesis-chat-symbol.svg" class="img-fluid"
    /></a>
  </div>
</template>

<script>
export default {
    props: {
        floatType: {
            type: String,
            default: ''
        },
    },
};
</script>

<style lang="scss">
.link-line{
    font-size: 20px;
    width: 200px;
    height: 85px;
    color: #fff;
    padding: 23px 12px;
    background-image: url('../asset/images/line_service.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: top center;
    &:hover{
        color: #fff;
    }
}
.blog-quiklink {
  position: fixed;
  bottom: 10%;
  right: 10px;
  z-index: 100;
  color: #ff5151;
  display: flex;
  animation: float 4s ease-in-out infinite;
  filter: drop-shadow(0px 0px 5px rgba(0, 0, 0, 0.1));
  .icon {
    cursor: pointer;
    width: 90px;
  }

  @keyframes float {
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
    margin-top: 600px;
    bottom: 5%;
    right: 0;
    .icon {
      width: 80px;
      margin-top: -.8rem;
    }
  }
}
</style>
