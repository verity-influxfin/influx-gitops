<template>
  <div class="bank-wrapper" id="bank-wrapper">
  	<div class="content-top skbank-top">
	</div>
    <div class="form">
		<div class="event-form">
            <div class="input-group">
                <img src="../asset/images/skBankTopContent.svg">
            </div>
            <div class="input-group group-text">
                <span style="color:#036EB7;">前三期利率</span>
                <span style="color:#E50012;">0.68</span>
                <span style="color:#036EB7;">起</span>
            </div>
            <div class="input-group" style="height: 100%;width:100%;">
                <img class="group-img" src="../asset/images/skBankMidContent.svg">
                <img class="group-img" src="../asset/images/skBankMidContent1.svg">
                <img class="group-img" src="../asset/images/skBankMidContent2.svg">
                <img class="group-img" src="../asset/images/skBankMidContent3.svg">
            </div>
            <div class="input-group group-text skbank-blue-link">
                <span style="color:#036EB7;">客製化您的貸款方案</span>
            </div>
            <div class="input-group" style="height: 100%;">
                <img src="../asset/images/skBankBottomContent.svg">
            </div>
            <div class="input-group" style="height: 100%;">
                <img src="../asset/images/skBankBottomContent1.svg">
            </div>
			<div class="input-group">
			  <a class="btn send-btn skbank-send-btn" href="https://www.youtube.com/" style="align-items: center;display: flex;justify-content: center;">立即申請</a>
			</div>
		</div>
	</div>
	<div class="skbank-content-bottom">
        <img src= "../asset/images/skBankBottom.svg">
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
};

</script>

<style lang="scss">
.bank-wrapper {
	overflow: hidden;
    width: 100%;
    position: relative;

	.content-top{
	    background-repeat: no-repeat;
	    position: relative;
		background-size: cover;
		width: 100%;
	    height: 45vw;
	}

    .skbank-top{
        background-image: url("../asset/images/skBankTop.svg");
    }

    .skbank-blue-link {
        width: 50%;
        height: 1px;
        border-bottom: solid #036EB7 1px;
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
        margin: 0px auto 0.5rem auto;
        padding: 5px 10px;

        span {
            display:flex;
            font-size: 4vw;
        }
      }

      .group-text {
          align-items:center;
          justify-content:center;
      }

      .group-img {
          height: 50%;
          width: 50%;
      }

      .form-control {
		width: 43vw;
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

	  .skbank-send-btn {
		background-image: url("../asset/images/skBankButton.svg");
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

  .skbank-content-bottom{
      background-color: #EEEEEF;
      position: relative;
	  background-size: cover;
      height: 10vw;
      display: flex;

      img {
          width: 35%;
          margin: auto;
          display: block;
      }
  }
}
@media screen and (max-width: 767px) {
  .bank-wrapper {
	  .content-top {
		  height: 117vw;
      }

      .skbank-top{
          background-image: url("../asset/images/skBankTopM.svg");
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
          height: 48vw;
      }
  }
}
</style>
