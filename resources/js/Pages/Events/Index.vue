<template>
  <MainLayout>
    <div class="max-w-7xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Événements</h1>
      <p class="text-gray-500 mb-8">Tous les événements du Dahira D.CH.KH.</p>

      <!-- Filtres -->
      <div class="flex flex-wrap gap-2 mb-8">
        <Link
          :href="route('events.index')"
          class="px-4 py-2 rounded-full text-sm transition"
          :class="!filtre ? 'bg-[#0d2f6e] text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border'"
        >
          Tous
        </Link>
        <Link
          v-for="cat in categories"
          :key="cat"
          :href="route('events.index', { categorie: cat })"
          class="px-4 py-2 rounded-full text-sm transition"
          :class="filtre === cat ? 'bg-[#0d2f6e] text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border'"
        >
          {{ cat }}
        </Link>
      </div>

      <!-- Grille -->
      <div v-if="events.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="event in events.data"
          :key="event.id"
          class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition"
        >
          <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.titre" class="w-full h-44 object-cover" />
          <div v-else class="w-full h-44 bg-gradient-to-br from-[#0d2f6e] to-[#1a4db5] flex items-center justify-center text-white text-4xl">📅</div>

          <div class="p-5">
            <span
              class="inline-block text-xs px-2 py-1 rounded mb-3"
              :class="categorieCouleur(event.categorie)"
            >
              {{ event.categorie }}
            </span>
            <h3 class="font-bold text-gray-800 mb-2">{{ event.titre }}</h3>
            <p class="text-gray-500 text-sm mb-1">📅 {{ formatDate(event.date_event) }}</p>
            <p v-if="event.heure_event" class="text-gray-500 text-sm mb-1">🕐 {{ event.heure_event }}</p>
            <p v-if="event.lieu" class="text-gray-500 text-sm">📍 {{ event.lieu }}</p>
            <Link :href="route('events.show', event.id)" class="mt-4 inline-block text-[#0d2f6e] text-sm font-semibold hover:underline">
              Détails →
            </Link>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-400 py-16">
        Aucun événement trouvé.
      </div>

      <!-- Pagination -->
      <div v-if="events.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link
          v-for="page in events.links"
          :key="page.label"
          :href="page.url || '#'"
          v-html="page.label"
          class="px-3 py-2 rounded text-sm border transition"
          :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  events: Object,
  categories: Array,
  filtre: String,
})

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}

function categorieCouleur(cat) {
  const map = {
    Magal: 'bg-yellow-100 text-yellow-800',
    Reunion: 'bg-blue-100 text-blue-800',
    Collecte: 'bg-green-100 text-green-800',
    Khassaides: 'bg-purple-100 text-purple-800',
    Autre: 'bg-gray-100 text-gray-700',
  }
  return map[cat] ?? 'bg-gray-100 text-gray-700'
}
</script>
