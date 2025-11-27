import api from '@/lib/axios'
import type { LoginFormInterface, RegisterFormInterface } from '@/types/auth'

export const apiLogin = async (payload: LoginFormInterface) => {
  await api.get('/sanctum/csrf-cookie', {
    withCredentials: true,
    baseURL: 'http://localhost:8000/',
  })
  const res = await api.post('/login', payload)
  return res.data
}

export const apiRegister = async (payload: RegisterFormInterface) => {
  await api.get('/sanctum/csrf-cookie', {
    withCredentials: true,
    baseURL: 'http://localhost:8000/',
  })
  const res = await api.post('/register', payload)
  return res.data
}

export const apiGetUser = async () => {
  const res = await api.get('/user')
  return res.data
}

export const apiGetUsers = async () => {
  try {
    const res = await api.get('/users')
    return res.data
  } catch (error) {
    console.error(error)
  }
}

export const apiGetUserById = async (id: number) => {
  try {
    const res = await api.get(`/user/${id}`)
    return res.data
  } catch (error) {
    console.error(error)
  }
}

export const apiGetDashboard = async () => {
  try {
    const res = await api.get(`/dashboard`)
    return res.data
  } catch (error) {
    console.error(error)
  }
}

export const apiLogout = async () => {
  try {
    const res = await api.post('/logout')
    return res.data
  } catch (error) {
    console.error(error)
  }
}
