import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

//* npm i bootstrap
import "bootstrap/dist/css/bootstrap.css"
//* npm i bootstrap-icons
import "bootstrap-icons/font/bootstrap-icons.css"

createApp(App).use(store).use(router).mount('#app')

//* npm i @popperjs/core
import "bootstrap/dist/js/bootstrap.js"