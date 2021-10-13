<template>
<div class="article-wrapper">
    <div class="flex">
        <div :class="[{
                'main-view': !search.includes('news'),
                'news-view': search.includes('news'),
            }]"
            >
            <h3 class="title" v-if="this.articleTitle">{{ this.articleTitle }}</h3>
            <div class="contenier">
                <div class="title-img" v-if="this.articleImg">
                    <img :src="this.articleImg" class="img-fluid" />
                </div>
                <div
                    class="main-content"
                    ref="articleHtml"
                    v-if="this.articleHtml"
                    v-html="this.articleHtml"
                ></div>
            </div>
            <div class="comments" v-if="!search.includes('news')">
                <fb:comments
                :href="this.link"
                num_posts="10"
                notify="true"
                :width="(this.width * 0.99).toFixed(0)"
                ></fb:comments>
                <div class="shere-btn">
                    <shareBtn :link="this.link"></shareBtn>
                </div>
            </div>
        </div>
        <div class="sub" v-if="!search.includes('news')">
            <div class="box">
                <h4>最新文章</h4>
                <div>
                    <div class="latest" v-for="(item, index) in latest" :key="index">
                        <a :href="item.link">{{ item.post_title }}</a>
                        <div class="float-right">－{{ item.post_modified.substr(0, 10) }}</div>
                    </div>
                </div>
            </div>
            <div class="box">
                <h4>分享文章</h4>
                <shareBtn :link="this.link"></shareBtn>
            </div>
            <div class="box">
                <h4>時間排序</h4>
                {{ group }}
                <tree :data="list" :key="new Date()">
                    <span class="tree-text" slot-scope="{ node }">
                        <template v-if="!node.hasChildren()">
                        －
                        <a :title="node.text.text" :href="node.text.link">{{
                            `${node.text.text.substr(0, 10)}...`
                        }}</a>
                        </template>
                        <template v-else>
                        <i
                        :class="[node.expanded() ? 'fas fa-folder-open' : 'fas fa-folder']"
                        ></i>
                        {{ node.text }}
                        </template>
                    </span>
                </tree>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import shareBtnComponent from "../../component/shareBtnComponent";
import LiquorTree from "liquor-tree";

export default {
  components: {
    shareBtn: shareBtnComponent,
    tree: LiquorTree,
  },
  data: () => ({
    width: window.outerWidth,
    link: window.location.toString(),
    articleTitle: "",
    articleImg: "",
    articleHtml: "",
    search: "",
  }),
  created() {
    this.getArticleData();
    if (!this.search.includes("news")) {
      this.$store.dispatch("getKnowledgeData");
    }
  },
  mounted() {},
  computed: {
    latest() {
      return this.$store.getters.KnowledgeData.splice(0, 3);
    },
    list() {
      let groups = [];
      this.$store.getters.KnowledgeData.forEach((kItem) => {
        let dateItem = kItem.post_date.substr(0, 7).split("-");
        if (groups.filter((gItem) => gItem.text === dateItem[0]).length === 0) {
          groups.push({ text: dateItem[0], children: [] });
        }
      });

      this.$store.getters.KnowledgeData.forEach((kItem) => {
        let dateItem = kItem.post_date.substr(0, 7).split("-");
        groups.forEach((gItem) => {
          if (
            gItem.text === dateItem[0] &&
            gItem.children.filter((iItem) => iItem.text === dateItem[1]).length === 0
          ) {
            gItem.children.push({ text: dateItem[1], children: [] });
          }
        });
      });

      this.$store.getters.KnowledgeData.forEach((kItem) => {
        groups.forEach((gItem, gindex) => {
          gItem.children.forEach((iItem, rindex) => {
            if (kItem.post_date.substr(0, 7) === `${gItem.text}-${iItem.text}`) {
              groups[gindex].children[rindex].children.push({
                text: { text: kItem.post_title, link: kItem.link },
              });
            }
          });
        });
      });

      return groups;
    },
  },
  methods: {
    async getArticleData() {
      const urlParams = new URLSearchParams(window.location.search);
      this.search = urlParams.get("q");
      let type = urlParams.get("q").split("-");
      if (type[0] == "news") {
        let res = await axios.post(`${location.origin}/getNewsArticle`, {
          ID: type[1],
        });

        $("title").text(`${res.data.post_title} - inFlux普匯金融科技`);
        FB.XFBML.parse();
        this.articleTitle = res.data.post_title;
        this.articleImg = res.data.image_url ? res.data.image_url : "";
        this.articleHtml = res.data.post_content;
      } else {
        let res = await axios.post(`${location.origin}/getArticleData`, {
          filter: this.search,
        });

        $("title").text(`${res.data.post_title} - inFlux普匯金融科技`);
        FB.XFBML.parse();
        this.articleTitle = res.data.post_title;
        this.articleImg = res.data.media_link ? res.data.media_link : "";
        this.articleHtml = res.data.post_content;
      }

      this.$nextTick(() => {
        $('meta[name="description"]').attr(
          "content",
          $(this.$refs.articleHtml)[0].innerText.substr(0, 150)
        );
      });
    },
  },
};
</script>

<style lang="scss">
.article-wrapper {
  width: 100%;
  overflow: hidden;
  padding: 50px 30px 30px 30px;
  background-image: url("../../asset/images/article.png");
  background-position: 50% 50%;
  background-repeat: no-repeat;
  background-size: 100% 100%;

  .title {
    font-size: 40px;
    font-weight: bold;
    line-height: normal;
    text-align: center;
    color: #061164;
    margin-bottom: 4rem;
  }

  .flex {
    display: flex;
  }

  %contenier {
    .contenier {
      .title,
      .title-img {
        text-align: center;

        img {
          width: 600px;
        }
      }

      .main-content {
        padding: 20px;

        img {
          width: 100% !important;
          height: auto !important;
        }
      }
    }
  }

  .news-view {
    width: 80%;
    margin: 0px auto;

    @extend %contenier;
  }

  .main-view {
    width: 70%;
    margin-left: 7rem;
    margin-right: 2rem;

    @extend %contenier;
    .comments {
      margin-top: 50px;
      position: relative;
      border-radius: 10px;
      box-shadow: 0px 2px 5px 0 #6ab0f2;
      background-color: rgba(255, 255, 255, 0.7);
      padding: 15px;
    }
  }

  .sub {
    width: 30%;
    margin-right: 6rem;

    .box {
      margin-bottom: 3rem;

      .latest {
        $gray: #9b9b9b;

        overflow: auto;
        margin-bottom: 5px;
        a {
          color: $gray;
          text-align: justify;
          display: block;
        }

        color: $gray;
      }
    }
  }

  .share-btn {
    display: none;
  }
}

@media screen and (max-width: 767px) {
  .article-wrapper {
    padding: 20px 10px 10px 10px;

    .title {
      font-size: 20px;
      margin-bottom: 1rem;
    }

    .title-img {
      img {
        width: 100%;
      }
    }

    .flex {
      flex-direction: column;
    }

    .news-view {
      width: 100%;
      margin: 0px auto;
    }

    .main-view {
      width: 100%;
      margin: 0px auto;
    }

    .sub {
      display: none;
    }

    .share-btn {
      display: block;
    }
  }
}
</style>
