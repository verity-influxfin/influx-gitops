<template>
  <div class="blog-quiklink" ref="quiklink">
    <a class="icon" href="https://line.me/R/ti/p/%40kvd1654s" target="_blank"
      ><img src="/images/alesis-chat-symbol.svg" class="img-fluid"
    /></a>
  </div>
</template>

<script>
export default {
  data: () => ({
    offset: {},
  }),
  created() {
    window.addEventListener("scroll", this.handleScroll);
  },
  mounted() {
    this.offset = $(this.$refs.quiklink).offset();
  },
  methods: {
    handleScroll() {
      if (window.scrollY > this.offset.top) {
        $(this.$refs.quiklink)
          .stop()
          .animate({
            marginTop: window.scrollY + this.offset.top,
          });
      } else {
        $(this.$refs.quiklink).stop().animate({
          marginTop: this.offset.top,
        });
      }
    },
  },
};
</script>

<style lang="scss">
.blog-quiklink {
  z-index: 2;
  float: right;
  margin-top: 30%;
  margin-bottom: 1rem;
  position: absolute;
  right: 10px;
  color: #ff5151;
  display: flex;
  animation: float 4s ease-in-out infinite;
  filter: drop-shadow(0px 0px 5px rgba(0, 0, 0, 0.1));
  top: 0px;

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

    .icon {
      width: 80px;
      margin-top: -.8rem;
    }
  }
}
</style>
