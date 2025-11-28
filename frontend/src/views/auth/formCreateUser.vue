<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore'
import type { RegisterFormInterface, UserInterface } from '@/types/auth'
import { onMounted, reactive, ref } from 'vue'

const auth = useAuthStore()
const user = ref<UserInterface | null>(null)

interface createUserPayload extends RegisterFormInterface {
  image?: File | null
}

onMounted(async () => {
 user.value = await auth.getUserById(17)
})

const form = reactive<createUserPayload>({
  name: '',
  email: '',
  password: '',
  image: null,
  password_confirmation: '',
})

const crear = async (payload: createUserPayload) => {
  const formData = new FormData()
  formData.append('name', payload.name)
  formData.append('email', payload.email)         
  formData.append('password', payload.password)
  formData.append('image', payload.image ?? '')
  formData.append('password_confirmation', payload.password_confirmation)
  await auth.create(formData)
} 

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  if (target.files) {
    form.image = target.files?.[0] ?? null
  } else {
    form.image = null
  }
}

</script>

<template>
  <fieldset class="text-center font-semibold text-2xl mt-5">CREAR USUARIO</fieldset>
<form @submit.prevent="crear(form)" class="flex flex-col my-4 w-full max-w-lg mx-auto px-3 py-3 rounded-sm bg-secondary text-primary">
  <div class="flex flex-col w-full">
    <label for="name">Nombre</label>
    <input v-model="form.name" type="text" name="name" id="name" class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body" placeholder="Ingresar nombre..."/>
  </div>
  <div class="flex flex-col mt-4 w-full">
    <label for="email">Email</label>
    <input v-model="form.email" type="email" name="email" id="email" class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body" placeholder="Ingresar email..."/>    
  </div>
  <div class="block mt-4">
    <label for="password">Contraseña</label>
    <input v-model="form.password" type="password" name="password" id="password" class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body" placeholder="Ingresar contraseña..."/>    
  </div>
  <div class="block mt-4">
    <label for="password_confirmation">Confirmar Contraseña</label>
    <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password_confirmation" class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body" placeholder="Confirmar contraseña..."/>   
  </div>
  <div class="block mt-4">
    <label for="image">Seleccionar imagen de perfil</label>
    <input @change="onFileChange" type="file" name="image" id="image" accept="image/*" class="block w-full px-3 py-2.5 bg-inputbg text-heading text-sm rounded-sm focus:outline-none shadow-xs placeholder:text-body"/>  
  </div>
  <div class="flex justify-end mt-6">
    <button type="submit" class="px-4 py-2 bg-primary text-secondary text-sm cursor-pointer font-medium rounded-sm hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand">
      Registrar
    </button> 
  </div>
</form>

<div>
  {{ user }}
  <!-- <img :src="user.image_url" alt="image"> -->
</div>
</template>