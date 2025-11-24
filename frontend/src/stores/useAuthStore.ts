import api from '@/lib/axios'
import router from '@/router'
import {
  apiGetDashboard,
  apiGetUser,
  apiGetUserById,
  apiGetUsers,
  apiLogin,
  apiLogout,
  apiRegister,
} from '@/server/auth'
import type { LoginFormInterface, RegisterFormInterface, UserInterface } from '@/types/auth'
import { AxiosError } from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import { useTaskStore } from './useTaskStore'

export const useAuthStore = defineStore(
  'auth',
  () => {
    const user = ref<UserInterface | null>(null)
    const users = ref<UserInterface[]>([])
    const token = ref<string>('')
    const isLoggedIn = ref<boolean>(false)
    const isLoading = ref<boolean>(false)
    const errors = ref<ValidationErrors>({})
    const toast = useToast();

    api.interceptors.request.use((config) => {
      if (token.value) {
        config.headers.Authorization = `Bearer ${token.value}`
      }
      return config
    })

    interface ValidationErrors {
      [key: string]: string[] | undefined
    }
    const clearErrors = (field: string) => {
      if (errors.value[field]) {
        errors.value[field] = [] 
      }
    }

    const login = async (payload: LoginFormInterface) => {
      isLoading.value = true
      try {
        const res = await apiLogin(payload)
        token.value = res.data.token
        localStorage.setItem('token', token.value)
        isLoggedIn.value = true
        await getUser()

      } catch (error) {
        if (error instanceof AxiosError && error.response?.status === 422) {
          errors.value = error.response.data.errors || { 'message': error.response.data.message }
          toast.error('[Error de validación] \nPor favor, revisa los campos e inténtalo de nuevo.');
        }
      } finally {
        isLoading.value = false
      }
    }

    const loginWithGoogle = async (credential: string) => {
      isLoading.value = true
      try {
        const res = await api.post('/auth/google', { credential })
        token.value = res.data.token
        localStorage.setItem('token', token.value)
        isLoggedIn.value = true
        await getUser()
      } catch (error) {
        console.error('Error en login con Google:', error)
        toast.error('[Error] \nError en login con Google.');
      } finally {
        isLoading.value = false
      }
    }

    const register = async (payload: RegisterFormInterface) => {
      isLoading.value = true
      try {
        await apiRegister(payload)
        await getUser()
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          errors.value = error.response.data.errors
          toast.error('[Error de validación] \nPor favor, revisa los campos e inténtalo de nuevo.');
        }
      } finally {
        isLoading.value = false
      }
    }

    const getUser = async () => {
      isLoading.value = true
      try {
        const res = await apiGetUser()
        user.value = {
          id: res.user.id,
          name: res.user.name,
          email: res.user.email,
          roles: res.user.roles,
          permissions: res.user.permissions,
        }
        isLoggedIn.value = true
        if (user.value.roles![0]?.name === 'admin') await getUsers()
        router.push('/tasks')
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.error(error.response.statusText)
          toast.error('[Error] \nUsuario no encontrado.');
        }
      } finally {
        isLoading.value = false
      }
    }

    const getUsers = async () => {
      isLoading.value = true
      try {
        const res = await apiGetUsers()
        users.value = res.data
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.error(error.response.statusText)
          toast.error('[Error] \nUsuarios no encontrados.');
        }
      } finally {
        isLoading.value = false
      }
    }

    const getUserById = async (id: number) => {
      isLoading.value = true
      try {
        const res = await apiGetUserById(id)
        return res.data
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.error(error.response.statusText)
          toast.error('[Error] \nUsuario no encontrado.');
        }
      } finally {
        isLoading.value = false
      }
    }

    const getDashboardData = async () => {
      isLoading.value = true
      try {
        const res = await apiGetDashboard()
        return res
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.error(error.response.statusText)
        }
      } finally {
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
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.error(error.response.statusText)
          toast.error(`[Error] ${'\n' + error.response.data.message}`);
        }
      } finally {
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
      errors.value = []
      localStorage.clear()
    }

    return {
      user,
      users,
      login,
      loginWithGoogle,
      register,
      logout,
      getUser,
      getUsers,
      getUserById,
      getDashboardData,
      cleanStore,
      isLoading,
      isLoggedIn,
      token,
      errors,
      clearErrors,
    }
  },
  {
    persist: {
      storage: sessionStorage,
      pick: ['user', 'isLoggedIn', 'token', 'users'],
    },
  },
)
