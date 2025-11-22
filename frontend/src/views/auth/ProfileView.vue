<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import { storeToRefs } from 'pinia'

const auth = useAuthStore()
const { user } = storeToRefs(auth)
</script>

<template>
  <h1 class="text-5xl font-sans text-center p-4">Perfil</h1>
  <div class="max-w-md text-left mx-auto">
    <h3 class="text-2xl font-sans text-left px-4 pb-2">
      Bienvenid@!!
      <span class="text-info">{{ user?.name }}</span>
    </h3>
    <p class="text-faded text-xl font-sans px-4 pb-4">Aquí podrás ver tus datos personales.</p>
  </div>

  <div class="flex mb-8 mx-10 rounded-lg md:mb-12">
    <figure
      class="flex flex-col w-full max-w-xl mx-auto items-center justify-center p-8 text-center bg-white rounded-sm dark:bg-gray-700 dark:border-gray-700"
    >
      <blockquote class="max-w-2xl mx-auto mb-4 text-gray-500 lg:mb-8 dark:text-gray-400">
        <h3 class="text-2xl font-semibold text-info">Datos de perfil</h3>
      </blockquote>
      <figcaption class="flex items-center mr-auto justify-center">
        <div class="space-y-0.5 font-medium text-white uppercase text-left rtl:text-right ms-3">
          <div class="mb-4">
            <p class="text-xl">Nombre:</p>
            <div class="text-lg font-sans text-gray-500 dark:text-gray-400">{{ user?.name }}</div>
          </div>
          <div class="mb-4">
            <p class="text-xl">Email:</p>
            <div class="text-lg font-sans text-gray-500 dark:text-gray-400">{{ user?.email }}</div>
          </div>
          <div class="mb-4">
            <p class="text-xl">Rol:</p>
            <div class="text-lg font-sans text-gray-500 dark:text-gray-400">
              {{ user?.roles![0]?.name }}
            </div>
          </div>
          <!-- <div class="mb-4">
            <p class="text-xl">Permisos:</p>
            <div class="text-lg font-sans text-gray-500 dark:text-gray-400" v-for="permiso in user?.permissions" :key="permiso.id">
              {{ permiso.name }}
            </div>
          </div> -->
        </div>
      </figcaption>
    </figure>
  </div>
</template>
