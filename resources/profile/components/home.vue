<template>
  <section class="d-flex flex-column justify-content-center align-items-center">


    <h2 class="mdl-typography--display-1 font-vazir text-dark">ویرایش اطلاعات</h2>
    <section :class="['mdl-spinner' , 'mdl-js-spinner' ,  'is-active' ,  {'d-none' : ! inProgress} , 'spiner']"></section>

    <section class="d-flex flex-column justify-content-center align-items-center" v-if="! inProgress">

      <img :src="getImageUrl(user.picture)"
           class="dropdown-toggle rounded-circle image-edit-profile"
           role="img" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
           alt="profile" v-if="user.picture != null"/>

      <img src="images/profile.jpg" class="dropdown-toggle rounded-circle image-edit-profile"
           role="img" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
           alt="profile" v-else/>

      <input type="file" ref="file" style="display: none" accept="image/*" @change="picuploaded">

      <button class="mdl-button mdl-js-button mdl-button--accent" @click="$refs.file.click()" v-text="picture != null? picture.name : 'تغیر'"></button>

      <textbox type="text" holder="ایمیل" icon="alternate_email" message="ایمیل خود را وارد کنید" v-model="email"></textbox>
      <textbox type="text" holder="نام" icon="face" message="نام خود را وارد کنید" v-model="name"></textbox>
      <textbox type="text" holder="نام خانوادگی" icon="supervisor_account" message="نام خانوادگی خود را وارد کنید" v-model="family"></textbox>
      <textbox type="password" holder="گذرواژه" icon="lock" message="گذرواژه باید حد اقل ۸ کاراکتر باشد" v-model="password"></textbox>
      <textbox type="password" holder="تکرار گذرواژه" icon="repeat" message="گذرواژه خود را  دباره وارد کنید" v-model="confirmpassword"></textbox>


      <!-- buttons -->
      <section class="row mt-4">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary font-vazir"  @click="register">
          <span class="material-icons">login</span>
          ویرایش
        </button>

      </section>

    </section>

  </section>

</template>

<script>
import textbox from "../../global/TextBox";
export default {
  name: "home",
  components: {textbox},
  data(){
    return {
      user : window.user,
      name : window.user.name,
      family : window.user.family,
      password : "",
      email : window.user.email,
      confirmpassword : "",
      inProgress : false,
      picture : null

    }
  },
  methods:{
    register(){
      if (this.validatedata()){
        this.inProgress = true;

        let formdata = new FormData();

        if (window.user.email !== this.email)
          formdata.append("email" , this.email)

        if (this.picture != null){
          formdata.append("picture" , this.picture)
        }


        formdata.append("name" , this.name)
        formdata.append("family" , this.family)

        if (this.password.trim().length !== 0)
          formdata.append("password" , this.password)

        this.inProgress = true;
        axios.post("api.php?a=profileupdate",formdata , {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
            .then(value => {
              this.toastAll(value.data.payload);
              window.user = value.data.payload.values[0]
              this.user = window.user;
              this.picture = null;

              console.log(window.app)

              window.app.$emit("infoupdate");

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


      if (this.password.trim().length !== 0 && ! (8 <=  this.password.length))
      {
        this.toast('گذر واژه باید بیشتر از ۸ کاراکتر باشد' , 'error');
        return false;
      }

      if (this.password.trim().length !== 0 && this.password !== this.confirmpassword){
        this.toast('تکرار گذر واژه همخوانی ندارد' , 'error');
        return false;
      }

      return true;
    } ,
    picuploaded(e){
      this.picture = e.target.files[0]
    }
  }
}
</script>

<style scoped>
.image-edit-profile{
  width: 150px;
  height: 150px;
}
</style>