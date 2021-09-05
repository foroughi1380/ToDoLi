<template>
  <section class="d-flex flex-column justify-content-center align-items-center">

    <h2 class="mdl-typography--display-1 font-vazir text-dark">ورود</h2>

    <section :class="['mdl-spinner' , 'mdl-js-spinner' ,  'is-active' ,  {'d-none' : ! inProgress} , 'spiner']"></section>



    <section class="d-flex flex-column justify-content-center align-items-center" v-if="! inProgress">

      <textbox type="text" holder="نام کاربری" icon="face" message="نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد" v-model="username"></textbox>
      <textbox type="password" holder="گذرواژه" icon="lock" message="گذرواژه خود را وارد کنید" v-model="password"></textbox>


      <!-- buttons -->
      <section class="row mt-4">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir"  @click="login">
          <span class="material-icons">login</span>
          ورود
        </button>

        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mr-4 font-vazir">
          <span class="material-icons">vpn_key</span>
          فراموشی رمز عبور
        </button>

      </section>

    </section>

  </section>
</template>

<script>
import textbox from "../../global/TextBox";
export default {
  name: "login",
  components: {textbox},
  data(){
    return{
      inProgress : false,
      username : "",
      password : ""
    }
  },
  methods: {
    login() {

      let formdata = new FormData();
      formdata.append("username" , this.username)
      formdata.append("password" , this.password)

      this.inProgress = true;
      axios.post("api.php?a=login",formdata).then(value => {
        this.toastAll(value.data.payload);
        if (value.data.status)
          this.redirect("profile.html")
      }).catch(err => {
        this.toastAll(err.response.data.payload);
      }).finally(() => {
        this.inProgress = false;
      })
    }
  }
}
</script>

<style scoped>

</style>