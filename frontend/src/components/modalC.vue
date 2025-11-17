<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
  show: boolean,
  title: string
}>()
const emit = defineEmits(["close"])
const isOpen = ref<boolean>(false)

const close = () => emit('close')

watch(
  () => props.show,
  (val) => isOpen.value = val,
  { immediate: true }
)

</script>

<template>
<div v-if="isOpen" id="modalC" tabindex="-1" aria-hidden="true" class="overflow-y-auto overflow-x-hidden fixed mt-18 top-10 right-0 left-0 z-50 justify-center items-center w-full max-w-xl mx-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-secondary rounded-base shadow-sm p-4 md:p-6">
            <div class="flex items-center justify-between border-b border-default pb-4 md:pb-5">
                <h3 class="text-lg font-medium text-heading">
                    <slot name="header"></slot>
                    {{ props.title }}
                </h3>
                <button 
                  type="button" 
                  class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center rounded-sm items-center hover:bg-gray-200"
                  @click.prevent="close"
                >
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <slot name="body"></slot>
        </div>
    </div>
</div> 
</template>