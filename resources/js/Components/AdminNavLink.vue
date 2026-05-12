<template>
  <Link
    :href="href"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors"
    :class="isActive ? 'bg-white/20 text-white font-semibold' : 'text-blue-200 hover:text-white hover:bg-white/10'"
  >
    <component :is="icon" class="w-5 h-5 flex-shrink-0" />
    <span><slot /></span>
  </Link>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  href: String,
  icon: { type: Object, required: true },
})
const page = usePage()
const isActive = computed(() => page.url.startsWith(new URL(props.href, window.location.origin).pathname))
</script>
