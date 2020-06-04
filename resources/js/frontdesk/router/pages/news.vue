<template>
  <div class="news-page-wrapper">
    <h1>最新消息</h1>

    <ul class="news-content" ref="content"></ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
let newsRow = Vue.extend({
  props: ["item"],
  template: `
      <li class="card">
        <a :href="'#'+item.link" style="text-align: center;">
          <img :src="item.media_link" class="img-custom" />
        </a>
        <span>{{item.post_title}}</span>
        <p>
          <span class="gray">Posted by</span>
          {{item.author}}
          <span class="gray">Comments:</span>
        </p>
        <p class="gray">{{item.post_content}}…</p>
        <a class="btn btn-info" :href="'#'+item.link">Read More</a>
      </li>
  `
});

export default {
  computed: {
    news() {
      let $this = this;
      $.each($this.$store.getters.NewsData, (index, row) => {
        $this.$store.getters.NewsData[
          index
        ].post_content = `${row.post_content
          .replace(/<[^>]*>/g, "")
          .substr(0, 10)}...`;
      });
      return $this.$store.getters.NewsData;
    }
  },
  created() {
    this.$store.dispatch("getNewsData");
    $("title").text(`最新消息 - inFlux普匯金融科技`);
  },
  watch: {
    news() {
      this.pagination();
    }
  },
  mounted() {
    this.pagination();
  },
  methods: {
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.news,
          callback(data, pagination) {
            $($this.$refs.content).html("");
            data.forEach((item, index) => {
              let component = new newsRow({
                propsData: {
                  item
                }
              }).$mount();

              $($this.$refs.content).append(component.$el);
            });
          }
        });
      });
    }
  }
};
</script>

<style lang="scss">
.news-page-wrapper {
  padding: 30px;
  overflow: auto;

  h1 {
    font-weight: bolder;
    text-align: center;
    margin-bottom: 30px;
  }

  .news-content {
    width: 75%;
    overflow: auto;
    margin: 0px auto;
    padding: 0px;

    .img-custom {
      height: 300px;
      max-width: 100%;
    }

    .card {
      margin: 10px;
      width: 48%;
      padding: 20px;
      float: left;
    }

    .gray {
      color: #9a9a9a;
    }

    .btn-info {
      width: fit-content;
    }
  }

  .pagination {
    margin: 0px auto;
    width: fit-content;
  }

  @media (max-width: 1023px) {
    .news-content {
      width: 95%;

      .card {
        width: 46%;
      }
    }
  }

  @media (max-width: 767px) {
    .news-content {
      width: 100%;

      .card {
        width: 96%;
      }
    }
  }
}
</style>

