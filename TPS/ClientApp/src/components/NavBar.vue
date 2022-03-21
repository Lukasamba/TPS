<script setup lang="ts">
import { Collapse } from "bootstrap";
import { onMounted, ref } from "vue";

const navBarRef = ref<HTMLElement | null>(null);
const collapse = ref<Collapse | null>(null);

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
      </div>
    </div>
  </nav>
</template>
