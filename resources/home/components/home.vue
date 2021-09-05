<template>
  <section class="d-flex flex-column  align-items-center">
    <img class="mb-5" src="images/logo.png" alt="logo" id="home_image">
    <p class="home_text mdl-typography--display-1">شروع یک سبک جدید.</p>
    <p class="home_text mdl-typography--title-color-contrast mt-1">مدریت وظایف اعضای گروه و یا شرکت خود را به ما بسپارید.</p>
    <a href="profile.html" v-if="user">
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mr-4 font-vazir">
        <span class="material-icons">assignment_ind</span>
        ورود به پروفایل کاربری
      </button>
    </a>


    <section class="row" v-else>
      <!-- Raised button with ripple -->
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir" @click="goto('login')">
        <span class="material-icons">login</span>
        ورود
      </button>

      <!-- Raised button with ripple -->
      <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mr-4 font-vazir" @click="goto('register')">
        <span class="material-icons">assignment_ind</span>
        ثبت نام
      </button>

    </section>
  </section>
</template>

<script>
export default {
name: "home",
  data(){
    return{
      user : window.user
    }
  },
  created() {
    let self = this;
    let interval = setInterval(function(){
          if (window.user != undefined){
            self.user = window.user
            clearInterval(interval)
          }
      }, 100);
  }
}
</script>

<style scoped>
  #home_image{
    width: 150px;
    height: 150px;
  }

  .home_text{
    font-weight: bold;
    color: #e0e0e0;
    font-family: "vazir-regular";
  }
</style>