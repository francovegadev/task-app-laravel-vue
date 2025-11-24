<script setup lang="ts">
import { getStatusColor } from '@/helpers/getStatusColor'
import { useTaskStore } from '@/stores/useTaskStore'
import { watch } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const taskStore = useTaskStore()

watch(
  () => route.params.id,
  async (id) => {
    await taskStore.getTask(Number(id))
  },
  { immediate: true },
)
</script>

<template>
  <section class="bg-neutral-primary" v-if="taskStore.task && taskStore.task.image?.path">
    <div class="py-8 px-4 mx-auto max-w-screen-2xl text-center lg:py-16">
      <h1 class="mb-6 text-4xl font-bold tracking-tighter text-heading md:text-5xl flex flex-col lg:text-6xl">
        Detalle de la tarea: <span class="text-faded">{{ taskStore.task.title }}</span>
      </h1>
      <div class="flex flex-col bg-navbarbg px-4 py-4 rounded-sm shadow-sm w-full max-w-2xl mx-auto">
        <div class="flex w-full border border-faded justify-center rounded-sm">
          <figure class="max-w-lg">
            <img class="h-48 max-w-full rounded-sm" :src="taskStore.task.image.path"
              alt="image description">
          </figure>
        </div>

        <div class="flex w-full border border-faded rounded-sm">
          <p
            class="w-full p-2 border uppercase font-sans font-semibold text-lg border-faded max-w-lg mx-auto bg-gray-200">
            Título
          </p>
          <p class="w-full p-2 font-sans max-w-lg text-lg mx-auto">{{ taskStore.task.title }}</p>
        </div>
        <div class="flex justify-center w-full border border-faded rounded-sm">
          <p
            class="w-full p-2 border flex items-center justify-center font-sans font-semibold uppercase text-lg border-faded max-w-lg mx-auto bg-gray-200">
            Descripción
          </p>
          <p class="w-full p-2 font-sans max-w-lg text-lg mx-auto">
            {{ taskStore.task.description }}
          </p>
        </div>
        <div class="flex w-full border border-faded rounded-sm">
          <p
            class="w-full p-2 border font-sans font-semibold uppercase text-lg border-faded max-w-lg mx-auto bg-gray-200">
            Fecha de vencimiento
          </p>
          <p class="w-full p-2 font-sans max-w-lg text-lg mx-auto">{{ taskStore.task.due_date }}</p>
        </div>
        <div class="flex w-full border border-faded rounded-sm">
          <p
            class="w-full p-2 border font-sans font-semibold uppercase text-lg border-faded max-w-lg mx-auto bg-gray-200">
            Estado
          </p>
          <p :class="[
            'w-full p-2 font-sans max-w-lg text-lg mx-auto font-semibold',
            getStatusColor(taskStore.task.status),
          ]">
            {{ taskStore.task.status }}
          </p>
        </div>
        <div class="flex w-full border border-faded rounded-sm">
          <p
            class="w-full p-2 border font-sans font-semibold uppercase text-lg border-faded max-w-lg mx-auto bg-gray-200">
            Usuario
          </p>
          <p class="w-full p-2 font-sans max-w-lg text-lg mx-auto">
            {{ taskStore.task.user?.name }}
          </p>
        </div>
      </div>
      <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0 md:space-x-4 my-4">
        <router-link :to="{ name: 'tasksView' }"
          class="inline-flex items-center justify-center text-white bg-primary hover:bg-gray-700 box-border border border-transparent transition-colors focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-base px-5 py-3 focus:outline-none">
          Volver
        </router-link>
      </div>
    </div>
  </section>
  <section v-else>
  <div class="flex items-center justify-center h-96">
    <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16">Loading</div>
  </div>
  </section>>
</template>

