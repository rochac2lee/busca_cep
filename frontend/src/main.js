import Vue from 'vue'
import App from './App'
import router from './router'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)
const vuetify = new Vuetify()

import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask)

export const eventbus = new Vue({
  methods: {
    toggleSnackbar(snackbarObj) {
      this.$emit('toggleSnackbar', snackbarObj)
    },
  }
})

import api from './provider/api'

Vue.config.productionTip = false
Vue.prototype.$api = api

new Vue({
  router,
  vuetify,
  render: (h) => h(App),
}).$mount('#app')