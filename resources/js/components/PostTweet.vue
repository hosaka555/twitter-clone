<template>
  <div>
    <h1>New Tweet</h1>
    <div class="newTweet-container">
      <ProfileIcon :user-icon="getAuthUserIcon()" />
      <textarea class="newTweet__input" placeholder="いまどうしている？" v-model="message" />
      <div class="newTweet-bottomBox">
        <div class="bottomBox-rightSide">
          <div class="bottomBox-rightSide__count" :class="{countover: countOver }">{{ restOfCount }}</div>
          <button
            class="button bottomBox-rightSide__button"
            @click.prevent="postTweet"
            :disabled="countOver || message.length === 0 || isProcessing"
          >投稿</button>
        </div>
      </div>

      <div class="newTweet-imagesBox">
        <div v-if="previewImages.length" class="newTweet-imagesBox--if">
          <div
            v-for="(image,index) in previewImages"
            :key="index"
            class="newTweet-imagesBox__wrapper"
          >
            <div class="newTweet-imagesBox__image">
              <ShowImage :uri="image" :class="imageClass" />
              <div
                class="newTweet-imagesBox__button newTweet-imagesBox__button--delete"
                @click="deleteImage(index)"
              >
                <font-awesome-icon
                  icon="times-circle"
                  class="newTweet-imagesBox__icon--delete"
                  :class="{'newTweet-imagesBox__icon--delete--one': imagesCount === 1}"
                />
              </div>
            </div>
          </div>
        </div>
        <div v-if="imagesCount < 4" class="newTweet-imagesBox--if">
          <label for="tweet_image">
            <div class="newTweet-imagesBox__button newTweet-imagesBox__button--select">
              <font-awesome-icon icon="plus-circle" class="newTweet-imagesBox__icon--plus" />
            </div>
            <span>画像の追加</span>

            <input
              type="file"
              id="tweet_image"
              @change="addImage"
              accept=".jpg, .png, image/jpeg, image/png"
            />
          </label>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProfileIcon from "./ProfileIcon";
import ShowImage from "./ShowImage";

export default {
  props: {
    isRedirect: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      message: "",
      countOver: false,
      isProcessing: false,
      imagesData: [],
      previewImages: []
    };
  },
  components: {
    ProfileIcon,
    ShowImage
  },
  computed: {
    restOfCount() {
      let count = 140 - this.message.length;
      this.countOver = count < 0 ? true : false;

      return count;
    },
    imagesCount() {
      return this.imagesData.length;
    },
    imageClass() {
      switch (this.imagesCount) {
        case 1:
          return "tweet__image--one";
          break;
        case 2:
          return "tweet__image--two";
          break;
        case 3:
          return "tweet__image--three";
          break;
        case 4:
          return "tweet__image--four";
          break;
        default:
          return "";
          break;
      }
    }
  },
  methods: {
    async postTweet() {
      if (!this.countOver && !this.isProcessing) {
        this.isProcessing = true;
        const url = `/api/users/${this.$store.state.user.user.account_id}/tweets/tweet`;
        let data = new FormData();

        data.append("message", this.message);
        this.imagesData.forEach((v, i) => data.append("images[]", v));

        const clearState = () => {
          this.message = "";
          this.imagesData = [];
          this.previewImages = [];
        };

        const isRedirect = this.isRedirect;

        await this.$store.dispatch("tweet/postTweet", {
          url,
          data,
          clearState,
          isRedirect
        });

        this.isProcessing = false;
      }
    },
    getAuthUserIcon() {
      return this.$store.getters["profile/profile_icon"];
    },
    addImage(e) {
      let file = e.target.files[0];
      this.createImage(file);
      this.imagesData.push(file);
    },
    createImage(file) {
      let reader = new FileReader(file);
      reader.onload = e => {
        this.previewImages.push(e.target.result);
      };
      reader.readAsDataURL(file);
    },
    deleteImage(index) {
      this.imagesData.splice(index, 1);
      this.previewImages.splice(index, 1);
    }
  }
};
</script>