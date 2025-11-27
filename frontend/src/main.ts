import '@/global.css'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createApp } from 'vue'
import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import App from './App.vue'
import router from './router'

const app = createApp(App)

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

app.use(pinia)
app.use(Toast, {
  position: POSITION.TOP_RIGHT,
  timeout: 3000,
})

// import { subscribeToNotifications } from './helpers/useSubscribeNotification'
// import { useAuthStore } from './stores/useAuthStore'
// const authStore = useAuthStore()
// if (!authStore.isLoggedIn) {
//   window.Echo.disconnect()
// }
// watch(
//   () => authStore.user?.id,
//   (id) => {
//     if (id && authStore.isLoggedIn) subscribeToNotifications(id)
//   },
// )

app.use(router)
app.mount('#app')
