<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import type { LoginFormInterface } from '@/types/auth'
import { onMounted, reactive } from 'vue'

const { login, loginWithGoogle } = useAuthStore()
const auth = useAuthStore()
const form = reactive<LoginFormInterface>({
  email: '',
  password: '',
})

onMounted(() => {
  if (!window.google) {
    console.error('Google API no cargó')
    return
  }

  /* global google */
  google.accounts.id.initialize({
    client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID as string,
    callback: handleGoogleResponse,
  })
  google.accounts.id.renderButton(document.getElementById('googleBtn'), {
    theme: 'outline',
    size: 'large',
    width: '500',
  })
})

interface GoogleResponse {
  credential: string
}

const handleGoogleResponse = async (response: GoogleResponse) => {
  if (auth.isLoggedIn) return
  const credential = response.credential
  await loginWithGoogle(credential)
}
</script>

<template>
  <div class="p-4 max-lg:p-2">
    <form
    class="max-w-xl mx-auto my-2 max-lg:my-1 px-8 py-6 bg-secondary rounded-sm shadow-lg"
    @submit.prevent="login(form)"
    >
    <fieldset>
      <legend
      class="font-semibold font-sans text-3xl mb-2 w-full bg-primary text-secondary px-3 py-3 text-center rounded-sm"
      >
      Iniciar sesión
    </legend>
      <div v-if="auth.errors.message" class="text-center text-danger font-sans text-lg font-semibold"> {{ auth.errors.message}}</div>
        <div class="row">
          <label
            for="email_login"
            class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color"
          >
            Email
          </label>
          <div class="col-sm-10">
            <input
              type="text"
              class="block w-full px-3 py-3 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
              id="email_login"
              placeholder="example@email.com"
              v-model="form.email"
              :aria-invalid="!!auth.errors.email?.[0]"
              @input="auth.clearErrors('email')"
              autocomplete="off"
            />
            <small class="text-danger font-sans" v-if="auth.errors.email?.[0]">{{ auth.errors.email?.[0] }}</small>
          </div>
        </div>
        <div>
          <label
            for="password_login"
            class="mt-6 mb-2 inline-block text-lg font-light text-a-light-color"
          >
            Contraseña
          </label>
          <input
            type="password"
            class="block w-full px-3 py-3 text-lg font-light text-a-light-color bg-inputbg border-0 rounded-lg focus:outline-faded"
            id="password_login"
            placeholder="Password"
            v-model="form.password"
            autocomplete="off"
            @input="auth.clearErrors('password')"
          />
          <small class="text-danger font-sans" v-if="auth.errors.password?.[0]">{{ auth.errors.password?.[0] }}</small>
        </div>

        <div class="flex items-center justify-start">
          <label class="mt-6 mb-2 mx-2 text-lg font-light text-a-light-color" for="check_login">
            Recuerdame
          </label>
          <input
            class="px-3 py-5 mt-6 mb-2 text-lg font-light text-a-light-color bg-inputbg border focus:border-navlink-colorH checked:shadow-lg checked:border-navlink-colorH"
            type="checkbox"
            value=""
            id="check_login"
          />
        </div>

        <button
          type="submit"
          class="text-secondary my-4 text-center w-44 bg-primary px-6 py-3 rounded-sm hover:bg-primaryH"
        >
          Acceder
        </button>

        <p>
          <router-link to="/register" class="text-blue-500 hover:text-blue-700"
            >¿No tienes una cuenta? Registrate</router-link
          >
        </p>
      </fieldset>

      <div id="googleBtn" class="mx-auto w-full max-w-sm my-3 max-sm:max-w-[100px]"></div>
    </form>
  </div>
</template>
