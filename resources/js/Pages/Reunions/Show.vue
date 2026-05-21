<template>
  <MainLayout>
    <div class="max-w-3xl mx-auto px-4 py-10 space-y-6">

      <!-- Header -->
      <div>
        <Link :href="route('reunions.index')" class="text-sm text-gray-400 hover:text-[#0d2f6e] flex items-center gap-1 mb-3 transition">
          <ArrowLeftIcon class="w-4 h-4" /> Retour aux réunions
        </Link>
        <div class="flex items-center gap-3 mb-2">
          <span class="px-2 py-1 rounded-full text-xs font-medium" :class="reunion.statut === 'terminee' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'">
            {{ reunion.statut === 'terminee' ? 'Terminée' : 'Planifiée' }}
          </span>
        </div>
        <h1 class="text-2xl font-bold text-[#0d2f6e]">{{ reunion.titre }}</h1>
      </div>

      <!-- Infos de base -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
          <div>
            <p class="text-xs text-gray-400 mb-1">Date</p>
            <p class="font-medium text-gray-800 flex items-center gap-1.5">
              <CalendarDaysIcon class="w-4 h-4 text-[#0d2f6e]" /> {{ formatDate(reunion.date_reunion) }}
            </p>
          </div>
          <div v-if="reunion.heure_debut">
            <p class="text-xs text-gray-400 mb-1">Heure</p>
            <p class="font-medium text-gray-800 flex items-center gap-1.5">
              <ClockIcon class="w-4 h-4 text-[#0d2f6e]" /> {{ reunion.heure_debut }}{{ reunion.heure_fin ? ' — ' + reunion.heure_fin : '' }}
            </p>
          </div>
          <div v-if="reunion.lieu">
            <p class="text-xs text-gray-400 mb-1">Lieu</p>
            <p class="font-medium text-gray-800 flex items-center gap-1.5">
              <MapPinIcon class="w-4 h-4 text-[#0d2f6e]" /> {{ reunion.lieu }}
            </p>
          </div>
        </div>
      </div>

      <!-- Ordre du jour -->
      <div v-if="reunion.ordre_du_jour" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
          <ClipboardIcon class="w-5 h-5 text-[#0d2f6e]" /> Ordre du jour
        </h2>
        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ reunion.ordre_du_jour }}</p>
      </div>

      <!-- Compte-rendu -->
      <div v-if="reunion.statut === 'terminee' && reunion.compte_rendu" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
          <DocumentTextIcon class="w-5 h-5 text-green-600" /> Compte-rendu
        </h2>
        <p class="text-sm text-gray-600 whitespace-pre-line leading-relaxed">{{ reunion.compte_rendu }}</p>
      </div>

      <!-- Participants -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
          <UsersIcon class="w-5 h-5 text-[#0d2f6e]" /> Participants ({{ reunion.membres.length }})
        </h2>
        <div v-if="reunion.membres.length" class="flex flex-wrap gap-2">
          <span
            v-for="membre in reunion.membres"
            :key="membre.id"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-800 text-sm rounded-full"
          >
            <span class="w-5 h-5 rounded-full bg-[#0d2f6e] text-white text-xs flex items-center justify-center font-bold flex-shrink-0">
              {{ membre.prenom[0] }}
            </span>
            {{ membre.prenom }} {{ membre.nom }}
          </span>
        </div>
        <p v-else class="text-sm text-gray-400 italic">Aucun participant enregistré.</p>
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLeftIcon, CalendarDaysIcon, ClockIcon, MapPinIcon, ClipboardIcon, DocumentTextIcon, UsersIcon } from '@heroicons/vue/24/outline'

defineProps({ reunion: Object })

function formatDate(date) {
  return new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}
</script>
