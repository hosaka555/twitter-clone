import { async } from '@/services/http';
import router from '@/router';

const state = {};

const getters = {};

const mutations = {};

const actions = {
  async postTweet(context, { url, data, redirectToName }) {
    const successCB = async response => await router.push({ name: redirectToName });
    const errorCB = error => console.log(error);
    await async.post(url, data, successCB, errorCB);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};;