import { async } from '@/services/http';

const initialState = {
  token: null,
  user: null
};

const state = () => Object.assign({}, initialState);

const getters = {
  token: state => state.token,
  me: state => state.user
};

const mutations = {
  setToken(state, token) {
    state.token = token;
  },
  setUser(state, user) {
    state.user = user;
  },
  clearVuex(state) {
    for (let key in state) {
      if (initialState.hasOwnProperty(key)) {
        state[key] = initialState[key];
      }
    }
  }
};

const actions = {
  async setToken(context, { token }) {
    context.commit('setToken', token);
    console.log("Finitsh set token.\n" + token);
  },
  async me(context) {
    const successCB = (response) => {
      let user = { account_id: '', email: '', };
      let profile = { username: '', profile_icon: '', introduction: '', header_icon: '' };

      const headerIconStorage = window.localStorage.getItem('header_icon') || "";
      if (headerIconStorage) {
        profile.header_icon = headerIconStorage;
        profile.introduction = window.localStorage.getItem('introduction');
      }

      Object.keys(response.data).map((key) => {
        if (['username', 'profile_icon'].includes(key)) {
          profile[key] = response.data[key];
        } else {
          user[key] = response.data[key];
        }
      });

      context.commit('setUser', user);
      context.commit('profile/setProfile', { profile: profile }, { root: true });
    };
    const errorCB = () => this.dispatch('user/logout');

    await async.get('/api/me', successCB, errorCB);
  },
  async logout(context) {
    const successCB = response => {
      console.log("Logout.\n Redirect to Login Page.");
    };
    const errorCB = error => {
      console.log(error);
    };
    const afterCB = () => {
      console.log("Vuex CLEAR");
      context.commit('clearVuex');
      localStorage.removeItem('header_icon');
      localStorage.removeItem('introduction');
      window.location.href = "/login";
    };

    await async.post('/logout', '', successCB, errorCB, afterCB);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};