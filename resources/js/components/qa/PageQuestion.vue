<template>
    <div class="container-fuild bg-white">
        <div class="form-group row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card border-0 mt-4">
                    <div class="card-header border-0">
                        <h6 class="text-uppercase text-primary">hỏi đáp trực tuyến</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <label class="text-label">Từ khóa</label>
                            <input v-model="search.keyword" class="form-control" placeholder="Nhập từ khóa..." />
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <label class="text-label">Lĩnh vực</label>
                            <select v-model="search.category_id" class="form-control">
                                <option value="">Tất cả</option>
                                <option v-for="(category,index) in categories" :key="index" :value="category.id" > 
                                    {{category.name}}
                                </option>
                           
                            </select>
                            <button @click="buttonSearch()" class="btn btn-primary ml-2 btn-search">Tìm kiếm</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="form-group p-4 bg-white">
            <div class="item border ">
                <b-table striped hover :bordered="true" responsive :items="pageQuestions" :fields="fields">
                    <template #cell(id)="data">
                        <span> {{ data.index + 1 }} </span>
                    </template>
                    <template #cell(content)="data">
                        <button @click="pageAnswer(data.item.question_id,data.item.tag.tag_id)" class="btn rounded-pill btn-outline border-0">
                            <i class="fas fa-comments text-primary"></i>
                        </button>
                    </template>
                </b-table>
                <div v-if="pageQuestions.length == 0" class="text-center">
                    <p>Hiện tại danh sách này chưa có dữ liệu</p>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            token: '',
            loading: false,

            search: {
                keyword: '',
                category_id: '',
            },

            categories: [],
            pageQuestions: [],

            fields: [
                {
                    key: 'id',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'question.title',
                    label: 'Câu hỏi',
                    class: 'text-nowrap'
                },
                {
                    key: 'question.category.name',
                    label: 'Lĩnh vực',
                    class: 'text-nowrap'
                },
                {
                    key: 'content',
                    label: 'Câu trả lời',
                    class: 'text-nowrap text-center'
                },

            ],

         
            page_url_index_question: "/api/qa/pageQuestion",
            page_url_index_category: "/api/qa/category",
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        this.fetchPageQuestion();
        this.fetchCategory();
    },
    methods: {
        fetchPageQuestion() {
            this.loađing = true;
            const params = new URLSearchParams({
                search: this.search.keyword,
                category_id: this.search.category_id,
            });
            let page_url = this.page_url_index_question + '?' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.pageQuestions = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        fetchCategory() {
            this.loading = true;
            fetch(this.page_url_index_category, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.categories = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        buttonSearch() {
            this.fetchPageQuestion();
        },
        pageAnswer(question_id, tag_id) {
            window.location.href = '/qa/pageQuestion?type=answer&question_id=' + question_id + '&tag_id=' + tag_id;
        },
    },
    computed: {
        //
    },

};
</script>

<style lang="scss" scoped>
.text-label {
    flex: 0 0 100px;
}

.btn-search {
    flex: 1 1 160px;
}
</style>