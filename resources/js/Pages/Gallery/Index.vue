<template>
  <MainLayout>
    <div class="max-w-7xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Galerie</h1>
      <p class="text-gray-500 mb-8">Photos et vidéos des activités du Dahira, organisées par album</p>

      <div v-if="albums.length" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <Link
          v-for="album in albums"
          :key="album.id"
          :href="route('gallery.show', album.id)"
          class="group rounded-2xl overflow-hidden shadow-sm border border-gray-100 bg-white hover:shadow-md transition"
        >
          <div class="relative aspect-video bg-gray-100 overflow-hidden">
            <img
              v-if="album.cover_image"
              :src="`/storage/${album.cover_image}`"
              :alt="album.nom"
              class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <FolderOpenIcon class="w-16 h-16 text-gray-200" />
            </div>
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition" />
          </div>
          <div class="p-4">
            <h2 class="font-semibold text-gray-800 truncate">{{ album.nom }}</h2>
            <p v-if="album.description" class="text-xs text-gray-400 mt-1 line-clamp-2">{{ album.description }}</p>
            <div class="flex items-center gap-3 mt-3 text-xs text-gray-400">
              <span v-if="album.photos_count" class="flex items-center gap-1">
                <PhotoIcon class="w-3.5 h-3.5" /> {{ album.photos_count }} photo{{ album.photos_count > 1 ? 's' : '' }}
              </span>
              <span v-if="album.videos_count" class="flex items-center gap-1">
                <VideoCameraIcon class="w-3.5 h-3.5" /> {{ album.videos_count }} vidéo{{ album.videos_count > 1 ? 's' : '' }}
              </span>
              <span v-if="!album.items_count" class="italic">Vide</span>
            </div>
          </div>
        </Link>
      </div>

      <div v-else class="text-center text-gray-400 py-24">
        <FolderOpenIcon class="w-16 h-16 mx-auto mb-4 text-gray-200" />
        <p>Aucun album disponible pour le moment.</p>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import { FolderOpenIcon, PhotoIcon, VideoCameraIcon } from '@heroicons/vue/24/outline'

defineProps({
  albums: Array,
})
</script>
