<template>

  <section class="d-flex flex-column justify-content-center align-items-center">

    <h2 class="mdl-typography--display-1 font-vazir text-dark">گروه یا شرکت جدید</h2>

    <section :class="['mdl-spinner' , 'mdl-js-spinner' ,  'is-active' ,  {'d-none' : ! inProgress} , 'spiner']"></section>



    <section class="d-flex flex-column justify-content-center align-items-center" v-if="! inProgress">
      <textbox type="text" holder="نام" icon="face" message="نام باید بین ۳ تا ۳۰ کاراکتر باشد" v-model="name"></textbox>
      <TextArea  holder="توضیحات" icon="lock" message="توضیحات باید حد اقل ۱۰ و حداکثر ۱۵۰ کاراکتر باشد" v-model="desc"/>

      <section class="row mt-4 justify-content-center">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir"  @click="register">
          <span class="material-icons">login</span>
          ثبت
        </button>
      </section>

    </section>

  </section>

</template>

<script>
import Textbox from "../../global/TextBox";
import TextArea from "../../global/TextArea";
export default {
name: "CreateGroup",
  components: {TextArea, Textbox},
  data(){
    return{
      inProgress : false,
      name : "",
      desc : ""
    }
  },
  methods:{
    register(){
      this.inProgress = true
      if (this.validate()){
        let data = new FormData();
        data.append("name" , this.name)
        data.append("description" , this.desc)
        axios.post("api.php?a=groupadd" ,data)
        .then(value => {
          this.toastAll(value.data.payload);
          if (value.data.status){
            window.app.$emit("groupUpdate")
            this.goto("group/" + value.data.payload.values[0].id)
          }
        }).catch(err => {
          this.toastAll(err.response.data.payload);
        }).finally(() => this.inProgress = false)

      }else {
        this.inProgress = false
      }
    },
    validate(){
      if (! (3 <= this.name.length && this.name.length <= 30)){
        this.toast("نام باید بین ۳ تا ۳۰ کاراکتر باشد" , "error")
        return false;
      }
      if (! (3 <= this.desc.length && this.desc.length <= 150)){
        this.toast("توضیحات باید بین ۳ تا ۱۵۰ کاراکتر باشد" , "error")
        return false;
      }
      return true;
    }
  }
}
</script>

<style scoped>

</style>