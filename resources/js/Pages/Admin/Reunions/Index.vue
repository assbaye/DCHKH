<template>
  <AdminLayout title="Gestion des réunions">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ reunions.total }} réunion(s)</p>
      <Link :href="route('admin.reunions.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Nouvelle réunion
      </Link>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Réunion</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">Date</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500 hidden lg:table-cell">Lieu</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500 hidden md:table-cell">Participants</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Statut</th>
            <th class="px-5 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="reunion in reunions.data" :key="reunion.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
              <p class="font-medium text-gray-800">{{ reunion.titre }}</p>
              <p class="text-xs text-gray-400 md:hidden mt-0.5">{{ formatDate(reunion.date_reunion) }}</p>
            </td>
            <td class="px-5 py-4 text-gray-600 hidden md:table-cell">{{ formatDate(reunion.date_reunion) }}</td>
            <td class="px-5 py-4 text-gray-500 hidden lg:table-cell">{{ reunion.lieu || '—' }}</td>
            <td class="px-5 py-4 text-gray-500 hidden md:table-cell">{{ reunion.membres_count }}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-1 rounded-full text-xs font-medium" :class="reunion.statut === 'terminee' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
                {{ reunion.statut === 'terminee' ? 'Terminée' : 'Planifiée' }}
              </span>
            </td>
            <td class="px-5 py-4">
              <div class="flex items-center gap-2 justify-end">
                <Link :href="route('admin.reunions.show', reunion.id)" class="p-1.5 text-gray-400 hover:text-blue-600 transition">
                  <EyeIcon class="w-4 h-4" />
                </Link>
                <Link :href="route('admin.reunions.edit', reunion.id)" class="p-1.5 text-gray-400 hover:text-amber-600 transition">
                  <PencilSquareIcon class="w-4 h-4" />
                </Link>
                <button @click="supprimer(reunion)" class="p-1.5 text-gray-400 hover:text-red-600 transition">
                  <TrashIcon class="w-4 h-4" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!reunions.data.length" class="py-16 text-center text-gray-400">
        <ClipboardIcon class="w-10 h-10 mx-auto mb-3 text-gray-200" />
        Aucune réunion enregistrée.
      </div>
    </div>

    <!-- Pagination -->
    <div v-if="reunions.last_page > 1" class="flex justify-center gap-2 mt-6">
      <Link v-for="page in reunions.links" :key="page.label" :href="page.url || '#'" v-html="page.label"
        class="px-3 py-2 rounded text-sm border transition"
        :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'" />
    </div>

    <ConfirmModal
      :show="confirmModal"
      message="Supprimer cette réunion ? Cette action est irréversible."
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
import { PlusIcon, PencilSquareIcon, TrashIcon, EyeIcon, ClipboardIcon } from '@heroicons/vue/24/outline'

defineProps({ reunions: Object })

const confirmModal = ref(false)
const reunionASupprimer = ref(null)

function supprimer(reunion) { reunionASupprimer.value = reunion; confirmModal.value = true }
function confirmerSuppression() {
  router.delete(route('admin.reunions.destroy', reunionASupprimer.value.id))
  confirmModal.value = false
}

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
