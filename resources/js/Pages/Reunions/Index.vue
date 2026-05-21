<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Réunions</h1>
      <p class="text-gray-500 mb-8">Réunions et assemblées du Dahira</p>

      <div v-if="reunions.data.length" class="space-y-4">
        <Link
          v-for="reunion in reunions.data"
          :key="reunion.id"
          :href="route('reunions.show', reunion.id)"
          class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition group"
        >
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="reunion.statut === 'terminee' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
                  {{ reunion.statut === 'terminee' ? 'Terminée' : 'Planifiée' }}
                </span>
              </div>
              <h2 class="font-semibold text-gray-800 group-hover:text-[#0d2f6e] transition truncate">{{ reunion.titre }}</h2>
              <div class="flex flex-wrap items-center gap-3 mt-2 text-sm text-gray-400">
                <span class="flex items-center gap-1">
                  <CalendarDaysIcon class="w-4 h-4" /> {{ formatDate(reunion.date_reunion) }}
                </span>
                <span v-if="reunion.lieu" class="flex items-center gap-1">
                  <MapPinIcon class="w-4 h-4" /> {{ reunion.lieu }}
                </span>
                <span v-if="reunion.heure_debut" class="flex items-center gap-1">
                  <ClockIcon class="w-4 h-4" /> {{ reunion.heure_debut }}
                </span>
              </div>
            </div>
            <ChevronRightIcon class="w-5 h-5 text-gray-300 group-hover:text-[#0d2f6e] flex-shrink-0 mt-1 transition" />
          </div>
        </Link>
      </div>

      <div v-else class="text-center text-gray-400 py-24">
        <ClipboardIcon class="w-14 h-14 mx-auto mb-4 text-gray-200" />
        <p>Aucune réunion disponible.</p>
      </div>

      <!-- Pagination -->
      <div v-if="reunions.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link v-for="page in reunions.links" :key="page.label" :href="page.url || '#'" v-html="page.label"
          class="px-3 py-2 rounded text-sm border transition"
          :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'" />
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import { CalendarDaysIcon, MapPinIcon, ClockIcon, ClipboardIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'

defineProps({ reunions: Object })

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
