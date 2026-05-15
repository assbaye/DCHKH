<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto px-4 py-10">

      <!-- En-tête profil -->
      <div class="bg-gradient-to-r from-[#0d2f6e] to-[#1a4db5] rounded-2xl p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 hero-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
          <!-- Avatar -->
          <div class="w-24 h-24 rounded-full border-4 border-[#c9973a] overflow-hidden flex-shrink-0 bg-[#0d2f6e]">
            <img v-if="member?.photo" :src="`/storage/${member.photo}`" class="w-full h-full object-cover" alt="Photo profil" />
            <div v-else class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
              {{ initiales }}
            </div>
          </div>

          <div class="text-center sm:text-left flex-1">
            <div v-if="member">
              <h1 class="text-2xl font-bold">{{ member.prenom }} {{ member.nom }}</h1>
              <p class="text-blue-200 text-sm mt-1">{{ $page.props.auth.user.email }}</p>
              <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                <span class="text-xs px-3 py-1 rounded-full font-medium bg-white/20">{{ member.role }}</span>
                <span class="text-xs px-3 py-1 rounded-full font-medium" :class="member.statut === 'actif' ? 'bg-green-500/80' : 'bg-gray-500/80'">
                  {{ member.statut }}
                </span>
                <span class="text-xs px-3 py-1 rounded-full bg-[#c9973a]/80">
                  Membre depuis {{ formatDate(member.date_adhesion) }}
                </span>
              </div>
            </div>
            <div v-else>
              <h1 class="text-2xl font-bold">{{ $page.props.auth.user.name }}</h1>
              <p class="text-blue-200 text-sm mt-1">{{ $page.props.auth.user.email }}</p>
              <p class="text-amber-300 text-sm mt-3 flex items-center gap-2">
                <ExclamationTriangleIcon class="w-4 h-4" />
                Votre profil membre n'est pas encore complété.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Infos personnelles -->
        <div class="md:col-span-2 space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
              <UserIcon class="w-5 h-5 text-[#0d2f6e]" />
              Informations personnelles
            </h2>

            <div v-if="member" class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Prénom</p>
                <p class="text-gray-800 font-medium">{{ member.prenom }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Nom</p>
                <p class="text-gray-800 font-medium">{{ member.nom }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Téléphone</p>
                <p class="text-gray-800 font-medium">{{ member.telephone || '—' }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Ville</p>
                <p class="text-gray-800 font-medium">{{ member.ville || '—' }}</p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Adresse</p>
                <p class="text-gray-800 font-medium">{{ member.adresse || '—' }}</p>
              </div>
            </div>

            <div v-else class="text-center py-6 text-gray-400">
              <UserIcon class="w-10 h-10 mx-auto mb-2 text-gray-200" />
              <p class="text-sm">Profil non encore créé par l'administration.</p>
            </div>
          </div>

          <!-- Cotisations -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
              <BanknotesIcon class="w-5 h-5 text-[#0d2f6e]" />
              Historique des cotisations
            </h2>

            <div v-if="member?.cotisations?.length">
              <div class="space-y-3">
                <div v-for="cot in member.cotisations" :key="cot.id" class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                  <div>
                    <p class="text-sm font-medium text-gray-800">{{ cot.collection?.titre || 'Cotisation libre' }}</p>
                    <p class="text-xs text-gray-400">{{ formatDate(cot.date_paiement) }} · {{ cot.mode_paiement }}</p>
                  </div>
                  <span class="text-green-600 font-bold text-sm">{{ formatMontant(cot.montant) }}</span>
                </div>
              </div>
              <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                <span class="text-sm text-gray-500 font-medium">Total contribué</span>
                <span class="font-bold text-[#0d2f6e]">{{ formatMontant(totalCotisations) }}</span>
              </div>
            </div>

            <div v-else class="text-center py-8 text-gray-400">
              <BanknotesIcon class="w-10 h-10 mx-auto mb-2 text-gray-200" />
              <p class="text-sm">Aucune cotisation enregistrée.</p>
            </div>
          </div>
        </div>

        <!-- Colonne droite -->
        <div class="space-y-6">
          <!-- Compte -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <Cog6ToothIcon class="w-5 h-5 text-[#0d2f6e]" />
              Mon compte
            </h2>
            <div class="space-y-3 text-sm">
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Nom d'utilisateur</p>
                <p class="text-gray-800 font-medium">{{ $page.props.auth.user.name }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Email</p>
                <p class="text-gray-800 font-medium break-all">{{ $page.props.auth.user.email }}</p>
              </div>
            </div>
            <div class="mt-5 flex flex-col gap-2">
              <Link :href="route('profile.edit')" class="w-full inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                <PencilSquareIcon class="w-4 h-4" />
                Modifier le compte
              </Link>
              <Link v-if="member" :href="route('member.card')" class="w-full inline-flex items-center justify-center gap-2 border border-[#0d2f6e]/30 text-[#0d2f6e] px-4 py-2 rounded-lg text-sm hover:bg-[#0d2f6e] hover:text-white transition">
                <IdentificationIcon class="w-4 h-4" />
                Ma carte membre
              </Link>
            </div>
          </div>

          <!-- Stat cotisation -->
          <div v-if="member" class="bg-gradient-to-br from-[#c9973a] to-yellow-600 rounded-2xl p-5 text-white">
            <p class="text-xs font-medium opacity-80 mb-1">Total contribué au Dahira</p>
            <p class="text-2xl font-bold">{{ formatMontant(totalCotisations) }}</p>
            <p class="text-xs opacity-70 mt-2">{{ member.cotisations?.length || 0 }} paiement(s)</p>
          </div>

          <!-- Liens rapides -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Accès rapide</h3>
            <div class="space-y-2">
              <Link :href="route('events.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <CalendarDaysIcon class="w-4 h-4 text-gray-400" /> Événements
              </Link>
              <Link :href="route('khassaides.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <MusicalNoteIcon class="w-4 h-4 text-gray-400" /> Khassaïdes
              </Link>
              <Link :href="route('cotisations.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <BanknotesIcon class="w-4 h-4 text-gray-400" /> Collectes en cours
              </Link>
            </div>
          </div>
        </div>

      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import {
  UserIcon, BanknotesIcon, Cog6ToothIcon, PencilSquareIcon,
  CalendarDaysIcon, MusicalNoteIcon, ExclamationTriangleIcon, IdentificationIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({ member: Object })
const page = usePage()

const initiales = computed(() => {
  const name = page.props.auth.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const totalCotisations = computed(() =>
  props.member?.cotisations?.reduce((sum, c) => sum + parseFloat(c.montant || 0), 0) ?? 0
)

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
function formatMontant(v) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0)
}
</script>

<style scoped>
.hero-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpolygon points='40,5 75,20 75,60 40,75 5,60 5,20'/%3E%3C/g%3E%3C/svg%3E");
  background-size: 80px;
}
</style>
