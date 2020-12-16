<template>
  <div class="blog-quiklink" ref="quiklink">
    <div class="icon"><img src="../asset/images/line.svg" class="img-fluid" /></div>
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
  z-index: 1;
  float: right;
  margin-top: 254px;
  position: absolute;
  right: 10px;
  color: #ff5151;
  display: flex;
  animation: float 4s ease-in-out infinite;
  filter: drop-shadow(0px 0px 5px black);
  top: 0px;

  .icon {
    cursor: pointer;
    width: 70px;
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
}
</style>
