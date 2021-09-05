<template>
  <section class="d-flex flex-column justify-content-center align-items-center">

    <h2 class="mdl-typography--display-1 font-vazir text-dark">تماس با ما</h2>

    <section :class="['mdl-spinner' , 'mdl-js-spinner' ,  'is-active' ,  {'d-none' : ! inProgress} , 'spiner']"></section>



    <section class="d-flex flex-column justify-content-center align-items-center" v-if="! inProgress">


      <textbox type="text" holder="نام" icon="face" message="نام باید بین ۳ تا ۳۰ کاراکتر باشد" v-model="name"></textbox>
      <textbox type="text" holder="ایمیل" icon="lock" message="ایمیل فقط برای پاسخگویی شما است و افشا نمیشود" v-model="email"></textbox>
      <TextArea  holder="پیام" icon="lock" message="پیام خود را بنویسد پیام باید حد اقل ۱۰ و حداکثر ۱۵۰ کاراکتر باشد" v-model="message"/>

      <!-- buttons -->
      <section class="row mt-4">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir"  @click="send">
          <span class="material-icons">login</span>
          ارسال
        </button>

      </section>

    </section>

  </section>

</template>

<script>
import textbox from "../../global/TextBox";
import TextArea from "../../global/TextArea";
export default {
  name: "contactus",
  components: {TextArea, textbox},
  data(){
    return{
      inProgress : false,
      email : "",
      message : "",
      name : ""
    }
  },
  methods: {
    send(){
      this.inProgress = true
      if (this.validate()){

        let formdata = new FormData();
        formdata.append("email" , this.email)
        formdata.append("name" , this.name)
        formdata.append("message" , this.message)

        this.inProgress = true;
        axios.post("api.php?a=contactus",formdata)
            .then(value => {
              console.log(value);
              this.toastAll(value.data.payload);
              if (value.data.status){
                this.message = ""
                this.name = ""
                this.email = "";
              }
            }).catch(err => {

          this.toastAll(err.response.data.payload);

        }).finally(() => {

          this.inProgress = false;

        })


      }else{
        this.inProgress = false;
      }
    },

    validate(){
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
      if (! (10 <=  this.message.length && this.message.length <= 150))
      {
        this.toast('پیام  باید بین ۱۰ تا ۱۵۰ کاراکتر باشد' , 'error');
        return false;
      }
      return true;
    }

  }

  }
</script>

<style scoped>

</style>