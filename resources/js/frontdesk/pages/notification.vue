<template>
  <div class="notification-wrapper">
    <div class="notification-banner">
      <h2>通知</h2>
      <div v-if="unreadCount !== 0" class="btn btn-warning unread-tip" @click="allRead">
        一鍵已讀
        <span>{{unreadCount}}</span>
      </div>
    </div>
    <div class="progress">
      <div
        class="progress-bar"
        role="progressbar"
        style="width: 75%"
        aria-valuenow="75"
        aria-valuemin="0"
        aria-valuemax="100"
      ></div>
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
        <i v-if="item.status == 1" class="fas fa-circle"></i>
        <i v-else></i>
        <p>{{item.title}}</p>
        <span>{{dateToString(parseInt(item.created_at + "000"))}}</span>
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
        sec: (dateObj.getSeconds() < 10 ? "0" : "") + dateObj.getSeconds()
      };

      return `${date_item.year}/${date_item.month}/${date_item.day} ${date_item.hour}:${date_item.min}:${date_item.sec}`;
    },
    read(id, status, index) {
      if (status == 1) {
        axios.post("read", { id }).then(res => {
          this.$props.vm.unreadCount --;
          this.$props.vm.notifications[index].status = 2;
        });
      }
    }
  }
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
    notifications: []
  }),
  created() {
    this.getNotification();
  },
  watch: {
    notifications() {
      this.pagination();
    }
  },
  methods: {
    getNotification() {
      this.unreadCount = 0;
      axios
        .post("getNotification")
        .then(res => {
          this.notifications = res.data.data.list;
          this.notifications.forEach((item, index) => {
            if (item.status == 1) {
              this.unreadCount++;
            }
          });
        })
        .catch(error => {
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
                  vm: $this
                }
              }).$mount();
              $($this.$refs.container).append(component.$el);
            });
          }
        });
      });
    },
    async allRead() {
      await axios.get("allRead");
      this.getNotification();
    }
  }
};
</script>

<style lang="scss">
.notification-wrapper {
  width: 90%;
  margin: 0px auto;

  .notification-banner {
    position: relative;
    display: flex;

    .unread-tip {
      position: absolute;
      right: 0;
      color: #ffffff;
      font-weight: bolder;
      text-shadow: 0 0 2px #000000;

      span {
        position: absolute;
        top: -12px;
        left: -12px;
        background: red;
        border-radius: 50%;
        width: 24px;
        font-size: 16px;
      }
    }
  }

  .progress {
    height: 4px;

    .progressbar {
      background-color: #576cff;
    }
  }

  .notification-container {
    margin-bottom: 20px;
    padding: 0px;
    list-style: none;

    .notification-row {
      padding: 10px 0px;

      .title {
        display: flex;
        cursor: pointer;
      }

      .content {
        border-top: 1px dashed #0008ff;
        margin: 10px;
        padding-top: 5px;
      }

      i {
        line-height: 24px;
        font-size: 10px;
        color: red;
      }

      span {
        color: #9a9a9a;
      }

      p {
        margin: 0px 10px;
        font-weight: bolder;
        width: 80%;
      }
    }

    .notification-row:not(:last-child) {
      border-bottom: 1px solid #b1b1b1;
    }
  }

  .pagination {
    width: fit-content;
    margin: 0px auto;
  }

  @media screen and (max-width: 1023px) {
    margin: 10px auto;
  }
}
</style>