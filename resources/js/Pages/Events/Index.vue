<template>
  <MainLayout>
    <div class="max-w-7xl mx-auto px-4 py-10">

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-[#0d2f6e] mb-1">Événements</h1>
        <p class="text-gray-500">Tous les événements du Dahira D.CH.KH.</p>
      </div>

      <!-- Filtres -->
      <div class="flex flex-wrap gap-2 mb-8">
        <Link
          :href="route('events.index')"
          class="px-4 py-2 rounded-full text-sm font-medium transition"
          :class="!filtre ? 'bg-[#0d2f6e] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
        >
          Tous
        </Link>
        <Link
          v-for="cat in categories"
          :key="cat"
          :href="route('events.index', { categorie: cat })"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition"
          :class="filtre === cat ? 'bg-[#0d2f6e] text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
        >
          <component :is="categorieIcone(cat)" class="w-3.5 h-3.5" />
          {{ cat }}
        </Link>
      </div>

      <!-- Grille -->
      <div v-if="events.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="event in events.data"
          :key="event.id"
          class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 border border-gray-100"
        >
          <!-- Image ou placeholder -->
          <div class="relative">
            <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.titre" class="w-full h-48 object-cover" />
            <div v-else class="w-full h-48 flex items-center justify-center" :class="categorieBg(event.categorie)">
              <component :is="categorieIcone(event.categorie)" class="w-14 h-14 opacity-80" :class="categorieIconColor(event.categorie)" />
            </div>
            <!-- Badge catégorie -->
            <span
              class="absolute top-3 left-3 inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full"
              :class="categorieCouleur(event.categorie)"
            >
              <component :is="categorieIcone(event.categorie)" class="w-3 h-3" />
              {{ event.categorie }}
            </span>
          </div>

          <div class="p-5">
            <h3 class="font-bold text-gray-800 text-base mb-3 line-clamp-2">{{ event.titre }}</h3>

            <div class="space-y-1.5">
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <CalendarDaysIcon class="w-4 h-4 text-[#0d2f6e] flex-shrink-0" />
                <span>{{ formatDate(event.date_event) }}</span>
              </div>
              <div v-if="event.heure_event" class="flex items-center gap-2 text-sm text-gray-500">
                <ClockIcon class="w-4 h-4 text-[#0d2f6e] flex-shrink-0" />
                <span>{{ event.heure_event }}</span>
              </div>
              <div v-if="event.lieu" class="flex items-center gap-2 text-sm text-gray-500">
                <MapPinIcon class="w-4 h-4 text-[#0d2f6e] flex-shrink-0" />
                <span>{{ event.lieu }}</span>
              </div>
            </div>

            <Link
              :href="route('events.show', event.id)"
              class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-[#0d2f6e] hover:gap-2.5 transition-all"
            >
              Voir les détails
              <ArrowRightIcon class="w-4 h-4" />
            </Link>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
        <CalendarDaysIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        <p class="text-gray-400">Aucun événement trouvé.</p>
      </div>

      <!-- Pagination -->
      <div v-if="events.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link
          v-for="page in events.links"
          :key="page.label"
          :href="page.url || '#'"
          v-html="page.label"
          class="px-3 py-2 rounded-lg text-sm border transition"
          :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-200'"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  CalendarDaysIcon,
  ClockIcon,
  MapPinIcon,
  ArrowRightIcon,
  MusicalNoteIcon,
  UserGroupIcon,
  StarIcon,
  ChatBubbleLeftRightIcon,
} from '@heroicons/vue/24/outline'

defineProps({
  events: Object,
  categories: Array,
  filtre: String,
})

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}

function categorieIcone(cat) {
  const map = {
    'Journees Khassaides': MusicalNoteIcon,
    'Dahira':              UserGroupIcon,
    'Ziar annuelle':       StarIcon,
    'Reunion':             ChatBubbleLeftRightIcon,
  }
  return map[cat] ?? CalendarDaysIcon
}

function categorieCouleur(cat) {
  const map = {
    'Journees Khassaides': 'bg-purple-100 text-purple-700',
    'Dahira':              'bg-yellow-100 text-yellow-700',
    'Ziar annuelle':       'bg-green-100 text-green-700',
    'Reunion':             'bg-blue-100 text-blue-700',
  }
  return map[cat] ?? 'bg-gray-100 text-gray-600'
}

function categorieBg(cat) {
  const map = {
    'Journees Khassaides': 'bg-purple-50',
    'Dahira':              'bg-yellow-50',
    'Ziar annuelle':       'bg-green-50',
    'Reunion':             'bg-blue-50',
  }
  return map[cat] ?? 'bg-gray-50'
}

function categorieIconColor(cat) {
  const map = {
    'Journees Khassaides': 'text-purple-300',
    'Dahira':              'text-yellow-300',
    'Ziar annuelle':       'text-green-300',
    'Reunion':             'text-blue-300',
  }
  return map[cat] ?? 'text-gray-300'
}
</script>
