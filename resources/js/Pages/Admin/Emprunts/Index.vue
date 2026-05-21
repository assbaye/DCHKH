<template>
  <AdminLayout title="Emprunts">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <ArrowsRightLeftIcon class="w-6 h-6 text-[#0d2f6e]" />Emprunts
      </h1>
      <Link :href="route('admin.emprunts.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" />Nouvel emprunt
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Matériel</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Membre</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Qté</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Retour prévu</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Statut</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="e in emprunts.data" :key="e.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 font-medium text-gray-800">{{ e.materiel?.nom }}</td>
            <td class="px-5 py-4 text-gray-600">{{ e.membre?.prenom }} {{ e.membre?.nom }}</td>
            <td class="px-5 py-4 text-gray-600">{{ e.quantite }}</td>
            <td class="px-5 py-4 text-gray-500">{{ formatDate(e.date_retour_prevue) }}</td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statutClass(e.statut)">{{ statutLabel(e.statut) }}</span>
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <Link
                  v-if="e.statut !== 'rendu'"
                  :href="route('admin.emprunts.retour', e.id)"
                  method="patch"
                  as="button"
                  class="text-xs text-green-600 hover:underline font-medium"
                >Retour ✓</Link>
                <Link :href="route('admin.emprunts.edit', e.id)" class="text-xs text-amber-600 hover:underline">Modifier</Link>
                <Link :href="route('admin.emprunts.destroy', e.id)" method="delete" as="button" class="text-xs text-red-500 hover:underline">Supprimer</Link>
              </div>
            </td>
          </tr>
          <tr v-if="!emprunts.data?.length">
            <td colspan="6" class="px-5 py-10 text-center text-gray-400">Aucun emprunt enregistré.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="emprunts.last_page > 1" class="flex justify-end px-5 py-4 border-t border-gray-100 gap-2">
        <Link v-for="link in emprunts.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
          class="px-3 py-1 rounded text-sm border transition"
          :class="link.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'text-gray-600 border-gray-200 hover:bg-gray-50'" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ArrowsRightLeftIcon, PlusIcon } from '@heroicons/vue/24/outline'

defineProps({ emprunts: Object })

function statutLabel(s) { return { en_cours: 'En cours', rendu: 'Rendu', en_retard: 'En retard' }[s] ?? s }
function statutClass(s) { return { en_cours: 'bg-blue-100 text-blue-700', rendu: 'bg-green-100 text-green-700', en_retard: 'bg-red-100 text-red-700' }[s] ?? 'bg-gray-100 text-gray-600' }
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '—' }
</script>
