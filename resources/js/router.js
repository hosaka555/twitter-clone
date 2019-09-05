import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/pages/Home';
import Tweet from "@/pages/NewTweet";
import Profile from '@/pages/Profile';
import EditProfile from '@/pages/EditProfile';
Vue.use(VueRouter);

const routes = [
  {
    path: '/home',
    name: 'home',
    component: Home,
  },
  {
    path: '/users/:account_id',
    name: "profile",
    component: Profile,
  },
  {
    path: '/users/:account_id/tweets/tweet',
    name: "newTweet",
    component: Tweet,
  },
  {
    path: '/users/:account_id/edit',
    name: "edit-profile",
    component: EditProfile,
  }
];

export default new VueRouter({
  mode: 'history',
  routes,
});