<template>
    <div class="container">
        <div id="toolbar">
            <button type="button"
                @click="$refs.chart.add({ id: +new Date(), x: 10, y: 10, name: 'New', type: 'operation', approvers: [] })">
                Add
            </button>
            <button type="button" @click="$refs.chart.remove()">
                Del
            </button>
            <button type="button" @click="$refs.chart.editCurrent()">
                Edit
            </button>
            <button type="button" @click="$refs.chart.save()">
                Save
            </button>
        </div>

        <flowchart :nodes="nodes" :connections="connections" :width="780" :height="500" @editnode="handleEditNode"
            @dblclick="handleDblClick" @editconnection="handleEditConnection" @save="handleChartSave" ref="chart">
        </flowchart>
    </div>
</template>

<script>
import Vue from "vue";
import FlowChart from "flowchart-vue";

Vue.use(FlowChart);
export default {
    components: {
        FlowChart,
    },
    data: function () {
        return {
            nodes: [
                // Basic fields
                { id: 1, x: 20, y: 40, name: "Start", type: "start" },
                { id: 2, x: 600, y: 240, name: "End", type: "end", }, //max y = 520
                {
                    id: 3, x: 180, y: 40, width: 160, name: "Xác nhận", type: "operation",
                    approvers: [{ name: "Xác nhận 1" }],
                },
                {
                    id: 4, x: 180, y: 140, width: 160, name: "Xác nhận", type: "operation",
                    approvers: [{ name: "Xác nhận 2" }],
                },
                {
                    id: 5, x: 180, y: 240, width: 160, name: "Xác nhận", type: "operation",
                    approvers: [{ name: "Xác nhận 3" }],
                },
                {
                    id: 6, x: 380, y: 40, width: 160, name: "Xem xét", type: "operation",
                    approvers: [{ name: "Xem xét 1" }],
                },
                {
                    id: 7, x: 380, y: 140, width: 160, name: "Xem xét", type: "operation",
                    approvers: [{ name: "Xem xét 2" }],
                },
                {
                    id: 8, x: 380, y: 240, width: 160, name: "Xem xét", type: "operation",
                    approvers: [{ name: "Xem xét 3" }],
                },
                {
                    id: 9, x: 580, y: 40, width: 160, name: "Phê duyệt", type: "operation",
                    approvers: [{ name: "Phê duyệt" }],
                },
            ],
            connections: [
                {
                    type: "pass",
                    source: { id: 1, position: "right" },
                    destination: { id: 3, position: "left" },
                },
                {
                    type: "pass",
                    source: { id: 3, position: "bottom" },
                    destination: { id: 4, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 4, position: "bottom" },
                    destination: { id: 5, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 5, position: "bottom" },
                    destination: { id: 6, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 6, position: "bottom" },
                    destination: { id: 7, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 7, position: "bottom" },
                    destination: { id: 8, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 8, position: "bottom" },
                    destination: { id: 9, position: "top" },
                },
                {
                    type: "pass",
                    source: { id: 9, position: "bottom" },
                    destination: { id: 2, position: "top" },
                },
            ],
        };
    },
    methods: {
        handleChartSave(nodes, connections) {
            console.log(nodes);
            // axios.post(url, {nodes, connections}).then(resp => {
            //   this.nodes = resp.data.nodes;
            //   this.connections = resp.data.connections;
            //   // Flowchart will refresh after this.nodes and this.connections changed
            // });
        },
        handleEditNode(node) {
            console.log(node);
        },
        handleEditConnection(connection) { },
        handleDblClick(position) {
            this.$refs.chart.add({
                id: +new Date(),
                x: position.x,
                y: position.y,
                name: "New",
                type: "operation",
                approvers: [],
            });
        },
    },
};
</script>

<style scoped>
#toolbar {
    margin-bottom: 10px;
}

.title {
    margin-top: 10px;
    margin-bottom: 0;
}

.subtitle {
    margin-bottom: 10px;
}

#toolbar>button {
    margin-right: 4px;
}

.container {
    width: 800px;
    margin: auto;
}
</style>