<template>
    <div class="blog-wrapper" id="blog-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9">
                    <form onsubmit="return false;" class="search-form d-block d-lg-none mb-4" autocomplete="off" v-if="latest_articles">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="搜尋"
                                autocomplete="off"
                                v-model="filter"
                            />
                            <div class="input-group-append">
                                <button type="button" class="input-group-text btn-search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <template v-if="Array.isArray(filterKnowledge) && filterKnowledge.length === 0">
                    <div class="empty">
                        <div class="empty-img">
                            <img src="../asset/images/empty.svg" class="img-fluid" />
                        </div>
                        <h3>沒有結果</h3>
                        <p>根據您的搜索，我們似乎找不到結果</p>
                    </div>
                    </template>
                    <template v-else>
                        <div class="blog-content">
                            <div class="panel panel-default article" v-for="item in filterKnowledgePage">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col">
                                            <h2 class="title">
                                                <a :href="item.link">{{item.post_title}}</a>
                                            </h2>
                                            <p class="date">{{item.post_date.substr(0,10)}}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-7">
                                            <a :href="item.link">
                                                <img class="cover" :src="item.media_link ? item.media_link : '/images/default-image.png'">
                                            </a>
                                        </div>
                                        <div class="col-lg-5">
                                            <p class="summary" v-html="make_summary(item.post_content)"></p>
                                            <a class="readmore" :href="item.link">繼續閱讀</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination" ref="pagination"></div>
                    </template>
                </div>
                <div class="d-none d-lg-flex col-lg-3 ">
                    <div class="panel panel-default" v-if="latest_articles">
                        <div class="panel-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="/scsbank?move=page" class="ad_aside" target="_blank">
                                        <img src="/images/blog_ad_aside.jpg" />
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <form onsubmit="return false;" class="search-form" autocomplete="off">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="搜尋"
                                                autocomplete="off"
                                                v-model="filter"
                                            />
                                            <div class="input-group-append">
                                                <button type="button" class="input-group-text btn-search">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col latest-article">
                                    <h3 class="section-title">最新文章</h3>
                                    <div class="list-group list-group-flush">
                                        <a v-for="article in latest_articles" :href="article.link" class="list-group-item list-group-item-action">
                                            <h5 class="title">{{article.title}}</h5>
                                            <small class="date">{{article.date}}</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data: () => ({
    filter: "",
    pageHtml: "",
    filterKnowledge: null,
    current_page: $cookies.get('page') ? $cookies.get('page') : 1,
    page_size: 9,
    latest_articles: null,
  }),
  computed: {
    knowledge() {
      return this.$store.getters.KnowledgeData;
    },
    filterKnowledgePage() {
        if (Array.isArray(this.filterKnowledge)) {
            let start = (this.current_page - 1) * this.page_size;
            let end   = (this.current_page * this.page_size) - 1;
            return this.filterKnowledge.slice(start, end);
        }
        return this.filterKnowledge;
    },
  },
  created() {
    $('title').text('小學堂金融科技 - inFlux普匯金融科技');
    let self = this;
    this.$store.dispatch('getKnowledgeData').then(() => {
        self.latest_articles = self.knowledge.map(item => {
                return {
                    title: item.post_title,
                    date: item.post_date.substr(0,10),
                    link: item.link
                };
            }).sort((a, b) => {
            switch (true) {
                case a.date < b.date:
                    return 1;
                case a.date > b.date:
                    return -1;
                default:
                    return 0;
            }
        }).slice(0, 5);
    });
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      particlesJS.load("blog-wrapper", "data/mobile.json");
    });
  },
  watch: {
    knowledge(newVal) {
      this.filterKnowledge = newVal;
      this.pagination();
    },
    filter(newVal) {
      newVal = newVal.toLowerCase();
      this.filterKnowledge = this.knowledge.filter(item => {
        return item.post_title.toLowerCase().indexOf(newVal) > -1;
      });
      this.pagination();
    },
  },
  methods: {
    make_summary(content) {
        return content.replace(/(<([^>]+)>)/gi, '')
                    .trim()
                    .replace(/\&.+\;/gm, '')
                    .substr(0, 140) + '...';
    },
    pagination() {
      let self = this;
      this.$nextTick(() => {
        $('.pagination').pagination({
          dataSource: self.filterKnowledge,
          pageSize: self.page_size,
          pageNumber: self.current_page,
          pageRange: 1,
          prevText: '上一頁',
          nextText: '下一頁',
          callback(data, page) {
            self.current_page = page.pageNumber;
            window.scrollTo(0, 0);
          },
        });
        window.dispatchEvent(new Event("resize"));
      });
    },
  },
};
</script>

<style lang="scss">
@import "../asset/scss/bootstrap/functions";
@import "../asset/scss/bootstrap/variables";
@import "../asset/scss/bootstrap/mixins/_breakpoints";

.blog-wrapper {
  padding: 30px;
  overflow: hidden;
  position: relative;

  .particles-js-canvas-el {
    display: none;
  }

  .blog-content {
    @include media-breakpoint-up(lg) {
        border-right: 2px solid #eee;
        padding-right: 2em;
    }
  }

  .empty {
    text-align: center;

    .empty-img > img {
        height: 16em;
    }

    h3 {
        color: #153a71;
    }

    p {
        color: #5d5555;
    }
  }

  .paginationjs {

    &-prev, &-next {
        &.disabled{
             > a {
                opacity: 1 !important;
                color: #b4b4b4 !important;
             }
        }
    }
    &-ellipsis {
        &.disabled{
             > a {
                opacity: 1 !important;
             }
        }
    }
    &-page {
        &.active{
            border:1px solid #036eb7 !important;
            border-right:none !important;
             > a {
                background: none !important;
                line-height: 28px !important;
             }
        }
    }
    &-prev, &-next, &-page, &-ellipsis {
        border-color: #036eb7 !important;
        > a {
            color: #036eb7 !important;
            height: auto !important;
            @include media-breakpoint-up(lg) {
                font-size: 1.4em !important;
                padding: 3px 12px;
            }
        }
    }
    &-prev, &-prev > a {
        border-radius: 10px 0 0 10px !important;
    }
    &-next, &-next > a {
        border-radius: 0 10px 10px 0 !important;
    }
  }

  .article {
    margin-bottom: 6em;

    .row > div {
        max-height: 14em;
    }

    .title a{
        color: #153a71;
        font-weight: bold;
        text-decoration: none;
    }
    img.cover {
        width: 100%;
        height: 14em;
        object-fit: contain;
    }
    .date {
        color: #5d5555;
        margin-left: .5em;
    }
    .summary {
        color: #706969;
        text-align: justify;
        overflow-wrap: break-word;
    }
    .readmore {
        color: #1a3e74;
        float: right;
        margin: 0;
    }
  }

  .ad_aside {
    img {
        width: 100%;
    }
  }

  .search-form {
    .form-control {
        border-color: #3489c5;
        border-right: none;
    }
    .btn-search {
        border-left: none;
        border-color: #3489c5;
        background: none;
        color: #3489c5;
    }
  }

  .latest-article {
    .section-title {
        color: #153a71;
        font-size: 1.3em;
        font-weight: bold;
    }
    .list-group-item {
        padding: .75rem .5rem;

        &:hover .title {
            text-decoration: underline;
        }
        .title {
            color: #5d5555;
            white-space: nowrap;
            font-size: 1em;
            font-weight: bold;
            width: 16em;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .date {
            color: #036eb7;
        }
    }
  }
}
</style>
