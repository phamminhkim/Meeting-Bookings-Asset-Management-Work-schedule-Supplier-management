import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from './components/Home.vue'
import About from './components/About.vue'


Vue.use(VueRouter)

const routes = [
  {
    path: '/demo/home',
    name: 'home',
    component: Home
  },
  {
    path: '/demo/about',
    name: 'about',
    component: About
  }
]

const router = new VueRouter({
  mode: 'history',
  routes
})

export default router