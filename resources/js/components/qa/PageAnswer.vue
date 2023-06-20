<template>
    <div class="container-fuild">
        <div class="form-group bg-white p-4">
            <div class="card border-0">  
                <div class="card-header question-answer">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-primary font-weight-bold mt-2">hỏi đáp trực tuyến</h6>
                        </div>
                        <div class="col-md-6">
                            <button @click="pageHome()" class="btn btn-primary font-weight-bold rounded-pill float-right">Trang chủ</button>
                        </div>  
                    </div>
                </div>
                <div class="card-body">
                    <div class="question border-bottom">
                        <div class="form-group d-flex align-items-baseline">
                            <i class="fas fa-question-circle text-danger mr-2"></i>
                            <label v-if="answer.question_id"> {{ answer.question.title }} </label>
                        </div>
                        <p v-if="answer.user_id">Ngưởi gửi: {{ answer.question.user.name }} </p>
                        <div class="form-group">
                            <p v-if="answer.question_id" class="question-content"> {{ answer.question.content }} </p>
                        </div>
                    </div>
                    <div class="answer mt-3">
                        <div class="form-group d-flex align-items-baseline">
                            <i class="fas fa-comment-dots text-danger mr-2"></i>
                            <label>Câu trả lời </label>
                        </div>
                        <div class="form-group">
                            <p class="question-content"> {{ answer.content }} </p>
                        </div>
                        <p v-if="answer.user_id">Người trả lời: {{ answer.user.name }} - {{ answer.user.department.name }}  </p>
                    </div>
                    <div class="form-group">
                        <p class="text-uppercase text-danger font-weight-bold" style="border-bottom:3px solid red" >các câu hỏi liên quan khác</p>
                        <b-table :fields="field_question_tags" :items="question_tags">
                            <template #cell(id)="data">
                                <span> {{ data.index + 1 }} </span>
                            </template>
                            <template #cell(content)="data">
                                <button @click="pageAnswer(data.item.question_id,data.item.tag_id)" class="btn rounded-pill btn-outline border-0">
                                    <i class="fas fa-comments text-primary"></i>
                                </button>
                            </template>
                        </b-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        question_id: String,
        tag_id: String,
    },
    data() {
        return {
            token: '',
            loading: false,

            answer:{
                id: '',
                question_id: '',
                user_id: '',
                content: '',
            },

            question_tags: [],

            field_question_tags: [
                {
                    key: 'id',
                    label: '#',
                    class: 'text-nowrap'
                },
                {
                    key: 'tag.name',
                    label: 'Tag',
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


            page_url_show_answer: "/api/qa/pageQuestion",
            page_url_index_question_tag: "/api/qa/questionTag",
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        this.fetchShowAnswer();
        this.fetchQuestionTag();
    },
    methods: {
        fetchShowAnswer() {
            this.loading = true;
            var page_url = this.page_url_show_answer + "/" + this.question_id + "/" + this.tag_id;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.answer = data.data;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        fetchQuestionTag(){
            this.loading = true;
            const params = new URLSearchParams({
                tag_id: this.tag_id,
                question_id: this.question_id,
            });
            var page_url = this.page_url_index_question_tag + '?' + params.toString();
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.question_tags = data.data;
                    this.loading = false;
                }).catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        pageAnswer(question_id, tag_id) {
            window.location.href = '/qa/pageQuestion?type=answer&question_id=' + question_id + '&tag_id=' + tag_id;
        },
        pageHome() {
            window.location.href = '/qa/pageQuestion';
        },
    }
}
</script>

<style lang="scss" scoped>
.question-content{
    word-break: break-word;
    line-height: 1.5;
    text-align: justify;
}
.question-answer{
    border-bottom: 3px solid red;
}
</style>