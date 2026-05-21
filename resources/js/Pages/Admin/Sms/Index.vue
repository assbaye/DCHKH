<template>
  <AdminLayout title="Historique SMS">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <DevicePhoneMobileIcon class="w-6 h-6 text-[#0d2f6e]" />
        Historique des SMS
      </h1>
      <button @click="showModal = true" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition">
        <PaperAirplaneIcon class="w-4 h-4" />
        Nouveau SMS
      </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Date</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Type</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Message</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Envoyé par</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Résultats</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="log in logs.data" :key="log.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">{{ formatDate(log.created_at) }}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="typeBadge(log.type)">{{ log.type }}</span>
            </td>
            <td class="px-5 py-4 text-gray-800 max-w-xs truncate">{{ log.message }}</td>
            <td class="px-5 py-4 text-gray-600 whitespace-nowrap">
              {{ log.expediteur?.name ?? '—' }}
            </td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
              <span class="text-green-600 font-medium">{{ log.nb_succes }} ✓</span>
              <span v-if="log.nb_echecs" class="text-red-500 font-medium ml-2">{{ log.nb_echecs }} ✗</span>
              <span class="text-gray-400 ml-2">/ {{ log.nb_destinataires }}</span>
            </td>
          </tr>
          <tr v-if="!logs.data?.length">
            <td colspan="5" class="px-5 py-10 text-center text-gray-400">Aucun SMS envoyé pour l'instant.</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="logs.last_page > 1" class="flex items-center justify-between px-5 py-4 border-t border-gray-100">
        <span class="text-sm text-gray-500">{{ logs.total }} envoi(s) au total</span>
        <div class="flex gap-2">
          <Link
            v-for="link in logs.links"
            :key="link.label"
            :href="link.url ?? '#'"
            v-html="link.label"
            class="px-3 py-1 rounded text-sm border transition"
            :class="link.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'text-gray-600 border-gray-200 hover:bg-gray-50'"
          />
        </div>
      </div>
    </div>

    <SmsModal :show="showModal" type="autre" @close="showModal = false" />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SmsModal from '@/Components/SmsModal.vue'
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { DevicePhoneMobileIcon, PaperAirplaneIcon } from '@heroicons/vue/24/outline'

defineProps({ logs: Object })

const showModal = ref(false)

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function typeBadge(type) {
  const map = {
    reunion:    'bg-blue-100 text-blue-700',
    cotisation: 'bg-green-100 text-green-700',
    autre:      'bg-gray-100 text-gray-600',
  }
  return map[type] ?? map.autre
}
</script>
