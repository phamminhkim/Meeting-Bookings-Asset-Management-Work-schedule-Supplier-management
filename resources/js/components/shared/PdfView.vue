<template>
    <div>

           <b-modal id="modal-xl" size="xl"  :title="filename" hide-footer>
                <div class="card">
                    <!-- <div class="card-header">


                    </div> -->
                    <div class="card-content">
                          <!-- <div id="actionPanel"   style="display:block; background-color:red;z-index:1000;">
                                     <span><i class="fas fa-trash"></i></span>
                                 </div> -->

                         <div class="row">
                             <div class="col-sm-9">
                                 <div class="row">
                                    <div class="col"  >
                                        <!-- <input type="file" ref="file" @change="onPdf($event)" /> -->
                                            <div style="display:inline;float:right">
                                                <span >{{$t('form.page')}}: {{ currentPage}}</span>
                                                    /<span id="totalPage"></span>
                                            </div>
                                            <!-- <div id="toado">toa do</div> -->
                                            <div v-show="false" id="canvas_height">chiều cao trang: </div>
                                         <!-- <button @click="loadPDF()">Show file</button> -->
                                         <button id="showChuky" v-show="false" @click="showChuKy()">Click</button>
                                    </div>
                                </div>
                                 <div style="width:100%;background-color:#eee;overflow:auto;height:500px;padding-top:10px" v-on:scroll="sroll($event)">
                                    <div id="holder" class="holder" ref="holder" v-on:click="resetHolder($event)"   v-on:drop="drop($event)"   v-on:dragover="allowDrop($event)"  ></div>
                                    <div class="center overlay" style="display:none"   id="loading">
                                        <i
                                            class="fa fa-spinner fa-spin fa-4x fa-fw gray center"
                                        ></i>
                                        <span class="sr-only">Loading...</span>
                                        <!-- <b-spinner variant="primary" label="Spinning"></b-spinner> -->
                                    </div>
                                </div>
                             </div>
                             <div class="col-sm-3" >

                                <!-- <div class="approved draggable" id="popover-button-variant" @click="showActionPanel($event)"  style="text-align: center;" >

                                <span>Đã ký: </span><br>
                                <span>22/10/2021 15:27:44 </span>
                                </div> -->
                                <div class="col-sm-12">
                                    <div class="row center mt-5">

                                    <div class="col-md-8">
                                     <div  class="panelItemSign"
                                        @dragstart="dragStart($event)"
                                        @dragend="dragEnd($event)"
                                        @dragenter="dragEnter($event)"
                                        @dragover.prevent
                                        @dragleave="dragLeave"
                                        @mouseup="insertHtml($event)"
                                        ><i class="fas fa-sign"></i>  {{$t('form.signer_location')}} </div>

                                    </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 mt-2 center">
                                            <button @click.prevent="savePosition()" class="btn btn-primary">{{$t('form.save_info')}} </button>

                                        </div>
                                    </div>
                                </div>


                             </div>
                         </div>

                        <!-- <loading id="loading" v-bind:loading="loading" ref="loading" ></loading> -->

                    </div>
                </div>

                <!-- <canvas  ref="pdfViewer"></canvas> -->

           </b-modal>

    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
 // import the styles
 import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
     components:{
      Treeselect
    },
    props:{

        eventname:String,
        fileInfo:Object,
        manySign:Boolean,

    },
    data() {
        return {
            currentPage:1,
            scrollTop:0,
            show_actionPanel:true,
            filename:"Tên file:",
            outputArray:[],

            signInfo:{
                page:"",
                x:"",
                y:"",
                sign:"",//vị trí tương ứng với sign1,sign2,...
                show_sign:"",//Có hiển thị chữ ký = X
                height:"",
                width:"",
                height_c:"",
                width_c:"",
                cx:"",//convert sang mm của tọa độ X
                cy:"",//convert sang mm của tọa độ Y
            },
             page_url_users:"api/user/all",

             users:[],
             tree_users:[],
             user_approve:null,

        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.init();
        this.getUser();


    },

   mounted(){


  },
  watch:{
      fileInfo(){
        this.loadPDF();
      },
  },

  computed:{

    totalPage(){
         var total = $('#totalPage').innerHTML;
         return total;
    },

  },
    methods:{
        getlistUser(val){
            var data = [];
            this.users.forEach(user => {


                if (user.name.toLocaleLowerCase().includes(val.toLocaleLowerCase()) ||
                   user.username.toLocaleLowerCase().includes(val.toLocaleLowerCase())) {
                    data.push(user);
                }

            });
            return data;
        },
         getUser(){

         var  page_url = this.page_url_users
          //console.log(page_url);
          fetch(page_url,{
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
          }).then(res=>res.json())
          .then(data=>{
              this.users = data.data;
          }) .catch(err => {

                    console.log(err);
                });

      },
                getUserTree(){

                var  page_url = this.page_url_users+"?type=tree_combobox"
                console.log(page_url);
                fetch(page_url,{
                        headers: {
                            Authorization: this.token,
                            "content-type": "application/json"
                        }
                }).then(res=>res.json())
                .then(data=>{
                    this.tree_users = data.data;
                }) .catch(err => {

                            console.log(err);
                        });

            },
            loadPDF(){


                this.filename =  this.fileInfo.name;
                if (this.fileInfo) {
                    var pdfData =  new Uint8Array(this.convertDataURIToBinaryFF(this.fileInfo.base64));
                   // console.log(pdfData);
                      $('#loading').show();
                      var checkExist = setInterval(function() {
                        if ($('#loading').length) {
                             $('#loading').show();
                            clearInterval(checkExist);
                        }
                        }, 100); // check every 100ms



                      setTimeout(() => {
                           this.displayPdf(pdfData);
                       },500);


                }
            },
         convertDataURIToBinaryFF(dataURI) {
             var BASE64_MARKER = ';base64,';
             var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
             var raw = window.atob(dataURI.substring(base64Index));
                return Uint8Array.from(Array.prototype.map.call(raw, function(x) {
                    return x.charCodeAt(0);
                }));
            },

           resetHolder(ev){
                    //  console.log(ev.target.tagName);
                  if(ev.target.tagName == 'CANVAS'){
                      var list = document.getElementsByClassName('boxsign');
                      for (let index = 0; index < list.length; index++) {
                          const element = list[index];
                          element.classList.remove('edit');
                      }
                  }
            },
            savePosition:function(){
                var list = document.getElementsByClassName('boxsign');
                 this.fileInfo.signs = [];

                 //console.log(list);
                for (let index = 0; index < list.length; index++) {
                     var  element = list[index];
                     var inputs = element.getElementsByTagName("input");
                    // var show_sign = element.getElementsByTagName("input");
                    var show_sign = null;
                     for (let input of inputs) {
                         if (input.classList.contains('check_show_sign')) {
                                  show_sign = input;
                            }
                     }
                      //console.log( show_sign);
                     var selectSign = element.getElementsByTagName('select');

                    //  console.log("top:" + list[0].style.top + " - left" + list[0].style.left);
                    var y = element.style.top.replace('px','');
                    var x = element.style.left.replace('px','');
                    var signInPage = this.findPageNum(y);
                    var canvas_height = document.getElementById('canvas_height').innerHTML;
                    const InchToMm = 2.83464567;
                    var space = 5;//pt
                    const PageHeight = canvas_height ; //chieu cao trang (mm) + margin top bottom
                    var onPageX = Math.floor((x) / InchToMm) ;
                    // var onPageY = Math.floor((y) / InchToMm) - ((signInPage - 1) * PageHeight);
                    var onPageY = Math.floor(y)  - ((signInPage - 1 ) * PageHeight) - (space * (signInPage - 1 ));
                        onPageY =  Math.floor(onPageY) /InchToMm;
                    this.signInfo.x = x;
                    this.signInfo.y = y;
                    this.signInfo.page = signInPage;
                    this.signInfo.sign = selectSign[0].value;
                    // this.signInfo.show_sign = show_sign[0].checked;
                    this.signInfo.show_sign = show_sign.checked;
                    this.signInfo.cx = onPageX;
                    this.signInfo.cy = onPageY;

                     var rect = element.getBoundingClientRect();

                    this.signInfo.width = rect.width;
                    this.signInfo.height = rect.height ;

                    this.signInfo.width_c = Math.floor( rect.width / InchToMm);
                    this.signInfo.height_c = Math.floor( rect.height / InchToMm);


                    this.fileInfo.signs.push({...this.signInfo});


                    // this.signInfo.show_sign = signInPage;

                }
                  this.$emit(this.eventname,this.fileInfo);

                this.$bvModal.hide('modal-xl');
                // console.log("top:" + list[0].style.top + " - left" + list[0].style.left);
                // var y = list[0].style.top.replace('px','');
                // var x = list[0].style.left.replace('px','');
                // var signInPage = this.findPageNum(y);
                // var canvas_height = document.getElementById('canvas_height').innerHTML;
                // const InchToMm = 2.83464567;
                // const PageHeight = canvas_height ; //chieu cao trang (mm) + margin top bottom

                // var onPageX = Math.floor((x) / InchToMm) ;
                // var onPageY = Math.floor((y) / InchToMm) - ((this.currentPage - 1) * PageHeight);

                // var msg = "Page: " + signInPage + " - Mouse (mm): "
                // + onPageX + ' X ' + onPageY
                // +" ~  "+ x + " x " + y;

                // var todo = document.getElementById('toado');
                // todo.innerHTML=msg;
                // console.log(todo);
            },
            init(){
                //khởi tạo các hàm mousemove
                const d = document.getElementsByClassName("draggable");
                document.onmousedown = this.filter;

                for (let i = 0; i < d.length; i++) {
                d[i].style.position = "relative";

                }

            },

             filter:function(e) {
                let target = e.target;

                if (!target.classList.contains("draggable") ) {
                 return;
                }

                target.moving = true;

                if (e.clientX) {
                target.oldX = e.clientX; // If they exist then use Mouse input
                target.oldY = e.clientY;

            } else {
                target.oldX = e.touches[0].clientX; // Otherwise use touch input
                target.oldY = e.touches[0].clientY;
            }
            //NOTICE THIS Since there can be multiple touches, you need to mention which touch to look for, we are using the first touch only in this case

            target.oldLeft =  window.getComputedStyle(target).getPropertyValue('left').split('px')[0] * 1;
            target.oldTop =  window.getComputedStyle(target).getPropertyValue('top').split('px')[0] * 1;

                document.onmousemove = dr;
                document.ontouchmove = dr;
                function dr(event) {

                    event.preventDefault();
                    if (!target.moving) {
                    return;
                    }

                    if (event.clientX) {
                        target.distX = event.clientX - target.oldX;
                        target.distY = event.clientY - target.oldY;
                        } else {
                        target.distX = event.touches[0].clientX - target.oldX;
                        target.distY = event.touches[0].clientY - target.oldY;
                        }
                        //NOTICE THIS
                    var holder = document.getElementById("holder");
                    var rect = holder.getBoundingClientRect();
                    var rect_targer = target.getBoundingClientRect();
                    var newLeft = target.oldLeft + target.distX;
                    var newTop =  target.oldTop + target.distY ;
                    target.style.left = newLeft + "px";
                    target.style.top = newTop + "px";
                    //Chỉ duy chuyển trong thẻ Holder
                    if (newLeft < 0 ) {
                        newLeft = 0;
                        target.style.left = newLeft + "px";

                    }
                    if ( newTop < 0 ) {
                        newTop =  0;
                         target.style.top = newTop + "px";
                        //console.log("lon hon");
                     }
                      if ((newLeft + rect_targer.width) > rect.width  ) {
                             newLeft = rect.width - rect_targer.width;
                            target.style.left = newLeft + "px";

                    }
                     if ( (newTop + rect_targer.height) > rect.height ) {
                          newTop =  rect.height - rect_targer.height  ;
                         target.style.top = newTop + "px";
                        //console.log("lon hon");
                     }


                }
                function endDrag() {
                    target.moving = false;
                }
                target.onmouseup = endDrag;
                target.ontouchend = endDrag;

            },
            allowDrop:function(ev) {

                ev.preventDefault();
            },
           sroll(ev){
               console.log(ev.srcElement.scrollTop );
               this.scrollTop = ev.srcElement.scrollTop;
                var holder = document.getElementById('holder');
                var child = holder.childNodes[0];
                var rect = child.getBoundingClientRect();
                console.log("test: "+ev.srcElement.scrollTop +"-"+(rect.height ));
                if(rect && rect.height > 0){
                    this.currentPage = Math.floor(ev.srcElement.scrollTop / (rect.height )) +1;
                }

           },
           findPageNum(pointTop){

                var postion = pointTop;
                var pagenum = 0;
                var sum = 0;
                 var space = 0; //lấy từ class css : canvas-space
                var holder = document.getElementById('holder');

                for (let index = 0; index <  holder.childNodes.length; index++) {

                    var child =  holder.childNodes[index];
                    var rect = child.getBoundingClientRect();
                     if(rect && rect.height > 0){
                         sum = + sum +  ( rect.height + space );

                     }

                      if(sum >= postion ){
                         pagenum =  index ;
                         break;
                     }


                }


                return pagenum  + 1;
           },
           showActionPanel:function(ev){
               var actionPanel = document.getElementById("actionPanel");
               console.log(actionPanel);
                actionPanel.style.top = "200px";
                actionPanel.style.left = "200px";
                this.actionPanel = true;
           },
           getTemplateSign(type){
               var temp = "";
                switch (type) {
                    case "location":
                      temp = '<div class="approved draggable" @click="showActionPanel($event)"   style="text-align: center;" >'
                            +'<span>Đã ký: </span><br>'
                            +'<span>22/10/2021 15:27:44 </span>'
                            +'</div>';
                        break;

                    default:
                        break;
                }
                return temp;

           },
            removeBoxsign:function(ev) {

                ev.target.parentElement.parentElement.parentElement.remove();
            },
            showListSearchUser:function(ev){

                //TODO
                var list = ev.target.parentElement.childNodes;
                for (let index = 0; index < list.length; index++) {
                    const element = list[index];
                       // console.log(element.classList.contains('search'));
                       console.log( element);
                    if (element.classList.contains('search')) {
                         element.classList.add('show');
                        //element.style.top = ev.target.style.top * -1;
                    }else if(element.classList.contains('form-select form-control')){
                         //element.classList.add('hideElement');
                    }


                }
            },
            //Chọn user từ danh sách tìm kiếm
            selectUser:function(ev){

                  var list = ev.target.parentElement.parentElement.childNodes;
                for (let index = 0; index < list.length; index++) {
                    let txtFind = this.$t('form.hide_find');
                    const element = list[index];
                    if (element.classList.contains('txtsearch')) {
                         element.value = "";
                         element.classList.add('hideElement');
                         element.classList.remove('show');

                    }else if(element.classList.contains('form-select')){
                         element.classList.remove('hideElement');
                         element.classList.add('show');
                         var iduser = $(ev.target).data('id');
                         $(element).val(iduser);

                    }else if(element.textContent.includes(txtFind)){
                        // console.log("ẩn tìm");
                         element.textContent = this.$t('form.find')
                    }
                }

                ev.target.parentElement.classList.remove('show');


            },
            showSearch:function(ev){

                 var txtFind = this.$t('form.find') ;
                 var txtHideFind = this.$t('form.hide_find') ;
                 var flag = ev.target.textContent == txtFind?true:false;
                 console.log("flag:"+flag);
                ev.target.textContent = flag ?txtHideFind:txtFind;

                 //TODO
                var list = ev.target.parentElement.childNodes;
                for (let index = 0; index < list.length; index++) {
                    const element = list[index];

                    if (element.classList.contains('txtsearch')) {
                        element.value = "";
                         if (flag) {
                             element.classList.add('show');
                            element.classList.remove('hideElement');

                         }else{
                            element.classList.add('hideElement');
                            element.classList.remove('show');
                         }

                       // element.style.top = ev.target.style.top * -1;

                    }else if(element.classList.contains('form-select')){
                         if (flag) {
                              element.classList.remove('show');
                             element.classList.add('hideElement');
                         }else{
                              element.classList.remove('hideElement');
                              element.classList.add('show');
                         }

                    }else if(element.classList.contains('search')){
                         element.replaceChildren();
                        //  if (flag) {
                        //      element.classList.add('show');
                        //     element.classList.remove('hideElement');

                        //  }else{
                        //     element.classList.add('hideElement');
                        //     element.classList.remove('show');
                        //  }

                    }
                }

            },
            fillListUser:function(ev){
                 //Thẻ ul

                    ev.target.parentElement.childNodes.forEach(element => {
                        if (element.classList.contains('search')) { //  thẻ ul
                           var list = [];

                              element.replaceChildren();
                              list = this.getlistUser(ev.target.value);
                              list.forEach(user => {
                                   var li1 = document.createElement("li");
                                    li1.className = "list-group-item";
                                    li1.setAttribute('data-id',user.id) ;
                                    li1.title = "MSNV: "+ user.username;
                                    li1.appendChild(document.createTextNode(user.username+"-"+ user.name));
                                    li1.addEventListener('click',this.selectUser);
                                    element.appendChild(li1);
                              });

                        }

                    });


            },
           showSigner:function(ev){

               var chuky = ev.target.parentElement.parentElement.parentElement.parentElement;
               if (chuky.offsetHeight == '80') {
                   chuky.style.height = '54px';
               }else  {
                    chuky.style.height = '80px';
               }

               console.log(ev.target.parentElement.parentElement.parentElement.parentElement);
           },
           boxsignEdit: function(ev) {
                if ( ev.target.classList.contains('boxsign')) {
                        ev.target.classList.toggle('edit');
                        var list = ev.target.childNodes;
                        for (let index = 0; index < list.length; index++) {
                            const element = list[index];
                            // console.log("hung test");
                            if (element.classList.contains('popup')) {
                               // console.log( ev.target.style);
                              element.style.top = ev.target.style.top * -1;

                            }

                        }

                    }

            },
            showHtml(singinfo){
               var holder = document.getElementById('holder');

               // console.log("them html");
              // console.log(holder.childNodes);
                 var chuky = document.createElement('div');
                 chuky.className = 'draggable approved boxsign';
                 chuky.style = 'text-align: center;';
                 chuky.addEventListener('click',this.boxsignEdit) ;

                 var card = document.createElement('div');
                 card.className = "card popup shadow  bg-body rounded";
                 card.style = 'style="width: 18rem;"';
                 var card_body =  document.createElement('div');
                 card_body.className = 'card-body';
                 var select =  document.createElement('select');
                 select.className = 'form-select form-control';
                 this.users.forEach(user => {
                      select.innerHTML +=  '<option value="'+user.id+'" '+ (singinfo.sign==user.id?'selected':'') +'>'+ user.username  +'-' +user.name+'</option>';
                 });

                 card_body.appendChild(select);

                  //tìm kiếm
                  var input_find = document.createElement('input');
                  input_find.className = 'form-control form-control-sm txtsearch hideElement';
                  input_find.style ="padding:5px";
                   input_find.addEventListener('keyup',this.fillListUser);
                   input_find.addEventListener('focus',this.showListSearchUser);
                   card_body.appendChild(input_find);
                   //Thẻ ul
                    var ul_find = document.createElement('ul');
                    ul_find.className = 'list-group search dropdown';
                    ul_find.style = "text-align:left";

                card_body.appendChild(ul_find);

                 var form_check = document.createElement('div');

                 var chkSigner = document.createElement('input');
                     chkSigner.type="checkbox";
                     chkSigner.className = 'form-check-input check_show_sign';

                     chkSigner.addEventListener('change',this.showSigner);
                     chkSigner.checked =  singinfo.show_sign ;
                    // console.log("singinfo.show_sign"+singinfo.show_sign);
                 var chkSigner_label = document.createElement('label');
                     chkSigner_label.className = "form-check-label ";

                     chkSigner_label.innerHTML =  this.$t('form.display_signer_name') ;

                  form_check.appendChild(chkSigner);
                  form_check.appendChild(chkSigner_label);
                  card_body.appendChild(form_check);
                 var button_del =  document.createElement('button');
                 button_del.className = 'card-link btn btn-light mt-2';
                 button_del.addEventListener('click',this.removeBoxsign) ;
                 button_del.innerHTML = 'Delete';
                 card_body.appendChild(button_del);
                  //Nút tìm
                  var btn_search = document.createElement('button');
                    btn_search.className = "card-link btn btn-light mt-2";
                    // btn_search.style = "widh:80px";
                    btn_search.textContent = this.$t('form.find') ;
                    btn_search.addEventListener('click',this.showSearch);
                    card_body.appendChild(btn_search);

                 card.appendChild(card_body);
                 chuky.appendChild(card);

              var child = holder.childNodes[this.currentPage-1];
              if(child){
                    var rect =  child.getBoundingClientRect();
                    chuky.style.top = singinfo.y +"px";
                    chuky.style.left =  singinfo.x +"px";

                     if (singinfo.show_sign) {
                        chuky.style.height = '80px';
                     }else{
                         chuky.style.height = '54px';
                     }
                      holder.appendChild(chuky);
              }
           },
           insertHtml(ev){

               var holder = document.getElementById('holder');
                //console.log("them html");
                //console.log(holder.childNodes);
                 var chuky = document.createElement('div');
                 chuky.className = 'draggable approved boxsign';
                 chuky.style = 'text-align: center;';
                 chuky.addEventListener('click',this.boxsignEdit) ;
                 var card = document.createElement('div');
                 card.className = "card popup shadow  bg-body rounded";
                 card.style = 'style="width: 18rem;"';
                 var card_body =  document.createElement('div');
                 card_body.className = 'card-body';
                 var select =  document.createElement('select');
                 select.className = 'form-select form-control';

                  this.users.forEach(user => {
                          select.innerHTML += '<option value="'+user.id+'" >'+ user.username  +'-' +user.name+'</option>'
                     });

                  card_body.appendChild(select);
                  //tìm kiếm
                  var input_find = document.createElement('input');
                  input_find.className = 'form-control form-control-sm txtsearch hideElement';
                  input_find.style ="padding:5px";
                   input_find.addEventListener('keyup',this.fillListUser);
                   input_find.addEventListener('focus',this.showListSearchUser);
                   card_body.appendChild(input_find);
                   //Thẻ ul
                    var ul_find = document.createElement('ul');
                    ul_find.className = 'list-group search dropdown';
                    ul_find.style = "text-align:left";

                    card_body.appendChild(ul_find);

                 var form_check = document.createElement('div');

                 var chkSigner = document.createElement('input');
                     chkSigner.type="checkbox";
                     chkSigner.className = 'form-check-input check_show_sign';
                     chkSigner.checked = '1';
                     chkSigner.addEventListener('change',this.showSigner);
                 var chkSigner_label = document.createElement('label');
                     chkSigner_label.className = "form-check-label ";

                     chkSigner_label.innerHTML =   this.$t('form.display_signer_name') ;

                  form_check.appendChild(chkSigner);
                  form_check.appendChild(chkSigner_label);
                  card_body.appendChild(form_check);
                 var button_del =  document.createElement('button');
                 button_del.className = 'card-link btn btn-light mt-2';
                 button_del.addEventListener('click',this.removeBoxsign) ;
                 button_del.innerHTML = 'Delete';
                 card_body.appendChild(button_del);
                 //Nút tìm
                  var btn_search = document.createElement('button');
                    btn_search.className = "card-link btn btn-light mt-2";
                    // btn_search.style = "widh:80px";
                    btn_search.textContent = this.$t('form.find') ;
                    btn_search.addEventListener('click',this.showSearch);
                    card_body.appendChild(btn_search);
                //add body
                 card.appendChild(card_body);
                 chuky.appendChild(card);

              var child = holder.childNodes[this.currentPage-1];
              if(child){
                    var rect =  child.getBoundingClientRect();
                    chuky.style.top = (this.scrollTop + 250 ) +"px";//
                    chuky.style.left = (rect.width /2   )+"px";
                    holder.appendChild(chuky);
              }
           },
           drop:function(ev) {
             var data = ev.dataTransfer.getData("Text");
            // //ev.target.appendChild(document.getElementById(data));
             var  canvas =   ev.target;
                // console.log(canvas);
             var chuky = document.createElement('div');
                 chuky.className = 'draggable';
                 chuky.innerHTML = "Chữ ký";
                // chuky.draggable = "true";

              var rect =  canvas.parentElement.getBoundingClientRect();
                 chuky.style.top = (ev.clientY - rect.top   ) +"px";
                 chuky.style.left = (ev.clientX  - rect.left   )+"px";

                 canvas.parentElement.appendChild(chuky);
                 //console.log(canvas.parentElement);
                // var mp = getPositionMouse(canvas.parentElement,ev);
                // var dpi = 120 ;
                // var msg = " - Chữ ký mm:  " + Math.floor((mp.x * 25.4) / dpi ) + ' X ' + Math.floor((mp.y * 25.4) / dpi );
                // var demo1 = document.getElementById('demo1');
                // demo1.innerHTML = msg;
                ev.preventDefault();
            },
           dragStart:function(ev){

                ev.dataTransfer.setData("Text",ev.target.id);
                //ev.dataTransfer.setData("Text", ev.target.id);
               // console.log( ev.clientX);
                // var control = document.getElementById(ev.target.id);
                // control.style.top = ev.clientY - control.height;
                // control.style.left = ev.clientX - control.width;

            //     var demo1 = document.getElementById('demo1');
            //     demo1.innerHTML = "";
            // //  ev.dataTransfer.dropEffect = 'move';

            },
             dragEnd:function(ev) {
                // moveAt(ev,ev.pageX, ev.pageY);
            },
           dragFinish:function( ev) {
                //   this.moveItem(this.dragging, to,step);
                // get the draggable element
                // const id = ev.dataTransfer.getData('text/plain');
                // console.log(id);
                // const draggable = document.getElementById(id);
                // alert("OK");
            },
             dragEnter:function(ev) {
                return true;
            },
             dragLeave:function(ev) {
                },
             dragOver:function(ev) {
                    return false;
                },
          resetInfo(){
            this.currentPage = 1;

          },
          displayPdf:function(data){


                this.resetInfo();
                var   pdfjsLib = window['pdfjs-dist/build/pdf'];
                //   var canvas =  this.$refs.pdfViewer;
                var   canvasContainer = document.getElementById('holder');
                canvasContainer.innerHTML = "";
                canvasContainer.style.display = "none";
                var thePdf = null;
                var loadingTask = pdfjsLib.getDocument({data: data});
                    loadingTask.promise.then(function(pdf){
                         thePdf = pdf;
                        // console.log("PDF loaded");

                       document.getElementById("totalPage").innerHTML = pdf.numPages;
                        var pageNum = pdf.numPages;

                        for(let index = 1; index <= pdf.numPages; index++) {

                                var wrapper = document.createElement("div");
                                wrapper.className = "canvas-wrapper";
                                var space = document.createElement("div");
                                space.className = "canvas-space";
                                var canvas = document.createElement("canvas");
                                wrapper.appendChild(canvas)
                                wrapper.appendChild(space);
                                canvasContainer.appendChild(wrapper);
                                renderPage(index, canvas);
                            }

                       function  renderPage (pageNumber, canvas) {

                           thePdf.getPage(pageNumber).then(function(page){
                                var scale = 1;
                                var viewport = page.getViewport({scale:scale});
                                var context = canvas.getContext('2d');
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;
                                if (pageNumber == 1) {
                                     document.getElementById("canvas_height").innerHTML = canvas.height;
                                }

                               //  console.log("canvas"+ canvas.width);
                               // console.log(canvas.parentElement.parentElement.style.width='550px');
                                canvas.parentElement.parentElement.style.width = canvas.width+ "px";
                                var renderContext={
                                    canvasContext : context,
                                    viewport: viewport
                                }
                                //  wrapper.appendChild(canvas)
                                // canvasContainer.appendChild(wrapper);
                                var renderTask = page.render(renderContext);
                                renderTask.promise.then(function () {
                                        console.log('Page rendered' );
                                    });
                                if (thePdf.numPages === pageNumber) {
                                        canvasContainer.style.display = "block";
                                        document.getElementById("showChuky").click() ;
                                        $('#loading').hide();
                                }

                            });
                        }
                    },

                    );
          },
          showChuKy:function(){

                if(this.fileInfo.signs && this.fileInfo.signs.length > 0){
                    for (let index = 0; index < this.fileInfo.signs.length; index++) {
                        this.showHtml(this.fileInfo.signs[index]);
                    }
                }
          },

          onPdf(event){
            // this.loading = true;
            this.resetInfo();
            var   pdfjsLib = window['pdfjs-dist/build/pdf'];
           // console.log(pdfjsLib);
            var file = event.target.files[0];
            //   var canvas =  this.$refs.pdfViewer;
            var   canvasContainer = document.getElementById('holder');
            canvasContainer.innerHTML = '';
            var thePdf = null;
            // vm = this;
            if(file.type == 'application/pdf'){
                  $('#loading').show();
                var fileReader = new FileReader();
                fileReader.onload = () => {
                 const docs = {
                      name: file.name,
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                    //   base64: fileReader.result,
                      arrayData: fileReader.result
                  };
                  var pdfData = new Uint8Array(fileReader.result);
                  this.displayPdf(pdfData);

                }
                //fileReader.readAsDataURL(file);
                fileReader.readAsArrayBuffer(file);
            }else{
                 $('#loading').hide();
                 this.resetInfo();
            }
        }
    }
}
</script>
 <style   lang="scss">
        .holder{
            /* background: red; */
            padding: 0 ;
            /* position: relative; */
            position: relative;
            margin: 0 auto;
            display: block;
            }
            .canvas-wrapper{
            /* margin-bottom: 16px; */
             height: auto;
             position: relative;
             margin: 0 auto;
             display: block;
            }
            canvas{
                margin: 0 auto;
                display: block;
            }
            .canvas-space{
                display: block;
                height: 5px;
                background-color: rgb(238, 238, 238);
            }
            /* .container {
            position: relative;
            text-align: center;
            color: white;
            } */

            /* Bottom left text */
            .bottom-left {
                position: absolute;
                bottom: 8px;
                left: 16px;
            }
            /* Top left text */
            .top-left {
            position: absolute;
            top: 8px;
            left: 16px;
            }

            /* Top right text */
            .top-right {
                position: absolute;
                top: 8px;
                right: 16px;
            }
            /* Bottom right text */
            .bottom-right {
                position: absolute;
                bottom: 8px;
                right: 16px;
            }
            /* Centered text */
            .panelItemSign {
                transition: box-shadow .3s;
                /* position: absolute; */
                width: 100%;
                min-height: 50px;
                padding-top: 10px !important;
                background-color: rgb(250, 250, 250);
                cursor: pointer;
                padding:0px 12px;
                text-align: center;
                border-radius:2px;
                border: 1px solid rgb(196, 184, 184);
                justify-content: space-around;
            }
             .panelItemSign:hover{
                 box-shadow: 0 0 11px rgba(33,33,33,.2);
            }
            .draggable {
                position: absolute;
                width: 100px;
                height: 40px;
                background: gray;
                z-index: 1000;
            }
         .approved{
            display: block;
            border:1px solid red;
            width:170px;
            height:80px;
            margin:auto;
            cursor:move;
            background:url('/img/check.png') no-repeat center center;
        }
        .boxsign{

             top:230px;
             left:230px;
             background-color: white;
             border: 1px solid gray;
         }
         .boxsign .edit{

             width: 150px;
             height: 50px;
             top:230px;
             left:230px;
             background-color: white;
             border: 1px solid gray;
         }
         .boxsign:hover{
             border: 1px solid blue;
         }
         .popup {
            position: relative;
            top:-170px;
            width:250px;
            display: none;
         }
          .edit .popup  {
            position: relative;
            top:-170px;
            display: block;
         }
         .txtsearch{
             padding: 0;
         }
         .search{
                position: relative;
                line-height: 1em;
                max-height: 150px;
                border: 1px solid #ccc;
                padding: 0;
                margin: 0;
                overflow: scroll;
                overflow-x: hidden;
                display: none;
                cursor:pointer;
         }
        .show{

             display: block;
         }
         .hideElement{

             display: none;
         }
         .check_show_sign{
             cursor: pointer;
         }

    </style>
