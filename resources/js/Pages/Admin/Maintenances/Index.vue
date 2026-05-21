<template>
  <AdminLayout title="Maintenances">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <WrenchScrewdriverIcon class="w-6 h-6 text-[#0d2f6e]" />Maintenances
      </h1>
      <Link :href="route('admin.maintenances.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" />Nouvelle maintenance
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Matériel</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Titre</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Date</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Coût</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Statut</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="m in maintenances.data" :key="m.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 font-medium text-gray-800">{{ m.materiel?.nom }}</td>
            <td class="px-5 py-4 text-gray-700">{{ m.titre }}</td>
            <td class="px-5 py-4 text-gray-500">{{ formatDate(m.date_maintenance) }}</td>
            <td class="px-5 py-4 text-gray-600">{{ m.cout ? formatMontant(m.cout) : '—' }}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statutClass(m.statut)">{{ statutLabel(m.statut) }}</span>
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <Link :href="route('admin.maintenances.edit', m.id)" class="text-xs text-amber-600 hover:underline">Modifier</Link>
                <Link :href="route('admin.maintenances.destroy', m.id)" method="delete" as="button" class="text-xs text-red-500 hover:underline">Supprimer</Link>
              </div>
            </td>
          </tr>
          <tr v-if="!maintenances.data?.length">
            <td colspan="6" class="px-5 py-10 text-center text-gray-400">Aucune maintenance enregistrée.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="maintenances.last_page > 1" class="flex justify-end px-5 py-4 border-t border-gray-100 gap-2">
        <Link v-for="link in maintenances.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
          class="px-3 py-1 rounded text-sm border transition"
          :class="link.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'text-gray-600 border-gray-200 hover:bg-gray-50'" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { WrenchScrewdriverIcon, PlusIcon } from '@heroicons/vue/24/outline'

defineProps({ maintenances: Object })

function statutLabel(s) { return { planifiee: 'Planifiée', en_cours: 'En cours', terminee: 'Terminée' }[s] ?? s }
function statutClass(s) { return { planifiee: 'bg-amber-100 text-amber-700', en_cours: 'bg-blue-100 text-blue-700', terminee: 'bg-green-100 text-green-700' }[s] ?? 'bg-gray-100 text-gray-600' }
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '—' }
function formatMontant(v) { return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0) }
</script>
