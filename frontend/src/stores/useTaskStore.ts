import { toFormData } from '@/helpers/toFormDataTask'
import router from '@/router'
import {
  apiAllTasks,
  apiCreateTask,
  apiDeleteTask,
  apiGetTask,
  apiGetTaskStatus,
  apiUpdateTask
} from '@/server/tasks'
import {
  type TasksFormInterface,
  type TasksInterface,
  type TasksStatusInterface,
} from '@/types/tasks'
import { AxiosError } from 'axios'
import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useToast } from 'vue-toastification'
import { useAuthStore } from './useAuthStore'

export const useTaskStore = defineStore(
  'tasks',
  () => {
    const tasks = ref<TasksInterface[] | null>(null)
    const task = ref<TasksInterface | null>(null)
    const errors = ref<Record<string, string>[] | null>(null)
    const status = ref<TasksStatusInterface[] | null>(null)
    const isLoading = ref<boolean>(false)
    const toast = useToast()

    const getTask = async (id: number) => {
      isLoading.value = true
      try {
        const res = await apiGetTask(id)
        task.value = res.data
      } catch (error) {
        console.error(error)
        toast.error('[Error]: No se pudo encontrar la tarea.')
      } finally {
        isLoading.value = false
      }
    }

    const allTasks = async () => {
      isLoading.value = true
      const auth = useAuthStore()
      try {
        if (!auth.isLoggedIn) return
        const res = await apiAllTasks()
        tasks.value = res.data
        await getTaskStatus()
      } catch (error) {
        console.error(error)
        toast.error('[Error]: No se pudo cargar las tareas.')
      } finally {
        isLoading.value = false
      }
    }

    const createTask = async (payload: TasksFormInterface) => {
      isLoading.value = true
      try {
        const formData = toFormData(payload) 
        await apiCreateTask(formData)
        await allTasks()
        toast.success('Tarea creada correctamente.')
        router.push('/tasks')
      } catch (error) {
        console.error(error)
        if (error instanceof AxiosError && error.response?.status === 422) {
          console.log(error.response)
          errors.value = error.response.data.errors
          console.log(errors)
        }
        toast.error('[Error]: No se pudo crear la tarea.')
      } finally {
        isLoading.value = false
      }
    }

    const updateTask = async (payload: TasksFormInterface, id: number) => {
      isLoading.value = true
      try {
        await apiUpdateTask(payload, id)
        await allTasks()
        toast.success('Tarea actualizada correctamente.')
        router.push('/tasks')
      } catch (error) {
        console.error(error)
        toast.error('[Error]: No se pudo actualizar la tarea.')
      } finally {
        isLoading.value = false
      }
    }

    const deleteTask = async (id: number) => {
      isLoading.value = true
      try {
        await apiDeleteTask(id)
        await allTasks()
        toast.success('Tarea eliminada correctamente.')
        router.push('/tasks')
      } catch (error) {
        console.error(error)
        toast.error('[Error]: No se pudo eliminar la tarea.')
      } finally {
        isLoading.value = false
      }
    }

    const getTaskStatus = async () => {
      if (status.value?.length) return
      isLoading.value = true
      try {
        const res = await apiGetTaskStatus()
        status.value = res
      } catch (error) {
        console.error(error)
        toast.error('[Error]: No se pudo encontrar los estados de las tareas.')
      } finally {
        isLoading.value = false
      }
    }

    return {
      tasks,
      task,
      status,
      errors,
      getTask,
      allTasks,
      createTask,
      updateTask,
      deleteTask,
      getTaskStatus,
      isLoading,
    }
  },
  {
    persist: {
      storage: sessionStorage,
      pick: ['tasks', 'status', 'task'],
    },
  },
)
