import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/pages/Home';
import Tweet from "@/pages/NewTweet";
import Profile from '@/pages/Profile';
import EditProfile from '@/pages/EditProfile';
import DetailTweet from "@/pages/DetailTweet";
import Users from "@/pages/Users";
import NotFoundError from "@/pages/errors/NotFoundError";
import SystemError from "@/pages/errors/SystemError";

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
  },
  {
    path: `/users/:account_id/tweets/:tweet_id`,
    name: "detail-tweet",
    component: DetailTweet,
  },
  {
    path: `/users`,
    name: "users",
    component: Users,
  },
  {
    path: '/404',
    component: NotFoundError,
  },
  {
    path: '/500',
    component: SystemError,
  },
  {
    path: '*',
    component: NotFoundError,
  },
];

export default new VueRouter({
  mode: 'history',
  routes,
});