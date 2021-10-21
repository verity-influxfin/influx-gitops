<template>
    <div class="knowledge-wrapper" id="knowledge-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9">
                    <article class="article">
                        <h1 class="title">{{article.title}}</h1>
                        <div class="info">
                            <span class="date">{{article.date}}</span>
                        </div>
                        <img class="cover" :src="article.cover_img" v-if="article.cover_img" />
                        <div class="content" v-html="article.content"></div>
                    </article>
                    <div class="row share">
                        <div class="col">
                            <span class="title">分享：</span>
                            <button class="btn btn_link link" @click="addToFB">
                                <img :src="'/images/facebook.svg'" class="img-fluid" />
                            </button>
                            <button class="btn btn_link link" @click="addToLINE">
                                <img :src="'/images/line.png'" class="img-fluid" />
                            </button>
                            <!-- <button class="btn btn_link link" data-toggle="modal" data-target="#copy_link_modal">
                                <img :src="'/images/link_grey.svg'" class="img-fluid" />
                            </button> -->
                            <button class="btn btn_link link" @click="copyLink">
                                <img :src="'/images/link_grey.svg'" class="img-fluid" />
                            </button>
                            <span v-if="copied">網址複製成功 !</span>
                        </div>
                    </div>
                    <div class="modal fade" tabindex="-1" id="copy_link_modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="input-group">
                                    <span class="input-group-addon heavy-color">
                                        <img :src="'/images/link.svg'" class="img-fluid" />
                                    </span>
                                    <input type="text" class="form-control" :value="article.link" @click="copy()" />
                                </div>
                                <div v-if="this.isCopyed" class="copyed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-flex col-lg-3 ">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row mb-3">
                                <div class="col">
                                    <a href="/scsbank?move=page" class="ad_aside" target="_blank">
                                        <img src="/images/blog_ad_aside.jpg" />
                                    </a>
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
            latest_articles: null,
            copied: false,
            article: {
                cover_img: null,
                title    : null,
                date     : null,
                content  : null,
            },
        }),
        created() {
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
            this.getArticleData();
        },
        mounted() {
        },
        computed: {
            knowledge() {
                return this.$store.getters.KnowledgeData;
            },
            page_link() {
                return window.location.href;
            }
        },
        methods: {
            async getArticleData() {
                let res = await axios.post(`/getArticleData`, {
                    filter: this.$route.query['q'],
                });

                FB.XFBML.parse();

                if (res.data) {
                    let data = res.data;

                    this.article = {
                        cover_img: data.media_link ? data.media_link : '',
                        title    : data.post_title,
                        date     : data.post_date.substr(0, 10),
                        content  : data.post_content,
                    };

                    $('title').text(`${data.post_title} - inFlux普匯金融科技`);

                    $('meta[name="description"]').attr(
                        'content',
                        data.post_content.substr(0, 150)
                    );
                }
            },

            addToFB() {
              window.open(
                `https://www.addtoany.com/add_to/facebook?linkurl=${this.page_link}`,
                "_blank",
                "top=" +
                  (window.outerHeight / 2 - 265) +
                  ", left=" +
                  (window.outerWidth / 2 - 265) +
                  ",height=530,width=530,toolbar=no,resizable=no,location=no"
              );
            },
            addToLINE() {
              window.open(
                `https://lineit.line.me/share/ui?url=${this.page_link}`,
                "_blank",
                "top=" +
                  (window.outerHeight / 2 - 265) +
                  ", left=" +
                  (window.outerWidth / 2 - 265) +
                  ",height=530,width=530,toolbar=no,resizable=no,location=no"
              );
            },
            copyLink() {
                let self = this;
                navigator.clipboard.writeText(this.page_link).then(function(){
                    self.copied = true;
                    setTimeout(function() {
                        self.copied = false;
                    }, 1000);
                });
            },
        },
    };
</script>


<style lang="scss">
@import "../../asset/scss/bootstrap/functions";
@import "../../asset/scss/bootstrap/variables";
@import "../../asset/scss/bootstrap/mixins/_breakpoints";

.knowledge-wrapper {
  padding: 30px;
  overflow: hidden;
  position: relative;

  .article {
    .title {
        color: #153a71;
        font-weight: bold;
        font-size: 2em;
    }
    img.cover {
        width: 100%;
        object-fit: contain;
        margin-bottom: 1em;
        @include media-breakpoint-up(lg) {
            padding-right: 4em;
        }
    }
    .info {
        margin-bottom: 1em;
        .date {
            color: #909090;
            font-size: 1.1em;
            margin-left: 0.3em;
        }
    }
    .content {
        @include media-breakpoint-up(lg) {
            padding-right: 4em;
        }
        img {
            margin-bottom: 1em;
        }
        big,
        big strong {
            color: #153a71;
            font-weight: bold;
        }
        p {
            color: #5d5555;
        }
    }
  }

  .share {
    .title {
        color: #153a71;
        font-weight: bold;
        font-size: 1.5em;
        margin-top: 1em;
    }
    .link {
        padding: 0;
        margin: 0 1em;
        img {
            max-height: 2em;
        }
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