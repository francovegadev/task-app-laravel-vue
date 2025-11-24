<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import { useTaskStore } from '@/stores/useTaskStore'
import { computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const taskStore = useTaskStore()
const authStore = useAuthStore()
const route = useRoute()
const router = useRouter()
const id = route.params.id

watch(
  () => Number(route.params.id),
  async () => await taskStore.getTask(Number(id)),
  { immediate: true },
)

const today = computed(() => {
  return new Date().toISOString().split('T')[0]
})

const onFileChange = (evt: Event) => {
  const target = evt.target as HTMLInputElement
  const files = target.files
  if (files && files.length > 0 && taskStore.task) {
    taskStore.task.image = files[0] ?? null    
  }
}

const update = async () => {
  if (taskStore.task) {
    taskStore.task.user_id = authStore.user!.id
    await taskStore.updateTask(taskStore.task, Number(id))
  }
}

const redirect = () => {
  router.push('/tasks')
}
</script>

<template>
  <form v-if="taskStore.task && authStore.user" class="max-w-xl mx-auto mt-6 bg-secondary shadow-sm px-3 py-6"
    enctype="multipart/form-data" @submit.prevent="update">
    <fieldset>
      <h5
        class="text-xl font-semibold text-heading text-center uppercase bg-primary rounded-sm px-3 py-3 text-secondary mb-6">
        Editar tarea
      </h5>
    </fieldset>
    <input type="hidden" name="user_id" v-model="authStore.user.id" />
    <div class="relative z-0 w-full mb-5 group">
      <label for="title" class="block mb-2.5 text-lg font-sans font-medium text-heading">Título</label>
      <input type="text" id="title" v-model="taskStore.task.title"
        class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder="Ingresar título" required />
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <label for="description" class="lock mb-2.5 text-lg font-sans font-medium text-heading">Descripción</label>
      <textarea id="description" v-model="taskStore.task.description" rows="4"
        class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder="Write your thoughts here..."></textarea>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <label for="floating_due_date" class="lock mb-2.5 text-lg font-sans font-medium text-heading">
        Fecha vencimiento
      </label>
      <input type="date" name="due_date" id="floating_due_date" v-model="taskStore.task.due_date" :min="today"
        class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body"
        placeholder=" " required />
    </div>
    <div class="flex w-full">
      <div class="relative z-0 w-full mb-5 group">
        <label for="status" class="block mb-2.5 text-lg font-sans font-medium text-heading">
          Estado
        </label>
        <select id="status" v-model="taskStore.task.status"
          class="bg-inputbg text-heading text-sm rounded-sm focus:outline-none block w-full px-3 py-2.5 shadow-xs placeholder:text-body">
          <option selected>Seleccionar estado</option>
          <option :value="task" v-for="(task, idx) in taskStore.status" :key="idx">
            {{ task }}
          </option>
        </select>
      </div>
    </div>

    <div class="col-span-2">
      <label class="block mb-2.5 text-sm font-medium text-heading" for="file_input">Seleccionar imagen</label>
      <input id="image_task" type="file" name="image" accept="image/*" capture @change="onFileChange"
        class="block w-full max-w-md px-3 py-2.5 bg-inputbg mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
    </div>

    <div class="flex gap-4">
      <button type="submit"
        class="text-white bg-primary rounded-md box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base cursor-pointer text-sm px-4 py-2.5 hover:bg-gray-800 transition-colors focus:outline-none">
        Actualizar
      </button>
      <button type="button" @click.prevent="redirect"
        class="text-white bg-gray-400 rounded-md box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 cursor-pointer rounded-base text-sm px-4 hover:bg-gray-500 transition-colors py-2.5 focus:outline-none">
        Cancelar
      </button>
    </div>
  </form>
</template>
