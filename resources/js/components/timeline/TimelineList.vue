<template>
    <div class="card">
      <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <button class="btn btn-secondary btn-sm mb-3"  @click="display()">{{$t('form.history')}}</button>
        <div class="timeline timeline-inverse" v-show="show">
           <!-- <div class="time-label">
                <span class="bg-gray">
                  Lịch sử
                </span>
              </div> -->

              <div  v-for="item in list" v-bind:key="item.id">


                  <i v-if="showicon && item.icon" :class="item.icon"> </i>
                  <i v-else  class="fas fa-clock text-gray "> </i>
                  <div class="timeline-item">
                      <span class="time"><i class="far fa-clock"></i> {{item.created_at|formatDateTime}}</span>
                      <h3 class="timeline-header mute"><i><a style="color:gray" >{{item.user.name}}:</a></i> {{$t(item.title)}}</h3>
                      <div class="timeline-body" v-if="item.content">
                          {{item.content}}
                      </div>
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
  methods: {
    display(){
      this.show = !this.show;
    }
  },
  data () {
    return {
      show:true,

    }
  },
  props: {
    list:Array,
    showicon:Boolean,
  },

}
</script>

<style>

</style>
