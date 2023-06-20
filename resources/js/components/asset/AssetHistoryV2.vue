<template>
    <div>
        <div class="modal" id="History-v2" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lịch sử tài sản - {{ asset.asset_id }} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <h4> {{ asset.name }} </h4>
                            </div>
                            <div class="card-body">
                                <b-table :fields="fields" :items="asset.history_v2" hover responsive striped >
                                    <template #cell(id)="data">
                                        <span class="badge text-black">{{ data.index + 1 }}</span>
                                    </template>
                                    <template #cell(trans_type)="data">
                                        <span v-if="data.item.trans_type == 1" class="badge text-success">Bàn giao</span>
                                        <span v-if="data.item.trans_type == 2" class="badge text-warning">Thu hồi</span>
                                        <span v-if="data.item.trans_type == 3" class="badge text-danger">Thanh lý</span>
                                    </template>
                                    <template #cell(user_id)="data">
                                        <span v-if="data.item.user_id"> {{ data.item.user.name }} </span>
                                    </template>
                                    <template #cell(department_id)="data">
                                        <span v-if="data.item.department_id"> {{ data.item.department.name }} </span>
                                    </template>
                                    <template #cell(confirm)="data">
                                        <span v-if="data.item.confirm == 0" class="badge badge-danger"> Chưa xác nhận </span>
                                        <span v-if="data.item.confirm == 1" class="badge badge-success"> Đã xác nhận </span>
    
                                    </template>
                                </b-table>
                            </div>
                        </div>
                      
                        
                    </div>
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

            asset: {
                asset_id: '',
                name: '',
                history_v2: [],
            },
            fields: [
                {
                    key: 'id',
                    label: 'ID',
                },
                {
                    key: 'trans_type',
                    label: 'Loại giao dịch',
                },
                {
                    key: 'create_by.name',
                    label: 'Ngưởi tạo',
                },
                {
                    key: 'user_id',
                    label: 'Ngưởi sử dụng',
                },
                {
                    key: 'department_id',
                    label: 'Phòng ban',
                },
                {
                    key: 'date_transaction',
                    label: 'Ngày tạo/giao dịch',
                },
                {
                    key: 'asset_status_id',
                    label: 'Trạng thái',
                },
                {
                    key: 'confirm',
                    label: 'Xác nhận',
                },
                {
                    key: 'description',
                    label: 'lý do',
                },
                

            ],

            page_url_get_history_v2: '/api/asset/asset-history-v2',
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

    },
    methods: {
        fetchHistoryV2() {
            this.loađing = true;
            const params = new URLSearchParams({
                asset_id: this.asset.asset_id,
            });
            let page_url = this.page_url_get_history_v2 + '?' + params.toString();
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.asset.history_v2 = res.data;
                    this.loading = false;
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });

        },
        showModal(item) {
            this.asset.asset_id = item.id;
            this.asset.name = item.name;
            this.fetchHistoryV2();
            $('#History-v2').modal('show');
        }
    }
}
</script>