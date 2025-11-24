import api from '@/lib/axios'
import { AxiosError } from 'axios'

export const apiGetTaskStatus = async () => {
  try {
    const res = await api.get(`/task-status`)
    return res.data
  } catch (error) {
    console.error('[backend]: Error al cargar status.', error)
  }
}

export const apiGetTask = async (id: number) => {
  try {
    const res = await api.get(`/task/${id}`)
    return res.data
  } catch (error) {
    console.error('[backend]: Error al cargar tarea.', error)
  }
}

export const apiAllTasks = async (page: number) => {
  try {
    const res = await api.get(`/tasks?page=${page}`)
    return res.data
  } catch (error) {
    console.error('[backend]: Error al cargar tareas.', error)
  }
}

export const apiCreateTask = async (payload: FormData) => {
  try {
    const res = await api.post('/task', payload, { headers: { 'Content-Type': 'multipart/form-data' }})
    return res.data
  } catch (error) {
    console.error('[backend]: Error al crear tarea.', error)
    if (error instanceof AxiosError && error.response?.status === 422) {
      console.log(error.response)
      console.log(error)
    }
  }
}

export const apiUpdateTask = async (payload: FormData, id: number) => {
  try {
    console.log(payload);
    payload.append('_method', 'PUT')
    const res = await api.post(`/task/${id}`, payload, { headers: { 'Content-Type': 'multipart/form-data' }})
    return res.data
  } catch (error) {
    console.error('[backend]: Error al actualizar tarea.', error)
  }
}

export const apiDeleteTask = async (id: number) => {
  try {
    const res = await api.delete(`/task/${id}`)
    return res.data
  } catch (error) {
    console.error('[backend]: Error al eliminar tarea.', error)
  }
}
