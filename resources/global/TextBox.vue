<template>
  <section class="d-flex flex-column justify-content-center align-items-center mt-3">
      <section class="input-textbox">
        <span class="material-icons" v-text="icon"></span>
        <input :type="type" :placeholder="holder" class="form-control"    :value="value"  @input="$emit('input', $event.target.value)">
      </section>

    <section class="d-flex justify-content-center align-items-center">
      <span v-show="showeye"> نمایش : </span>
      <span v-show="showeye" class="material-icons" v-if="type === 'password'" @click="type = 'text'">visibility</span>
      <span v-show="showeye" class="material-icons" v-else @click="type = 'password'">visibility_off</span>
      <span class="text-dark mr-2" v-text="message" ></span>
    </section>
  </section>
</template>

<script>
export default {
  name: "textbox",
  props:[
    'type',
    'icon',
    'holder',
    'value',
    'message'
  ],
  data(){
    return {
      showeye : false
    }
  },
  created() {
    this.showeye = this.type === "password"
  }
}
</script>

<style scoped>

</style>