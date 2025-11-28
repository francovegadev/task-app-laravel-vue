<script setup lang="ts">
const searchValue = defineModel<string>('searchValue')
const selectedStatus = defineModel<string>('selectedStatus')
</script>

<template>
  <!-- select status tasks -->
  <div>
    <label for="searchValue" class="text-md font-sans font-semibold">Filtrar tareas</label>
    <input
      type="text"
      class="px-3 py-3 bg-inputbg rounded-sm mb-6 mt-3 w-full border-0 shadow-sm focus-visible:outline-none"
      name="searchValue"
      id="searchValue"
      v-model="searchValue"
      placeholder="Buscar tarea.."
    />
    <label for="selectedStatus" class="text-md font-sans font-semibold">Filtrar por estado</label>
    <select
      v-model="selectedStatus"
      class="px-3 py-3 bg-inputbg rounded-sm mb-6 mt-3 w-full border-0 shadow-sm focus-visible:outline-none"
    >
      <option value="">Todos</option>
      <option value="in_progress">En proceso</option>
      <option value="pending">Pendiente</option>
      <option value="completed">Completada</option>
    </select>
  </div>
  <!-- end select status tasks -->
</template>
