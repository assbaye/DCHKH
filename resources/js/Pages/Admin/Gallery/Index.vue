<template>
  <AdminLayout title="Gestion de la galerie">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ items.total }} élément(s)</p>
      <Link :href="route('admin.galerie.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Ajouter un média
      </Link>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="item in items.data" :key="item.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="relative aspect-square bg-gray-100">
          <img v-if="item.type === 'photo' && item.fichier" :src="`/storage/${item.fichier}`" :alt="item.titre" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full flex items-center justify-center">
            <VideoCameraIcon class="w-10 h-10 text-gray-300" />
          </div>
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
            <Link :href="route('admin.galerie.edit', item.id)" class="p-2 bg-white rounded-lg text-amber-700 hover:bg-amber-50 transition">
              <PencilSquareIcon class="w-4 h-4" />
            </Link>
            <button @click="supprimer(item)" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50 transition">
              <TrashIcon class="w-4 h-4" />
            </button>
          </div>
        </div>
        <div class="p-2">
          <p class="text-xs font-medium text-gray-700 truncate">{{ item.titre || 'Sans titre' }}</p>
          <div class="flex items-center justify-between mt-1">
            <span class="text-xs text-gray-400">{{ item.album || '—' }}</span>
            <span class="text-xs px-1.5 py-0.5 rounded" :class="item.type === 'photo' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'">{{ item.type }}</span>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!items.data.length" class="bg-white rounded-2xl p-16 text-center text-gray-400 border border-gray-100 mt-4">
      <PhotoIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
      Aucun élément dans la galerie.
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { PlusIcon, PencilSquareIcon, TrashIcon, PhotoIcon, VideoCameraIcon } from '@heroicons/vue/24/outline'

defineProps({ items: Object })
function supprimer(item) { if (confirm('Supprimer cet élément ?')) router.delete(route('admin.galerie.destroy', item.id)) }
</script>
