<script setup lang="ts">
import { useAuthStore } from "@/stores/useAuthStore";
import { storeToRefs } from "pinia";
import { ref } from "vue";

const auth = useAuthStore()
const { user } = storeToRefs(auth)
const roles = auth.user?.roles

const isOpenMenu = ref(true)
const isOpenMenuMobile = ref(false)
const isOpenDropDown = ref(false)
const isOpenDropDownUser = ref(false)

const openMenu = () => {
  isOpenMenuMobile.value = !isOpenMenuMobile.value
  console.log(isOpenMenuMobile.value)
}

const closeMenus = () => {
  isOpenDropDown.value = false
  isOpenDropDownUser.value = false
  isOpenMenuMobile.value = false
}

const openDropDownUser = () => {
  isOpenDropDownUser.value = !isOpenDropDownUser.value
}

const logout = async () => {
  closeMenus()
  await auth.logout()
}
</script>

<template>
  <nav class="bg-navbarbg py-6 px-2 text-sm font-semibold uppercase">
    <div class="flex items-center justify-between max-lg:block">
      <a class="mr-8 text-xl font-sans font-medium" href="#">
        Gestión <span class="text-sky-600 font-medium">Tareas</span>
      </a>
      <button class="navbar-toggler items-center float-left absolute top-6 left-44 hidden max-lg:block" type="button" aria-controls="navbarColor04"
        aria-expanded="false" aria-label="Toggle navigation" @click="openMenu">
        <svg class="w-8 h-8 text-gray-800 outline-borderC focus:border-borderC" aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" strokeLinecap="round" strokeWidth="2" d="M5 7h14M5 12h14M5 17h14" />
        </svg>
      </button>
      <!-- menu principal -->
      <div v-if="isOpenMenu" class="navbar-collapse flex items-center font-sans font-light max-lg:hidden"
        id="navbarColor04">
        <ul class="flex p-0 py-2 me-auto font-semibold font-sans w-full max-lg:w-56">
          <li class="nav-item" v-if="auth.isLoggedIn && roles?.length && roles![0]?.name === 'admin'">
            <router-link to="/dashboard"
              v-if="auth.isLoggedIn"
              class="nav-link font-sans py-2 px-3 text-navlink-color hover:text-navlink-colorH active"
              @click="closeMenus"
            >
              Dashboard
            </router-link>
          </li>
          <li const class="nav-item">
            <router-link to="/products"
              v-if="auth.isLoggedIn"
              class="nav-link font-sans py-2 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Tareas
            </router-link>
          </li>
          <li class="nav-item" v-if="auth.isLoggedIn">
            <router-link to="/users" class="nav-link font-sans py-2 px-3 text-navlink-color hover:text-navlink-colorH"
              v-if="auth.isLoggedIn"
              @click="closeMenus"
          >
              Usuarios
            </router-link>
          </li>
          <li class="nav-item" v-if="!auth.isLoggedIn">
            <router-link to="/login" class="nav-link font-sans py-2 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
          >
              Iniciar sesión
            </router-link>
          </li>
          <li class="nav-item" v-if="!auth.isLoggedIn">
            <router-link to="/register"
              class="nav-link font-sans py-2 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Registrarse
            </router-link>
          </li>
        </ul>
      </div>
      <!-- end menu principal -->

      <!-- user managment -->
      <div class="relative max-w-xl float-right" v-if="auth.isLoggedIn">
        <button class="flex items-center max-w-32 px-3 py-2 bg-primary rounded-md hover:bg-primaryH transition-colors"
          @click="openDropDownUser">
          <p class="text-secondary text-md font-sans font-semibold uppercase hover:text-secondaryH transition-colors">
            {{ user?.name }}
          </p>
          <svg v-if="!isOpenDropDownUser" class="w-6 h-6 text-secondary dark:text-white" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m8 10 4 4 4-4" />
          </svg>

          <svg v-else class="w-6 h-6 text-secondary dark:text-white" aria-hidden="true"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m16 14-4-4-4 4" />
          </svg>
        </button>
        <div v-if="isOpenDropDownUser"
          class="navbar-collapse absolute bg-navbarbg rounded-md shadow-lg p-4 right-1 top-11 w-[14em] items-center font-sans font-light max-lg:flex"
          id="navbarColor04">
          <ul class="flex flex-col p-0 py-2 font-semibold font-sans w-auto">
            <li class="nav-item">
              <router-link @click="closeMenus" to="/profile"
                class="nav-link flex items-center gap-2 py-2 px-3 text-navlink-color hover:text-navlink-colorH active">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path fill="currentColor"
                    d="M15 7q-.425 0-.712-.288T14 6t.288-.712T15 5h6q.425 0 .713.288T22 6t-.288.713T21 7zm0 4q-.425 0-.712-.288T14 10t.288-.712T15 9h6q.425 0 .713.288T22 10t-.288.713T21 11zm0 4q-.425 0-.712-.288T14 14t.288-.712T15 13h6q.425 0 .713.288T22 14t-.288.713T21 15zm-7-1q-1.25 0-2.125-.875T5 11t.875-2.125T8 8t2.125.875T11 11t-.875 2.125T8 14m-6 5v-.9q0-.525.25-1t.7-.75q1.125-.675 2.388-1.012T8 15t2.663.338t2.387 1.012q.45.275.7.75t.25 1v.9q0 .425-.288.713T13 20H3q-.425 0-.712-.288T2 19m2.15-1h7.7q-.875-.5-1.85-.75T8 17t-2 .25t-1.85.75M8 12q.425 0 .713-.288T9 11t-.288-.712T8 10t-.712.288T7 11t.288.713T8 12m0 6" />
                </svg>
                Perfil
              </router-link>
            </li>
            <li const class="nav-item">
              <a @click="closeMenus"
                class="nav-link flex items-center gap-2 py-2 px-2 text-navlink-color hover:text-navlink-colorH"
                href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <g fill="none">
                    <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="2" />
                    <path fill="currentColor"
                      d="m5.399 5.88l.5-.867a1 1 0 0 0-1.234.186zM3.4 9.344l-.956-.295a1 1 0 0 0 .456 1.16zm-.002 5.311l-.5-.866a1 1 0 0 0-.455 1.162zm2 3.464l-.734.68a1 1 0 0 0 1.234.186zm4.6 2.655H9a1 1 0 0 0 .778.975zm4.001.002l.223.975a1 1 0 0 0 .777-.975zM18.6 18.12l-.5.866a1 1 0 0 0 1.233-.186zm1.998-3.466l.956.295a1 1 0 0 0-.456-1.16zm.002-5.311l.5.866a1 1 0 0 0 .455-1.162zm-2-3.465l.734-.679a1 1 0 0 0-1.234-.187zM14 3.225h1a1 1 0 0 0-.777-.975zm-4-.002l-.223-.975A1 1 0 0 0 9 3.223zm4 1.849h-1zm5 8.66l-.5.866zm-2 3.464l-.5.866zM5 13.732l.5.866zm2-6.928l-.5.866zM4.356 9.639a8 8 0 0 1 1.776-3.08L4.665 5.2a10 10 0 0 0-2.22 3.85zM5.072 16a8 8 0 0 1-.718-1.64l-1.91.592c.217.701.515 1.388.896 2.048zm1.06 1.441A8 8 0 0 1 5.073 16L3.34 17c.38.66.827 1.261 1.325 1.8zm7.646 2.361a8 8 0 0 1-3.556-.002l-.445 1.95a10 10 0 0 0 4.446.002zm5.866-5.441a8 8 0 0 1-1.776 3.08l1.467 1.36a10 10 0 0 0 2.22-3.85zM18.928 8c.306.53.545 1.08.718 1.64l1.91-.592A10 10 0 0 0 20.66 7zm-1.06-1.441c.397.43.754.91 1.06 1.441l1.732-1a10 10 0 0 0-1.325-1.8zm-7.646-2.361a8 8 0 0 1 3.556.002l.444-1.95a10 10 0 0 0-4.445-.002zm.778.874v-1.85H9v1.85zm-3.5.866l-1.601-.925l-1 1.732l1.6.925zm-3 6.928l-1.601.924l1 1.732l1.6-.924zm1-3.464l-1.6-.923l-1 1.732l1.6.923zM11 20.775v-1.847H9v1.847zM6.5 16.33l-1.601.925l1 1.732l1.6-.925zm12.601.925L17.5 16.33l-1 1.732l1.601.925zM15 20.777v-1.849h-2v1.85zm5.101-12.3l-1.601.925l1 1.732l1.601-.925zm.998 5.312l-1.599-.923l-1 1.732l1.6.923zM15 5.072V3.225h-2v1.847zm3.101-.059l-1.601.925l1 1.732l1.601-.925zM13 5.072c0 2.31 2.5 3.753 4.5 2.598l-1-1.732a1 1 0 0 1-1.5-.866zm5.5 4.33c-2 1.155-2 4.041 0 5.196l1-1.732a1 1 0 0 1 0-1.732zm-1 6.928c-2-1.154-4.5.289-4.5 2.598h2a1 1 0 0 1 1.5-.866zM11 18.928c0-2.31-2.5-3.753-4.5-2.598l1 1.732a1 1 0 0 1 1.5.866zm-5.5-4.33c2-1.155 2-4.041 0-5.196l-1 1.732a1 1 0 0 1 0 1.732zM9 5.072a1 1 0 0 1-1.5.866l-1 1.732c2 1.154 4.5-.289 4.5-2.598z" />
                  </g>
                </svg>
                Ajustes
              </a>
            </li>
            <div class="dropdown-divider my-1 h-1 border-light bg-light"></div>
            <li class="nav-item">
              <a href="/logout" @click.prevent="logout"
                class="nav-link flex items-center gap-2 py-2 px-2 text-navlink-color hover:text-navlink-colorH">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 1024 1024">
                  <path fill="currentColor"
                    d="M868 732h-70.3c-4.8 0-9.3 2.1-12.3 5.8c-7 8.5-14.5 16.7-22.4 24.5a353.8 353.8 0 0 1-112.7 75.9A352.8 352.8 0 0 1 512.4 866c-47.9 0-94.3-9.4-137.9-27.8a353.8 353.8 0 0 1-112.7-75.9a353.3 353.3 0 0 1-76-112.5C167.3 606.2 158 559.9 158 512s9.4-94.2 27.8-137.8c17.8-42.1 43.4-80 76-112.5s70.5-58.1 112.7-75.9c43.6-18.4 90-27.8 137.9-27.8s94.3 9.3 137.9 27.8c42.2 17.8 80.1 43.4 112.7 75.9c7.9 7.9 15.3 16.1 22.4 24.5c3 3.7 7.6 5.8 12.3 5.8H868c6.3 0 10.2-7 6.7-12.3C798 160.5 663.8 81.6 511.3 82C271.7 82.6 79.6 277.1 82 516.4C84.4 751.9 276.2 942 512.4 942c152.1 0 285.7-78.8 362.3-197.7c3.4-5.3-.4-12.3-6.7-12.3m88.9-226.3L815 393.7c-5.3-4.2-13-.4-13 6.3v76H488c-4.4 0-8 3.6-8 8v56c0 4.4 3.6 8 8 8h314v76c0 6.7 7.8 10.5 13 6.3l141.9-112a8 8 0 0 0 0-12.6" />
                </svg>
                Cerrar sesión
              </a>
            </li>
          </ul>
        </div>
      </div>
      <!-- / end user managment -->

      <!-- mobile version nab -->
      <div v-if="isOpenMenuMobile"
        class="navbar-collapse flex justify-between items-center font-sans font-light max-lg:flex" id="navbarColor04"
      >
        <ul class="flex flex-col px-0 py-6 font-semibold font-sans w-full max-lg:w-56">
          <li class="nav-item py-2 px-0">
            <router-link to="/dashboard"
              v-if="auth.isLoggedIn"
              class="nav-link font-sans py-6 px-3 text-navlink-color hover:text-navlink-colorH active"
              @click="closeMenus"
            >
              Dashboard
            </router-link>
          </li>
          <li const class="nav-item py-2 px-0">
            <router-link to="/products"
              v-if="auth.isLoggedIn"
              class="nav-link font-sans py-6 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Tareas
            </router-link>
          </li>
          <li class="nav-item py-2 px-0" v-if="auth.isLoggedIn">
            <router-link 
              v-if="auth.isLoggedIn"
              to="/users" 
              class="nav-link font-sans py-6 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Usuarios
            </router-link>
          </li>
          <li class="nav-item py-2 px-0" v-if="!auth.isLoggedIn">
            <router-link 
              to="/login" 
              class="nav-link font-sans py-6 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Iniciar sesión
            </router-link>
          </li>
          <li class="nav-item py-1 px-0" v-if="!auth.isLoggedIn">
            <router-link to="/register"
              class="nav-link font-sans py-6 px-3 text-navlink-color hover:text-navlink-colorH"
              @click="closeMenus"
            >
              Registrarse
            </router-link>
          </li>
        </ul>
      </div>
      <!-- end mobile version nab -->
    </div>
  </nav>
</template>
