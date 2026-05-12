<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    href:   { type: String, required: true },
    active: { type: Boolean, default: false },
})
</script>

<template>
    <Link
        :href="href"
        class="px-4 py-2 rounded-md text-sm font-medium transition-all duration-150"
        :class="active
            ? 'bg-white text-[#0d2f6e] font-semibold shadow-sm'
            : 'text-white hover:bg-white/15 hover:text-white'"
    >
        <slot />
    </Link>
</template>
