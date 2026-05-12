<template>
  <AdminLayout title="Gestion des collectes">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ collections.total }} collecte(s)</p>
      <Link :href="route('admin.collections.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Nouvelle collecte
      </Link>
    </div>

    <div class="space-y-4">
      <div v-for="col in collections.data" :key="col.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 mb-2">
              <h3 class="font-bold text-gray-800">{{ col.titre }}</h3>
              <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="col.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                {{ col.active ? 'Active' : 'Terminée' }}
              </span>
            </div>
            <p v-if="col.description" class="text-sm text-gray-500 mb-3 truncate">{{ col.description }}</p>
            <div class="flex items-center gap-2 mb-1">
              <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div class="h-2 rounded-full bg-gradient-to-r from-[#0d2f6e] to-[#c9973a]" :style="{ width: `${pourcentage(col)}%` }"></div>
              </div>
              <span class="text-xs font-bold text-[#c9973a]">{{ pourcentage(col) }}%</span>
            </div>
            <div class="flex gap-4 text-xs text-gray-500 mt-1">
              <span>Collecté : <strong class="text-gray-800">{{ formatMontant(col.cotisations_sum_montant ?? 0) }}</strong></span>
              <span>Objectif : <strong class="text-gray-800">{{ formatMontant(col.objectif) }}</strong></span>
              <span>Début : {{ formatDate(col.date_debut) }}</span>
            </div>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <Link :href="route('admin.collections.edit', col.id)" class="inline-flex items-center gap-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">
              <PencilSquareIcon class="w-3.5 h-3.5" /> Modifier
            </Link>
            <button @click="supprimer(col)" class="inline-flex items-center gap-1.5 text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
              <TrashIcon class="w-3.5 h-3.5" /> Supprimer
            </button>
          </div>
        </div>
      </div>
    <ConfirmModal
      :show="confirmModal"
      :message="`Êtes-vous sûr de vouloir supprimer la collecte « ${itemASupprimer?.titre} » ?`"
      @confirm="confirmerSuppression"
      @cancel="confirmModal = false"
    />

      <div v-if="!collections.data.length" class="bg-white rounded-2xl p-16 text-center text-gray-400 border border-gray-100">
        <BanknotesIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        Aucune collecte.
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { PlusIcon, PencilSquareIcon, TrashIcon, BanknotesIcon } from '@heroicons/vue/24/outline'

defineProps({ collections: Object })
const confirmModal = ref(false)
const itemASupprimer = ref(null)
function pourcentage(col) {
  if (!col.objectif) return 0
  return Math.min(100, Math.round(((col.cotisations_sum_montant ?? 0) / col.objectif) * 100))
}
function formatMontant(v) { return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v) }
function formatDate(d) { return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) }
function supprimer(col) { itemASupprimer.value = col; confirmModal.value = true }
function confirmerSuppression() {
  router.delete(route('admin.collections.destroy', itemASupprimer.value.id))
  confirmModal.value = false
}
</script>
