/* eslint-disable @typescript-eslint/no-explicit-any */
import '@/global.css'
import Echo from 'laravel-echo'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import Pusher from 'pusher-js'
import { createApp, ref, watch } from 'vue'
import Toast, { useToast } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import axios, { AxiosError } from 'axios'
import App from './App.vue'
import router from './router'
import { useAuthStore } from './stores/useAuthStore'

const app = createApp(App)

const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)

app.use(pinia)

const authStore = useAuthStore()

if (authStore.token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
}

window.Pusher = Pusher
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  wsHost: 'ws.pusher.com',
  forceTLS: true,
  wsPort: 443,
  authorizer: (channel: any) => {
    return {
      authorize: (socketId: any, callback: any) => {
        axios.defaults.baseURL = import.meta.env.VITE_API_URL
        axios.defaults.withCredentials = true
        axios
          .post('/broadcasting/auth', {
            socket_id: socketId,
            channel_name: channel.name,
          }, {
            headers: {
              Authorization: `Bearer ${authStore.token}`,
            }
          })
          .then((res) => {
            callback(null, res.data)
          })
          .catch((err) => {
            callback(err as AxiosError, null)
          })
      },
    }
  },
})

const upcomingTasks = ref<Array<{id: number; title: string; due_date: string; message: string}>>([])
interface notification {
  task_id: number;
  title: string;
  due_date: string;
  message: string;
}
const subscribeToNotifications = (userId: number) => {
  const toast = useToast()
  window.Echo.private(`App.Models.User.${userId}`).notification((notification: notification) => {
    console.log('Notificación recibida:', notification)
    upcomingTasks.value.push({
            id: notification.task_id,
            title: notification.title,
            due_date: notification.due_date,
            message: notification.message,
        });

        toast.warning(notification.message);
  })
}

if (authStore.user?.id) {
  subscribeToNotifications(authStore.user.id)
}

watch(
  () => authStore.user,
  (newUser) => {
    if (newUser?.id) {
      subscribeToNotifications(newUser.id)
    }
  },
)

app.use(router)
app.use(Toast)

app.mount('#app')
