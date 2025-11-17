<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore';
import { onMounted, ref } from 'vue';

const auth = useAuthStore()
const dash = ref()

onMounted(async () => {
  dash.value = await auth.getDashboardData()
})
</script>

<template>
  <div>
    
  </div>
  <div>
Cantidad Usuarios
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-brand-subtle text-secondary text-sm font-medium rounded bg-success">
  {{  dash.usuarios.total }}
</span>
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-default text-heading text-sm font-medium rounded bg-neutral-primary-soft">
  Alternative
</span>
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-default-medium text-heading text-sm font-medium rounded bg-neutral-secondary-medium">
  Gray
</span>
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-danger-subtle text-fg-danger-strong text-sm font-medium rounded bg-danger-soft">
  Danger
</span>
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-success-subtle text-fg-success-strong text-sm font-medium rounded bg-success-soft">
  Success
</span>
<span class="inline-flex items-center px-2 py-1 ring-1 ring-inset ring-warning-subtle text-fg-warning text-sm font-medium rounded bg-warning-soft">
  Warning
</span>

  </div>
  <div>
    {{  dash }}
  </div>

</template>