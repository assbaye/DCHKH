<template>
  <MainLayout>
    <div class="max-w-7xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Galerie</h1>
      <p class="text-gray-500 mb-8">Photos et vidéos des activités du Dahira</p>

      <!-- Filtres -->
      <div class="flex flex-wrap gap-3 mb-8">
        <Link :href="route('gallery.index')" class="px-4 py-2 rounded-full text-sm border transition" :class="!filtre_type && !filtre_album ? 'bg-[#0d2f6e] text-white' : 'bg-white text-gray-600 hover:bg-gray-100'">Tout</Link>
        <Link :href="route('gallery.index', { type: 'photo' })" class="px-4 py-2 rounded-full text-sm border transition" :class="filtre_type === 'photo' ? 'bg-[#0d2f6e] text-white' : 'bg-white text-gray-600 hover:bg-gray-100'">📷 Photos</Link>
        <Link :href="route('gallery.index', { type: 'video' })" class="px-4 py-2 rounded-full text-sm border transition" :class="filtre_type === 'video' ? 'bg-[#0d2f6e] text-white' : 'bg-white text-gray-600 hover:bg-gray-100'">🎬 Vidéos</Link>
        <Link
          v-for="album in albums"
          :key="album"
          :href="route('gallery.index', { album })"
          class="px-4 py-2 rounded-full text-sm border transition"
          :class="filtre_album === album ? 'bg-[#c9973a] text-white border-[#c9973a]' : 'bg-white text-gray-600 hover:bg-gray-100'"
        >
          {{ album }}
        </Link>
      </div>

      <!-- Grille photos -->
      <div v-if="items.data.length" class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
        <div
          v-for="item in items.data"
          :key="item.id"
          class="break-inside-avoid"
        >
          <div
            v-if="item.type === 'photo'"
            class="relative group cursor-pointer rounded-xl overflow-hidden shadow-sm"
            @click="ouvrirLightbox(item)"
          >
            <img :src="`/storage/${item.fichier}`" :alt="item.titre" class="w-full object-cover group-hover:scale-105 transition duration-300" />
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition flex items-center justify-center">
              <span class="text-white opacity-0 group-hover:opacity-100 text-2xl transition">🔍</span>
            </div>
          </div>

          <div v-else class="rounded-xl overflow-hidden shadow-sm bg-black">
            <div v-if="item.url_video?.includes('youtube')" class="aspect-video">
              <iframe :src="youtubeEmbed(item.url_video)" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            </div>
            <div v-else class="aspect-video bg-gray-800 flex items-center justify-center text-gray-400">Vidéo non disponible</div>
          </div>

          <p v-if="item.titre" class="text-xs text-gray-500 mt-1 px-1 truncate">{{ item.titre }}</p>
        </div>
      </div>

      <div v-else class="text-center text-gray-400 py-16">Aucun élément dans la galerie.</div>

      <!-- Lightbox -->
      <div v-if="lightboxItem" class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" @click.self="lightboxItem = null">
        <button @click="lightboxItem = null" class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300">✕</button>
        <img :src="`/storage/${lightboxItem.fichier}`" :alt="lightboxItem.titre" class="max-w-full max-h-[90vh] rounded-lg object-contain" />
        <p v-if="lightboxItem.titre" class="absolute bottom-6 text-white text-sm">{{ lightboxItem.titre }}</p>
      </div>

      <!-- Pagination -->
      <div v-if="items.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link
          v-for="page in items.links"
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
import { ref } from 'vue'

defineProps({
  items: Object,
  albums: Array,
  filtre_type: String,
  filtre_album: String,
})

const lightboxItem = ref(null)

function ouvrirLightbox(item) {
  lightboxItem.value = item
}

function youtubeEmbed(url) {
  const id = url.match(/(?:v=|youtu\.be\/)([^&\s]+)/)?.[1]
  return id ? `https://www.youtube.com/embed/${id}` : url
}
</script>
