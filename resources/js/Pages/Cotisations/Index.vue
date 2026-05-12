<template>
  <MainLayout>
    <div class="max-w-5xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Cotisations & Collectes</h1>
      <p class="text-gray-500 mb-10">Soutenez les activités du Dahira D.CH.KH.</p>

      <!-- Collections actives -->
      <h2 class="text-xl font-bold text-[#0d2f6e] mb-5">Collectes en cours</h2>
      <div v-if="collections.length" class="space-y-5">
        <div
          v-for="col in collections"
          :key="col.id"
          class="bg-white rounded-2xl shadow-sm p-6"
        >
          <div class="flex items-start justify-between mb-3">
            <div>
              <h3 class="font-bold text-gray-800 text-lg">{{ col.titre }}</h3>
              <p v-if="col.description" class="text-gray-500 text-sm mt-1">{{ col.description }}</p>
            </div>
            <span class="text-2xl font-bold text-[#c9973a]">{{ col.pourcentage }}%</span>
          </div>

          <!-- Barre de progression -->
          <div class="w-full bg-gray-100 rounded-full h-3 mb-3">
            <div
              class="h-3 rounded-full bg-gradient-to-r from-[#0d2f6e] to-[#c9973a] transition-all duration-500"
              :style="{ width: `${col.pourcentage}%` }"
            ></div>
          </div>

          <div class="flex justify-between text-sm text-gray-500">
            <span>{{ formatMontant(col.montant_collecte) }} collectés</span>
            <span>Objectif : {{ formatMontant(col.objectif) }}</span>
          </div>

          <div class="mt-3 text-xs text-gray-400">
            Du {{ formatDate(col.date_debut) }}
            <span v-if="col.date_fin"> au {{ formatDate(col.date_fin) }}</span>
          </div>
        </div>
      </div>
      <div v-else class="text-center text-gray-400 py-8 bg-white rounded-2xl">Aucune collecte en cours.</div>

      <!-- Historique -->
      <div v-if="historique.length" class="mt-12">
        <h2 class="text-xl font-bold text-[#0d2f6e] mb-5">Collectes passées</h2>
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
              <tr>
                <th class="text-left px-5 py-3">Titre</th>
                <th class="text-right px-5 py-3">Montant collecté</th>
                <th class="text-right px-5 py-3">Objectif</th>
                <th class="text-right px-5 py-3">Période</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="h in historique" :key="h.id" class="hover:bg-gray-50">
                <td class="px-5 py-4 font-medium text-gray-800">{{ h.titre }}</td>
                <td class="px-5 py-4 text-right text-green-600 font-semibold">{{ formatMontant(h.cotisations_sum_montant ?? 0) }}</td>
                <td class="px-5 py-4 text-right text-gray-500">{{ formatMontant(h.objectif) }}</td>
                <td class="px-5 py-4 text-right text-gray-400">{{ formatDate(h.date_debut) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'

defineProps({
  collections: Array,
  historique: Array,
})

function formatMontant(val) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(val)
}

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
