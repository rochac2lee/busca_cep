<template>
  <v-app>
    <v-snackbar
      v-model="snackbarOpen"
      :color="snackbarColor"
      :timeout="snackbarTimeout"
      height="60"
      fixed
      bottom
    >
      {{ snackbarText }}
    </v-snackbar>
    <router-view />
  </v-app>
</template>

<script>
import { eventbus } from "@/main.js";

export default {
  name: "App",
  data: () => ({
    snackbarOpen: false,
    snackbarColor: "success",
    snackbarText: "",
    snackbarTimeout: 3000
  }),
  mounted() {
    eventbus.$on("toggleSnackbar", snackbarObj => {
      this.snackbarOpen = snackbarObj.show;
      this.snackbarColor = snackbarObj.color;
      this.snackbarText = snackbarObj.text;
      this.snackbarTimeout = snackbarObj.timeout;
    });
  }
};
</script>

<style>
#app {
  font-family: "Rb", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
  margin-top: 30px;
}
</style>
