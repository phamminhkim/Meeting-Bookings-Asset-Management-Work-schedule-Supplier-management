<template>
      <div class="float-sm-right">
        <!-- <div class="input-group">
                <div class="input-group-prepend">

                    <label class="input-group-text"  > {{ $t('form.create')}}</label>
                </div>
            <select name="" class="custom-select"  v-model="document_code"  @change="showAdd(document_code)"  id="">
                    <option   selected value="" >------{{ $t('form.select')}}------ </option>
                    <option   v-for="doc in document_types" v-bind:key="doc.id" v-bind:value="doc.code" class="dropdown-item">{{ $t(doc.name)}}</option>

            </select>

        </div> -->


        <div class="btn-group-vertical ">
            <button class="btn btn-primary dropdown-toggle dropdown-icon" :disabled="loading"   data-toggle="dropdown"> {{ $t('form.create')}} </button>
            <div class="dropdown-menu dropdown-menu-right" role="menu" >
            <div v-for="(doc,index) in document_types_submenu" v-bind:key="index">
                <a href="#" v-if="doc.id!=''"   @click.prevent="showAdd(doc.code)" class="dropdown-item">{{ $t(doc.name)}}</a>
                <li v-if="doc.id ==''"  class="dropdown-submenu dropdown-hover ">
                    <a   href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle ">{{doc.name}}</a>
                    <ul   class="dropdown-menu dropdown-menu-md-right border-0 shadow  ">

                    <li v-for="(sub,index_sub) in doc.submenu" v-bind:key="index_sub">
                        <a href="#"  @click.prevent="showAdd(sub.code)"  class="dropdown-item">{{sub.name}}</a>
                    </li>
                    </ul>
                </li>
            </div>
            <!-- <a href="#" v-for="doc in document_type_groups" v-bind:key="doc.id" @click.prevent="showAdd(doc.code)" class="dropdown-item">{{ $t(doc.name)}}</a> -->

                     <loading :loading="loading"></loading>

            </div>
        </div>

    </div>
</template>

<script>
import Loading from '../shared/Loading.vue';
export default {
     components: { Loading},
    props: {
        url: String,
        module:String,
    },
    data() {
        return {
            loading: false,
            document_types_submenu:[],
            page_url_document_type_sub_menu : "/api/category/documenttype/submenu",

        };
    },
     created() {

        this.token = "Bearer " + window.Laravel.access_token;
        this.fetDocumentTypeSubmenu();
    },
    methods:{
     fetDocumentTypeSubmenu() {
        // const this.token = "Bearer " + window.Laravel.access_this.token;
         this.loading = true;
        var page_url = this.page_url_document_type_sub_menu+"?module="+this.module;
        fetch(page_url, { headers: { Authorization: this.token } })
            .then(res => res.json())
            .then(res => {

                this.document_types_submenu = res.data;
                 this.loading = false;
                 // console.log(this.document_types_submenu);
            })
            .catch(err =>{
                this.loading = false;
                console.log(err)
            } );
        },
     showAdd(code){

        window.location.href=this.url+code;
      },
    }
}
</script>

<style lang="scss" scoped>

 .form-group {
    margin-bottom: 1px  !important;
}
.dropdown-submenu > .dropdown-menu  {
    left:auto !important;
    margin-left: 10px;
    margin-top: 30px;
    top: 0;
    // right:auto !important;
    //   right: 100%;
}

</style>
