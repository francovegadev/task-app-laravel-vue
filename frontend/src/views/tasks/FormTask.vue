<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore';
import { useTaskStore } from '@/stores/useTaskStore';
import { type UserInterface } from '@/types/auth';
import type { TasksFormInterface } from '@/types/tasks';
import { computed, ref, watch } from 'vue';

const taskStore = useTaskStore()
const auth = useAuthStore()

const props = defineProps<{
  buttonName: string,
  title: string,
  modelValue?: TasksFormInterface | null
}>()

const emit = defineEmits(["update:modelValue", 'submit'])

const internalForm = ref<TasksFormInterface | null>(null)

const is_admin = computed(() => {
  console.log(auth.user?.roles?.[0]?.name === 'admin');
  return auth.user?.roles?.[0]?.name === 'admin' ? true : false
})

const users = computed<UserInterface[]>(() => {
  return auth.users
})

if (props.modelValue) {
  internalForm.value = props.modelValue
}

watch(
  () => props.modelValue,
  (value) => {
    if (internalForm.value) {
      internalForm.value = value ?? null
    }
  }
)

watch(
  internalForm,
  (val) => emit("update:modelValue", val),
  { deep: true }
)

const today = computed(() => {
  return new Date().toLocaleDateString("es-ES") ?? new Date().toISOString().split('T')[0]
})

const handleSubmit = () => {
  if (!internalForm.value) return
  emit("submit", internalForm.value)
}
</script>

<template>
  <form v-if="internalForm" class="max-w-md mx-auto" @submit.prevent="handleSubmit">
    <fieldset>
      <h5
        class="text-xl font-semibold text-heading text-center uppercase bg-primary rounded-sm px-3 py-3 text-secondary mb-6">
        {{ props.title }}
      </h5>
    </fieldset>
    <div class="relative z-0 w-full mb-5 group">
      <input type="title" name="floating_title" id="floating_title"
        class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
        v-model="internalForm.title" placeholder=" " required />
      <p class="text-danger text-sm capitalize" v-for="(error, idx) in taskStore.errors" :key="idx">{{ error }}</p>
      <label for="floating_title"
        class="absolute text-md font-sans text-body duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-left peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">
        Título
      </label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <textarea name="description" id="description" rows="4"
        class="block py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
        v-model="internalForm.description" placeholder=" " required>
      </textarea>
      <label for="description"
        class="absolute text-md font-sans text-body duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-left peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">
        Descripción
      </label>
    </div>
    <div class="relative z-0 w-full mb-5 group">
      <label for="floating_due_date"
        class="absolute text-md font-sans text-body duration-300 transform origin-left -translate-y-6 scale-75 top-3 -z-10 peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">
        Fecha vencimiento
      </label>
      <input type="date" name="due_date" id="floating_due_date" v-model="internalForm.due_date" :min="today"
        class="block mt-5 py-2.5 px-0 w-full text-sm text-heading bg-transparent border-0 border-b-2 border-default-medium appearance-none focus:outline-none focus:ring-0 focus:border-brand peer"
        placeholder=" " required />
    </div>
    <div class="flex w-full">
      <div class="relative z-0 w-full mb-5 group">
        <label for="status"
          class="absolute text-md font-sans mb-5 text-body duration-300 transform origin-left -translate-y-6 scale-75 top-3 -z-10 peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">
          Estado
        </label>
        <select id="status" v-model="internalForm.status"
          class="block w-full px-3 py-2.5 bg-neutral-secondary-medium mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
          <option value="" disabled selected>Seleccionar estado</option>
          <option :value="task.in_progress" v-for="(task, idx) in taskStore.status" :key="idx">
            {{ task }}
          </option>
        </select>
      </div>
    </div>

    <input type="hidden" name="user_id" v-model="internalForm.user_id">
    <!-- <div class="flex w-full">
      <div class="relative z-0 w-full mb-5 group">
        <label for="status"
          class="absolute text-md font-sans mb-5 text-body duration-300 transform origin-left -translate-y-6 scale-75 top-3 -z-10 peer-focus:start-0 peer-focus:text-fg-brand peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">
          Asignar a usuario
        </label>
        <select id="user_id" name="user_id" v-model="internalForm.user_id"
          class="block w-full px-3 py-2.5 bg-neutral-secondary-medium mt-5 text-heading text-sm rounded-base focus:ring-brand focus:border-brand shadow-xs placeholder:text-body">
          <option value="" disabled selected>Seleccionar usuario</option>
          <option :value="user.id" v-for="user in users" :key="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>
    </div> -->

    <div class="flex gap-4">
      <button type="submit"
        class="text-white bg-primary rounded-md box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base cursor-pointer text-sm px-4 py-2.5 hover:bg-gray-900 transition-colors focus:outline-none">
        {{ buttonName || 'Crear' }}
      </button>
      <button type="button"
        class="text-white bg-gray-400 rounded-md box-border border border-transparent hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 cursor-pointer rounded-base text-sm px-4 hover:bg-gray-500 transition-colors py-2.5 focus:outline-none">
        Cancelar
      </button>
    </div>
  </form>

</template>