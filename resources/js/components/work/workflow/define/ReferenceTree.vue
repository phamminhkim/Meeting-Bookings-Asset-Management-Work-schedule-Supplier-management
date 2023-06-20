<template>
    <treeselect 
      :placeholder="placeholder"
      :options="convertJsonToTree(this.workflow_structure)" 
     
      :value="value"
      @input="handleInput" >
    </treeselect>
</template>

<script>
import Treeselect from '@riophae/vue-treeselect'
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
  components: {
    Treeselect,
  },
  props: {
    workflow_structure: Object,
    placeholder: String,
    value: String,
  },
  methods: {
    handleInput(e) {
      this.$emit('input', e);
    },
    convertJsonToTree(container, parentKey = 'workflow') {
      var data = [];
      if (container != null) {
        for (var key in container) {
          var id = isNaN(key) ? parentKey + '.' + key : parentKey + '[' + key + ']';
          var value = container[key];
          var label = key;//isNaN(key) ? parentKey + '.' + key : parentKey + '[' + key + ']';

          var output = {};
          if (value !== null && typeof (value) == "object") {
            output = {
              id: id,
              label: label,
              children: this.convertJsonToTree(value, id)
            };
          } else {
            output = {
              id: id,
              label: label
            };
          }
          data.push(output);
        }
      }

      return data;
    },
  },
};
</script>