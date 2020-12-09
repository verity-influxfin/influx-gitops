<template>
  <div :class="['notification-wrapper', $router.currentRoute.name]">
    <div class="notification-banner">
      <h3>通知</h3>
      <div v-if="unreadCount !== 0" class="btn unread-tip" @click="allRead">
        一鍵已讀
        <span>{{ unreadCount }}</span>
      </div>
    </div>
    <ul class="notification-container" ref="container"></ul>
    <div class="pagination" ref="pagination"></div>
  </div>
</template>

<script>
let notificationRow = Vue.extend({
  props: ["item", "index", "vm"],
  template: `
    <li class="notification-row">
      <div class="notification title collapsed" @click="read(item.id,item.status,index)" data-toggle="collapse" :data-target="'#collapse'+item.id" aria-expanded="true">   
        <p>{{item.title}}</p>
        <span>{{dateToString(parseInt(item.created_at + "000"))}}</span>
        <i v-if="item.status == 1" class="fas fa-circle"></i>
        <i v-else></i>
      </div>
      <div :id="'collapse'+item.id" class="collapse" data-parent=".notification-container">
        <div class="content" v-html="item.content"></div>
      </div>
    </li>
  `,
  methods: {
    dateToString(milliseconds) {
      let dateObj = new Date(milliseconds);

      let date_item = {
        year: dateObj.getFullYear(),
        month:
          (dateObj.getMonth() + 1 < 10 ? "0" : "") + (dateObj.getMonth() + 1),
        day: (dateObj.getDate() < 10 ? "0" : "") + dateObj.getDate(),
        hour: (dateObj.getHours() < 10 ? "0" : "") + dateObj.getHours(),
        min: (dateObj.getMinutes() < 10 ? "0" : "") + dateObj.getMinutes(),
        sec: (dateObj.getSeconds() < 10 ? "0" : "") + dateObj.getSeconds(),
      };

      return `${date_item.year}/${date_item.month}/${date_item.day} ${date_item.hour}:${date_item.min}:${date_item.sec}`;
    },
    read(id, status, index) {
      if (status == 1) {
        axios.post(`${location.origin}/read`, { id }).then((res) => {
          this.$props.vm.unreadCount--;
          this.$props.vm.notifications[index].status = 2;
        });
      }
    },
  },
});

export default {
  beforeRouteEnter(to, from, next) {
    if (sessionStorage.length === 0 || sessionStorage.flag === "logout") {
      next("/index");
    } else {
      next();
    }
  },
  data: () => ({
    unreadCount: 0,
    notifications: [],
  }),
  created() {
    this.$parent.pageIcon = "";
    this.$parent.pageTitle = "";
    this.$parent.pagedesc = "";
    this.getNotification();
  },
  watch: {
    notifications() {
      this.pagination();
    },
  },
  methods: {
    getNotification() {
      this.unreadCount = 0;
      axios
        .post(`${location.origin}/getNotification`)
        .then((res) => {
          this.notifications = res.data.data.list;
          this.notifications.forEach((item, index) => {
            if (item.status == 1) {
              this.unreadCount++;
            }
          });

          this.$parent.unreadCount = this.unreadCount;
        })
        .catch((error) => {
          console.log("getNotification 發生錯誤，請稍後再試");
        });
    },
    pagination() {
      let $this = this;
      $this.$nextTick(() => {
        $($this.$refs.pagination).pagination({
          dataSource: $this.notifications,
          pageSize: 15,
          callback(data, pagination) {
            $($this.$refs.container).html("");
            data.forEach((item, index) => {
              let component = new notificationRow({
                propsData: {
                  item,
                  index,
                  vm: $this,
                },
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          },
        });
      });
    },
    async allRead() {
      await axios.get(`${location.origin}/allRead`);
      this.getNotification();
    },
  },
};
</script>

<style lang="scss">
.notification-wrapper {
  width: 85%;
  margin: 0px auto;
  padding: 25px 33px;

  .notification-banner {
    position: relative;
    display: flex;

    h3 {
      font-weight: bold;
    }

    .unread-tip {
      position: absolute;
      right: 0;
      width: 114px;
      color: #ffffff;
      font-weight: bolder;
      border-radius: 7px;
      background-color: #083a6e;

      span {
        position: absolute;
        top: -12px;
        right: -12px;
        background: #08deb1;
        border-radius: 50%;
        width: 24px;
        font-size: 16px;
        font-weight: initial;
      }
    }
  }

  .notification-container {
    margin-bottom: 20px;
    padding: 0px;
    list-style: none;

    .notification-row {
      padding: 10px;
      border-radius: 13px;
      margin: 10px 0px;
      background-color: #ffffff;

      .title {
        display: flex;
        cursor: pointer;
        position: relative;

        span {
          right: 15px;
        }

        p {
          margin: 0px 10px;
          font-weight: bolder;
          width: 60%;
        }

        i {
          line-height: 24px;
          font-size: 10px;
          color: red;
          position: absolute;
          right: 15px;
        }
      }

      .content {
        border-top: 1px dashed #a9a9a9;
        margin: 10px 10px 0px 10px;
        padding-top: 5px;
      }
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;

    .paginationjs-pages {
      li {
        border: 0px solid #aaa;

        a {
          height: 28px;
          color: #083a6e;
          background: #f5f5f5;
        }
      }
      .paginationjs-prev,
      .paginationjs-next,
      .paginationjs-page.active {
        a {
          background: #083a6e;
          color: #ffffff;
        }
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .notification-wrapper {
    margin: 10px auto;
    width: 100%;
    padding: 20px;

    .notification-container {
      .notification-row {
        .title {
          flex-direction: column;

          p {
            width: 100%;
          }
        }
      }
    }
  }
}
</style>