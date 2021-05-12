<template>
  <div class="bank-wrapper" id="bank-wrapper">
  	<div class="content-top">
	</div>
    <div class="form">
		<div class="event-form">
			<div class="input-group">
	          <input
	            type="text"
	            class="form-control"
	            placeholder="姓名"
	            v-model="name"
	          />
	        </div>
			<div class="input-group">
	          <input
	            type="text"
	            class="form-control"
				maxlength="10"
	            placeholder="電話"
	            v-model="phone"
	          />
	        </div>
			<div class="input-group">
	          <input
	            type="text"
	            class="form-control"
	            placeholder="E-mail"
	            v-model="email"
	          />
	        </div>
			<div class="input-group">
			  <button class="btn send-btn" @click="sendBankEvent">立即申請</button>
			</div>
		</div>
	</div>
	<div class="content-bottom">
	</div>
  </div>
</template>

<script>
function viewport_convert(px = 0, vw = 0, vh = 0){
  if(px != 0){
	  if(vw){
		  return (100 * px / window.innerWidth);
	  } else {
		  return (100 * px / window.innerHeight);
	  }
  } else if(vw != 0 && vh != 0){
	  var w_h_arr = [];
	  w_h_arr["width"] = Math.ceil((window.innerWidth * vw / 100));
	  w_h_arr["height"] = Math.ceil((window.innerHeight * vh / 100));
	  return w_h_arr;
  } else if(vw != 0){
	  return Math.ceil((window.innerWidth * vw / 100));
  } else if(vh != 0){
	  return Math.ceil((window.innerHeight * vh / 100));
  }
}

$(document).ready(function() {
	const urlParams = new URLSearchParams(window.location.search);
    let move = urlParams.get("move");
	let screen_width = screen.width;
	let move_range;
	if(move){
		if(screen_width > 767){
			move_range = 49;
		}else{
			move_range = 130;
		}
		let height_range = viewport_convert(0,move_range);
		$("html, body").animate({ scrollTop: height_range }, 2000);
	}
});
export default {
  data: () => ({
    phone: "",
    email: "",
	name: ""
  }),
  created() {
    $("title").text(`普匯x上海商銀`);
  },
  watch: {
    phone(newdata) {
      this.phone = newdata.replace(/[^\d]/g, "");
    }
  },
  methods: {
	  sendBankEvent() {
    	  let phone = this.phone;
    	  let email = this.email;
    	  let name = this.name;
		  let page_from = 'event';

		  let phone_reg = /^09[0-9]{8}$/;
		  let email_reg = /^([A-Za-z0-9_\-\.\u4e00-\u9fa5])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,8})$/;

		  if(! name){
			  alert('未填寫姓名');
			  return;
		  }

		  if(! phone){
			  alert('未填寫電話號碼');
			  return;
		  }

		  if(! email){
			  alert('未填寫電子郵箱');
			  return;
		  }

		  if(! phone_reg.test(phone)){
			  alert('電話號碼格式不正確，請重新輸入');
			  return;
		  }

		  if(! email_reg.test(email)){
			  alert('電子郵箱格式不正確，請重新輸入');
			  return;
		  }

    	  axios
    		.post(`/sendBankEvent`, {
    		  phone,
    		  name,
    		  email,
			  page_from
    		})
    		.then((res) => {
				alert('送出成功，即將為您跳轉頁面');
				window.location.href = 'https://bit.ly/3xRNTdE';
    		})
    		.catch((error) => {
				alert(error);
    		});
      },
  }
};

</script>

<style lang="scss">
.bank-wrapper {
	overflow: hidden;
    width: 100%;
    position: relative;

	.content-top{
		background-image: url("../asset/images/bankTop.svg");
	    background-repeat: no-repeat;
	    position: relative;
		background-size: cover;
		width: 100%;
	    height: 79vw;
	}

  .form {

    .event-form {
	  max-width: 80%;
      margin: 0rem auto;
      overflow: hidden;
      padding: 0rem 4rem;
      z-index: 4;
      position: relative;
      background-size: cover;

      .input-group {
        width: 43vw;
		height: 7vw;
        margin: 0px auto 0.5rem auto;
        padding: 5px 10px;
      }

      .form-control {
		width: 43vw;
  		height: 7vw;
		background-image: url("../asset/images/bankInput.svg");
  	    background-repeat: no-repeat;
        border: 0px;
		border-radius: 15px;
		font-size: 38px;
		font-weight: bold;
		padding: .375rem 1.75rem;
      }

      .btn-success {
        font-size: 27px;
        display: block;
        margin: 0px auto;
        font-weight: bold;
        width: 300px;
      }

      .btn-disable {
        width: 300px;
        font-size: 27px;
        cursor: default;
        display: block;
        margin: 0px auto;
        font-weight: bold;
        color: #495057;
      }

	  .send-btn {
		background-image: url("../asset/images/bankButton.svg");
    	background-repeat: no-repeat;
		width: 43vw;
		height: 7vw;
        border: 0px;
  		border-radius: 15px;
		color: white;
		width: 100%;
		font-size: 27px;
		font-weight: bold;
	  }
    }
  }

  .content-bottom{
	  background-image: url("../asset/images/bankBottom.svg");
	  background-repeat: no-repeat;
	  position: relative;
	  background-size: cover;
	  width: 100%;
	  height: 40vw;
  }

}
@media screen and (max-width: 767px) {
  .bank-wrapper {
	  .content-top {
    	  background-image: url("../asset/images/bankTopM.svg");
		  height: 164vw;
      }

      .form {
    	padding: 5px;

    	.event-form {
    	  padding: initial;
		  max-width: 90%;

    	  .input-group {
			width: 100%;
      		height: 100%;
			margin: 0px auto 2px auto;
			padding: initial;

			.form-control {
				background-size: contain;
			    height: 16vw;
    			font-weight: bold;
				font-size: 25px;
    			left: initial;
    		}

			.send-btn {
				height: 16vw;
				font-size: 25px;
			}
    	  }

    	}
      }
      .content-bottom {
    	  background-image: url("../asset/images/bankBottomM.svg");
		  height: 48vw;
      }
  }
}
</style>
