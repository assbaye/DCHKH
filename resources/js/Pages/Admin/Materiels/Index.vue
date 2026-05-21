<template>
  <AdminLayout title="Matériels">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <WrenchScrewdriverIcon class="w-6 h-6 text-[#0d2f6e]" />
        Matériels du Dahira
      </h1>
      <Link :href="route('admin.materiels.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" />Ajouter
      </Link>
    </div>

    <!-- Stats rapides -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div v-for="s in summary" :key="s.label" class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
        <div class="p-2 rounded-lg" :class="s.bg"><component :is="s.icon" class="w-5 h-5" :class="s.color" /></div>
        <div><div class="text-xl font-bold text-gray-800">{{ s.value }}</div><div class="text-xs text-gray-500">{{ s.label }}</div></div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Matériel</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Catégorie</th>
            <th class="text-center px-5 py-3 font-semibold text-gray-600">Stock</th>
            <th class="text-center px-5 py-3 font-semibold text-gray-600">Dispo</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">État</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="m in materiels.data" :key="m.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
              <div class="flex items-center gap-3">
                <img v-if="m.photo" :src="'/storage/' + m.photo" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" />
                <div v-else class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                  <WrenchScrewdriverIcon class="w-5 h-5 text-gray-400" />
                </div>
                <div>
                  <div class="font-medium text-gray-800">{{ m.nom }}</div>
                  <div v-if="m.valeur" class="text-xs text-gray-400">{{ formatMontant(m.valeur) }}</div>
                </div>
              </div>
            </td>
            <td class="px-5 py-4 text-gray-600">{{ m.categorie }}</td>
            <td class="px-5 py-4 text-center font-medium text-gray-800">{{ m.quantite }}</td>
            <td class="px-5 py-4 text-center">
              <span class="font-semibold" :class="m.quantite_disponible === 0 ? 'text-red-600' : 'text-green-600'">{{ m.quantite_disponible }}</span>
            </td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="etatClass(m.etat)">{{ etatLabel(m.etat) }}</span>
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <Link :href="route('admin.materiels.show', m.id)" class="text-xs text-[#0d2f6e] hover:underline">Détail</Link>
                <Link :href="route('admin.materiels.edit', m.id)" class="text-xs text-amber-600 hover:underline">Modifier</Link>
                <button @click="confirmerSuppression(m)" class="text-xs text-red-500 hover:underline">Supprimer</button>
              </div>
            </td>
          </tr>
          <tr v-if="!materiels.data?.length">
            <td colspan="6" class="px-5 py-10 text-center text-gray-400">Aucun matériel enregistré.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="materiels.last_page > 1" class="flex justify-end px-5 py-4 border-t border-gray-100 gap-2">
        <Link v-for="link in materiels.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
          class="px-3 py-1 rounded text-sm border transition"
          :class="link.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'text-gray-600 border-gray-200 hover:bg-gray-50'" />
      </div>
    </div>

    <!-- Modal suppression -->
    <div v-if="aSupprimer" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="aSupprimer = null">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-800 mb-2">Supprimer ce matériel ?</h3>
        <p class="text-sm text-gray-600 mb-5">« {{ aSupprimer.nom }} » sera supprimé définitivement avec tout son historique.</p>
        <div class="flex justify-end gap-3">
          <button @click="aSupprimer = null" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</button>
          <Link :href="route('admin.materiels.destroy', aSupprimer.id)" method="delete" as="button"
            class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</Link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { WrenchScrewdriverIcon, PlusIcon, CubeIcon, ExclamationTriangleIcon, ClockIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ materiels: Object })

const aSupprimer = ref(null)
const confirmerSuppression = (m) => aSupprimer.value = m

const summary = computed(() => [
  { label: 'Total matériels', value: props.materiels.total ?? 0, icon: CubeIcon, bg: 'bg-blue-50', color: 'text-blue-500' },
  { label: 'En bon état', value: props.materiels.data?.filter(m => m.etat === 'bon').length ?? 0, icon: CheckCircleIcon, bg: 'bg-green-50', color: 'text-green-500' },
  { label: 'Usagés', value: props.materiels.data?.filter(m => m.etat === 'usage').length ?? 0, icon: ClockIcon, bg: 'bg-amber-50', color: 'text-amber-500' },
  { label: 'Hors service', value: props.materiels.data?.filter(m => m.etat === 'hors_service').length ?? 0, icon: ExclamationTriangleIcon, bg: 'bg-red-50', color: 'text-red-500' },
])

function etatLabel(etat) {
  return { bon: 'Bon état', usage: 'Usagé', hors_service: 'Hors service' }[etat] ?? etat
}
function etatClass(etat) {
  return { bon: 'bg-green-100 text-green-700', usage: 'bg-amber-100 text-amber-700', hors_service: 'bg-red-100 text-red-700' }[etat] ?? 'bg-gray-100 text-gray-600'
}
function formatMontant(v) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0)
}
</script>
