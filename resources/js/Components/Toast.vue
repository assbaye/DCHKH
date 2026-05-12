<template>
  <Teleport to="body">
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-2 pointer-events-none" style="max-width: 360px">
      <Transition
        v-for="t in toasts"
        :key="t.id"
        enter-from-class="opacity-0 translate-x-8"
        enter-active-class="transition duration-300"
        leave-to-class="opacity-0 translate-x-8"
        leave-active-class="transition duration-200"
      >
        <div
          class="pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-xl shadow-lg border text-sm font-medium"
          :class="t.type === 'success'
            ? 'bg-white border-green-200 text-green-800'
            : 'bg-white border-red-200 text-red-800'"
        >
          <CheckCircleIcon v-if="t.type === 'success'" class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" />
          <XCircleIcon v-else class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" />
          <span class="flex-1">{{ t.message }}</span>
          <button @click="remove(t.id)" class="text-gray-400 hover:text-gray-600 transition ml-1">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </Transition>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const page = usePage()
const toasts = ref([])
let nextId = 0

function add(message, type = 'success') {
  const id = ++nextId
  toasts.value.push({ id, message, type })
  setTimeout(() => remove(id), 4000)
}

function remove(id) {
  toasts.value = toasts.value.filter(t => t.id !== id)
}

watch(() => page.props.flash?.success, val => { if (val) add(val, 'success') })
watch(() => page.props.flash?.error,   val => { if (val) add(val, 'error') })
</script>
