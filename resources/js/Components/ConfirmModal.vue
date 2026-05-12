<template>
  <Teleport to="body">
    <Transition
      enter-from-class="opacity-0"
      enter-active-class="transition duration-200"
      leave-to-class="opacity-0"
      leave-active-class="transition duration-150"
    >
      <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" @click="$emit('cancel')" />
        <div class="relative bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 z-10">
          <!-- Icône -->
          <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <ExclamationTriangleIcon class="w-6 h-6 text-red-600" />
          </div>

          <h3 class="text-center font-bold text-gray-800 text-lg mb-2">{{ title }}</h3>
          <p class="text-center text-sm text-gray-500 mb-6">{{ message }}</p>

          <div class="flex gap-3">
            <button
              @click="$emit('cancel')"
              class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition"
            >
              Annuler
            </button>
            <button
              @click="$emit('confirm')"
              class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-xl text-sm font-semibold transition"
            >
              {{ confirmLabel }}
            </button>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline'

defineProps({
  show:         { type: Boolean, default: false },
  title:        { type: String, default: 'Confirmer la suppression' },
  message:      { type: String, default: 'Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?' },
  confirmLabel: { type: String, default: 'Supprimer' },
})

defineEmits(['confirm', 'cancel'])
</script>
