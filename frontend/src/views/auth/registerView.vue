<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import type { RegisterFormInterface } from '@/types/auth'
import { reactive, ref } from 'vue'

const form = reactive<RegisterFormInterface>({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = ref({
  name: [],
  email: [],
  password: [],
  password_confirmation: [],
})

const { register } = useAuthStore()
</script>

<template>
  <div class="max-w-2xl p-4 mx-auto">
    <form
      @submit.prevent="register(form)"
      class="mx-auto my-6 px-6 py-6 bg-secondary rounded-lg shadow-lg"
    >
      <fieldset>
        <legend
          class="font-semibold font-sans text-4xl bg-primary px-3 py-3 text-secondary mb-2 w-full text-center rounded-sm"
        >
          Registrar usuario
        </legend>
        <div class="row">
          <label for="name" class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color">
            Nombre
          </label>
          <input
            type="text"
            name="name"
            class="block w-full px-3 py-5 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
            placeholder="Ingresar nombre"
            label="Nombre"
            forLabel="nombre"
            v-model="form.name"
            :errors="errors.name"
          />
        </div>

        <div class="row">
          <label for="email" class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color">
            Email
          </label>
          <input
            type="text"
            name="email"
            class="block w-full px-3 py-5 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
            placeholder="ingresar email"
            label="Email"
            forLabel="email"
            v-model="form.email"
            :errors="errors.email"
          />
        </div>

        <div class="row">
          <label
            for="password"
            class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color"
          >
            Contraseña
          </label>
          <input
            type="password"
            name="password"
            placeholder="ingresar contraseña"
            label="Contraseña"
            class="block w-full px-3 py-5 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
            forLabel="contraseña"
            v-model="form.password"
            :errors="errors.password"
          />
        </div>

        <div class="row">
          <label
            for="password_confirm"
            class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color"
          >
            Confirmar Contraseña
          </label>
          <input
            type="password"
            name="password_confirm"
            placeholder="confirmar contraseña"
            label="Confirmar Contraseña"
            class="block w-full px-3 py-5 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
            forLabel="confirmarcontraseña"
            v-model="form.password_confirmation"
            :errors="errors.password_confirmation"
          />
        </div>

        <button
          type="submit"
          class="text-a-primary-bg my-4 rounded-md text-secondary text-center w-44 bg-primary px-6 py-3 hover:bg-primaryH"
        >
          Registrarse
        </button>
        <p>
          <router-link to="/login" class="text-blue-500 hover:text-blue-700"
            >¿Ya tienes una cuenta?</router-link
          >
        </p>
      </fieldset>
    </form>
  </div>
</template>
