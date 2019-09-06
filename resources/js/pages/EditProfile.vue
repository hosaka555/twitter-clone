<template>
  <div class="editProfile-container">
    <BackButton />
    <div class="editProfile-headerIconBox">
      <div v-if="previewHeaderIcon">
        <img :src="previewHeaderIcon" class="editProfile__image" />
      </div>
      <div v-else>
        <div v-if="profile['header_icon']">
          <img :src="profile['header_icon']" class="editProfile__image" />
        </div>
        <div v-else>
          <div class="editProfile__image"></div>
        </div>
      </div>

      <label for="header_icon">
        <div class="editProfile__button--select">
          <font-awesome-icon
            icon="plus-circle"
            class="editProfile__icon--headerIcon editProfile__icon--plus"
          />
        </div>

        <input
          type="file"
          id="header_icon"
          @change="onHeaderIconChange"
          accept=".jpg, .png, image/jpeg, image/png"
        />
      </label>

      <div class="editProfile__button--delete" @click="deleteHeaderIcon">
        <font-awesome-icon
          icon="times-circle"
          class="editProfile__icon--headerIcon editProfile__icon--delete"
        />
      </div>
    </div>
    <div class="editProfile-profileIconBox">
      <div v-if="previewProfileIcon">
        <ProfileIcon :user-icon="previewProfileIcon" class="editProfile__image--profileIcon" />
      </div>
      <div v-else>
        <div v-if="profile['profile_icon']">
          <ProfileIcon
            :user-icon="profile['profile_icon']"
            class="editProfile__image--profileIcon"
          />
        </div>
        <div v-else>
          <div class="editProfile__image--profileIcon"></div>
        </div>
      </div>

      <label for="profile_icon">
        <div class="editProfile__button--select">
          <font-awesome-icon
            icon="plus-circle"
            class="editProfile__icon--profileIcon editProfile__icon--plus"
          />
        </div>

        <input
          type="file"
          id="profile_icon"
          @change="onProfileIconChange"
          accept=".jpg, .png, image/jpeg, image/png"
        />
      </label>
      <div class="editProfile__button--delete" @click="deleteProfileIcon">
        <font-awesome-icon
          icon="times-circle"
          class="editProfile__icon--profileIcon editProfile__icon--delete"
        />
      </div>
    </div>

    <div class="editProfile-inputArea">
      <label for="username">
        ユーザーネーム
        <input
          type="text"
          v-model="profile['username']"
          class="editProfile__input"
          id="username"
        />
      </label>
    </div>

    <div class="editProfile-inputArea">
      <label for="introduction">
        自己紹介
        <textarea
          v-model="profile['introduction']"
          class="editProfile__input editProfile__input--introduction"
          id="introduction"
        />
      </label>
    </div>
    <button
      @click="updateProfile"
      :disabled="isProcessing ||profile.username.length === 0"
      class="editProfile__button"
    >更新する</button>
  </div>
</template>

<script>
import ProfileIcon from "@/components/ProfileIcon";
import BackButton from "@/components/BackButton";
export default {
  data() {
    return {
      profile: {
        username: "",
        introduction: "",
        header_icon: "",
        profile_icon: ""
      },
      uploadedHeaderIcon: "",
      previewHeaderIcon: "",
      uploadedProfileIcon: "",
      previewProfileIcon: "",
      isProcessing: false,
      changeHeaderIcon: 0,
      changeProfileIcon: 0,
      account_id: '',
    };
  },
  components: {
    ProfileIcon,
    BackButton
  },
  mounted() {
    this.getProfile();
  },
  created() {
    this.getAccountId();
    this.checkCurrentUser();
  },
  methods: {
    checkCurrentUser() {
      if (this.$store.getters["user/me"].account_id !== this.account_id) {
        this.$router.push({
          name: "profile",
          params: { account_id: this.$store.getters["user/me"].account_id }
        });
      }
    },
    getAccountId() {
      let pattern = /users\/(.+)\/edit/;
      let targetUrl = decodeURI(window.location.pathname);
      let result = targetUrl.match(pattern);
      this.account_id = result[1];
    },
    getProfile() {
      this.profile = this.$store.getters["profile/all"];
    },
    onHeaderIconChange(e) {
      let file = e.target.files[0];
      this.createImage(file, "previewHeaderIcon");
      this.uploadedHeaderIcon = file;
      this.changeHeaderIcon = 1;
    },
    onProfileIconChange(e) {
      let file = e.target.files[0];
      this.createImage(file, "previewProfileIcon");
      this.uploadedProfileIcon = file;
      this.changeProfileIcon = 1;
    },
    createImage(file, key) {
      let reader = new FileReader(file);
      reader.onload = e => {
        this[key] = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    deleteHeaderIcon() {
      this.changeHeaderIcon = 1;
      this.profile.header_icon = "";
      this.uploadedHeaderIcon = "";
      this.previewHeaderIcon = "";
    },
    deleteProfileIcon() {
      this.changeProfileIcon = 1;
      this.profile.profile_icon = "";
      this.uploadedProfileIcon = "";
      this.previewProfileIcon = "";
    },
    async updateProfile() {
      if (this.profile.username && !this.isProcessing) {
        this.isProcessing = true;

        const url = `/api/users/${this.$store.getters["user/me"].account_id}/edit`;

        let formData = new FormData();

        formData.append("username", this.profile.username);
        formData.append("introduction", this.profile.introduction);
        formData.append("header_icon", this.uploadedHeaderIcon);
        formData.append("profile_icon", this.uploadedProfileIcon);
        formData.append("changeHeaderIcon", this.changeHeaderIcon);
        formData.append("changeProfileIcon", this.changeProfileIcon);

        var config = {
          headers: {
            "content-type": "multipart/form-data"
          }
        };

        await axios
          .post(url, formData, config)
          .then(response => {
            this.$router.push({
              name: "profile",
              params: { account_id: this.$store.getters["user/me"].account_id }
            });
          })
          .catch(error => {
            console.log(error);
          })
          .then(() => {
            this.isProcessing = false;
          });
      }
    }
  }
};
</script>
