import api from "@/lib/axios"
import type { LoginFormInterface, RegisterFormInterface } from "@/types/auth"
import axios from "axios"

export const apiLogin = async (payload: LoginFormInterface) => {
  try {
   await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })
   const res = await api.post('/login', payload) 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiRegister = async (payload: RegisterFormInterface) => {
  try {
   await axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true })
   const res = await api.post('/register', payload) 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiGetUser = async () => {
  try {
   const res = await api.get('/user') 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiGetUsers = async () => {
  try {
   const res = await api.get('/users') 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiGetUserById = async (id: number) => {
  try {
   const res = await api.get(`/user/${id}`) 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiGetDashboard = async () => {
  try {
   const res = await api.get(`/dashboard`) 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}

export const apiLogout = async () => {
  try {
   const res = await api.post('/logout') 
   return res.data
  } catch (error) {
   console.error(error); 
  }
}