<template>
  <div class="video-wrapper">
    <div v-for="(item,index) in shares" class="video-container" :key="index">
      <div class="video-iframe">
        <iframe
          :src="item.video_link"
          frameborder="0"
          allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
        ></iframe>
      </div>
      <div class="video-info">
        <h3>{{item.post_title}}</h3>
        <router-link
          v-if="item.type === 'video'"
          :to="'/videopage/'+item.ID"
          class="btn btn-info"
        >了解更多</router-link>
        <a
          v-if="item.category === 'loan'"
          href="https://event.influxfin.com/line?event=web"
          target="_blank"
          class="btn btn-warning"
        >立即借款</a>
        <a
          v-if="item.category === 'invest'"
          href="https://event.influxfin.com/r/iurl?p=webinvest"
          target="_blank"
          class="btn btn-primary"
        >立即投資</a>
        <a
          v-if="item.category === 'sponsor'"
          href="https://docs.google.com/forms/d/1Pp02TNm2wtWZdUwJpuW1J_ZCjx2QR_h8pgU5PNiE6ks/viewform?edit_requested=true"
          target="_blank"
          class="btn btn-success"
        >贊助申請</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    shares() {
      return this.$store.getters.SharesData;
    }
  },
  watch: {
    $route(to, from) {
      this.refresh();
    }
  },
  created() {
    this.refresh();
    $("title").text(`影音分享 - inFlux普匯金融科技`);
  },
  methods: {
    refresh() {
      let category = this.$route.params.category;
      this.$store.dispatch("getSharesData", { category });
    }
  }
};
</script>

<style lang="scss">
.video-wrapper {
  .video-container {
    width: 80%;
    margin: 25px auto;
    display: flex;

    .video-iframe {
      iframe {
        width: 728px;
        height: 410px;
      }
    }

    .video-info {
      margin: 25px;
    }

    @media (max-width: 1025px) {
      display: block;

      .video-iframe {
        iframe {
          width: 100%;
          height: 350px;
        }
      }
    }
    @media (max-width: 767px) {
      display: block;

      .video-iframe {
        iframe {
          width: auto;
          height: auto;
        }
      }

      .video-info {
        margin: 0px;
      }
    }
  }
}
</style>
