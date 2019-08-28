import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/pages/Home';
import Tweet from "@/pages/NewTweet";

Vue.use(VueRouter);

const routes = [
  {
    path: '/home',
    name: 'home',
    component: Home,
  },
  {
    path: '/users/:account_id/tweets/tweet',
    name: "newTweet",
    component: Tweet,
  }
];

export default new VueRouter({
  mode: 'history',
  routes,
});