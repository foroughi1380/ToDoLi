<template>
  <section class="d-flex flex-column justify-content-center align-items-center mt-3">
      <section class="input-textbox">
        <span class="material-icons" v-text="icon"></span>

        <textarea :placeholder="holder" class="form-control ctextarea font-vazir"    :value="value"  @input="$emit('input', $event.target.value)"></textarea>

      </section>

    <section class="d-flex justify-content-center align-items-center">
      <span class="text-dark mr-2" v-text="message" ></span>
    </section>
  </section>
</template>

<script>
export default {
  name: "TextArea",
  props:[
    'icon',
    'holder',
    'value',
    'message'
  ],
}
</script>

<style scoped>

</style>