<template>
  <div class="search">
    <div class="pwd">
      <img class="home" src="../asset/images/home.svg" alt="" />
      <span class="right">&#10217;</span>
      <div class="path">普匯金融科技-首頁</div>
      <span class="right">&#10217;</span>
      <div class="path">全站搜尋</div>
    </div>
    <div class="title">
      <img class="title-img" src="../asset/images/all-search.svg" alt="" />
    </div>
    <div class="search-block">
      <div class="text">關鍵字</div>
      <span class="loupe">
        <input
          class="search-input"
          placeholder="搜尋..."
          ref="searchInput"
          type="text"
          v-model="searchInput"
          @keyup.enter="fetchSearchData({})"
        />
      </span>

      <div class="text" v-if="!isPaginationEmpty">
        共有
        <span class="num">{{ pagination.total }}</span>
        筆搜尋結果
      </div>
    </div>
    <div class="sub-pages-header">
      <div
        class="item"
        :class="{ active: searchType === 'all' }"
        @click="toPage({ type: 'all' })"
      >
        頁面內容
        <div class="rwd" :class="{ active: searchType === 'all' }">-</div>
      </div>
      <!-- rwd  content-->
      <div
        class="rwd content m-0"
        :class="{ active: searchType === 'all' }"
        v-if="!loading"
      >
        <div class="no-found" v-show="!isFind">
          <img class="no-found-img" src="../asset/images/no-found.png" alt="" />
          <div class="no-found-text">
            找不到符合搜尋字詞「<span class="text-red">{{ keyword }}</span
            >」
          </div>
          <div class="no-found-text">
            麻煩您在輸入一次
          </div>
        </div>
        <div v-show="isFind">
          <div
            class="content-item"
            v-for="(x, i) in list"
            :key="i"
            @click="openLink(x.link)"
          >
            <div class="item-title">{{ x.title }}</div>
            <div class="item-text">
              {{ x.snippet }}
              <!-- <span class="text-red">AAA</span>
            我是文字我是文字我是文字 -->
            </div>
          </div>
        </div>
        <ul
          class="pagination"
          v-if="!isPaginationEmpty && isFind && totalPage > 0"
        >
          <li class="page-item">
            <a
              class="page-link"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage - 1 })
              "
            >
              前一頁
            </a>
          </li>
          <li
            class="page-item"
            v-for="i in totalPage"
            :key="i"
            :class="{ active: pagination.currentPage === i }"
          >
            <a class="page-link" @click.prevent="toPage({ currentPage: i })">
              {{ i }}
            </a>
          </li>
          <li class="page-item">
            <a
              class="page-link"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage + 1 })
              "
            >
              後一頁
            </a>
          </li>
        </ul>
      </div>

      <div
        class="item"
        :class="{ active: searchType === 'blog' }"
        @click="toPage({ type: 'blog' })"
      >
        小學堂
        <div class="rwd" :class="{ active: item === 2 }">-</div>
      </div>
      <!-- rwd  content-->
      <div
        class="rwd content m-0"
        :class="{ active: searchType === 'blog' }"
        v-if="!loading"
      >
        <div class="no-found" v-show="!isFind">
          <img class="no-found-img" src="../asset/images/no-found.png" alt="" />
          <div class="no-found-text">
            找不到符合搜尋字詞「<span class="text-red">{{ keyword }}</span
            >」
          </div>
          <div class="no-found-text">
            麻煩您在輸入一次
          </div>
        </div>
        <div v-show="isFind">
          <div
            class="content-item"
            v-for="(x, i) in list"
            :key="i"
            @click="openLink(x.link)"
          >
            <div class="item-title">{{ x.title }}</div>
            <div class="item-text">
              {{ x.snippet }}
              <!-- <span class="text-red">AAA</span>
            我是文字我是文字我是文字 -->
            </div>
          </div>
        </div>
        <ul
          class="pagination"
          v-if="!isPaginationEmpty && isFind && totalPage > 0"
        >
          <li class="page-item">
            <a
              class="page-link"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage - 1 })
              "
            >
              前一頁
            </a>
          </li>
          <li
            class="page-item"
            v-for="i in totalPage"
            :key="i"
            :class="{ active: pagination.currentPage === i }"
          >
            <a class="page-link" @click.prevent="toPage({ currentPage: i })">
              {{ i }}
            </a>
          </li>
          <li class="page-item">
            <a
              class="page-link"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage + 1 })
              "
            >
              後一頁
            </a>
          </li>
        </ul>
      </div>
      <div
        class="item"
        :class="{ active: searchType === 'qa' }"
        @click="toPage({ type: 'qa' })"
      >
        常見問題
        <div class="rwd" :class="{ active: item === 3 }">-</div>
        <!-- rwd  content-->
        <div
          class="rwd content m-0"
          :class="{ active: searchType === 'qa' }"
          v-if="!loading"
        >
          <div class="no-found" v-show="!isFind">
            <img
              class="no-found-img"
              src="../asset/images/no-found.png"
              alt=""
            />
            <div class="no-found-text">
              找不到符合搜尋字詞「<span class="text-red">{{ keyword }}</span
              >」
            </div>
            <div class="no-found-text">
              麻煩您在輸入一次
            </div>
          </div>
          <div v-show="isFind">
            <div
              class="content-item"
              v-for="(x, i) in list"
              :key="i"
              @click="openLink(x.link)"
            >
              <div class="item-title">{{ x.title }}</div>
              <div class="item-text">
                {{ x.snippet }}
                <!-- <span class="text-red">AAA</span>
            我是文字我是文字我是文字 -->
              </div>
            </div>
          </div>
          <ul
            class="pagination"
            v-if="!isPaginationEmpty && isFind && totalPage > 0"
          >
            <li class="page-item">
              <a
                class="page-link"
                @click.prevent="
                  toPage({ currentPage: pagination.currentPage - 1 })
                "
              >
                前一頁
              </a>
            </li>
            <li
              class="page-item"
              v-for="i in totalPage"
              :key="i"
              :class="{ active: pagination.currentPage === i }"
            >
              <a class="page-link" @click.prevent="toPage({ currentPage: i })">
                {{ i }}
              </a>
            </li>
            <li class="page-item">
              <a
                class="page-link"
                @click.prevent="
                  toPage({ currentPage: pagination.currentPage + 1 })
                "
              >
                後一頁
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="content m-0" v-if="!loading">
      <div class="no-found" v-show="!isFind">
        <img class="no-found-img" src="../asset/images/no-found.png" alt="" />
        <div class="no-found-text">
          找不到符合搜尋字詞「<span class="text-red">{{ keyword }}</span
          >」
        </div>
        <div class="no-found-text">
          麻煩您在輸入一次
        </div>
      </div>
      <div v-show="isFind">
        <div
          class="content-item"
          v-for="(x, i) in list"
          :key="i"
          @click="openLink(x.link)"
        >
          <div class="item-title">{{ x.title }}</div>
          <div class="item-text">
            {{ x.snippet }}
            <!-- <span class="text-red">AAA</span>
            我是文字我是文字我是文字 -->
          </div>
        </div>
      </div>
      <ul
        class="pagination"
        v-if="!isPaginationEmpty && isFind && totalPage > 0"
      >
        <li class="page-item">
          <a
            class="page-link"
            @click.prevent="toPage({ currentPage: pagination.currentPage - 1 })"
          >
            前一頁
          </a>
        </li>
        <li
          class="page-item"
          v-for="i in totalPage"
          :key="i"
          :class="{ active: pagination.currentPage === i }"
        >
          <a class="page-link" @click.prevent="toPage({ currentPage: i })">
            {{ i }}
          </a>
        </li>
        <li class="page-item">
          <a
            class="page-link"
            @click.prevent="toPage({ currentPage: pagination.currentPage + 1 })"
          >
            後一頁
          </a>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import axios from "axios";
export default {
  data () {
    // type all blog qa
    return {
      loading: false,
      searchInput: '',
      searchType: 'all',
      keyword: '',
      list: [
        {
          "title": "\u7a0b\u5f0f\u4ea4\u6613\uff1a\u6230\u52dd\u4eba\u6027\u7684\u5f31\u9ede\uff1f - inFlux\u666e\u532f\u91d1\u878d\u79d1\u6280",
          "link": "https://www.influxfin.com/articlepage?q=knowledge-8280",
          "snippet": "2021\u5e744\u670822\u65e5 ... \u7a0b\u5f0f\u4ea4\u6613\u53ef\u4ee5\u900f\u904e\u6b77\u53f2\u6578\u64da\u53bb\u56de\u6e2c\uff08back-testing\uff09\u4ea4\u6613\u7b56\u7565\u7684\u904e\u5f80\u5831\u916c\u7387\uff0c\u4f7f\u6295\u8cc7\u4eba\u5728\u6295\u5165\u8cc7\u91d1\u3001\u63a1\u884c\u4ea4\u6613\u7b56\u7565\u524d\uff0c\u5373\u53ef\u9810\u671f\u53ef\u80fd\u7684\u98a8\u96aa\u548c\u6536\u76ca\uff0c\u4e26\u9069\u7576\u5730\u00a0..."
        }
      ],
      pagination: {},
    }
  },
  mounted () {
    this.searchInput = this.$route.query.q ?? ''
    this.fetchSearchData({})
      .then(() => {
        this.$nextTick(() => {
          this.$refs.searchInput.focus()
        })
      })
  },
  methods: {
    fetchSearchData ({ currentPage }) {
      let uri = `/api/v1/search?q=${this.searchInput}`
      this.keyword = this.searchInput
      if (currentPage) {
        uri += `&currentPage=${currentPage}`
      }
      if (this.searchType) {
        uri += `&type=${this.searchType}`
      }
      if (this.searchInput) {
        this.loading = true
        this.list = []
        this.pagination = {}
        return axios.get(uri).then((x) => {
          const obj = x.data.data
          // console.log(obj)
          this.list = obj.list
          this.pagination = obj.pagination
        })
          .catch(err => console.error(err))
          .finally(() => {
            this.loading = false
          })
      }
    },
    openLink (link) {
      window.open(link)
    },
    toPage ({ currentPage, type }) {
      if (currentPage > this.totalPage || currentPage < 1) {
        return
      }
      if (type) {
        this.searchType = type
      }
      return this.fetchSearchData({ currentPage, type: this.searchType })
    }
  },
  computed: {
    isPaginationEmpty () {
      const { pagination } = this
      return Object.keys(pagination).length < 1
    },
    isFind () {
      const { list } = this
      return list.length > 0
    },
    totalPage () {
      const { pagination } = this
      if (pagination.total && pagination.perPage) {
        return Math.floor(pagination.total / pagination.perPage) + 1
      }
      return 0
    }
  },
}
</script>

<style lang="scss" scoped>
.search {
  width: 900px;
  margin: auto;
  padding: 0 10px;
  .pwd {
    display: flex;
    align-items: center;
    margin-left: 34px;
    margin-top: 80px;
    .home {
      height: 24px;
      margin-right: 6px;
    }
    .right {
      margin: 0 8px;
      font-family: NotoSansTC;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.8px;
      text-align: center;
      color: #4d4d4d;
    }
    .path {
      font-family: NotoSansTC;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.8px;
      text-align: center;
      color: #4d4d4d;
    }
  }
  .title {
    margin: 33px 34px;
  }
  .search-block {
    display: flex;
    align-items: center;
    padding: 12px 34px;
    border-radius: 6px;
    background-color: #efefef;
    .text {
      font-family: NotoSansTC;
      font-size: 24px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.67;
      letter-spacing: 1.2px;
      text-align: left;
      color: #5d5555;
      .num {
        font-family: NotoSansTC;
        font-size: 28px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.71;
        letter-spacing: 1.4px;
        text-align: center;
        color: #4873a2;
      }
    }
    .search-input {
      position: relative;
      width: 450px;
      padding: 7px 34px;
      border-radius: 6px;
      border: solid 1px #036eb7;
      background-color: #fff;
      font-size: 16px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.95;
      letter-spacing: 0.8px;
      text-align: left;
      color: #4d4d4d;
    }
    .loupe {
      position: relative;
      margin: 0 18px;
      &::after {
        position: absolute;
        top: 11.5px;
        right: 34px;
        content: "";
        width: 22px;
        height: 22px;
        display: block;
        background-image: url("../asset/images/loupe.svg");
      }
    }
  }
  .sub-pages-header {
    margin: 12px 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: 1fr;
    gap: 0px 0px;
    grid-template-areas: ". . .";
    .item {
      font-family: NotoSansTC;
      font-size: 24px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.3;
      letter-spacing: 1.2px;
      color: #aaa;
      text-align: center;
      cursor: pointer;
      padding: 24px 0;
      border-bottom: 1px solid #aaa;
      &.active {
        color: #326398;
        position: relative;
        &::after {
          content: "";
          border-bottom: 1px solid #326398;
          position: absolute;
          width: 100%;
          left: 0;
          bottom: 0;
        }
      }
      .rwd {
        display: none;
      }
    }
  }
  .content {
    &.rwd {
      display: none;
    }
    display: flex;
    flex-direction: column;
    .content-item {
      &:hover {
        text-decoration: underline;
        cursor: pointer;
      }
      padding-bottom: 40px;
      .item-title {
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 1.2px;
        text-align: left;
        color: #5d5555;
      }
      .item-text {
        font-family: NotoSansTC;
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 0.8px;
        text-align: left;
        color: #4d4d4d;
      }
      .text-red {
        color: #f00;
      }
    }
    .no-found {
      margin: 90px 0;
      .no-found-img {
        display: block;
        margin: auto;
      }
      .no-found-text {
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.67;
        letter-spacing: 1.2px;
        text-align: center;
        color: #5d5555;
        .text-red {
          color: #f00;
        }
      }
    }
  }
  .pagination {
    max-width: 900px;
    overflow-x: auto;
    justify-content: center;
    padding-bottom: 80px;
  }
}
@media screen and (max-width: 767px) {
  .search {
    max-width: 100%;
    padding: 10px;
    .pwd {
      display: none;
    }
    .title {
      margin: 40px 0 20px;
    }
    .search-block {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      padding: 6px 12px;
      .text {
        font-family: NotoSansTC;
        font-size: 18px;
        .num {
          font-family: NotoSansTC;
          font-size: 22px;
        }
      }
      .search-input {
        position: relative;
        width: 250px;
        padding: 4px 12px;
      }
      .loupe {
        position: relative;
        margin-bottom: 8px;
        &::after {
          position: absolute;
          top: 9px;
          right: 12px;
          content: "";
          width: 22px;
          height: 22px;
          display: block;
          background-image: url("../asset/images/loupe.svg");
        }
      }
    }
    .sub-pages-header {
      margin: 12px 0;
      display: flex;
      flex-direction: column;
      .item {
        display: flex;
        justify-content: space-between;
        text-align: left;
        font-size: 20px;
        padding: 14px 12px;
        &::after {
          content: "+";
        }
        .rwd.active {
          display: block;
        }
      }
    }
    .content {
      display: none;
    }
    .content.active {
      display: flex;
      .no-found {
        margin: 60px 0;
        .no-found-img {
          width: 150px;
        }
        .no-found-text {
          font-size: 16px;
          .text-red {
            color: #f00;
          }
        }
      }
      .content-item {
        padding: 0 12px 25px;
        .item-title {
          font-size: 20px;
        }
        .item-text {
          font-size: 14px;
        }
        .text-red {
          color: #f00;
        }
      }
    }
    .pagination {
      justify-content: center;
      padding-bottom: 40px;
      border-radius: 0;
      border-bottom: 1px solid #aaa;
    }
  }
}
</style>