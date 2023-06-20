<template>
    <div class="form-search">
        <input class="form-control input-search" type="text" name="search" placeholder="Tìm kiếm tài sản....."
            autocomplete="off" v-model="searchKeyword" @input="onSearch">
        <div class="card item-search" v-if="searchKeyword !== ''">
            <div class="text-center text-secondary font-weight-lighter font-italic" v-if="search_assets.length == 0"
                style="padding:15px">
                <p><i class="far fa-lightbulb" style="font-size:30px;opacity:0.5"></i><br> Không tìm thấy <b
                        class="font-weight-bold">{{ searchKeyword }}</b> trong hệ thống</p>
            </div>
            <div class="text-center" v-if="loading">
                <div class="position-loading">
                    <i class="fad fa-spinner fa-pulse "
                        style="--fa-primary-color: #3277d2; --fa-primary-opacity: 0.3; --fa-secondary-color: #2a3ea2; --fa-secondary-opacity: 0.8;"></i>
                    <br>
                    <span class="text-secondary font-weight-bold text-xs font-italic">Vui lòng chờ giây lát...</span>
                </div>
            </div>
            <div class="form-group text-secondary mt-2 ml-3 font-italic" v-if="search_assets.length !== 0">
                <span>Tìm thấy <b>{{ search_assets.length }}</b> kết quả gần nhất </span>
            </div>
            <div class="form-group query" v-for="(query, index) in search_assets" :key="index"
                @click="getValue(query.name)">
                <div class="mt-1">
                    <span class="text-secondary">
                        <i class="fas fa-search mr-2" style="opacity:0.5"></i>
                        <span class="font-weight-bold">{{ index + 1 }} </span> .
                        <span v-html="highlightMatched(query.name)"></span>
                    </span>
                    <span class="float-right text-secondary font-italic text-xs"
                        v-html="hightlightDeviceName(query.deviceName)"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        fetchAssets: Function,
        fetchAssetTools: Function,
        fetchWarehouse: Function,
        reset: Function,
    },
    data() {
        return {
            loading: false,
            url_search_asset: "/api/asset/search",
            searchKeyword: '',
            search_assets: [],
            typingTimer: null,
            isFocused: false,
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        console.log('Component created');
    },
    methods: {
        fetchSearch() {
            this.loading = true;
            const queryParams = new URLSearchParams({
                search: this.searchKeyword,
            });
            fetch(this.url_search_asset + '?' + queryParams.toString(), {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.search_assets = data.data;
                    this.search_assets.forEach(asset => {
                        const deviceDetail = asset.asset_details.find(detail => detail.name.toLowerCase() === 'device name');
                        if (deviceDetail) {
                            asset.deviceName = deviceDetail.value;
                        } else {
                            asset.deviceName = null;
                        }
                    });
                    this.loading = false

                }).catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        getValue(name) {
            this.searchKeyword = name;
            this.fetchAssets();

            this.fetchWarehouse();

        },
        onSearch() {
            this.$emit('search-changed', this.searchKeyword);
        },
        highlightMatched(str) {
            const regex = new RegExp(this.searchKeyword, 'gi');
            return str.replace(regex, match => `<span class="text-black font-weight-bold">${match}</span>`)
        },
        hightlightDeviceName(str) {
            if (!str) {
                return '';
            }
            const regex = new RegExp(this.searchKeyword, 'gi');
            return str.replace(regex, match => `<span class="text-black font-weight-bold">${match}</span>`);
        },



        resetSearch() {
            this.searchKeyword = '';
        },
        resetList() {
            this.resetSearch();
            this.reset();
        },
        deleteItemSearch() {
            this.resetSearch();
            this.fetchAssets();
        },
    },
    watch: {
        searchKeyword: function (newVal, oldVal) {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.fetchSearch()
            }, 500);

        }
    },
    computed: {
        getName() {
            return this.searchKeyword;
        }
    }
}
</script>

<style lang="scss" scoped>
.form-search {
    position: relative;
}

.item-search {
    position: absolute;
    width: 100%;
    z-index: 1;
    visibility: hidden;
    opacity: 0;
    transition: opacity .3s ease-out, visibility .3s ease-out;

    &::before {
        position: absolute;
        content: '';
        width: 100%;
        background-color: red;
    }
}

.input-search {
    border-radius: 20px;
}

.query {
    padding: 5px;

    &:hover {
        border-left: 3px solid darkblue;
        background: rgb(242, 242, 242);
        cursor: pointer;
    }

}


input:focus+.item-search {
    visibility: visible;
    opacity: 1;
}

.search-item {
    color: gray;
    font-weight: 700;

    &:hover {
        border: 1px solid gray;
        color: rgb(0, 140, 255);
    }

}
.position-loading {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>