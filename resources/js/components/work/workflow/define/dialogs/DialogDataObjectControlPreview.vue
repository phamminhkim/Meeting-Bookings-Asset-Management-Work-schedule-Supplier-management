<template>
    <div class="modal fade" id="modalDataObjectPreviewPreview" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content card">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">
                            <span> {{ title }}</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <data-object-control :workflow_id="workflow_id" :users="users" :controls="controls"
                                :tree_users="tree_users" :editmode="true" :canedit="true" />
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ $t("form.close") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import DataObjectControl from "../../runtime/objects/DataObjectControl.vue";
export default {
    components: { DataObjectControl },
    props: {
        workflow_id: Number,
        controls: Array,
        users: Array,
        tree_users: Array,
        title: String,
    },
    data() {
        return {

        };
    },
    methods: {

    }
};
</script>
