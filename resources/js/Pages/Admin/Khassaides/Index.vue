<template>
  <AdminLayout title="Gestion des Khassaïdes">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ khassaides.total }} khassaïde(s)</p>
      <Link :href="route('admin.khassaides.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Nouveau khassaïde
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3">Titre</th>
            <th class="text-left px-5 py-3">Auteur</th>
            <th class="text-left px-5 py-3">Interprète</th>
            <th class="text-center px-5 py-3">Audio</th>
            <th class="text-center px-5 py-3">Publié</th>
            <th class="text-right px-5 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="k in khassaides.data" :key="k.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 font-medium text-gray-800">{{ k.titre }}</td>
            <td class="px-5 py-4 text-gray-500">{{ k.auteur }}</td>
            <td class="px-5 py-4 text-gray-500 text-xs">{{ k.interprete || '—' }}</td>
            <td class="px-5 py-4 text-center">
              <MusicalNoteIcon v-if="k.fichier_audio" class="w-5 h-5 text-green-500 mx-auto" />
              <span v-else class="text-gray-300 text-xs">—</span>
            </td>
            <td class="px-5 py-4 text-center">
              <CheckCircleIcon v-if="k.publie" class="w-5 h-5 text-green-500 mx-auto" />
              <XCircleIcon v-else class="w-5 h-5 text-gray-300 mx-auto" />
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end gap-2">
                <Link :href="route('admin.khassaides.edit', k.id)" class="inline-flex items-center gap-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">
                  <PencilSquareIcon class="w-3.5 h-3.5" /> Modifier
                </Link>
                <button @click="supprimer(k)" class="inline-flex items-center gap-1.5 text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                  <TrashIcon class="w-3.5 h-3.5" /> Supprimer
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!khassaides.data.length" class="text-center py-16 text-gray-400">
        <MusicalNoteIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        Aucun khassaïde.
      </div>
    </div>

    <ConfirmModal
      :show="confirmModal"
      :message="`Êtes-vous sûr de vouloir supprimer « ${itemASupprimer?.titre} » ?`"
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
import { PlusIcon, PencilSquareIcon, TrashIcon, CheckCircleIcon, XCircleIcon, MusicalNoteIcon } from '@heroicons/vue/24/outline'

defineProps({ khassaides: Object })
const confirmModal = ref(false)
const itemASupprimer = ref(null)
function supprimer(k) { itemASupprimer.value = k; confirmModal.value = true }
function confirmerSuppression() {
  router.delete(route('admin.khassaides.destroy', itemASupprimer.value.id))
  confirmModal.value = false
}
</script>
