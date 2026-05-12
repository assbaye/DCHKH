<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto px-4 py-10">
      <Link :href="route('events.index')" class="text-[#c9973a] text-sm hover:underline mb-6 inline-block">← Retour aux événements</Link>

      <div class="bg-white rounded-2xl shadow-md overflow-hidden">
        <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.titre" class="w-full h-64 object-cover" />
        <div v-else class="w-full h-48 bg-gradient-to-br from-[#0d2f6e] to-[#1a4db5] flex items-center justify-center text-white text-6xl">📅</div>

        <div class="p-8">
          <span class="inline-block text-xs px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 mb-4">{{ event.categorie }}</span>
          <h1 class="text-3xl font-bold text-[#0d2f6e] mb-4">{{ event.titre }}</h1>

          <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
            <div class="flex items-center gap-2">
              <span>📅</span>
              <span>{{ formatDate(event.date_event) }}</span>
            </div>
            <div v-if="event.heure_event" class="flex items-center gap-2">
              <span>🕐</span>
              <span>{{ event.heure_event }}</span>
            </div>
            <div v-if="event.lieu" class="flex items-center gap-2">
              <span>📍</span>
              <span>{{ event.lieu }}</span>
            </div>
          </div>

          <div v-if="event.description" class="text-gray-700 leading-relaxed mb-8 whitespace-pre-wrap">
            {{ event.description }}
          </div>

          <!-- Galerie liée -->
          <div v-if="event.galerie?.length" class="mt-8">
            <h2 class="text-xl font-bold text-[#0d2f6e] mb-4">Photos de l'événement</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
              <img
                v-for="item in event.galerie.filter(i => i.type === 'photo')"
                :key="item.id"
                :src="`/storage/${item.fichier}`"
                :alt="item.titre"
                class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'

defineProps({ event: Object })

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
