const state = {
  username: "",
  introduction: "",
  header_icon: "",
  profile_icon: ""
};

const getters = {
  all: state => state,
  username: state => state.username,
  introduction: state => state.introduction,
  header_icon: state => state.header_icon,
  profile_icon: state => state.profile_icon,

};

const mutations = {
  setProfile(state, { profile }) {
    state.username = profile.username;
    state.introduction = profile.introduction;
    state.header_icon = profile.header_icon;
    state.profile_icon = profile.profile_icon;
  }
};

const actions = {
  setProfile(context, { profile: profile }) {
    context.commit("setProfile", { profile: profile });
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};