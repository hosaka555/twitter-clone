<template>
  <header class="header-container">
    <nav class="header-menu">
      <ul>
        <li class="header-menu__item">
          <router-link class="header-menu__link" to="/home">HOME</router-link>
        </li>

        <li class="header-menu__item">
          <router-link class="header-menu__link" :to="{ name: 'users' }">ユーザー一覧</router-link>
        </li>
        <li class="header-menu__item">
          <router-link
            class="header-menu__link"
            :to="{ name: 'profile', params: { account_id: this.$store.getters['user/me'].account_id} }"
          >プロフィール</router-link>
        </li>
        <li class="header-menu__item">
          <router-link
            class="header-menu__link"
            :to="{ name: 'newTweet', params: { account_id: this.$store.getters['user/me'].account_id} }"
          >ツイートの作成</router-link>
        </li>

        <li class="header-menu__item">
          <div class="header-menu__link header-menu__link-logout">
            <button @click.prevent="logout()">ログアウト</button>
          </div>
        </li>
      </ul>
    </nav>
  </header>
</template>

<script>
export default {
  methods: {
    async logout() {
      await this.$store.dispatch("user/logout");
    }
  }
};
</script>
