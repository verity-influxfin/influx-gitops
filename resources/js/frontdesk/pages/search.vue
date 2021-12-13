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
      <div class="search-input-group">
        <input
          class="search-input"
          placeholder="搜尋..."
          ref="searchInput"
          type="text"
          v-model="searchInput"
          @keyup.enter="fetchSearchData({})"
        />
        <img
          class="loupe"
          src="../asset/images/loupe.svg"
          @click="fetchSearchData({})"
        />
      </div>

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
        <div
          class="pagination"
          v-if="!isPaginationEmpty && isFind && totalPage > 0"
        >
          <div class="page-item">
            <a
              class="page-link before"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage - 1 })
              "
            >
              前一頁
            </a>
          </div>
          <div class="page-nums">
            <div
              class="page-item"
              v-for="i in totalPage"
              :key="i"
              :class="{ active: pagination.currentPage === i }"
            >
              <a class="page-link" @click.prevent="toPage({ currentPage: i })">
                {{ i }}
              </a>
            </div>
          </div>
          <div class="page-item">
            <a
              class="page-link after"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage + 1 })
              "
            >
              後一頁
            </a>
          </div>
        </div>
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
        <div
          class="pagination"
          v-if="!isPaginationEmpty && isFind && totalPage > 0"
        >
          <div class="page-item">
            <a
              class="page-link before"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage - 1 })
              "
            >
              前一頁
            </a>
          </div>
          <div class="page-nums">
            <div
              class="page-item"
              v-for="i in totalPage"
              :key="i"
              :class="{ active: pagination.currentPage === i }"
            >
              <a class="page-link" @click.prevent="toPage({ currentPage: i })">
                {{ i }}
              </a>
            </div>
          </div>
          <div class="page-item">
            <a
              class="page-link after"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage + 1 })
              "
            >
              後一頁
            </a>
          </div>
        </div>
      </div>
      <div
        class="item"
        :class="{ active: searchType === 'qa' }"
        @click="toPage({ type: 'qa' })"
      >
        常見問題
        <div class="rwd" :class="{ active: item === 3 }">-</div>
        <!-- rwd  content-->
      </div>
      <div
        class="rwd content m-0"
        :class="{ active: searchType === 'qa' }"
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
        <div
          class="pagination"
          v-if="!isPaginationEmpty && isFind && totalPage > 0"
        >
          <div class="page-item">
            <a
              class="page-link before"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage - 1 })
              "
            >
              前一頁
            </a>
          </div>
          <div class="page-nums">
            <div
              class="page-item"
              v-for="i in totalPage"
              :key="i"
              :class="{ active: pagination.currentPage === i }"
            >
              <a class="page-link" @click.prevent="toPage({ currentPage: i })">
                {{ i }}
              </a>
            </div>
          </div>
          <div class="page-item">
            <a
              class="page-link after"
              @click.prevent="
                toPage({ currentPage: pagination.currentPage + 1 })
              "
            >
              後一頁
            </a>
          </div>
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
      <div
        class="pagination"
        v-if="!isPaginationEmpty && isFind && totalPage > 0"
      >
        <div class="page-item">
          <a
            class="page-link before"
            @click.prevent="toPage({ currentPage: pagination.currentPage - 1 })"
          >
            前一頁
          </a>
        </div>
        <div class="page-nums">
          <div
            class="page-item"
            v-for="i in totalPage"
            :key="i"
            :class="{ active: pagination.currentPage === i }"
          >
            <a class="page-link" @click.prevent="toPage({ currentPage: i })">
              {{ i }}
            </a>
          </div>
        </div>
        <div class="page-item">
          <a
            class="page-link after"
            @click.prevent="toPage({ currentPage: pagination.currentPage + 1 })"
          >
            後一頁
          </a>
        </div>
      </div>
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
      list: [],
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
      window.scrollTo({ top: 0, behavior: 'smooth' })
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
    .search-input-group {
      position: relative;
      margin: 0 18px;
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
      position: absolute;
      right: 10px;
      top: 11px;
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
    .page-nums {
      max-width: 900px;
      display: flex;
      overflow: auto;
    }
    justify-content: center;
    padding-bottom: 80px;
    .page-item.active {
      .page-link {
        color: #fff;
      }
    }
    .page-link {
      color: #326398;
      border: 1px solid #326398;
    }
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
      .search-input-group {
        position: relative;
        margin: 0 0 0 14px;
      }
      .search-input {
        position: relative;
        width: 250px;
        padding: 4px 12px;
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
      .page-nums {
        max-width: 55%;
      }
      justify-content: center;
      padding-bottom: 40px;
      border-radius: 0;
      border-bottom: 1px solid #aaa;
    }
  }
}
</style>