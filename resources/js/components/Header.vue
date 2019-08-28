<template>
  <header class="header-container">
    <nav class="header-menu">
      <ul>
        <li class="header-menu__item">
          <a href class="header-menu__link">ユーザー一覧</a>
        </li>
        <li class="header-menu__item">
          <a href class="header-menu__link">プロフィール</a>
        </li>
        <li class="header-menu__item">
          <router-link
            class="header-menu__link"
            :to="{ name: 'newTweet', params: { account_id: 'user@example.com'} }"
          >ツイートの作成</router-link>
        </li>

        <li class="header-menu__item">
          <router-link class="header-menu__link" to="/home">HOME</router-link>
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
