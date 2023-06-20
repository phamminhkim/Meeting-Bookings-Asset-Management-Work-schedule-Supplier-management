<style scoped lang="scss">
.rating {
  display: flex;

  align-items: center;
  padding: 5px;
  color: #b7b7b7;
  background: #fff;
  border-radius: 8px;

  .list {
    padding: 0;
    margin: 0 5px 0 0;
    &:hover {
      .star {
        color: #e5e75e;
      }
    }
    .star {
      display: inline-block;
      font-size: 25px;
      transition: all 0.2s ease-in-out;
      cursor: pointer;
      &:hover {
        ~ .star:not(.active) {
          color: inherit;
        }
      }
      &:first-child {
        margin-left: 0;
      }
      &.active {
        color: #7bff00;
      }
    }
  }
  .info {
    margin-top: 15px;
    font-size: 40px;
    text-align: center;
    display: table;
    .divider {
      margin: 0 5px;
      font-size: 30px;
    }
    .score-max {
      font-size: 30px;
      vertical-align: sub;
    }
  }
}
</style>
<template>
  <div class="widget-box">   
   
    <div class="widget-header widget-header-flat" >
      <div class="image"><img v-bind:src="'.'+fnUser.avatar" alt="User Image" class="img-circle elevation-2" style="height: 34px;">
  
      Request by 
      <span class="label label-info label-sm">{{ fnUser.name }}</span
      ><br />
      {{ fnUser.department_id }}
    </div></div>
    <div class="">
      <div class=" ">
        <div class="row">
          <div class="col-xs-12 col-sm-12">     
            <ul class="list-unstyled spaced p-3">
              <li>
                <i class="ace-icon fa fa-envelope bigger-110 purple"></i>
                Email: {{fnUser.email}}
              </li>
              <li>
                <i class="ace-icon fa fa-phone bigger-110 green"></i>
                Phone: {{fnUser.phone}}
              </li>
            </ul>
                <hr/>
          </div>  </div>
      
              <div class="row">
          <div class="col-xs-12 col-sm-12">
            <ul class="list-unstyled spaced p-3">
              <!-- <li><b> Ticket:</b> {{ fnaItem.objectable_id }}</li> -->
              <li>
                <b> Điểm số:</b>                
                <div class="rating">
                  <ul class="list">
                    <li
                      @click="rate(star)"
                      v-for="star in maxStars"
                      :class="{ active: star <= fnaItem.rating }"
                      :key="star.grade"
                      class="star"
                    >
                      <i
                        :class="star <= grade ? 'fas fa-star' : 'far fa-star'"
                      ></i>
                    </li>
                  </ul>
                </div>
              </li>
              <li>
                <b> Nội dung:</b>
                <div class="">
                  <div class="">
                    <textarea
                      name="review"
                      id=""
                      cols="30"
                      rows="2"
                      placeholder="review"
                      v-model="fnaItem.reviews"
                    ></textarea>
                  </div>
                  <div class="" style="float: right;">
                    <button
                      class="btn btn-sm btn-info"
                      v-on:click="reviewrating(fnaItem)"
                    >
                   <i class="fa fa-send">Send</i>
                    </button>
                  </div>
                </div>
              </li>
            </ul>
          </div>
           </div>
          <div>
            <!--<div class="row">-->
          </div>
          <!--<div class="widget-main">-->
        </div>
        <!--<div class="widget-body">-->
      </div>
      <!-- <div class="widget-box">-->
    </div>
    <!--<div class="card-body">-->
  </div>
</template>
<script>
export default {
  props: {
    id: 0,   
    user_id:{},
  },
  data() {
    return {
      obj: {
        reviews: " ",
        user_id: 1,
        rating: 5,
        objectable_id: 7,
        objectable_type: "ticket",
      },
      aItem:{},
      grade: 5,
      maxStars: 5,
      hasCounter: 0,
      pItem: {},
      userInfo:{},
    };
  },
  created() {
      this.getticketbyid(this.id);      
     
  },
  computed: {
      fnaItem: function () {         
      var _item = {...this.aItem};
     _item.objectable_id = this.pItem.id;
      return _item;
    },
     fnpItem: function () {         
      var _item = {...this.pItem}; 
      return _item;
    },
    fnUser: function () {         
      var _item = {...this.userInfo};    
      return _item;
    },
  },
  methods: {
        headers: function () {
      var _headers = {};
      var _token = window.Laravel.access_token;
      // console.log('_token',_token);
      _headers.Authorization = "Bearer " + _token;
      _headers.Accept = "application/json";
      return _headers;
    },

    promiseApi: function (pars) {
      var results = [];
      pars.headers = this.headers();
      //  console.log('header',document.cookie);
      let _promise2 = new Promise((resolve, reject) => {
        $.ajax({
          type: pars.type || "get",
          dataType: pars.dataType || "json",
          url: pars.url,
          data: pars.data,
          headers: pars.headers,
        })
          .done((data) => {
            resolve(data);
            console.log("pars", pars);
          })
          .fail((errmsg) => {
            reject(errmsg);
          });
      });
      return _promise2;
    },
    reviewrating: function (item) {
    //  if (this.pitem) item.objectable_id = this.pItem.id;
      var pars = {};
      if(item.reviews||item.reviews=='')
        item.reviews=item.rating + " sao";
      pars.data = {
        reviews: item.reviews,
        rating: item.rating||5,
        objectable_id: item.objectable_id,
      };
      console.log("reviewrating",pars.data);
      pars.url = "/api/service/reviewsRating";
      pars.type = "post";
      this.promiseApi(pars).then((respone) => {
        alert("insert rating thành công!");      
      });
    },

    rate(star) {
      if (typeof star === "number" && star <= this.maxStars && star >= 0) {
        this.grade = this.grade === star ? star - 1 : star;
        this.fnaItem.rating = this.grade;
      }
    }, 
    getUserbyId: function(id)
    {
        if (id && id > 0) {
        var pars = {};
        pars.data = {id:id};
        pars.url = "/api/user/userinfo/"+id; 
           this.promiseApi(pars).then((response) => {
           this.userInfo=response.data;
           });
        }
    },
    getticketbyid: function (id) {
      if (id && id > 0) {
        var pars = {};
        pars.data = {id:id};
        pars.url = "/api/service/ticketbyId?";
        this.promiseApi(pars).then((response) => {
          this.pItem = {...response.data[0]};
          this.aItem={...response.reviewrating};
         this.getUserbyId(this.user_id);
      //   this.getUserbyId(16);
          console.log("window.Laravel", window);
        });
      }
    },
  },
};
</script>
