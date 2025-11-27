<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import { useTaskStore } from '@/stores/useTaskStore'
import type { TasksFormInterface } from '@/types/tasks'
import { computed, reactive, ref, watch } from 'vue'

const props = defineProps<{
  show: boolean
  title: string
}>()

const taskStore = useTaskStore()
const authStore = useAuthStore()

const users = computed(() => {
  return authStore.users
})
const is_admin = computed(() => {
  return authStore.user?.roles?.[0]?.name === 'admin' ? true : false
})
const form = reactive<TasksFormInterface>({
  title: '',
  description: '',
  due_date: '',
  image: null,
  status: '',
  user_id: authStore.user?.id ?? 0,
})

const date = computed(() => {
  return new Date().toISOString().split('T')[0]
})
const emit = defineEmits(['close', 'crear'])
const isOpen = ref<boolean>(false)

const close = () => emit('close')

const crear = (formData: TasksFormInterface) => {
  emit('crear', formData)
}

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.image = target.files?.[0] ?? null
  } else {
    cleanForm()
  }
}

const cleanForm = () => {
  form.title = ''
  form.description = ''
  form.due_date = ''
  form.status = ''
  form.user_id = authStore.user?.id ?? 0
}

watch(
  () => props.show,
  (val) => (isOpen.value = val),
  { immediate: true },
)
</script>

<template>
  <div
    v-if="isOpen"
    id="modalC"
    tabindex="-1"
    aria-hidden="true"
    class="overflow-y-auto overflow-x-hidden fixed mt-2 top-10 right-0 left-0 z-50 justify-center items-center w-full max-w-xl mx-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
  >
    <div class="relative p-4 w-full max-w-xl max-h-full">
      <div class="relative bg-secondary rounded-base shadow-sm p-4 md:p-6">
        <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
          <h3 class="text-lg font-medium text-heading">
            <slot name="header"></slot>
            {{ props.title }}
          </h3>
          <button
            type="button"
            class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center rounded-sm items-center hover:bg-gray-200"
            @click.prevent="close"
          >
            <svg
              class="w-5 h-5"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                stroke="currentColor"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18 17.94 6M18 18 6.06 6"
              />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- <slot name="body"></slot> -->
        <form @submit.prevent="crear(form)" enctype="multipart/form-data">
          <div class="grid gap-4 grid-cols-2 py-4 md:py-6">
            <input type="number" name="user_id" hidden v-model="form.user_id" />
            <div class="col-span-2">
              <label for="title" class="block mb-2.5 text-sm font-medium text-heading"
                >Título</label
              >
              <input
                type="text"
                name="title"
                id="title"
                v-model="form.title"
                class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                placeholder="Ingresar título..."
                required
              />
            </div>
            <div class="col-span-2">
              <label for="description" class="block mb-2.5 text-sm font-medium text-heading">
                Descripción
              </label>
              <textarea
                id="description"
                name="description"
                rows="2"
                v-model="form.description"
                class="block bg-inputbg text-heading text-sm rounded-sm focus:outline-none w-full p-3.5 shadow-xs placeholder:text-body"
                placeholder="Ingresar descripción..."
              ></textarea>
            </div>
            <div class="col-span-2 sm:col-span-1">
              <label for="due_date" class="block mb-2.5 text-sm font-medium text-heading"
                >Fecha vencimiento</label
              >
              <input
                type="date"
                name="due_date"
                id="due_date"
                :min="date"
                v-model="form.due_date"
                class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
                required
              />
            </div>
            <div class="col-span-2 sm:col-span-1">
              <label for="status" class="block mb-2.5 text-sm font-medium text-heading"
                >Estado</label
              >
              <select
                id="status"
                v-model="form.status"
                class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body"
              >
                <option value="" selected>Seleccionar estado</option>
                <option v-for="(task, idx) in taskStore.status" :key="idx">{{ task }}</option>
              </select>
            </div>

            <div class="col-span-2">
              <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input"
                >Seleccionar imagen</label
              >
              <input
                id="image_task"
                type="file"
                name="image"
                accept="image/*"
                capture
                @change="onFileChange"
                class="block w-full max-w-md px-3 py-2.5 bg-inputbg mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
              />
            </div>

            <div class="flex w-full" v-if="is_admin">
              <div class="relative z-0 w-full mb-5 group">
                <label for="status" class="block mb-2.5 text-sm font-medium text-heading">
                  Asignar a usuario
                </label>
                <select
                  id="user_id"
                  name="user_id"
                  v-model="form.user_id"
                  class="block w-full px-3 py-2.5 bg-neutral-secondary-medium mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body"
                >
                  <option value="" disabled selected>Seleccionar usuario</option>
                  <option :value="user.id" v-for="user in users" :key="user.id">
                    {{ user.name }}
                  </option>
                </select>
              </div>
            </div>
          </div>
          <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
            <button
              type="submit"
              class="inline-flex items-center bg-primary text-white hover:bg-brand-strong box-border rounded-sm hover:bg-gray-600 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
            >
              <svg
                class="w-4 h-4 me-1.5 -ms-0.5"
                aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="none"
                viewBox="0 0 24 24"
              >
                <path
                  stroke="currentColor"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 12h14m-7 7V5"
                />
              </svg>
              Crear tarea
            </button>
            <button
              type="button"
              @click.prevent="close"
              class="text-body bg-secondary box-border hover:bg-secondaryH hover:text-heading focus:outline-none shadow-md font-medium leading-5 rounded-sm text-sm px-4 py-2.5"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
