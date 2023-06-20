<template>
  <li class="nav-item dropdown" v-if="current_language">
    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
      <i :class="'flag-icon flag-icon-' + getLangIcon(current_language)"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right p-0">
      <a v-for="language in languages" :key="language.symbol" v-bind:value="language.symbol"
        :href="'change-language/' + language.symbol"
        :class="getLangItemClass(language.symbol)">
        <i :class="'flag-icon flag-icon-' + getLangIcon(language.symbol) + ' mr-2'"></i> {{ language.name }}
      </a>
    </div>
  </li>

</template>

<script>
export default {
  methods: {
    getLangIcon(symbol) {
      var lang = this.languages.find(x => x.symbol == symbol);
      return lang ? lang.icon : '';
    },
    getLangItemClass(symbol) {
      var lang_class = 'dropdown-item';
      if (symbol == this.current_language) {
        lang_class += ' active';
      }
      return lang_class;
    }
  },
  data() {
    return {
      current_language: this.$i18n.locale,
      languages: [
        { symbol: 'vn', name: 'Việt Nam', icon: 'vn' },
        { symbol: 'en', name: 'English', icon: 'us' },
      ]
    };
  },
};
</script>
