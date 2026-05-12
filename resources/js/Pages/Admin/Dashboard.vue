<template>
  <AdminLayout title="Tableau de bord">
    <!-- Stats cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
      <StatCard label="Membres actifs"    :value="stats.membres_actifs"               :icon="UsersIcon"                color="blue" />
      <StatCard label="Événements"        :value="stats.evenements"                    :icon="CalendarDaysIcon"          color="amber" />
      <StatCard label="Khassaïdes"        :value="stats.khassaides"                   :icon="MusicalNoteIcon"           color="purple" />
      <StatCard label="Total collecté"    :value="formatMontant(stats.total_collecte)" :icon="BanknotesIcon"            color="green" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Derniers membres -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">
            <UsersIcon class="w-5 h-5 text-blue-500" />
            Derniers membres inscrits
          </h2>
          <Link :href="route('admin.membres.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">
            Voir tout <ArrowRightIcon class="w-3 h-3" />
          </Link>
        </div>
        <div class="space-y-3">
          <div v-for="m in derniers_membres" :key="m.id" class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-[#0d2f6e] text-white flex items-center justify-center text-sm font-bold flex-shrink-0">
              {{ m.prenom[0] }}{{ m.nom[0] }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-medium text-gray-800 truncate">{{ m.prenom }} {{ m.nom }}</div>
              <div class="text-xs text-gray-400">{{ formatDate(m.created_at) }}</div>
            </div>
            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
              :class="m.statut === 'actif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
              {{ m.statut }}
            </span>
          </div>
          <div v-if="!derniers_membres.length" class="text-gray-400 text-sm text-center py-4">Aucun membre</div>
        </div>
      </div>

      <!-- Prochains événements -->
      <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">
            <CalendarDaysIcon class="w-5 h-5 text-amber-500" />
            Prochains événements
          </h2>
          <Link :href="route('admin.evenements.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">
            Gérer <ArrowRightIcon class="w-3 h-3" />
          </Link>
        </div>
        <div class="space-y-3">
          <div v-for="e in prochains_evenements" :key="e.id" class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
              {{ new Date(e.date_event).getDate() }}
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-sm font-medium text-gray-800 truncate">{{ e.titre }}</div>
              <div class="text-xs text-gray-400">{{ formatDate(e.date_event) }} · {{ e.categorie }}</div>
            </div>
          </div>
          <div v-if="!prochains_evenements.length" class="text-gray-400 text-sm text-center py-4">Aucun événement à venir</div>
        </div>
      </div>
    </div>

    <!-- Actions rapides -->
    <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
      <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
        <BoltIcon class="w-5 h-5 text-yellow-500" />
        Actions rapides
      </h2>
      <div class="flex flex-wrap gap-3">
        <Link :href="route('admin.membres.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
          <UserPlusIcon class="w-4 h-4" /> Nouveau membre
        </Link>
        <Link :href="route('admin.evenements.create')" class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 transition">
          <CalendarDaysIcon class="w-4 h-4" /> Nouvel événement
        </Link>
        <Link :href="route('admin.khassaides.create')" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition">
          <MusicalNoteIcon class="w-4 h-4" /> Nouveau khassaïde
        </Link>
        <Link :href="route('admin.collections.create')" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">
          <BanknotesIcon class="w-4 h-4" /> Nouvelle collecte
        </Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  UsersIcon, CalendarDaysIcon, MusicalNoteIcon, BanknotesIcon,
  ArrowRightIcon, UserPlusIcon, BoltIcon,
} from '@heroicons/vue/24/outline'

defineProps({
  stats: Object,
  derniers_membres: Array,
  prochains_evenements: Array,
})

const colorMap = {
  blue:   { bg: 'bg-blue-50',   text: 'text-blue-600',   icon: 'text-blue-500' },
  amber:  { bg: 'bg-amber-50',  text: 'text-amber-600',  icon: 'text-amber-500' },
  purple: { bg: 'bg-purple-50', text: 'text-purple-600', icon: 'text-purple-500' },
  green:  { bg: 'bg-green-50',  text: 'text-green-600',  icon: 'text-green-500' },
}

const StatCard = {
  props: ['label', 'value', 'icon', 'color'],
  setup(props) {
    const c = colorMap[props.color] ?? colorMap.blue
    return { c }
  },
  template: `
    <div class="bg-white rounded-2xl shadow-sm p-5">
      <div class="flex items-center justify-between mb-3">
        <div class="p-2 rounded-lg" :class="c.bg">
          <component :is="icon" class="w-5 h-5" :class="c.icon" />
        </div>
        <span class="text-xs font-medium px-2 py-1 rounded-full" :class="[c.bg, c.text]">{{ label }}</span>
      </div>
      <div class="text-2xl font-bold text-gray-800">{{ value }}</div>
    </div>
  `,
}

function formatMontant(val) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(val ?? 0)
}
function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' })
}
</script>
