@@ -0,0 +1,66 @@
<template>
  <div :class="is_showing_search_list ? 'search-focus' : 'search-normal'">
    <div class="search-box">
      <div class="search-icon"><i class="fas fa-search"></i></div>
      <b-form-input id="search_textbox" @blur="onFocus(false)" @focus="onFocus(true)" @update="onChangeSearch()"
        class="search-input" type="text" v-model="query" placeholder="Tìm kiếm.." />


      <button v-show="is_showing_search_list" class="search-btn" @click="clearInput()" onclick="alert('hello');">
        <i class="fas fa-times"></i>
      </button>
    </div>



    <div class="search-list" v-show="is_showing_search_list">
      <div v-for="(item, index) in intellisense_array" :key="index" class="search-list_item"
        @hover="chooseValue(item.value)">
        <i v-if='item.past' class="fal fa-clock"></i>
          <i v-else class="fal fa-search"></i>
        <span v-html="renderHtmlRecord(item)"></span>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {

  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchDocumentTypes();
  },
  data() {
    return {
      page_url_document_type: "api/category/documenttypes",
      page_url_search: "/api/search",
      query_stack: [],

      document_types: [],
      is_showing_search_list: false,
      query: "",
      intellisense_array: [
        {
          value: "Tập đoàn <b>Thiên Long</b> thành lập vào năm nào ?",
          past: false
        },
        {
          value: "Thiên Long đến nay có tổng cộng bao nhiêu công ty thành viên ?",
          past: true
        },
      ],
    };
  },
  methods: {
    fetchDocumentTypes() {
      var page_url = this.page_url_document_type;
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.document_types = res.data;
        })
        .catch(err => console.log(err));
    },
    getDocumentName(document_type_id) {
      var obj = this.document_types.find(x => x.id == document_type_id);
      return obj ? '[' + obj.code + '] ' : '';
    },
    renderHtmlRecord(item) {
      let html = this.getDocumentName(item.document_type_id);
      html += item.value;
      return html;
    },
    chooseValue(value) {
      this.query = value;
    },
    onFocus(is_focus) {
      this.is_showing_search_list = is_focus;
    },
    clearInput() {
      this.query = "";
      this.is_showing_search_list = false;
    },
    onChangeSearch() {
      this.query_stack.push(this.query);

      setTimeout(() => {
        let query = this.query_stack.pop();

        //console.log(this.query_stack);
        this.query_stack = [];

        if (query) {
          console.log('Query: ' + query)
          var page_url = this.page_url_search + "?query_string=" + query;
          fetch(page_url, {
            headers: {
              Authorization: this.token,
              'content-type': 'application/json'
            }
          }).then(res => res.json())
            .then(data => {
              this.intellisense_array = data;
            });
        }

        if (!this.query) {
          this.intellisense_array = [];
        }

      }, 500);

    },
  },
  computed: {
    filteredList() {
      return this.intellisense_array.filter((fruit) =>
        fruit.value.toLowerCase().includes(this.query.toLowerCase())
      );
    }
  }
};
</script>
<style lang="scss">
.search-normal {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  flex: 1;
  flex-wrap: wrap;

  border: none;
  border-radius: 24px;
  background-color: white;
  color: black;
  box-shadow: 0 2px 5px 2px rgb(64 60 67 / 16%);
}

.search-focus {
  background-color: #fff;
  border-radius: 0;
  flex: 1;
  flex-wrap: wrap;
  box-shadow: 0 -2px 5px 2px rgb(64 60 67 / 16%);

  // box-shadow: 0 1px 12px #b8c6db;
  // -moz-box-shadow: 0 1px 12px #b8c6db;

  .search-input {
    background: transparent;
  }

  .search-box {
    width: 100%;
    position: relative;
    display: flex;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
  }

  .search-list {
    width: 98%;
    margin-top: 2px;
    position: absolute;
    background-color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 1px 5px 2px rgb(64 60 67 / 16%);

    &_item {
      padding: 0.4rem 0.4rem 0.4rem 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      //  border-bottom: 1px solid #e1e1e1;
      font-weight: 500;
      transition: all 0.3s ease;
      cursor: pointer;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;

      &:hover {
        background-color: #e1e1e1;
      }
    }
  }
}

.search-input {
  text-indent: 24px;
  width: 100%;
  font-family: 'Roboto', 'Segoe UI', Tahoma, sans-serif;
  font-size: 10;
  padding: 15px 45px 15px 15px;

  border: none;
  display: flex;
  border-radius: 24px;
  background-color: white;
  color: black;
}

.search-input:focus {
  border-radius: 10px 10px 0px 0px;

  outline: none;
}



.search-icon {
  display: flex;
  position: absolute;
  padding-left: 12px;
  padding-top: 12px;
}




.search-btn {
  background-color: transparent;
  font-size: 18px;
  padding: 6px 9px;
  margin-left: -35px;
  border: none;
  color: #6c6c6c;
  transition: all 0.4s;
}

.search-btn:hover {
  transform: scale(1.2);
  cursor: pointer;
  color: black;
}

.search-btn:focus {
  outline: none;
  color: black;
}
</style>