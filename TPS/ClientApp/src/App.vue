<script setup lang="ts">
// This starter template is using Vue 3 <script setup> SFCs

import { useAuthModule } from "./store/modules/auth";

// Check out https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup
import NavBar from "./components/NavBar.vue";

const authModule = useAuthModule();

authModule.actions.initialize();
</script>

<template>
  <NavBar />
  <router-view></router-view>
</template>
