<template>
  <NavbarComponent v-if="showNavbarSidebar()" />

  <div class="w-screen h-screen flex">
    <SidebarComponent v-if="this.$route.path !== '/register' && this.$route.path !== '/login'" />
    <div 
      class="h-screen bg-[rgba(255,255,255,.5)]"
      :class="{
        'w-[85%] overflow-y-auto pt-[4.5rem]': showNavbarSidebar(), 
        'w-full': !showNavbarSidebar()
      }">
      <RouterView />
    </div>
  </div>
</template>

<script>
import NavbarComponent from "./components/NavbarComponent.vue";
import SidebarComponent from "./components/SidebarComponent.vue";

export default {
  components: {
    NavbarComponent,
    SidebarComponent
  },

  methods: {
    showNavbarSidebar() {
      return !['/register', '/login'].includes(this.$route.path);
    }
  }

}
</script>

