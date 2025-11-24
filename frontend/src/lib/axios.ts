import router from '@/router'
import { useAuthStore } from '@/stores/useAuthStore'
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  withCredentials: true,
  withXSRFToken: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

api.interceptors.response.use(
  (response) => {
    return response
  },
  async (error) => {
    const auth = useAuthStore()
    switch (error.response.status) {
      case 401:
        auth.cleanStore()
        router.push('/login')
        break
      case 404:
        router.push('/404')
        break
      case 419:
        auth.cleanStore()
        router.push('/login')
        break
      case 500:
        router.push('/500')
        break
    }
    return Promise.reject(error)
  },
)

export default api
