<template>
  <AdminLayout title="Gestion des cotisations">
    <div class="flex justify-between items-center mb-6">
      <p class="text-gray-500 text-sm">{{ cotisations.total }} cotisation(s)</p>
      <Link :href="route('admin.cotisations.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" /> Enregistrer une cotisation
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3">Membre</th>
            <th class="text-left px-5 py-3">Collecte</th>
            <th class="text-right px-5 py-3">Montant</th>
            <th class="text-left px-5 py-3">Date</th>
            <th class="text-left px-5 py-3">Mode</th>
            <th class="text-right px-5 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="c in cotisations.data" :key="c.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
              <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-[#0d2f6e] text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ c.member?.prenom?.[0] }}{{ c.member?.nom?.[0] }}
                </div>
                <span class="font-medium text-gray-800">{{ c.member?.prenom }} {{ c.member?.nom }}</span>
              </div>
            </td>
            <td class="px-5 py-4 text-gray-500">{{ c.collection?.titre || '—' }}</td>
            <td class="px-5 py-4 text-right font-semibold text-green-600">{{ formatMontant(c.montant) }}</td>
            <td class="px-5 py-4 text-gray-500">{{ formatDate(c.date_paiement) }}</td>
            <td class="px-5 py-4">
              <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">{{ c.mode_paiement }}</span>
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end gap-2">
                <Link :href="route('admin.cotisations.edit', c.id)" class="inline-flex items-center gap-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">
                  <PencilSquareIcon class="w-3.5 h-3.5" /> Modifier
                </Link>
                <button @click="supprimer(c)" class="inline-flex items-center gap-1.5 text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                  <TrashIcon class="w-3.5 h-3.5" /> Supprimer
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!cotisations.data.length" class="text-center py-16 text-gray-400">
        <ClipboardDocumentListIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        Aucune cotisation enregistrée.
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { PlusIcon, PencilSquareIcon, TrashIcon, ClipboardDocumentListIcon } from '@heroicons/vue/24/outline'

defineProps({ cotisations: Object })
function formatMontant(v) { return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v) }
function formatDate(d) { return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) }
function supprimer(c) { if (confirm('Supprimer cette cotisation ?')) router.delete(route('admin.cotisations.destroy', c.id)) }
</script>
