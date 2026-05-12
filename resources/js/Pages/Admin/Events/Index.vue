<template>
  <AdminLayout title="Gestion des événements">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ events.total }} événement(s)</p>
      <Link :href="route('admin.evenements.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" />
        Nouvel événement
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-5 py-3">Titre</th>
            <th class="text-left px-5 py-3">Catégorie</th>
            <th class="text-left px-5 py-3">Date</th>
            <th class="text-left px-5 py-3">Lieu</th>
            <th class="text-center px-5 py-3">Publié</th>
            <th class="text-right px-5 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="event in events.data" :key="event.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 font-medium text-gray-800">{{ event.titre }}</td>
            <td class="px-5 py-4">
              <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">{{ event.categorie }}</span>
            </td>
            <td class="px-5 py-4 text-gray-500">{{ formatDate(event.date_event) }}</td>
            <td class="px-5 py-4 text-gray-500">{{ event.lieu || '—' }}</td>
            <td class="px-5 py-4 text-center">
              <CheckCircleIcon v-if="event.publie" class="w-5 h-5 text-green-500 mx-auto" />
              <XCircleIcon v-else class="w-5 h-5 text-gray-300 mx-auto" />
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end items-center gap-2">
                <Link
                  :href="route('admin.evenements.edit', event.id)"
                  class="inline-flex items-center gap-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition"
                >
                  <PencilSquareIcon class="w-3.5 h-3.5" />
                  Modifier
                </Link>
                <button
                  @click="supprimer(event)"
                  class="inline-flex items-center gap-1.5 text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition"
                >
                  <TrashIcon class="w-3.5 h-3.5" />
                  Supprimer
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!events.data.length" class="text-center text-gray-400 py-16">
        <CalendarDaysIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        Aucun événement.
      </div>
    </div>

    <ConfirmModal
      :show="confirmModal"
      :message="`Êtes-vous sûr de vouloir supprimer l'événement « ${itemASupprimer?.titre} » ?`"
      @confirm="confirmerSuppression"
      @cancel="confirmModal = false"
    />

    <!-- Pagination -->
    <div v-if="events.last_page > 1" class="flex justify-center gap-2 mt-6">
      <Link
        v-for="page in events.links"
        :key="page.label"
        :href="page.url || '#'"
        v-html="page.label"
        class="px-3 py-2 rounded-lg text-sm border transition"
        :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'"
      />
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { PlusIcon, PencilSquareIcon, TrashIcon, CheckCircleIcon, XCircleIcon, CalendarDaysIcon } from '@heroicons/vue/24/outline'

defineProps({ events: Object })
const confirmModal = ref(false)
const itemASupprimer = ref(null)

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
function supprimer(e) { itemASupprimer.value = e; confirmModal.value = true }
function confirmerSuppression() {
  router.delete(route('admin.evenements.destroy', itemASupprimer.value.id))
  confirmModal.value = false
}
</script>
