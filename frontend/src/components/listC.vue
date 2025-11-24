<script setup lang="ts" generic="T extends Record<string,any>">

const props = defineProps<{
  items: T[],
}>() 

const emit = defineEmits<{
  (e: 'view', item: T) : void
  (e: 'edit', item: T) : void
  (e: 'delete', item: T) : void
}>()

</script>
<template>
  <ul role="list" class="">
    <li class="pb-4 sm:pb-4 bg-secondary px-3 py-3 rounded-sm shadow-sm my-2" v-for="item in items" :key="item.id">
      <div class="flex items-center gap-2">
        <div class="flex-1 min-w-0">
          <slot name="content" :item="item"></slot>
        </div>
        <div class="inline-flex items-center space-x-1">
          <slot 
            name="actions" 
            :item="item" 
            :onView="() => emit('view', item)"
            :onEdit="() => emit('edit', item)"
            :onDelete="() => emit('delete', item)"
          >
          </slot>
        </div>
      </div>
    </li>
  </ul>
</template>