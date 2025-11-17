<script setup lang="ts">
import { ref, watch } from 'vue';

  const props = defineProps<{
    show: boolean,
    onConfirm: () => void
  }>()

  const emit = defineEmits(["close"])
  const modalId = ref<boolean>(false)

  const close = () => emit("close")
  const confirm = () => {
    props.onConfirm()
    emit("close")
  }
  
  watch(() => props.show,
    (val) => modalId.value = val,
    { immediate: true }
  )
</script>

<template>

<div v-if="modalId" 
  id="popup-modal" 
  tabindex="-1" 
  class="overflow-y-auto overflow-x-hidden absolute top-20 left-80 mx-auto z-50 justify-center items-center w-full md:max-w-2xl xl:left-[600px] max-sm:left-0 h-[calc(100%-1rem)] max-h-full transition-all duration-150 faded"
>
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-navbarbg rounded-lg shadow-md border-primary">
            <button 
              type="button" 
              class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal"
              @click="close"
              >
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-red-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal font-sans text-gray-500">
                  Usted está seguro que desea eliminar este registro?
                </h3>
                <button 
                  type="button" 
                  class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
                  @click="confirm"
                >
                    Eliminar
                </button>
                <button 
                  type="button" 
                  class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                  @click="close"
                >
                  Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<style scoped>
#popup-modal .faded {
  transition: all;
  transition-duration: 1000ms;
  animation: ease-in-out;
  animation-duration: 1000hms;
}
</style>