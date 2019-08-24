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
          <a href class="header-menu__link">ツイートの作成</a>
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
  data() {
    return {
      token: ""
    };
  },
  mounted() {
    this.setToken();
  },
  methods: {
    async setToken() {
      this.token = document
        .getElementById("token")
        .textContent.replace(/^\s*/, ""); // index.blade.phpからjwt-tokenを取得
      console.log(this.token);

      await axios
        .get("/api/me", {
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        })
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.log(error)
          this.logout(); // authに失敗した場合はログアウトを実行する。
        });
    },
    async logout() {
      await axios
        .post("/logout")
        .then(response => {
          console.log("LOGOUT");
          window.location.reload();
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
