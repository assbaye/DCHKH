<template>
  <AdminLayout title="Albums de la galerie">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ albums.total }} album(s)</p>
      <Link :href="route('admin.albums.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Créer un album
      </Link>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <div v-for="album in albums.data" :key="album.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
        <div class="relative aspect-video bg-gray-100">
          <img v-if="album.cover_image" :src="`/storage/${album.cover_image}`" :alt="album.nom" class="w-full h-full object-cover" />
          <div v-else class="w-full h-full flex items-center justify-center">
            <FolderOpenIcon class="w-12 h-12 text-gray-200" />
          </div>
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100">
            <Link :href="route('admin.albums.edit', album.id)" class="p-2 bg-white rounded-lg text-amber-700 hover:bg-amber-50 transition">
              <PencilSquareIcon class="w-4 h-4" />
            </Link>
            <button @click="supprimer(album)" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50 transition">
              <TrashIcon class="w-4 h-4" />
            </button>
          </div>
          <span v-if="!album.publie" class="absolute top-2 left-2 bg-gray-700/80 text-white text-xs px-2 py-0.5 rounded-full">Masqué</span>
        </div>
        <div class="p-3">
          <p class="text-sm font-semibold text-gray-800 truncate">{{ album.nom }}</p>
          <p class="text-xs text-gray-400 mt-0.5">{{ album.items_count }} élément(s)</p>
        </div>
      </div>
    </div>

    <div v-if="!albums.data.length" class="bg-white rounded-2xl p-16 text-center text-gray-400 border border-gray-100 mt-4">
      <FolderOpenIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
      Aucun album créé.
    </div>

    <ConfirmModal
      :show="confirmModal"
      message="Supprimer cet album ? Les médias liés resteront dans la galerie mais seront sans album."
      @confirm="confirmerSuppression"
      @cancel="confirmModal = false"
    />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { PlusIcon, PencilSquareIcon, TrashIcon, FolderOpenIcon } from '@heroicons/vue/24/outline'

defineProps({ albums: Object })

const confirmModal = ref(false)
const albumASupprimer = ref(null)

function supprimer(album) { albumASupprimer.value = album; confirmModal.value = true }
function confirmerSuppression() {
  router.delete(route('admin.albums.destroy', albumASupprimer.value.id))
  confirmModal.value = false
}
</script>
