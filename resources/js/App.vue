<template>
  <div>
    <div v-if="isReady">
      <Header />
      <main>
        <div class="container">
          <RouterView />
        </div>
      </main>
    </div>
    <div v-else style="    text-align: center;">
      <!-- Loading -->
      <img src="img/loading.gif">
    </div>
  </div>
</template>

<script>
import Header from "@/components/Header";

export default {
  components: {
    Header
  },
  computed: {
    isReady(){
      return !!this.$store.getters["user/me"];
    }
  },
  mounted() {
    this.setToken();
  },
  methods: {
    async setToken() {
      const token = document
        .getElementById("token")
        .textContent.replace(/^\s*/, ""); // index.blade.phpからjwt-tokenを取得

      await this.$store.dispatch("user/setToken", { token: token });
      await this.$store.dispatch("user/me");
    }
  }
};
</script>