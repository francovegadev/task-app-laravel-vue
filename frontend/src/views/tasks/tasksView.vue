<script setup lang="ts">
import ModalC from '@/components/modalC.vue'
import ModalDeleteC from '@/components/modalDeleteC.vue'
import { getStatusColor } from '@/helpers/getStatusColor'
import { todayDate } from '@/helpers/useUtil'
import { useAuthStore } from '@/stores/useAuthStore'
import { useTaskStore } from '@/stores/useTaskStore'
import type { RolesInterface, UserInterface } from '@/types/auth'
import type { TasksFormInterface } from '@/types/tasks'
import { computed, onMounted, reactive, ref } from 'vue'

const searchValue = ref<string>('')
const selectedStatus = ref('')
const taskStore = useTaskStore()
const isOpen = ref<boolean>(false)
const date = todayDate()
const authStore = useAuthStore()
const rol = ref<RolesInterface>()

const form = reactive<TasksFormInterface>({
  title: '',
  description: '',
  due_date: '',
  image: null,
  status: '',
  user_id: authStore.user?.id ?? 0,
})

const users = computed<UserInterface[]>(() => {
  return authStore.users
})

const is_admin = computed(() => {
  return authStore.user?.roles?.[0]?.name === 'admin' ? true : false
})

onMounted(async () => {
  if (authStore.user?.roles) rol.value = authStore.user.roles[0]
  await taskStore.allTasks()
  await taskStore.getTaskStatus()
})

const close = () => (isOpen.value = false)
const open = () => (isOpen.value = true)

const crear = async (payload: TasksFormInterface) => {
  console.log(payload);
  await taskStore.createTask(payload)
  if (!taskStore.isLoading) {
    cleanForm()
    close()
  }
}

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.image = target.files?.[0] ?? null
  }
  else {
    cleanForm()
  }
}

const filteredData = computed(() => {
  let data = taskStore.tasks || []
  if (searchValue.value) {
    const search = searchValue.value.toLowerCase()
    data = data.filter((item) =>
      Object.values(item).some((val) => val?.toString().toLowerCase().includes(search)),
    )
  }

  if (selectedStatus.value) {
    data = data.filter((item) => item.status === selectedStatus.value)
  }

  return data
})

// modal config
const showModal = ref(false)
const selectedId = ref<number | null>(null)

const deleteRegister = async (id: number | null) => {
  if (id !== null) {
    await taskStore.deleteTask(id)
    closeModal()
  }
}

const openModal = (id: number) => {
  selectedId.value = id
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
}

const confirmDelete = async () => {
  if (selectedId.value !== null) {
    await deleteRegister(selectedId.value)
  }
}

const cleanForm = () => {
  form.title = ''
  form.description = ''
  form.due_date = ''
  form.status = ''
  form.user_id = authStore.user?.id ?? 0
}
</script>

<template>
  <div v-if="!taskStore.isLoading && taskStore.tasks" class="w-full max-w-2xl mx-auto mt-6">
    <button type="button" @click.prevent="open" v-if="rol?.name !== 'viewer'"
      class="flex w-full max-w-48 items-center ml-auto my-6 justify-center uppercase bg-info text-secondary text-center px-3 py-3 box-border border border-transparent cursor-pointer hover:bg-infoH font-medium leading-5 rounded-sm focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512" class="mx-2">
        <path fill="currentColor"
          d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32v160H32c-17.7 0-32 14.3-32 32s14.3 32 32 32h160v160c0 17.7 14.3 32 32 32s32-14.3 32-32V288h160c17.7 0 32-14.3 32-32s-14.3-32-32-32H256z" />
      </svg>
      Crear tarea
    </button>
    <div class="w-full mx-auto p-6 bg-navbarbg rounded-sm shadow-md">
      <label for="searchValue" class="text-md font-sans font-semibold">Filtrar tareas</label>
      <input type="text"
        class="px-3 py-3 bg-inputbg rounded-sm mb-6 mt-3 w-full border-0 shadow-sm focus-visible:outline-none"
        name="searchValue" id="searchValue" v-model="searchValue" placeholder="Buscar tarea.." />
      <label for="selectedStatus" class="text-md font-sans font-semibold">Filtrar por estado</label>
      <select v-model="selectedStatus"
        class="px-3 py-3 bg-inputbg rounded-sm mb-6 mt-3 w-full border-0 shadow-sm focus-visible:outline-none">
        <option value="">Todos</option>
        <option value="in_progress">En proceso</option>
        <option value="pending">Pendiente</option>
        <option value="completed">Completada</option>
      </select>
      <h5
        class="text-xl font-semibold text-heading text-center uppercase bg-primary rounded-sm px-3 py-3 text-secondary mb-6">
        Listado tareas
      </h5>
      <div class="flow-root">
        <ul role="list" class="">
          <li class="pb-4 sm:pb-4 bg-secondary px-3 py-3 rounded-sm shadow-sm my-2" v-for="task in filteredData"
            :key="task.id">
            <div class="flex items-center gap-2">
              <div class="flex-1 min-w-0">
                <p class="text-sm text-faded font-semibold truncate">
                  {{ task.due_date }}
                </p>
                <p class="font-medium text-lg truncate">
                  {{ task.title }}
                </p>
                <p class="text-sm text-faded font-semibold truncate">
                  {{ task.description }}
                </p>
                <p :class="[
                  'flex text-sm px-3 py-1 bg-secondary capitalize shadow-sm mt-3 max-w-[120px] rounded-xl text-center font-semibold truncate',
                  getStatusColor(task.status),
                ]">
                  <span class="text-center mx-auto">{{ task.status }}</span>
                </p>
              </div>
              <div class="inline-flex items-center space-x-1">
                <router-link :to="`/task/${task.id}`"
                  class="box-border hover:text-heading font-medium leading-5 rounded-sm text-xs px-3 py-1.5 focus:outline-none shrink-0 cursor-pointer">
                  <svg class="hover:text-indigo-600 hover:skew-1 transition-colors" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" viewBox="0 0 16 16">
                    <path fill="currentColor" fill-rule="evenodd"
                      d="M1.87 8.515L1.641 8l.229-.515a6.708 6.708 0 0 1 12.26 0l.228.515l-.229.515a6.708 6.708 0 0 1-12.259 0M.5 6.876l-.26.585a1.33 1.33 0 0 0 0 1.079l.26.584a8.208 8.208 0 0 0 15 0l.26-.584a1.33 1.33 0 0 0 0-1.08l-.26-.584a8.208 8.208 0 0 0-15 0M9.5 8a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0M11 8a3 3 0 1 1-6 0a3 3 0 0 1 6 0"
                      clip-rule="evenodd" />
                  </svg>
                </router-link>
                <router-link :to="`/task/${task.id}/edit`" v-if="rol?.name !== 'viewer'"
                  class="box-border hover:text-heading font-medium leading-5 rounded-sm text-xs px-3 py-1.5 focus:outline-none shrink-0 cursor-pointer">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    class="hover:text-infoH transition-colors hover:skew-2" viewBox="0 0 512 512">
                    <path fill="currentColor"
                      d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L368 46.1l97.9 97.9l24.4-24.4c21.9-21.9 21.9-57.3 0-79.2zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L432 177.9L334.1 80zM96 64c-53 0-96 43-96 96v256c0 53 43 96 96 96h256c53 0 96-43 96-96v-96c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32z" />
                  </svg>
                </router-link>
                <button type="button" v-if="rol?.name !== 'viewer'" @click.prevent="openModal(task.id)"
                  class="inline-flex items-center justify-center text-danger bg-transparent box-border border border-transparent cursor-pointer hover:skew-2 font-medium leading-5 rounded-sm h-9 w-9 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 22 22">
                    <path fill="currentColor"
                      d="M10 7v9H8V7zm2 0h2v9h-2zM8 2h6v1h5v2h-1v14h-1v1H5v-1H4V5H3V3h5zM6 5v13h10V5z" />
                  </svg>
                  <div id="tooltip1" role="tooltip"
                    class="absolute invisible z-10 inline-block rounded-xl mb-20 ml-4 px-3 py-2 text-xs leading-4 font-medium text-white transition-opacity duration-300 bg-dark shadow-xs">
                    Eliminar tarea
                    <div class="tooltip-arrow" data-popper-arrow></div>
                  </div>
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="flex w-full gap-4">
      <ModalDeleteC :show="showModal" :onConfirm="confirmDelete" @close="closeModal" />
    </div>
  </div>

  <ModalC :show="isOpen" @close="close" title="Crear Tarea">
    <template #body>
      <form @submit.prevent="crear(form)" enctype="multipart/form-data">
        <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
          <input type="number" name="user_id" hidden v-model="form.user_id" />
          <div class="col-span-2">
            <label for="title" class="block mb-2.5 text-sm font-medium text-heading">Título</label>
            <input type="text" name="title" id="title" v-model="form.title"
              class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
              placeholder="Ingresar título..." required />
          </div>
          <div class="col-span-2">
            <label for="description" class="block mb-2.5 text-sm font-medium text-heading">
              Descripción
            </label>
            <textarea id="description" name="description" rows="2" v-model="form.description"
              class="block bg-inputbg text-heading text-sm rounded-sm focus:outline-none w-full p-3.5 shadow-xs placeholder:text-body"
              placeholder="Ingresar descripción..."></textarea>
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="due_date" class="block mb-2.5 text-sm font-medium text-heading">Fecha vencimiento</label>
            <input type="date" name="due_date" id="due_date" :min="date" v-model="form.due_date"
              class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
              required />
          </div>
          <div class="col-span-2 sm:col-span-1">
            <label for="status" class="block mb-2.5 text-sm font-medium text-heading">Estado</label>
            <select id="status" v-model="form.status"
              class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body">
              <option value="" selected>Seleccionar estado</option>
              <option v-for="(task, idx) in taskStore.status" :key="idx">{{ task }}</option>
            </select>
          </div>

          <div class="col-span-2">
            <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input">Seleccionar imagen</label>
            <input
              id="image_task" type="file"
              name="image"
              accept="image/*"
              capture
              @change="onFileChange"
              class="block w-full max-w-md px-3 py-2.5 bg-inputbg mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
            >
          </div>

          <div class="flex w-full" v-if="is_admin">
            <div class="relative z-0 w-full mb-5 group">
              <label for="status" class="block mb-2.5 text-sm font-medium text-heading">
                Asignar a usuario
              </label>
              <select id="user_id" name="user_id" v-model="form.user_id"
                class="block w-full px-3 py-2.5 bg-neutral-secondary-medium mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
                <option value="" disabled selected>Seleccionar usuario</option>
                <option :value="user.id" v-for="user in users" :key="user.id">
                  {{ user.name }}
                </option>
              </select>
            </div>
          </div>
        </div>
        <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
          <button type="submit"
            class="inline-flex items-center bg-primary text-white hover:bg-brand-strong box-border rounded-sm hover:bg-gray-600 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 12h14m-7 7V5" />
            </svg>
            Crear tarea
          </button>
          <button type="button" @click.prevent="close"
            class="text-body bg-secondary box-border hover:bg-secondaryH hover:text-heading focus:outline-none shadow-md font-medium leading-5 rounded-sm text-sm px-4 py-2.5">
            Cancel
          </button>
        </div>
      </form>
    </template>
  </ModalC>
</template>
