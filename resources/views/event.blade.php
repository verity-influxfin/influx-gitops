<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="cache-control" content="max-age=0" />
  <meta http-equiv="cache-control" content="no-cache" />
  <meta http-equiv="expires" content="0" />
  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
  <meta http-equiv="pragma" content="no-cache" />
  <meta name="csrf_token" content="{{ csrf_token() }}">
  <title></title>
  <link rel="icon" href="{{ asset('images/site_icon.png') }}">

  <!-- package -->
  <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">

  <!-- local -->
  <link rel="stylesheet" href="{{ asset('css/event.css') }}">
</head>

<body>
  <div id="event_wrapper">
    <div class="game" style="background-image: url('./images/3253525.jpg');">
      <div id="wheel-content" ref="wheel_content">
        <div class="wheel" :style="`transform: rotate(${wheelDeg}deg)`">
          <div id="prize-box">
            <div class="prize" v-for="(item,index) in prizeList" :key="index" :style="{ transform: transformHandler(index, 'prize') }">
              <div :style="{ transform: transformHandler(index, 'content') }" class="prize-content">
                <div class="prize-icon">
                  <i :class="item.image"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="pointer"></div>
        <div id="hand">
          <button v-if="disable" class="btn btn-dark press disabled" @click="check()">
            Lucky
            <br />Ball
          </button>
          <button v-else class="btn btn-dark press" @click="rotate()">
            Lucky
            <br />Ball
          </button>
        </div>
      </div>
    </div>
    <div class="user-form" v-if="Object.keys(userData).length !== 0">
      <p class="form-style" v-if="userData.name">您好，@{{userData.name}}</p>
      <span class="form-style">我的使用者編號：@{{userData.id}}</span>
    </div>
    <div class="link-content">
      <div class="form">
        <div class="info" ref="left_info">首次註冊普匯inFlux會員，立即得LINE POINT點數，請確認註冊手機號碼為正確號碼，點數將傳送至此註冊號碼。</div>
        <a class="hexagon" ref="login" href="#" @click="openLoginModal()">登入拿點數</a>
      </div>
      <div class="form">
        <a class="hexagon" ref="lottery" href="https://event.influxfin.com/R/url?p=17K5591Q" target="_blank">立即抽獎</a>
        <div class="info" ref="right_info">至「普匯inFlux」，首次完成實名認證並成功認證，即可參加轉盤活動</div>
      </div>
    </div>
    <div class="event-info">主辦單位保有最終修改、變更、活動解釋及取消本活動之權利，若有相關異動將會公告於網站， 恕不另行通知。</div>
    <div class="awards-block" ref="awards_block" @click="hide">
      <div class="awards-canvas" :style="`background-image: url('./images/02.jpg');`"></div>
      <div class="awards-dailog" :style="`background-image: url('./images/hot_explosion.png');`">
        恭喜你獲得
        <i :class="awards.image"></i>
        @{{awards.text}}一份！！
      </div>
    </div>

    <div id="loginForm" class="modal fade" ref="loginForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div id="registerModal" v-if="isRegister" class="modal-content">
          <div class="modal-header">
            <div type="button" class="back" @click="isRegister = false">＜</div>
            <div class="register-title">註冊會員</div>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <span class="input-group-addon label-text">手機：</span>
              <input type="text" class="form-control label-input" placeholder="10位數手機號碼" maxlength="10" v-model="account" maxlength="10">
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">密碼：</span>
              <input type="password" class="form-control label-input" placeholder="請輸入密碼" v-model="password" maxlength="50">
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">確認密碼：</span>
              <input type="password" class="form-control label-input" placeholder="請再次輸入密碼" v-model="confirmPassword" />
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">驗證碼：</span>
              <div class="captcha-row">
                <input type="text" class="form-control label-input" placeholder="請輸入6位數驗證碼" v-model="code" maxlength="6" />
                <button class="btn btn-captcha" @click="getCaptcha('registerphone')" v-if="!isSended">取得驗證碼</button>
                <div class="btn btn-disable" v-if="isSended">@{{counter}}S有效</div>
                <span class="tip" v-if="isSended">驗證碼已寄出</span>
              </div>
            </div>
            <div class="input-group">
              <div class="chiller_cb" style="margin:0px auto">
                <input id="confirmTerms" type="checkbox" @click="isAgree = !isAgree" :checked="isAgree" />
                <label for="confirmTerms" class="block"></label>
                <span></span>
                <div class="row">
                  我同意
                  <div class="terms" @click="getTerms('user')">貸款人服務條款</div>、
                  <div class="terms" @click="getTerms('privacy_policy')">隱私權條款</div>
                </div>
              </div>
            </div>
          </div>
          <div class="alert alert-danger" v-if="registerMessage">@{{registerMessage}}</div>
          <div class="modal-footer">
            <div v-if="(account && password && confirmPassword && code && isAgree) ? false : true" class="btn btn-disable" disable>送出</div>
            <button type="button" v-else class="btn btn-submit" @click="doRegister">送出</button>
          </div>
        </div>
        <div id="loginModal" v-else class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">✕</button>
            <div class="login-logo"><img src="./images/logo_puhey.svg" class="img-fluid"></div>
          </div>
          <div class="modal-body">
            <div class="input-group">
              <span class="input-group-addon label-text">帳號：</span>
              <input type="text" class="form-control label-input" placeholder="10位數手機號碼" maxlength="10" v-model="account" maxlength="10">
            </div>
            <div class="input-group">
              <span class="input-group-addon label-text">密碼：</span>
              <input type="password" class="form-control label-input" placeholder="請輸入密碼" v-model="password" maxlength="50">
            </div>
            <p class="register-row">沒有帳號嗎? <a class="register-link" @click="isRegister = true">立即註冊</a></p>
          </div>
          <div class="alert alert-danger" v-if="message">@{{message}}</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-submit" @click="doLogin">登入</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" ref="termsForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <div class="terms-title">@{{termsTitle}}</div>
            <button type="button" class="close" data-dismiss="modal">✕</button>
          </div>
          <div class="modal-body terms-content">
            <div v-html="termsContent"></div>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- package -->
  <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/gasp.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/vue-cookies.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>
  <!-- local -->
  <script type="text/javascript" src="{{ asset('js/event.js') }}"></script>
</body>

</html>