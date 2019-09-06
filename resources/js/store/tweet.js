import { http, async } from '@/services/http';
import router from '@/router';

const state = {
  home: {},
  profile: {}
};

const getters = {
  home: state => state.home,
  profile: state => state.profile,
};

const mutations = {
  setTweet(state, { page, tweets }) {
    Object.keys(state).map((key) => {
      if (key === page) {
        state[page] = tweets;
      }
    });
  },
  addTweet(state, { page, tweets }) {
    state[page].unshift(JSON.parse(tweets));
  }
};

const actions = {
  async postTweet(context, { url, data, pageName, clearMessage, isRedirect }) {
    const successCB = async response => {
      if (isRedirect) {
        await router.push({ name: pageName });
      } else {
        context.commit('addTweet', { page: pageName, tweets: response.data });
        clearMessage();
      }
    };

    const errorCB = error => console.log(error);
    await async.post(url, data, successCB, errorCB);
  },

  getTweets(context, { url, page }) {
    const successCB = (response) => context.commit('setTweet', { page: page, tweets: JSON.parse(response.data) });
    const errorCB = (error) => console.log(error);

    http.get(url, successCB, errorCB);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};