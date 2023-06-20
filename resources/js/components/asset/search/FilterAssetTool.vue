<template>
    <div>
        <input v-model="query_asset_type" class="form-control form-control-sm mb-2 query-search"
            placeholder="Tìm kiếm Model tài sản..." />
            <div>
                <label class="label text-sm">Model tài sản</label>
               
            </div>
            <div class="text-center" v-if="loading">
                <i class="fad fa-spinner fa-pulse " style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;font-size:30px"></i>
                <br>
                <span class="text-secondary font-weight-bold text-xs font-italic">Vui lòng chờ giây lát...</span>
            </div>
        <div class="form-group" v-for="(type, index) in asset_types" :key="index">
            <div class="p-1 item-group" :class="{ active: selectedGroupIndex === index }" @click.prevent="fillterAssetType(type, index)"
            :style="{ backgroundColor: selectedGroupIndex === index ? 'rgb(36, 143, 231)' : 'transparent' }">
                <span class="mr-2" style="font-size:10px">
                    <i class="fas fa-bars text-secondary" style="opacity:0.5"></i> 
                </span>
                <span style="font-size:10px"> {{ index + 1 }}. {{ type.name }} </span>
            </div>
        </div>
        <label class="label text-xs text-right"> {{ paginate.total }} items </label>
       
    </div>
</template>

<script>
export default {
    props: {
        fetchAssetTools: Function,
    },
    data() {
        return {
            query_asset_type: '',
            asset_type_id:'',
            name:'',

            selectedGroupIndex: null,
            loading: false,
            typingTimer: null,
            token: '',

            asset_types: [],
            paginate: {
                current_page: 1,
                last_page: 1,
                total: 0,
            },
            perPage: 10,

            url_asset_types: "/api/asset/search_asset_type",
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        this.fetchTypes();
    },
    methods: {
        fetchTypes() {
            this.loading = true;
            // let vm = this;
            // this.asset_types = Array();
            const params = new URLSearchParams({
                search: this.query_asset_type,
            });
            var page_url = this.url_asset_types + '?' + params.toString();
            // var page_url = this.url_asset_types;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                }
            })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    if (res.success == 1) {
                        this.asset_types = res.data;
                        this.paginate = res.pagination;
                    }

                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;

                });
        },
        fillterAssetType(type,index) {
            this.asset_type_id = type.id;
            this.name = type.name;
            this.selectedGroupIndex = index;
            this.$props.fetchAssetTools();
        },
        resetFilter() {
            this.asset_type_id = '';
            this.name='';
            this.selectedGroupIndex = null;
            this.$props.fetchAssetTools();
            
        },
    },
    watch: {
        query_asset_type: function (newVal, oldVal) {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.fetchTypes()
            }, 500);
        }
    }
}
</script>

<style lang="scss" scoped>
.query-search {
    border-radius: 30px;
}
.btn-filter{
    border-radius: 30px;
    border: 1px solid lightblue;
    font-size: 10px;
}
.item-group {
    &:hover {
        background: radial-gradient(#92cbfa, rgb(231, 244, 255) 100%, rgb(149, 207, 255));
        cursor: pointer;
        color: white;
    }
}
</style>