import Vue from 'vue';
import Vuex from 'vuex';

import user from './user';
import tweet from './tweet';
import profile from './profile';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    user,
    tweet,
    profile
  },
  plugins: [
    store => {
      store.subscribe((mutation, state) => {
        if (mutation.type === "profile/setProfile") {
          window.localStorage.setItem("header_icon", state.profile.header_icon);
          window.localStorage.setItem("introduction", state.profile.introduction);

        }
      });
    }
  ]
});

export default store;
