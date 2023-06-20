<template>
    <div class="lang">
        <select name="language" class="form-select" @change="changeLanguage" v-model="language">
            <option value="en">English</option>
            <option value="vn">Tiếng Việt</option>
        </select>
    </div>
</template>
<script>
export default {
    data() {
        return {
            language: this.$i18n.locale
        };
    },
    methods: {
        changeLanguage() {

            localStorage.setItem('language', this.language);
            this.$i18n.locale = this.language;
           // document.head.querySelector('meta[name="locale"]').content = this.language;
           // console.log("test ngon ngu");
            fetch(`api/language/${this.language}`);
        }
    }
};
</script>
