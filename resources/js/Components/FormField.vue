<template>
  <div>
    <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1.5">
      {{ label }} <span v-if="required" class="text-red-500">*</span>
    </label>
    <slot />
    <p v-if="error" class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
      <ExclamationCircleIcon class="w-3.5 h-3.5 flex-shrink-0" />
      {{ error }}
    </p>
    <p v-if="hint" class="mt-1.5 text-xs text-gray-400">{{ hint }}</p>
  </div>
</template>

<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline'
defineProps({ label: String, id: String, error: String, hint: String, required: Boolean })
</script>
