<template>
  <section class="d-flex flex-column justify-content-center align-items-center">


    <h2 class="mdl-typography--display-1 font-vazir text-dark">ثبت نام</h2>
    <section :class="['mdl-spinner' , 'mdl-js-spinner' ,  'is-active' ,  {'d-none' : ! inProgress} , 'spiner']"></section>

    <section class="d-flex flex-column justify-content-center align-items-center" v-if="! inProgress">


      <textbox type="text" holder="ایمیل" icon="alternate_email" message="ایمیل خود را وارد کنید" v-model="email"></textbox>
      <textbox type="text" holder="نام" icon="face" message="نام خود را وارد کنید" v-model="name"></textbox>
      <textbox type="text" holder="نام خانوادگی" icon="supervisor_account" message="نام خانوادگی خود را وارد کنید" v-model="family"></textbox>
      <textbox type="text" holder="نام کاربری" icon="account_circle" message="نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد" v-model="username"></textbox>
      <textbox type="password" holder="گذرواژه" icon="lock" message="گذرواژه باید حد اقل ۸ کاراکتر باشد" v-model="password"></textbox>
      <textbox type="password" holder="تکرار گذرواژه" icon="repeat" message="گذرواژه خود را  دباره وارد کنید" v-model="confirmpassword"></textbox>


      <!-- buttons -->
      <section class="row mt-4">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir"  @click="register">
          <span class="material-icons">login</span>
          ثبت نام
        </button>

      </section>

    </section>

  </section>

</template>

<script>
import textbox from "../../global/TextBox";
export default {
  name: "register",
  components: {textbox},
  data(){
    return {
      name : "",
      family : "",
      username : "",
      password : "",
      email : "",
      confirmpassword : "",
      inProgress : false
    }
  },
  methods:{
    register(){
      if (this.validatedata()){
        this.inProgress = true;

        let formdata = new FormData();
        formdata.append("username" , this.username)
        formdata.append("email" , this.email)
        formdata.append("name" , this.name)
        formdata.append("family" , this.family)
        formdata.append("password" , this.password)
        formdata.append("login" , true)

        this.inProgress = true;
        axios.post("api.php?a=register",formdata)
        .then(value => {
          console.log(value);
          this.toastAll(value.data.payload);
          if (value.data.status)
            this.redirect("profile.html")

        }).catch(err => {

          this.toastAll(err.response.data.payload);

        }).finally(() => {

          this.inProgress = false;

        })

      }
    },


    validatedata(){
      if (! this.validateEmail(this.email))
      {
        this.toast('ایمیل خود را صحیح وارد کنید' , 'error');
        return false;
      }

      if (! (3 <=  this.name.length && this.name.length <= 30))
      {
        this.toast('نام  باید بین ۳ تا ۳۰ کاراکتر باشد' , 'error');
        return false;
      }

      if (! (3 <=  this.family.length && this.family.length <= 30))
      {
        this.toast('نام خانوادگی باید بین ۳ تا ۳۰ کاراکتر باشد' , 'error');
        return false;
      }

      if (! (3 <=  this.username.length && this.username.length <= 20))
      {
        this.toast('نام کاربری باید بین ۳ تا ۲۰ کاراکتر باشد' , 'error');
        return false;
      }

      if (! (8 <=  this.password.length))
      {
        this.toast('گذر واژه باید بیشتر از ۸ کاراکتر باشد' , 'error');
        return false;
      }

      if (this.password !== this.confirmpassword){
        this.toast('تکرار گذر واژه همخوانی ندارد' , 'error');
        return false;
      }

      return true;
    }
  }
}
</script>

<style scoped>

</style>