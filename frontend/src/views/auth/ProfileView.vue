<template>
  <div class="w-full flex flex-col justify-center">
    <div class="row w-full flex justify-center">
      <ImagePreview
        :src="this.$global.personImage"
        alt="User" />
    </div>
  </div>
</template>

<script>
import ImagePreview from "@/components/profile/ImagePreview.vue";
import PersonImage from "@/assets/img/person.png";

export default {
  components: {
    ImagePreview
  },

  mounted() {
    this.$global.personImage = this.$store.getters.user.img ? `http://ecommerce.backend.com/storage/${this.$store.getters.user.img}` : PersonImage;
  }
}
</script>