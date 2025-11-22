<script setup lang="ts">
import { ref } from 'vue';


const sections = ref({
  dashboard: true,
  roles: false,
  tasks: false
})

type Sectionkey = keyof typeof sections.value
const open = (name: Sectionkey) => {
  Object.keys(sections.value).forEach((key) => {
    sections.value[key as Sectionkey] = key === name
  })
}

</script>

<template>

  <div class="md:flex min-h-dvh">
    <ul class="flex-column space-y space-y-4 text-sm font-medium text-body md:me-4 my-4 md:mb-0">
      <li>
        <a href="#" @click.prevent="open('dashboard')"
          class="inline-flex items-center px-4 py-3 rounded-base hover:text-heading hover:bg-neutral-secondary-soft w-full">
          <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.948 8.948 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
          </svg>
          Dashboard
        </a>
      </li>
      <li>
        <a href="#" @click.prevent="open('roles')"
          class="inline-flex items-center px-4 py-3 rounded-base hover:text-heading hover:bg-neutral-secondary-soft w-full">
          <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
          </svg>
          Roles Permisos
        </a>
      </li>
      <li>
        <a href="#" @click.prevent="open('tasks')"
          class="inline-flex items-center px-4 py-3 rounded-base hover:text-heading hover:bg-neutral-secondary-soft w-full">
          <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
            fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10 3v4a1 1 0 0 1-1 1H5m4 10v-2m3 2v-6m3 6v-3m4-11v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
          </svg>
          Tareas
        </a>
      </li>
    </ul>

    <!-- menu options -->
    <div v-if="sections.dashboard" class="p-6 bg-neutral-secondary text-medium text-body rounded-base w-full">
      <h3 class="text-lg font-semibold text-heading mb-4 uppercase">Dashboard</h3>
      <slot name="dashboard"></slot>
    </div>

    <div v-if="sections.roles" class="p-6 bg-neutral-secondary text-medium text-body rounded-base w-full">
      <h3 class="text-lg font-semibold text-heading mb-4 uppercase">Roles Permisos</h3>
      <slot name="roles"></slot>
    </div>

    <div v-if="sections.tasks" class="p-6 bg-neutral-secondary text-medium text-body rounded-base w-full">
      <h3 class="text-lg font-semibold text-heading mb-4 uppercase">Tareas</h3>
      <slot name="tasks"></slot>
    </div>
  </div>

</template>