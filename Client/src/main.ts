import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import './index.css'
import { router } from './router'
import PrimeVue from 'primevue/config'

const app = createApp(App)
app.use(router)
app.use(PrimeVue)
app.mount('#app')
