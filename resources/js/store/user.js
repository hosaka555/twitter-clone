import { async } from '@/services/http';

const initialState = {
  token: null,
  user: null
};

const state = () => Object.assign({}, initialState)

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
    const successCB = (response) => context.commit('setUser', response.data);
    const errorCB = (error) => this.dispatch('user/logout');

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