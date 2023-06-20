<template>
     <!-- <ol class="breadcrumb float-sm" >
        <li  ><i class="fas fa-walking"  title="Các bước qui trình"></i>&nbsp;&nbsp;</li>
        <li :class="getClassActive(phase)" v-for="(phase,index) in wlphases" v-bind:key="index"><span  > {{phase.name}}</span></li>
        
    </ol>   -->
    <div >
           <ul  id="breadcrumbs-one" class="mb-1" >
              <li v-for="(phase,index) in wlphases" v-bind:key="index">
                <a :class="getClassActive(phase)" >{{phase.name}} </a>
              </li>  
                 
           </ul>
    </div>

</template>

<script>
export default {
    props:{
        document:Object,
        wlphases:Array
    },
    methods:{
        getClassActive(phase){
             var re = "breadcrumb-item";
             if(phase.finished_date || ( phase.index == 999 && this.document.currentPhase.id == phase.id) ){
                 re = re + " pass";
             }else if(phase.id == this.document.currentPhase.id)
             {
                re = re + " current";
             }
            return  re;
        }
    }
}
</script>

<style lang="scss" scoped>
    // .active{
    //     color: #17a2b8;
    // }
    //  .pass{
    //     color:#28a745;
    // }
 
    ul {
    margin: 0;
    padding: 0;
    list-style: none;
}
#breadcrumbs-one {
   background: #ddd;
//   border-width: 1px;
//   border-style: solid;
//   border-color: #f5f5f5 #e5e5e5 #ccc;
  // min-height:34px;
  border-radius: 5px;
  box-shadow: 0 0 2px rgba(0,0,0,.2);
  overflow: hidden;
  width: 100%;
  font-size: 12px;
  
  li {
    float: left;

    &:first-child {
      a {
        padding-left: 1em;
        border-radius: 5px 0 0 5px;
      }
 
    }
  }
  
  a {
    padding: .7em 1em .7em 2em;
    float: left;
    text-decoration: none;
    color: #444;
    position: relative;
    // text-shadow: 0 1px 0 rgba(255,255,255,.5);
    background-color: #ddd;
    // background-image: linear-gradient(to right, #f5f5f5, #ddd);

    &:after,
    &:before {
      content: "";
      position: absolute;
      top: 50%;
      margin-top: -1.5em;
      border-top: 1.5em solid transparent;
      border-bottom: 1.5em solid transparent;
      border-left: 1em solid;
      right: -1em;
    }

    &:after {
      z-index: 2;
      border-left-color: #ddd;
    }

    &:before {
      border-left-color: white;// #ccc;
      right: -1.1em;
      z-index: 1;
    }

    &:hover {
      background: #fff;

      &:after {
        border-left-color: #fff;
      }
    }
  }

  .current {
    font-weight: bold;
    background: #3c8dbc;
    color: #fff;
    &:after,
    &:before {
      content: "";
       
    }
     &:after {
      z-index: 2;
      border-left-color: #3c8dbc;
    }
    // &:hover {
    //   font-weight: bold;
    //   background: none;
    // }
    &:hover {
      background: #6092af;

      &:after {
        border-left-color: #6092af;
      }
    }
  }
  .pass {
    font-weight: bold;
    background: #28a745;
    color: #fff;
    &:after,
    &:before {
      content: "";
       
    }
     &:after {
      z-index: 2;
      border-left-color: #28a745;
    }
 
    &:hover {
      background: #68a777;

      &:after {
        border-left-color: #68a777;
      }
    }
  }
  
}
</style>