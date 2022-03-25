<script setup lang="ts">
import { Collapse } from "bootstrap";
import { onMounted, ref } from "vue";
import { useAuthModule } from "../store/modules/auth";
import { LoginMsal, LogoutMsal } from "../msal";

const navBarRef = ref<HTMLElement | null>(null);
const collapse = ref<Collapse | null>(null);

const {
  state: { isLoggedIn, displayName },
} = useAuthModule();

onMounted(() => {
  navBarRef.value;
  if (navBarRef.value) {
    collapse.value = new Collapse(navBarRef.value, { toggle: false });
  }
});
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">TPS</a>
      <button
        class="navbar-toggler"
        type="button"
        @click="
          () => {
            collapse?.toggle();
          }
        "
        v-bind:class="{ active: true }"
        aria-controls="navbarContent"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" ref="navBarRef" id="navbarContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <router-link class="nav-link" to="/teams">Komandos</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/projects">Projektai</router-link>
          </li>
        </ul>
        <div class="navbar-nav">
          <template v-if="isLoggedIn">
            <span class="navbar-text">Prisijungta kaip {{ displayName }} </span>
            <div class="nav-item">
              <a href="#" @click="LogoutMsal" class="nav-link">Atsijungti</a>
            </div>
          </template>
          <div v-else class="nav-item">
            <a href="#" @click="LoginMsal" class="nav-link">Prisijungti</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
