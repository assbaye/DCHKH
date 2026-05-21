<template>
  <Link
    :href="href"
    class="flex items-center gap-2.5 rounded-lg text-sm transition-colors"
    :class="[
      indent ? 'px-2 py-1.5' : 'px-3 py-2.5',
      isActive ? 'bg-white/20 text-white font-semibold' : 'text-blue-200 hover:text-white hover:bg-white/10'
    ]"
  >
    <component :is="icon" class="flex-shrink-0" :class="indent ? 'w-3.5 h-3.5' : 'w-5 h-5'" />
    <span class="flex-1 flex items-center gap-1"><slot /></span>
  </Link>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  href:   String,
  icon:   { type: Object, required: true },
  indent: { type: Boolean, default: false },
})
const page = usePage()
const isActive = computed(() => page.url.startsWith(new URL(props.href, window.location.origin).pathname))
</script>
