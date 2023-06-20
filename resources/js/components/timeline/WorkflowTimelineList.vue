<template>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <button class="btn btn-secondary btn-sm mb-3" @click="display()">Xem {{ $t('form.history') }}</button>
          <div class="timeline" v-show="show">
            <template v-for="item in get_formatted_list()">
              <div v-if="item.type == 'date'" v-bind:key="item.index" class="time-label">
                <span class="bg-green"> {{ item.day }} </span>

              </div>
              <div v-else-if="item.type == 'continue'" v-bind:key="item.index1">
                <i class="fas fa-clock text-gray" style="margin-bottom: 15px;margin-right: 10px;position: relative;">
                </i>

              </div>
              <div v-else v-bind:key="item.index2">
                <i v-if="showicon && item.icon" :class="item.icon"> </i>
                <i v-else class="fas fa-clock text-gray "> </i>
                <div class="timeline-item">
                  <span class="time"><i class="far fa-clock"></i> {{ item.created_at | formatDateTime }}</span>
                  <h3 class="timeline-header"><b><a>{{ item.username }}:</a></b>
                    {{ $t(item.title) }}
                  </h3>
                  <div class="timeline-body" v-if="item.content">
                    {{ item.content }}

                  </div>
                </div>
              </div>

            </template>


          </div>
        </div>

      </div>
    </div>
  </div>

</template>

<script>
export default {
  methods: {
    display() {
      this.show = !this.show;
    },
    get_formatted_list() {
      var current_day_string = null;

      var results = [];
      if (this.list) {
        var index = 0;

        results.push({
          type: 'continue',
        });

        let reverse_list = this.list;//.reverse();

        reverse_list.forEach(event => {
          var created_date = new Date(event.created_at);

          var day_string = created_date.getUTCFullYear() + '-' + (created_date.getUTCMonth() + 1) + '-' + created_date.getUTCDate();

          // Ngày mới
          if (current_day_string != day_string) {
            current_day_string = day_string;
            var current_day = {
              type: 'date',
              day: day_string,
            }
            results.push({ ...current_day });
          }

          var item = {
            type: 'event',
            username: event.user.name,
            title: event.title,
            icon: event.icon,
            created_at: event.created_at,
            content: event.content
          };
          results.push({ ...item });

        });
      }

      return results;
    }
  },
  data() {
    return {
      show: true,
    }
  },
  props: {
    list: Array,
    showicon: Boolean,
  },

}
</script>

<style>

</style>
