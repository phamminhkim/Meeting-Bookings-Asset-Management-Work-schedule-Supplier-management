<template>
  <ul id="menu_hotline" class="hide-hotline" name="menu_hotline"
    style="list-style-type:none;border:2px solid red">
    <li style="color:red; text-transform: uppercase" class="ml-3"> <strong>Hotline hỗ trợ</strong></li>
    <li v-for="contact in contacts" :key="contact.name" v-bind:value="contact.name">
      <i>{{ contact.name }}</i> : 
      <a style="color:blue" target="_blank" :href="'https://zalo.me/' + contact.phone_text.replaceAll(' ','')">
      <strong>{{ contact.phone_text }}</strong>
      <img src="img/zalo.svg" width="20"/>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: {

  },
  methods: {

  },
  data() {
    return {
      contacts: [
        { name: 'A.Trí',  phone_text: '0903 342 134' },
        { name: 'A.Kiên', phone_text: '0907 996 569' }
      ]
    };
  },
};
</script>
