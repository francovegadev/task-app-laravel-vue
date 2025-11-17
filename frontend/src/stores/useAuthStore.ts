import api from '@/lib/axios';
import router from '@/router';
import { apiGetUser, apiLogin, apiLogout, apiRegister } from '@/server/auth';
import type { LoginFormInterface, RegisterFormInterface, UserInterface } from '@/types/auth';
import { AxiosError } from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';
import { useTaskStore } from './useTaskStore';

export const useAuthStore = defineStore('auth', () => {
  const user = ref<UserInterface | null>(null)
  const token = ref<string>('')
  const isLoggedIn = ref<boolean>(false)
  const isLoading = ref<boolean>(false)

  api.interceptors.request.use(
    (config) => {
      if (token.value) {
        config.headers.Authorization = `Bearer ${token.value}`
      }
      return config
    }
  )

  const login = async (payload: LoginFormInterface) => {
    isLoading.value = true
    try {
     const res = await apiLogin(payload) 
     token.value = res.data.token 
     localStorage.setItem('token', token.value)
     isLoggedIn.value = true
     await getUser()
    } catch (error) {
     console.error(error); 
     if (error instanceof AxiosError && error.response?.status === 422) {
      console.error(error.response.statusText); 
     }
    }
    finally {
      isLoading.value = false
    }
  }

  const register = async (payload: RegisterFormInterface) => {
    isLoading.value = true
    try {
     await apiRegister(payload) 
     await getUser()
    } catch (error) {
     console.error(error); 
     if (error instanceof AxiosError && error.response?.status === 422) {
      console.error(error.response.statusText); 
     }
    }
    finally {
      isLoading.value = false
    }
  }

  const getUser = async () => {
    isLoading.value = true
    try {
     const res = await apiGetUser() 
     user.value = { 'id': res.user.id, 'name': res.user.name, 'email': res.user.email, 'roles': res.user.roles}
     isLoggedIn.value = true
     router.push('/tasks')
    } catch (error) {
     console.error(error); 
     if (error instanceof AxiosError && error.response?.status === 422) {
      console.error(error.response.statusText); 
     }
    }
    finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    isLoading.value = true
    try {
     await apiLogout() 
     cleanStore()
     router.push('/login')
    } catch (error) {
     console.error(error); 
     if (error instanceof AxiosError && error.response?.status === 422) {
      console.error(error.response.statusText); 
     }
    }
    finally {
      isLoading.value = false
      isLoggedIn.value = false
    }
  }

  const cleanStore = () => {
    const task = useTaskStore()
    user.value = null
    isLoggedIn.value = false
    token.value = ''
    task.tasks = []
    task.task = null
    localStorage.clear()
  }

  return {
    user,
    login,
    register,
    logout,
    getUser,
    cleanStore,
    isLoading,
    isLoggedIn,
    token
  }

},
{
  persist: {
    storage: sessionStorage,
    pick: ["user", "isLoggedIn", "token"]
  }
}
)