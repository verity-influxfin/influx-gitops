<template>
    <div class="blog-wrapper" id="blog-wrapper">
        <section class="banner">
        </section>
        <section class="intro">
            <h1 class="h1">最新文章</h1>
            <h2 class="h2">讓普匯帶給您新知，介紹金融科技知識</h2>
        </section>
        <section class="container">
            <div class="article-type-tabs">
                <button
                    class="article-type-tab"
                    @click="category = ''"
                    :class="{ active: !category }"
                >
                    全文章
                </button>
                <button
                    class="article-type-tab"
                    @click="category = 'fintech'"  
                    :class="{ active: category === 'fintech' }"
                >
                    金融科技
                </button>
                <!-- article -->
                <button
                    class="article-type-tab"
                    @click="category = 'influx'"
                    :class="{ active: category === 'influx' }"
                >
                    普匯金融
                </button>
                <!-- video -->
                <button
                    class="article-type-tab"
                    @click="category = 'invest'"
                    :class="{ active: category === 'invest' }"
                >
                    投資理財
                </button>
                <button
                    class="article-type-tab"
                    @click="category = 'digital'"
                    :class="{ active: category === 'digital' }"
                >
                    數位生活
                </button>
                <button
                    class="article-type-tab"
                    @click="category = 'news'"
                    :class="{ active: category === 'news' }"
                >
                    時事焦點
                </button>
            </div>
            <div class="article-pr-list">
                <div
                    class="article-pr-grid"
                    v-for="item in renderedArticles"
                    :key="item.ID"
                >
                    <div class="article-pr-img">
                        <img :src="item.media_link ? item.media_link : '/images/default-image.png'" :alt="item.media_alt ? item.media_alt : item.media_link.split('/').pop().split('.').shift()" class="img-fluid" />
                    </div>
                    <a class="article-pr-title" :href="`/articlepage?q=knowledge-${item.ID}`">
                        {{ item.post_title }}
                    </a>
                    <div class="article-pr-type">{{ getType(item.type) }}</div>
                    <div
                        class="article-pr-text"
                        v-html="formatContent(item.post_content)"
                    ></div>
                    <div class="article-pr-footer">
                        <div class="article-pr-date">{{ item.postDate }}</div>
                        <a class="article-pr-link" :href="`/articlepage?q=knowledge-${item.ID}`">
                            繼續閱讀
                        </a>
                    </div>
                </div>
                <div class="pagination"></div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data: () => ({
        category: '',
        articles: [],
        current_page: 1,
        page_size: 10
    }),
    computed: {
        renderedArticles() {
            if (Array.isArray(this.articles)) {
                let start = (this.current_page - 1) * this.page_size
                let end = this.current_page * this.page_size - 1
                return this.articles.slice(start, end)
            }
            return this.articles
        }
    },
    created() {
        this.getData()
    },
    mounted() {
        this.$nextTick(() => {
            AOS.init();
            particlesJS.load("blog-wrapper", "data/mobile.json");
        });
    },
    methods: {
        async getData(category) {
            await axios.post(`${location.origin}/getKnowledgeData`, {
                type: this.category
            }).then((res) => {
                this.articles = res.data
            }).catch((err) => {
                console.log(err)
            })
        },
        getType(type) {
            switch (type) {
                case 'fintech':
                    return '金融科技'
                case 'influx':
                    return '普匯金融'
                case 'invest':
                    return '投資理財'
                case 'digital':
                    return '數位生活'
                case 'news':
                    return '時事焦點'
                default:
                    return '全文章'
            }
        },
        formatContent(content) {
            return `${content.replace(/(<([^>]+)>)/gi, '').substr(0, 100)}...`
        },
        pagination() {
            let self = this
            this.$nextTick(() => {
                $('.pagination').pagination({
                    dataSource: self.articles,
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
    watch: {
        articles() {
            this.pagination()
        },
        category(v) {
            this.getData(v)
        }
    }
};
</script>

<style lang="scss">
@import "../asset/scss/bootstrap/functions";
@import "../asset/scss/bootstrap/variables";
@import "../asset/scss/bootstrap/mixins/_breakpoints";

.h1 {
    text-align: center;
    font-style: normal;
    font-weight: 700;
    font-size: 48px;
    line-height: 1.4;
    
}
.h2 {
    text-align: center;
    font-style: normal;
    font-weight: 500;
    font-size: 40px;
    line-height: 1.4;
}

.blog-wrapper {
    overflow: hidden;
    position: relative;

    .banner {
        background-image: url('~images/blog/lil_school_banner.png');
        height: 40vw;
        background-size: cover;
        background-position: center;
    }

    .intro {
        margin: 76px 0 50px;
        font-style: normal;
        font-weight: 700;
        font-size: 28px;
        line-height: 1.4;
        color: #036EB7;
    }

    .article-type-tabs {
        margin-top: 12px;
        justify-content: center;
        display: flex;
        gap: 24px;
    }

    .article-type-tab {
        &.active {
            color: #f29500;
        }

        font-style: normal;
        font-weight: 400;
        font-size: 28px;
        line-height: 1.4;
        letter-spacing: 0.04em;
        color: #fff;
        background: #023D64;
        border-radius: 60px;
        width: 200px;
        cursor: pointer;
    }

    .article-pr-list {
        margin-top: 36px;
        min-height: 50vh;
    }
    .article-pr-grid {
        display: grid;
        grid-template-columns: 360px 1fr;
        grid-template-rows: repeat(3, auto) 1fr auto;
        height: 240px;
        gap: 6px 36px;
        align-items: stretch;
        margin-bottom: 36px;

        .article-pr-img {
            grid-area: 1 / 1 / 6 / 2;
            .img-fluid {
                aspect-ratio: 3 / 2;
                max-height: 240px;
            }
        }
        .article-pr-title {
            grid-area: 1 / 2 / 2 / 3;
            font-style: normal;
            font-weight: 500;
            font-size: 26px;
            line-height: 1.4;
            letter-spacing: 0.04em;
            color: #222529;
        }
        .article-pr-type {
            grid-area: 2 / 2 / 3 / 3;
            font-style: normal;
            font-weight: 350;
            font-size: 16px;
            line-height: 1.4;
            letter-spacing: 0.04em;

            color: #13316c;
        }
        .article-pr-text {
            grid-area: 3 / 2 / 4 / 3;
            font-style: normal;
            font-weight: 350;
            font-size: 16px;
            line-height: 1.4;
            letter-spacing: 0.04em;
            color: #393939;
        }
        .article-pr-footer {
            grid-area: 5 / 2 / 6 / 3;
            display: flex;
            justify-content: space-between;
            font-style: normal;
            font-weight: 350;
            font-size: 16px;
            line-height: 23px;
            letter-spacing: 0.04em;
            color: #393939;
            .article-pr-link {
                color: #13316c;
            }
        }
    }

    .pagination {
        justify-content: center;
        margin-bottom: 30px;
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
                    background: #036eb7 !important;
                    color: #fff !important;
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
}

@media only screen and (max-width: 768px) {
    .h1 {
        text-align: center;
        font-style: normal;
        font-weight: 700;
        font-size: 28px;
        line-height: 1.4;
        
    }
    .h2 {
        text-align: center;
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 1.4;
    }

    .blog-wrapper {
        overflow: hidden;
        position: relative;

        .banner {
            background-image: url('~images/blog/lil_school_banner_mobile.png');
            height: 220vw;
            // background-size: cover;
            background-position: center;
        }

        .intro {
            margin: 38px 0 25px;
        }

        .article-type-tabs {
            margin-top: 12px;
            justify-content: center;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .article-type-tab {
            &.active {
                color: #f29500;
            }

            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            line-height: 1.4;
            letter-spacing: 0.04em;
            color: #fff;
            background: #023D64;
            border-radius: 60px;
            width: 100px;
            cursor: pointer;
        }

        .article-pr-grid {
            display: block;
            height: auto;
        
            .article-pr-title {
                font-weight: 500;
                font-size: 20px;
                line-height: 29px;
                letter-spacing: 0.04em;
            }

            .article-pr-type {
                margin: 8px 0 8px;
            }

            .article-pr-text {
                font-weight: 350;
                font-size: 14px;
                line-height: 20px;
                letter-spacing: 0.04em;
            }
        }

    }
}
</style>
