/* eslint-disable @typescript-eslint/no-explicit-any */
import api from '@/lib/axios'
import type { AxiosError } from 'axios'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'

window.Pusher = Pusher
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true,
  wsPort: 443,
  wssPort: 443,
  enabledTransports: ['ws', 'wss'], // ⬅ SOLO websockets
  disableStats: true,
  authorizer: (channel: any) => {
    return {
      authorize: (socketId: any, callback: any) => {
        api.defaults.baseURL = import.meta.env.VITE_API_URL
        api
          .post('/broadcasting/auth', {
            socket_id: socketId,
            channel_name: channel.name,
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

export const upcomingTasks = ref<
  Array<{ id: number; title: string; due_date: string; message: string }>
>([])
interface notification {
  task_id: number
  title: string
  due_date: string
  message: string
}

export const subscribeToNotifications = (userId: number) => {
  const toast = useToast()
  window.Echo.private(`App.Models.User.${userId}`).notification((notification: notification) => {
    console.log('Notificación recibida:', notification)
    upcomingTasks.value.push({
      id: notification.task_id,
      title: notification.title,
      due_date: notification.due_date,
      message: notification.message,
    })
    toast.warning(notification.message)
  })
}
