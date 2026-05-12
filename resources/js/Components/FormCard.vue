<template>
  <div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
      <Link :href="back" class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-gray-800 hover:border-gray-300 transition shadow-sm">
        <ArrowLeftIcon class="w-4 h-4" />
      </Link>
      <div>
        <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
        <p v-if="subtitle" class="text-sm text-gray-500 mt-0.5">{{ subtitle }}</p>
      </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ArrowLeftIcon } from '@heroicons/vue/24/outline'
defineProps({ title: String, subtitle: String, back: String })
</script>
