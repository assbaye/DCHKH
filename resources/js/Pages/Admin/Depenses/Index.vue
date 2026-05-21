<template>
  <AdminLayout title="Dépenses">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-gray-800 flex items-center gap-2">
        <ArrowTrendingDownIcon class="w-6 h-6 text-red-500" />
        Dépenses
      </h1>
      <Link :href="route('admin.depenses.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition">
        <PlusIcon class="w-4 h-4" />Nouvelle dépense
      </Link>
    </div>

    <!-- Solde financier -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-3 mb-2">
          <div class="p-2 rounded-lg bg-green-50"><ArrowTrendingUpIcon class="w-5 h-5 text-green-500" /></div>
          <span class="text-sm text-gray-500">Total collecté</span>
        </div>
        <div class="text-2xl font-bold text-green-600">{{ formatMontant(total_cotisations) }}</div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center gap-3 mb-2">
          <div class="p-2 rounded-lg bg-red-50"><ArrowTrendingDownIcon class="w-5 h-5 text-red-500" /></div>
          <span class="text-sm text-gray-500">Total dépenses</span>
        </div>
        <div class="text-2xl font-bold text-red-600">{{ formatMontant(total_depenses) }}</div>
      </div>
      <div class="bg-white rounded-2xl shadow-sm p-5 border-2" :class="solde >= 0 ? 'border-green-200' : 'border-red-200'">
        <div class="flex items-center gap-3 mb-2">
          <div class="p-2 rounded-lg" :class="solde >= 0 ? 'bg-green-50' : 'bg-red-50'">
            <BanknotesIcon class="w-5 h-5" :class="solde >= 0 ? 'text-green-500' : 'text-red-500'" />
          </div>
          <span class="text-sm text-gray-500">Solde</span>
        </div>
        <div class="text-2xl font-bold" :class="solde >= 0 ? 'text-green-600' : 'text-red-600'">{{ formatMontant(solde) }}</div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-4 flex flex-wrap gap-3">
      <input
        v-model="filtres.mois"
        type="month"
        class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]"
        @change="filtrer"
      />
      <select v-model="filtres.categorie" @change="filtrer" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
        <option value="">Toutes catégories</option>
        <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
      </select>
      <button v-if="filtres.mois || filtres.categorie" @click="reinitialiser" class="text-sm text-gray-500 hover:text-gray-700 underline">Réinitialiser</button>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Date</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Titre</th>
            <th class="text-left px-5 py-3 font-semibold text-gray-600">Catégorie</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Montant</th>
            <th class="text-center px-5 py-3 font-semibold text-gray-600">Justificatif</th>
            <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="d in depenses.data" :key="d.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4 text-gray-500 whitespace-nowrap">{{ formatDate(d.date_depense) }}</td>
            <td class="px-5 py-4">
              <div class="font-medium text-gray-800">{{ d.titre }}</div>
              <div v-if="d.description" class="text-xs text-gray-400 truncate max-w-xs">{{ d.description }}</div>
            </td>
            <td class="px-5 py-4">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="categorieClass(d.categorie)">{{ d.categorie }}</span>
            </td>
            <td class="px-5 py-4 text-right font-semibold text-red-600">{{ formatMontant(d.montant) }}</td>
            <td class="px-5 py-4 text-center">
              <a v-if="d.justificatif" :href="'/storage/' + d.justificatif" target="_blank" class="inline-flex items-center gap-1 text-xs text-[#0d2f6e] hover:underline">
                <PaperClipIcon class="w-3.5 h-3.5" />Voir
              </a>
              <span v-else class="text-gray-300 text-xs">—</span>
            </td>
            <td class="px-5 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <Link :href="route('admin.depenses.edit', d.id)" class="text-xs text-amber-600 hover:underline">Modifier</Link>
                <button @click="aSupprimer = d" class="text-xs text-red-500 hover:underline">Supprimer</button>
              </div>
            </td>
          </tr>
          <tr v-if="!depenses.data?.length">
            <td colspan="6" class="px-5 py-10 text-center text-gray-400">Aucune dépense enregistrée.</td>
          </tr>
        </tbody>
      </table>

      <div v-if="depenses.last_page > 1" class="flex justify-end px-5 py-4 border-t border-gray-100 gap-2">
        <Link v-for="link in depenses.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
          class="px-3 py-1 rounded text-sm border transition"
          :class="link.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'text-gray-600 border-gray-200 hover:bg-gray-50'" />
      </div>
    </div>

    <!-- Modal suppression -->
    <div v-if="aSupprimer" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="aSupprimer = null">
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <h3 class="font-bold text-gray-800 mb-2">Supprimer cette dépense ?</h3>
        <p class="text-sm text-gray-600 mb-5">« {{ aSupprimer.titre }} » — {{ formatMontant(aSupprimer.montant) }}</p>
        <div class="flex justify-end gap-3">
          <button @click="aSupprimer = null" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</button>
          <Link :href="route('admin.depenses.destroy', aSupprimer.id)" method="delete" as="button"
            class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Supprimer</Link>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { ArrowTrendingDownIcon, ArrowTrendingUpIcon, BanknotesIcon, PlusIcon, PaperClipIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  depenses:           Object,
  total_depenses:     Number,
  total_cotisations:  Number,
  solde:              Number,
  categories:         Array,
  filters:            Object,
})

const aSupprimer = ref(null)

const filtres = ref({
  mois:      props.filters?.mois ?? '',
  categorie: props.filters?.categorie ?? '',
})

function filtrer() {
  router.get(route('admin.depenses.index'), filtres.value, { preserveState: true, replace: true })
}

function reinitialiser() {
  filtres.value = { mois: '', categorie: '' }
  filtrer()
}

function formatMontant(v) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0)
}
function formatDate(d) {
  return d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '—'
}

const categorieColors = {
  'Événement':     'bg-purple-100 text-purple-700',
  'Matériel':      'bg-blue-100 text-blue-700',
  'Alimentation':  'bg-orange-100 text-orange-700',
  'Transport':     'bg-cyan-100 text-cyan-700',
  'Entretien':     'bg-amber-100 text-amber-700',
  'Communication': 'bg-pink-100 text-pink-700',
  'Autre':         'bg-gray-100 text-gray-600',
}
function categorieClass(c) { return categorieColors[c] ?? 'bg-gray-100 text-gray-600' }
</script>
