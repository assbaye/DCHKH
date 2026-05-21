<template>
  <AdminLayout :title="materiel.nom">
    <div class="max-w-4xl mx-auto space-y-6">

      <!-- En-tête -->
      <div class="flex items-center gap-3">
        <Link :href="route('admin.materiels.index')" class="text-gray-400 hover:text-gray-600"><ArrowLeftIcon class="w-5 h-5" /></Link>
        <h1 class="text-xl font-bold text-gray-800 flex-1">{{ materiel.nom }}</h1>
        <Link :href="route('admin.materiels.edit', materiel.id)" class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 transition">
          <PencilIcon class="w-4 h-4" />Modifier
        </Link>
      </div>

      <!-- Fiche matériel -->
      <div class="bg-white rounded-2xl shadow-sm p-6 flex gap-6">
        <img v-if="materiel.photo" :src="'/storage/' + materiel.photo" class="w-32 h-32 rounded-xl object-cover flex-shrink-0" />
        <div v-else class="w-32 h-32 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
          <WrenchScrewdriverIcon class="w-12 h-12 text-gray-300" />
        </div>
        <div class="flex-1 grid grid-cols-2 gap-4">
          <div><p class="text-xs text-gray-500">Catégorie</p><p class="font-medium text-gray-800">{{ materiel.categorie }}</p></div>
          <div><p class="text-xs text-gray-500">État</p>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="etatClass(materiel.etat)">{{ etatLabel(materiel.etat) }}</span>
          </div>
          <div><p class="text-xs text-gray-500">Quantité totale</p><p class="font-medium text-gray-800">{{ materiel.quantite }}</p></div>
          <div><p class="text-xs text-gray-500">Disponible</p>
            <p class="font-semibold" :class="materiel.quantite_disponible === 0 ? 'text-red-600' : 'text-green-600'">{{ materiel.quantite_disponible }}</p>
          </div>
          <div v-if="materiel.valeur"><p class="text-xs text-gray-500">Valeur estimée</p><p class="font-medium text-gray-800">{{ formatMontant(materiel.valeur) }}</p></div>
          <div v-if="materiel.description" class="col-span-2"><p class="text-xs text-gray-500">Description</p><p class="text-sm text-gray-700">{{ materiel.description }}</p></div>
        </div>
      </div>

      <!-- Emprunts en cours -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-bold text-gray-800 flex items-center gap-2"><ClockIcon class="w-5 h-5 text-amber-500" />Emprunts</h2>
          <Link :href="route('admin.emprunts.create')" class="inline-flex items-center gap-1.5 text-[#0d2f6e] text-sm hover:underline"><PlusIcon class="w-4 h-4" />Nouvel emprunt</Link>
        </div>
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Membre</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Du</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Retour prévu</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Statut</th>
          </tr></thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="e in materiel.emprunts" :key="e.id">
              <td class="px-4 py-3">{{ e.membre?.prenom }} {{ e.membre?.nom }}</td>
              <td class="px-4 py-3 text-gray-500">{{ formatDate(e.date_emprunt) }}</td>
              <td class="px-4 py-3 text-gray-500">{{ formatDate(e.date_retour_prevue) }}</td>
              <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="statutClass(e.statut)">{{ statutLabel(e.statut) }}</span></td>
            </tr>
            <tr v-if="!materiel.emprunts?.length"><td colspan="4" class="px-4 py-6 text-center text-gray-400">Aucun emprunt.</td></tr>
          </tbody>
        </table>
      </div>

      <!-- Maintenances -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-bold text-gray-800 flex items-center gap-2"><WrenchScrewdriverIcon class="w-5 h-5 text-blue-500" />Maintenances</h2>
          <Link :href="route('admin.maintenances.create')" class="inline-flex items-center gap-1.5 text-[#0d2f6e] text-sm hover:underline"><PlusIcon class="w-4 h-4" />Nouvelle maintenance</Link>
        </div>
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Titre</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Date</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Coût</th>
            <th class="text-left px-4 py-2 text-gray-600 font-semibold">Statut</th>
          </tr></thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="m in materiel.maintenances" :key="m.id">
              <td class="px-4 py-3 font-medium text-gray-800">{{ m.titre }}</td>
              <td class="px-4 py-3 text-gray-500">{{ formatDate(m.date_maintenance) }}</td>
              <td class="px-4 py-3 text-gray-600">{{ m.cout ? formatMontant(m.cout) : '—' }}</td>
              <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="maintenanceStatutClass(m.statut)">{{ maintenanceStatutLabel(m.statut) }}</span></td>
            </tr>
            <tr v-if="!materiel.maintenances?.length"><td colspan="4" class="px-4 py-6 text-center text-gray-400">Aucune maintenance.</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLeftIcon, PencilIcon, WrenchScrewdriverIcon, ClockIcon, PlusIcon } from '@heroicons/vue/24/outline'

defineProps({ materiel: Object })

function etatLabel(e) { return { bon: 'Bon état', usage: 'Usagé', hors_service: 'Hors service' }[e] ?? e }
function etatClass(e) { return { bon: 'bg-green-100 text-green-700', usage: 'bg-amber-100 text-amber-700', hors_service: 'bg-red-100 text-red-700' }[e] ?? 'bg-gray-100 text-gray-600' }
function statutLabel(s) { return { en_cours: 'En cours', rendu: 'Rendu', en_retard: 'En retard' }[s] ?? s }
function statutClass(s) { return { en_cours: 'bg-blue-100 text-blue-700', rendu: 'bg-green-100 text-green-700', en_retard: 'bg-red-100 text-red-700' }[s] ?? 'bg-gray-100 text-gray-600' }
function maintenanceStatutLabel(s) { return { planifiee: 'Planifiée', en_cours: 'En cours', terminee: 'Terminée' }[s] ?? s }
function maintenanceStatutClass(s) { return { planifiee: 'bg-amber-100 text-amber-700', en_cours: 'bg-blue-100 text-blue-700', terminee: 'bg-green-100 text-green-700' }[s] ?? 'bg-gray-100 text-gray-600' }
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '—' }
function formatMontant(v) { return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0) }
</script>
