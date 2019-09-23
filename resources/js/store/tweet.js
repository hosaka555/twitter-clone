import { http, async } from '@/services/http';
import router from '@/router';

const state = {
  home: {},
  profile: {},
};

const getters = {
  home: state => state.home,
  profile: state => state.profile,
};

const mutations = {
  setTweet(state, { page, tweets }) {
    Object.keys(state).map((key) => {
      if (key === page) {
        tweets.map(tweet => {
          tweet.isLikingProcessing = false;
        });

        state[page] = tweets;
      }
    });
  },
  addTweet(state, { page, tweets }) {
    if (!["home", "profile"].includes(page)) {
      page = "home";
    }

    state[page].unshift(JSON.parse(tweets));
  },
  resetTweets(state, { page }) {
    state[page] = [];
  },
  like(state, { page, id }) {
    state[page].filter(tweet => {
      if (tweet.id === id) {
        tweet.is_liked = true;
        tweet.likes_count += 1;
        return;
      }
    });
  },
  unlike(state, { page, id }) {
    state[page].filter(tweet => {
      if (tweet.id === id) {
        tweet.is_liked = false;
        tweet.likes_count -= 1;
        return;
      }
    });
  },
  startProcess(state, { page, index: index }) {
    state[page][index].isLikingProcessing = true;
  },
  doneProcess(state, { page, index: index }) {
    state[page][index].isLikingProcessing = false;
  }
};

const actions = {
  async postTweet(context, { url, data, clearState, isRedirect }) {
    const page = getPage(context.rootState.user.page);
    const successCB = async response => {
      if (isRedirect) {
        await router.push({ name: page });
      } else {
        context.commit('addTweet', { page: page, tweets: response.data });
        clearState();
      }
    };

    const errorCB = error => console.log(error);
    await async.post(url, data, successCB, errorCB);
  },
  fetchTweets(context, { url }) {
    const page = getPage(context.rootState.user.page);
    const successCB = (response) => context.commit('setTweet', { page: page, tweets: JSON.parse(response.data) });
    const errorCB = (error) => console.log(error);

    http.get(url, successCB, errorCB);
  },
  clearTweets(context) {
    const page = getPage(context.rootState.user.page);
    context.commit('resetTweets', { page: page });
  },
  likeTweet(context, { id }) {
    const page = context.rootGetters['user/prevPage'] || getPage(context.rootState.user.page);
    context.commit('like', { page: page, id: id });
  },
  unlikeTweet(context, { id }) {
    const page = context.rootGetters['user/prevPage'] || getPage(context.rootState.user.page);
    context.commit('unlike', { page: page, id: id });
  },
  startLikingProcess(context, { index }) {
    const page = context.rootGetters['user/prevPage'] || getPage(context.rootState.user.page);
    context.commit('startProcess', { page: page, index: index });
  },
  doneLikingProcess(context, { index }) {
    const page = context.rootGetters['user/prevPage'] || getPage(context.rootState.user.page);
    context.commit('doneProcess', { page: page, index: index });
  },
};

const getPage = (current_page) => {
  let page = current_page;
  if (!["home", "profile"].includes(page)) {
    page = "home";
  }
  return page;
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};