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
    postTest1() {
      console.log(this.token);

      axios
        .post("/api/test1", "", {
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        })
        .then(response => {
          console.log("Success");
          console.log(response);
        })
        .catch(error => {
          this.logout();
        });
    },
    async setToken() {
      this.token = document
        .getElementById("token")
        .textContent.replace(/^\s*/, "");

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
          this.logout();
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
