import Vue from 'vue';
import Vuex from 'vuex';

import user from './user';
import tweet from './tweet';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    user,
    tweet,
  }
});

export default store;
