<template>
  <div>
    <button
      @click="$emit('toggle')"
      class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition text-blue-200 hover:bg-white/10 hover:text-white"
    >
      <component :is="icon" class="w-4 h-4 flex-shrink-0" />
      <span class="flex-1 text-left font-medium">{{ label }}</span>
      <ChevronDownIcon class="w-3.5 h-3.5 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" />
    </button>
    <div v-if="open" class="mt-0.5 ml-3 border-l border-blue-700/50 pl-2 space-y-0.5">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ChevronDownIcon } from '@heroicons/vue/24/outline'

defineProps({
  label: String,
  icon:  Object,
  open:  Boolean,
})
defineEmits(['toggle'])
</script>
