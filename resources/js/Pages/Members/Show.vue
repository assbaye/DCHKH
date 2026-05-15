<template>
  <MainLayout>
    <div class="max-w-3xl mx-auto px-4 py-10">

      <!-- Bouton retour -->
      <Link :href="route('members.index')" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-[#0d2f6e] transition mb-6">
        <ArrowLeftIcon class="w-4 h-4" />
        Retour à la liste
      </Link>

      <!-- En-tête profil -->
      <div class="bg-gradient-to-r from-[#0d2f6e] to-[#1a4db5] rounded-2xl p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 hero-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
          <!-- Avatar -->
          <div class="w-24 h-24 rounded-full border-4 border-[#c9973a] overflow-hidden flex-shrink-0 bg-[#0d2f6e]/60">
            <img v-if="member.photo" :src="`/storage/${member.photo}`" class="w-full h-full object-cover" :alt="member.prenom" />
            <div v-else class="w-full h-full flex items-center justify-center text-3xl font-bold text-white select-none">
              {{ initiales }}
            </div>
          </div>

          <div class="text-center sm:text-left flex-1">
            <h1 class="text-2xl font-bold">{{ member.prenom }} {{ member.nom }}</h1>
            <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
              <span class="text-xs px-3 py-1 rounded-full font-medium bg-white/20">{{ member.role }}</span>
              <span v-if="member.date_adhesion" class="text-xs px-3 py-1 rounded-full bg-[#c9973a]/80">
                Membre depuis {{ new Date(member.date_adhesion).getFullYear() }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Infos -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
          <UserIcon class="w-5 h-5 text-[#0d2f6e]" />
          Informations
        </h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div v-if="member.ville">
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Ville</p>
            <p class="text-gray-800 font-medium flex items-center gap-1">
              <MapPinIcon class="w-4 h-4 text-gray-400" />
              {{ member.ville }}
            </p>
          </div>
          <div v-if="member.date_adhesion">
            <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Membre depuis</p>
            <p class="text-gray-800 font-medium">{{ formatDate(member.date_adhesion) }}</p>
          </div>
        </div>
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import { UserIcon, MapPinIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ member: Object })

const initiales = computed(() =>
  ((props.member.prenom?.[0] || '') + (props.member.nom?.[0] || '')).toUpperCase()
)

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
</script>

<style scoped>
.hero-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpolygon points='40,5 75,20 75,60 40,75 5,60 5,20'/%3E%3C/g%3E%3C/svg%3E");
  background-size: 80px;
}
</style>
