<template>
      <li class="nav-item dropdown">
       
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" v-if="countItem < 100" >
              {{countItem}}
          </span>
           <span v-else>+99</span>
        </a>
       <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" > {{countItem}} {{$t('form.notification')}}</span>
           <div v-for="noti in unreadNotifications " v-bind:key="noti.id">
              
             <div class="dropdown-divider"></div>
              <!-- <a href="#" class="dropdown-item" v-if="noti.data.data && noti.data.data.icon && noti.data.data.url && noti.data.data.title">
                <i :class="noti.data.data.icon"></i>
                 <a :href="get_url(noti.data.data.url,noti.id)" class="ml-2"> 
                    <span >{{noti.data.data.title}}</span>
                    <p class="text-mute text-sm ml-4" v-if="noti.data.data.content">&nbsp;&nbsp;&nbsp;{{noti.data.data.content}}</p>
                 
                 </a> 
               
              </a> -->
               <a :href="get_url(noti.data.data.url,noti.id)" class="dropdown-item">
                  <!-- Message Start -->
                  <div class="media">
                     <i :class="noti.data.data.icon"></i>
                    <!-- <img src="img/avata-default.png" alt="User Avatar" class="img-size-50 mr-3 img-circle"> -->
                    <div class="media-body">
                      <h3 class="dropdown-item-title">
                        <span class="ml-2">{{noti.data.data.title}}</span>
                        <!-- <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span> -->
                      </h3>
                      <p class="text-sm"  v-if="noti.data.data.content">{{noti.data.data.content.substring(0,30)}} <span v-if="noti.data.data.content.length > 30">...</span></p>
                      <p class="text-sm text-muted"  ><i class="far fa-clock mr-1"></i> <span v-html="getTime(noti) "></span></p>

                    </div>
                  </div>
                  <!-- Message End -->
                </a>
           </div>
          
          
          <div class="dropdown-divider"></div>
          <a href="/notifications" class="dropdown-item dropdown-footer">{{$t('form.see_all_notification')}}</a>
        </div>
      </li>
  
</template>

<script>
 import moment from 'moment'
export default {
  methods: {
    get_url(url,noti_id){
      return url+"&notification_id="+noti_id;
    },
    getTime(data){
    //  console.log(data);
      if(data.created_at){
       //  return data.created_at.toLocaleDateString();
       return moment(String(data.created_at)).format('Y-M-D hh:mm')
      }else{
         return moment().format("hh:mm");
      }
     
    },
  },
  props: { unreads: Array, userid: String , count:Number},

  data() {
    return {
      unreadNotifications: this.unreads,
      countItem:this.count,
    };
  },

  mounted() {
  //   Echo.private('App.User.'+this.userid).notification((notification) => {
	//  // console.log(notification);
	//   let newUnreadNotifications = {  data:{
  //                                     data:{
  //                                       title:notification.data.title,
  //                                       icon:notification.data.icon,
  //                                       url:notification.data.url,
  //                                       content:notification.data.content,
  //                                       date:notification.data.date,
                                        
  //                                     },
                                    
  //                                   },
  //                                 id:notification.id,
  //                                 type:notification.type,
  //                               };
  //   this.countItem = this.countItem + 1; 
	//   this.unreads.push(newUnreadNotifications);
  //   });
  },
};
</script>

<style>
</style>