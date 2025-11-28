<script setup lang="ts">
import TabC from '@/components/tabC.vue'
import { useAuthStore } from '@/stores/useAuthStore'
import { onMounted, ref } from 'vue'

const auth = useAuthStore()
const dash = ref()

onMounted(async () => {
  dash.value = await auth.getDashboardData()
})
</script>

<template>
  <div v-if="dash">
    <div>
      <RouterLink :to="{ 'name': 'DashboardCreateUser' }">Crear Usuario</RouterLink>
    </div>
    <TabC>
      <template #dashboard>
        <!-- cantidad de usuarios -->
        <h1 class="my-2 font-semibold font-sans text-2xl">Cantidad Usuarios</h1>
        <span
          class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-brand-subtle text-secondary text-sm font-medium rounded bg-success">
          {{ dash.usuarios.total }}
        </span>
        <div class="flex flex-row bg-navbarbg px-1 py-1 rounded-sm shadow-sm w-full max-w-8xl mr-auto max-sm:flex-col"
          v-for="usuario in dash.usuarios.data" :key="usuario.id">
          <div class="flex w-full border border-faded rounded-sm">
            <p
              class="w-full p-2 border uppercase font-sans font-semibold text-sm text-center border-faded max-w-md mx-auto bg-gray-200">
              ID
            </p>
            <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ usuario.id }}</p>
          </div>
          <div class="flex w-full border border-faded rounded-sm">
            <p
              class="w-full p-2 border uppercase font-sans font-semibold text-sm text-center border-faded max-w-md mx-auto bg-gray-200">
              Nombre
            </p>
            <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ usuario.name }}</p>
          </div>
          <div class="flex w-full border border-faded rounded-sm">
            <p
              class="w-full p-2 border uppercase font-sans font-semibold text-sm text-center border-faded max-w-md mx-auto bg-gray-200">
              Email
            </p>
            <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ usuario.email }}</p>
          </div>
        </div>
        <!-- end cantidad de usuarios -->
      </template>

      <template #roles>
        <div class="flex gap-4 justify-evenly max-sm:flex-col">
          <!-- cantidad de roles creados -->
           <div>
             <h1 class="my-2 font-semibold font-sans text-2xl">Cantidad de roles creados</h1>
             <span
               class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-brand-subtle text-secondary text-sm font-medium rounded bg-success">
               {{ dash.roles.total }}
             </span>
             <div class="flex flex-row bg-navbarbg px-1 py-1 rounded-sm shadow-sm w-full max-w-md mr-auto max-sm:flex-col"
               v-for="rol in dash.roles.data" :key="rol.id">
               <div class="flex w-full border border-faded rounded-sm">
                 <p
                   class="w-full p-2 border uppercase font-sans font-semibold text-sm text-center border-faded max-w-md mx-auto bg-gray-200">
                   ID
                 </p>
                 <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ rol.id }}</p>
               </div>
               <div class="flex w-full border border-faded rounded-sm">
                 <p
                   class="w-full p-2 border uppercase font-sans font-semibold text-sm text-center border-faded max-w-md mx-auto bg-gray-200">
                   Nombre
                 </p>
                 <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ rol.name }}</p>
               </div>
             </div>
           </div>
          <!-- end cantidad de roles creados -->
  
          <div>
            <!-- cantidad de permisos creados  -->
            <h1 class="my-2 font-semibold font-sans text-2xl">Cantidad de permisos creados</h1>
            <span
              class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-brand-subtle text-secondary text-sm font-medium rounded bg-success">
              {{ dash.permisos.total }}
            </span>
            <div class="flex flex-row bg-navbarbg px-1 py-1 rounded-sm shadow-sm w-full max-w-lg mr-auto max-sm:flex-col"
              v-for="permiso in dash.permisos.data" :key="permiso.id">
              <div class="flex w-full border border-faded rounded-sm">
                <p
                  class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-sm mx-auto bg-gray-200">
                  ID
                </p>
                <p class="w-full p-2 font-sans max-w-sm text-sm text-center mx-auto">{{ permiso.id }}</p>
              </div>
              <div class="flex w-full border border-faded rounded-sm">
                <p
                  class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-xl mx-auto bg-gray-200">
                  Nombre
                </p>
                <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ permiso.name }}</p>
              </div>
            </div>
            <!-- end cantidad de permisos creados  -->
          </div>
        </div>
      </template>

      <template #tasks>
        <!-- tareas creadas por usuario  -->
        <h1 class="my-2 font-semibold font-sans text-2xl">Cantidad de tareas creadas según usuario</h1>
        <div class="flex flex-row bg-navbarbg px-1 py-1 rounded-sm shadow-sm w-full max-w-2xl mr-auto max-sm:flex-col"
          v-for="tarea in dash.tareas.por_usuario.cantidad" :key="tarea.id">
          <div class="flex w-full border border-faded rounded-sm">
            <p
              class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-sm mx-auto bg-gray-200">
              Usuario
            </p>
            <p class="w-full p-2 font-sans max-w-sm text-sm text-center mx-auto">
              {{ tarea.user.name }}
            </p>
          </div>
          <div class="flex w-full border border-faded rounded-sm">
            <p
              class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
              Cantidad de tareas
            </p>
            <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ tarea.total }}</p>
          </div>
        </div>
        <!-- end tareas creadas por usuario  -->

        <!-- tareas por estado -->
        <h1 class="my-2 font-semibold font-sans text-2xl">Cantidad de tareas según estado y usuario</h1>
        <div class="flex flex-row bg-navbarbg px-1 py-1 rounded-sm shadow-sm w-full max-w-8xl mr-auto max-sm:flex-col"
          v-for="tarea in dash.tareas.por_estado" :key="tarea.id">
          <!-- status in progress -->
          <div v-if="tarea.status === 'in_progress'" class="flex w-full max-w-8xl max-sm:flex-col">
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-sm mx-auto bg-gray-200">
                Usuario
              </p>
              <p class="w-full p-2 font-sans max-w-sm text-sm text-center mx-auto">
                {{ tarea.user.name }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Estado
              </p>
              <p class="w-full p-2 font-sans max-w-md text-sm text-center uppercase text-warning font-semibold mx-auto">
                {{ tarea.status }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Cantidad de tareas
              </p>
              <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ tarea.total }}</p>
            </div>
          </div>
          <!-- end status in progress -->

          <!-- status pending -->
          <div v-if="tarea.status === 'pending'" class="flex w-full max-w-8xl max-sm:flex-col">
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-sm mx-auto bg-gray-200">
                Usuario
              </p>
              <p class="w-full p-2 font-sans max-w-sm text-sm text-center mx-auto">
                {{ tarea.user.name }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Estado
              </p>
              <p class="w-full p-2 font-sans uppercase max-w-md text-sm text-center text-danger font-semibold mx-auto">
                {{ tarea.status }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Cantidad de tareas
              </p>
              <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ tarea.total }}</p>
            </div>
          </div>
          <!-- end status in pending -->

          <!-- status completed -->
          <div v-if="tarea.status === 'completed'" class="flex w-full max-w-8xl max-sm:flex-col">
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-sm mx-auto bg-gray-200">
                Usuario
              </p>
              <p class="w-full p-2 font-sans max-w-sm text-sm text-center mx-auto">
                {{ tarea.user.name }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Estado
              </p>
              <p class="w-full p-2 font-sans uppercase max-w-md text-sm text-center text-success font-semibold mx-auto">
                {{ tarea.status }}
              </p>
            </div>
            <div class="flex w-full border border-faded rounded-sm">
              <p
                class="w-full p-2 border uppercase font-sans font-semibold text-center text-sm border-faded max-w-md mx-auto bg-gray-200">
                Cantidad de tareas
              </p>
              <p class="w-full p-2 font-sans max-w-md text-sm text-center mx-auto">{{ tarea.total }}</p>
            </div>
          </div>
          <!-- end status in completed -->
        </div>
        <!-- end tareas por estado -->
      </template>
    </TabC>
  </div>

  <div v-if="dash" class="bg-navbarbg px-3 py-3">
  </div>
</template>
