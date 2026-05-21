<template>
  <AdminLayout :title="reunion.titre">
    <div class="max-w-3xl mx-auto space-y-6">

      <!-- Header -->
      <div class="flex items-start justify-between">
        <div>
          <Link :href="route('admin.reunions.index')" class="text-sm text-gray-400 hover:text-[#0d2f6e] flex items-center gap-1 mb-2 transition">
            <ArrowLeftIcon class="w-4 h-4" /> Retour aux réunions
          </Link>
          <h1 class="text-2xl font-bold text-gray-800">{{ reunion.titre }}</h1>
          <div class="flex items-center gap-3 mt-2">
            <span class="px-2 py-1 rounded-full text-xs font-medium" :class="reunion.statut === 'terminee' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
              {{ reunion.statut === 'terminee' ? 'Terminée' : 'Planifiée' }}
            </span>
          </div>
        </div>
        <Link :href="route('admin.reunions.edit', reunion.id)" class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
          <PencilSquareIcon class="w-4 h-4" /> Modifier
        </Link>
      </div>

      <!-- Infos -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-2 gap-4">
        <div>
          <p class="text-xs text-gray-400 mb-1">Date</p>
          <p class="font-medium text-gray-800">{{ formatDate(reunion.date_reunion) }}</p>
        </div>
        <div>
          <p class="text-xs text-gray-400 mb-1">Lieu</p>
          <p class="font-medium text-gray-800">{{ reunion.lieu || '—' }}</p>
        </div>
        <div v-if="reunion.heure_debut">
          <p class="text-xs text-gray-400 mb-1">Heure</p>
          <p class="font-medium text-gray-800">{{ reunion.heure_debut }}{{ reunion.heure_fin ? ' — ' + reunion.heure_fin : '' }}</p>
        </div>
      </div>

      <!-- Ordre du jour -->
      <div v-if="reunion.ordre_du_jour" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
          <ClipboardIcon class="w-4 h-4 text-[#0d2f6e]" /> Ordre du jour
        </h2>
        <p class="text-sm text-gray-600 whitespace-pre-line">{{ reunion.ordre_du_jour }}</p>
      </div>

      <!-- Compte-rendu -->
      <div v-if="reunion.compte_rendu" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
          <DocumentTextIcon class="w-4 h-4 text-green-600" /> Compte-rendu / PV
        </h2>
        <p class="text-sm text-gray-600 whitespace-pre-line">{{ reunion.compte_rendu }}</p>
      </div>

      <!-- Participants -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
          <UsersIcon class="w-4 h-4 text-[#0d2f6e]" /> Participants ({{ reunion.membres.length }})
        </h2>
        <div v-if="reunion.membres.length" class="grid grid-cols-2 sm:grid-cols-3 gap-2">
          <div v-for="membre in reunion.membres" :key="membre.id" class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg">
            <div class="w-7 h-7 rounded-full bg-[#0d2f6e] flex items-center justify-center flex-shrink-0">
              <span class="text-white text-xs font-bold">{{ membre.prenom[0] }}{{ membre.nom[0] }}</span>
            </div>
            <span class="text-sm text-gray-700 truncate">{{ membre.prenom }} {{ membre.nom }}</span>
          </div>
        </div>
        <p v-else class="text-sm text-gray-400 italic">Aucun participant enregistré.</p>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLeftIcon, PencilSquareIcon, ClipboardIcon, DocumentTextIcon, UsersIcon } from '@heroicons/vue/24/outline'

defineProps({ reunion: Object })

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
