<template>
  <div>
    <img
      class="img-fluid"
      :src="
        require(`../asset/images/selfieAuth/banner${
          isMobile ? '-mobile' : ''
        }.png`)
      "
      alt="banner"
    />

    <div class="container my-5 d-flex flex-column align-items-center">
      <div id="intro-video">
        <img
          src="../asset/images/selfieAuth/video-poster.png"
          alt="poster"
          class="img-fluid"
          @click="() => (showVideo = true)"
          v-show="!showVideo"
        />
        <!-- width="853"
          height="480"
           -->
        <iframe
          :width="isMobile ? 480 : 640"
          height="360"
          v-show="showVideo"
          src="https://www.youtube.com/embed/iyNxcXqME-4"
          title="SelfieAuth 數位身分驗證_持證自拍產品發布會"
          frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen
        ></iframe>
        <!-- <iframe
          src="https://drive.google.com/file/d/1oIWlsrjO5lqEWfoHYauq0kd4UMh4mE8n/preview"
        >
        </iframe> -->
      </div>
      <img
        class="img-fluid"
        :src="
          require(`../asset/images/selfieAuth/section1${
            isMobile ? '-mobile' : ''
          }.png`)
        "
        alt="section1"
      />
    </div>

    <div class="container my-5">
      <img
        class="img-fluid"
        :src="
          require(`../asset/images/selfieAuth/section2${
            isMobile ? '-mobile' : ''
          }.png`)
        "
        alt="section2"
      />
    </div>

    <img
      class="img-fluid"
      :src="
        require(`../asset/images/selfieAuth/section${index}${
          isMobile ? '-mobile' : ''
        }.png`)
      "
      :alt="`section${index}`"
      v-for="index in Array.from(
        Array.from(Array(7))
          .map((_, index) => index + 1)
          .slice(2)
      )"
      :key="index"
      :style="isMobile && index === 3 ? `width:100vw` : ``"
    />
  </div>
</template>

<script>
export default {
  data() {
    return {
      isMobile: window.innerWidth <= 768, // 默认为桌面设备
      showVideo: true,
    }
  },
  mounted() {
    // 监听窗口大小变化事件
    window.addEventListener('resize', this.checkScreenSize)
    // 初始化时调用一次检查
    this.checkScreenSize()
  },
  beforeDestroy() {
    // 在组件销毁前移除事件监听
    window.removeEventListener('resize', this.checkScreenSize)
  },
  methods: {
    checkScreenSize() {
      // 使用媒体查询来检查是否为移动设备
      const isMobileDevice = window.matchMedia('(max-width: 768px)').matches

      // 检查窗口宽度是否小于等于768px
      if (window.innerWidth <= 768) {
        this.isMobile = true
      } else {
        this.isMobile = isMobileDevice // 如果窗口宽度较大，根据媒体查询结果来设置isMobile
      }
    },
  },
}
</script>

<style lang="scss" scoped>
#intro-video {
  position: relative;
  top: -15vh;

  @media only screen and (max-width: 768px) {
    position: inherit;
  }
}
</style>
