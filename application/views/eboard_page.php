<!doctype html>
<html lang="zh-TW">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <title>電子看板</title>
    </head>
    <body id="app" style="background-color: #212529;">
        <table class="table table-dark table-striped h-100 w-100" v-if="state.data">
            <thead>
                <tr>
                    <th>日期</th>
                    <th>官網流量</th>
                    <th>新增會員</th>
                    <th>會員總數</th>
                    <th>APP下載(android)</th>
                    <th>APP下載(ios)</th>
                    <th>各產品申貸數(3S/學/上/微)</th>
                    <th>成交</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in state.data">
                    <th v-text="item.date"></th>
                    <td v-text="item.official_site_trends"></td>
                    <td v-text="item.new_member"></td>
                    <td v-text="item.total_member"></td>
                    <td v-text="item.android_downloads"></td>
                    <td v-text="item.ios_downloads"></td>
                    <td v-text="item.product_bids"></td>
                    <td v-text="item.deals"></td>
                </tr>
            </tbody>
        </table>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/vue@3"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script>
            const { reactive, onBeforeMount } = Vue;

            Vue.createApp({
              data() {
                return {
                  data: []
                }
              },
              setup(){
                  const state = reactive({
                      data: null,
                  })
                  onBeforeMount(() => {
                      axios.get("/page/get_eboard_data").then(function (response) {
                          state.data = response.data.data;
                      })
                  })
                  return { state };
              }
            }).mount('#app')
        </script>
    </body>
</html>
