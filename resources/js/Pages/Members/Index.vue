<template>
  <MainLayout>
    <div class="max-w-5xl mx-auto px-4 py-10">

      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
        <div>
          <h1 class="text-2xl font-bold text-[#0d2f6e] flex items-center gap-2">
            <UsersIcon class="w-6 h-6" />
            Membres du Dahira
          </h1>
          <p class="text-gray-400 text-sm mt-1">Communauté D.CH.KH. — Dielerlou Syll, Louga</p>
        </div>

        <!-- Stats -->
        <div class="flex gap-3">
          <div class="flex flex-col items-center justify-center bg-[#0d2f6e]/5 rounded-2xl px-5 py-3 min-w-[72px]">
            <span class="text-2xl font-bold text-[#0d2f6e]">{{ stats.total }}</span>
            <span class="text-[11px] text-gray-500 mt-0.5 whitespace-nowrap">Actifs</span>
          </div>
          <div class="flex flex-col items-center justify-center bg-blue-50 rounded-2xl px-5 py-3 min-w-[72px]">
            <span class="text-2xl font-bold text-blue-600">{{ stats.moderateurs }}</span>
            <span class="text-[11px] text-gray-500 mt-0.5 whitespace-nowrap">Modérateurs</span>
          </div>
          <div class="flex flex-col items-center justify-center bg-amber-50 rounded-2xl px-5 py-3 min-w-[72px]">
            <span class="text-2xl font-bold text-amber-600">{{ stats.admins }}</span>
            <span class="text-[11px] text-gray-500 mt-0.5 whitespace-nowrap">Admins</span>
          </div>
        </div>
      </div>

      <!-- Barre recherche + filtres rôle -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
          <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            v-model="searchInput"
            @keyup.enter="applyFilters"
            type="text"
            placeholder="Nom, prénom, ville…"
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
          />
        </div>
        <div class="flex gap-2 items-center flex-wrap">
          <button
            v-for="r in roles"
            :key="r.value"
            @click="toggleRole(r.value)"
            class="px-3.5 py-2 rounded-xl text-xs font-semibold border transition-all"
            :class="filtreRole === r.value
              ? 'bg-[#0d2f6e] text-white border-[#0d2f6e] shadow-sm'
              : 'bg-gray-50 text-gray-500 border-gray-200 hover:border-gray-300 hover:text-gray-700'"
          >
            {{ r.label }}
          </button>
        </div>
      </div>

      <!-- Compteur résultats -->
      <p class="text-xs text-gray-400 mb-4 px-1">
        {{ membres.total }} résultat{{ membres.total > 1 ? 's' : '' }}
        <span v-if="search"> · Recherche : « {{ search }} »</span>
        <button v-if="search || filtre_role" @click="reset" class="ml-2 text-[#c9973a] hover:underline font-medium">Effacer</button>
      </p>

      <!-- Grille membres -->
      <div v-if="membres.data.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <div
          v-for="m in membres.data"
          :key="m.id"
          class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
        >
          <!-- Top coloré avec avatar centré -->
          <div class="h-16 bg-gradient-to-br from-[#0d2f6e] to-[#1a4db5] flex items-end justify-center pb-0 relative">
            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 w-16 h-16 rounded-full border-4 border-white overflow-hidden bg-[#0d2f6e] shadow-md">
              <img v-if="m.photo" :src="`/storage/${m.photo}`" class="w-full h-full object-cover" :alt="m.prenom" />
              <div v-else class="w-full h-full flex items-center justify-center text-lg font-bold text-white select-none">
                {{ initiales(m) }}
              </div>
            </div>
          </div>

          <!-- Contenu -->
          <div class="pt-10 pb-5 px-4 flex flex-col items-center text-center">
            <p class="font-semibold text-gray-800 text-sm leading-tight">{{ m.prenom }} {{ m.nom }}</p>

            <p v-if="m.ville" class="flex items-center gap-1 text-[11px] text-gray-400 mt-1">
              <MapPinIcon class="w-3 h-3 flex-shrink-0" />
              {{ m.ville }}
            </p>

            <p v-if="m.date_adhesion" class="flex items-center gap-1 text-[11px] text-gray-400 mt-0.5">
              <CalendarDaysIcon class="w-3 h-3 flex-shrink-0" />
              Depuis {{ new Date(m.date_adhesion).getFullYear() }}
            </p>

            <span class="mt-3 text-[11px] px-2.5 py-0.5 rounded-full font-semibold" :class="roleCouleur(m.role)">
              {{ m.role }}
            </span>

            <Link
              :href="route('members.show', m.id)"
              class="mt-3 text-[11px] px-3 py-1 rounded-lg border border-[#0d2f6e]/20 text-[#0d2f6e] hover:bg-[#0d2f6e] hover:text-white transition font-medium"
            >
              Voir profil
            </Link>
          </div>
        </div>
      </div>

      <!-- Empty state -->
      <div v-else class="text-center py-20">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <UsersIcon class="w-8 h-8 text-gray-300" />
        </div>
        <p class="font-semibold text-gray-500">Aucun membre trouvé</p>
        <p class="text-sm text-gray-400 mt-1">Essayez de modifier votre recherche.</p>
        <button @click="reset" class="mt-4 inline-flex items-center gap-1.5 text-sm text-[#c9973a] hover:underline font-medium">
          <XMarkIcon class="w-4 h-4" /> Réinitialiser les filtres
        </button>
      </div>

      <!-- Pagination -->
      <div v-if="membres.last_page > 1" class="flex justify-center gap-2 mt-10 flex-wrap">
        <Link
          v-for="link in membres.links"
          :key="link.label"
          :href="link.url || '#'"
          v-html="link.label"
          class="px-3 py-1.5 rounded-lg text-sm border transition"
          :class="link.active
            ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]'
            : link.url
              ? 'border-gray-200 text-gray-600 hover:bg-gray-50'
              : 'border-gray-100 text-gray-300 cursor-default pointer-events-none'"
        />
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import {
  UsersIcon, MagnifyingGlassIcon, MapPinIcon, CalendarDaysIcon, XMarkIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  membres: Object,
  stats: Object,
  search: String,
  filtre_role: String,
})

const searchInput = ref(props.search || '')
const filtreRole = ref(props.filtre_role || '')

const roles = [
  { value: '', label: 'Tous' },
  { value: 'membre', label: 'Membres' },
  { value: 'moderateur', label: 'Modérateurs' },
  { value: 'admin', label: 'Admins' },
]

function applyFilters() {
  router.get(route('members.index'), {
    search: searchInput.value || undefined,
    role: filtreRole.value || undefined,
  }, { preserveState: true, replace: true })
}

function toggleRole(val) {
  filtreRole.value = filtreRole.value === val ? '' : val
  applyFilters()
}

function reset() {
  searchInput.value = ''
  filtreRole.value = ''
  applyFilters()
}

function initiales(m) {
  return ((m.prenom?.[0] || '') + (m.nom?.[0] || '')).toUpperCase()
}

function roleCouleur(role) {
  if (role === 'admin') return 'bg-amber-100 text-amber-700'
  if (role === 'moderateur') return 'bg-blue-100 text-blue-700'
  return 'bg-gray-100 text-gray-500'
}
</script>
