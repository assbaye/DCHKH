<template>
  <AdminLayout title="Tableau de bord">

    <!-- ===== ADMIN ===== -->
    <template v-if="isAdmin">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <StatCard label="Membres actifs"    :value="stats.membres_actifs"                :icon="UsersIcon"       color="blue" />
        <StatCard label="Événements"        :value="stats.evenements"                     :icon="CalendarDaysIcon" color="amber" />
        <StatCard label="Khassaïdes"        :value="stats.khassaides"                    :icon="MusicalNoteIcon"  color="purple" />
        <StatCard label="Total collecté"    :value="formatMontant(stats.total_collecte)"  :icon="BanknotesIcon"   color="green" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><UsersIcon class="w-5 h-5 text-blue-500" />Derniers membres</h2>
            <Link :href="route('admin.membres.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="m in derniers_membres" :key="m.id" class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-[#0d2f6e] text-white flex items-center justify-center text-sm font-bold flex-shrink-0">{{ m.prenom[0] }}{{ m.nom[0] }}</div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ m.prenom }} {{ m.nom }}</div>
                <div class="text-xs text-gray-400">{{ formatDate(m.created_at) }}</div>
              </div>
              <span class="text-xs px-2 py-0.5 rounded-full font-medium" :class="m.statut === 'actif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">{{ m.statut }}</span>
            </div>
            <div v-if="!derniers_membres?.length" class="text-gray-400 text-sm text-center py-4">Aucun membre</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><CalendarDaysIcon class="w-5 h-5 text-amber-500" />Prochains événements</h2>
            <Link :href="route('admin.evenements.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Gérer <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="e in prochains_evenements" :key="e.id" class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold flex-shrink-0">{{ new Date(e.date_event).getDate() }}</div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ e.titre }}</div>
                <div class="text-xs text-gray-400">{{ formatDate(e.date_event) }} · {{ e.categorie }}</div>
              </div>
            </div>
            <div v-if="!prochains_evenements?.length" class="text-gray-400 text-sm text-center py-4">Aucun événement à venir</div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><BoltIcon class="w-5 h-5 text-yellow-500" />Actions rapides</h2>
        <div class="flex flex-wrap gap-3">
          <Link :href="route('admin.membres.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition"><UserPlusIcon class="w-4 h-4" />Nouveau membre</Link>
          <Link :href="route('admin.evenements.create')" class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 transition"><CalendarDaysIcon class="w-4 h-4" />Nouvel événement</Link>
          <Link :href="route('admin.khassaides.create')" class="inline-flex items-center gap-2 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition"><MusicalNoteIcon class="w-4 h-4" />Nouveau khassaïde</Link>
          <Link :href="route('admin.collections.create')" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition"><BanknotesIcon class="w-4 h-4" />Nouvelle collecte</Link>
        </div>
      </div>
    </template>

    <!-- ===== SECRÉTAIRE ===== -->
    <template v-else-if="isSecretaire">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <StatCard label="Total réunions"   :value="stats.reunions_total"    :icon="ClipboardIcon"   color="blue" />
        <StatCard label="Planifiées"       :value="stats.reunions_planifiees" :icon="ClockIcon"      color="amber" />
        <StatCard label="Terminées"        :value="stats.reunions_terminees"  :icon="CheckCircleIcon" color="green" />
        <StatCard label="Membres actifs"   :value="stats.membres_actifs"     :icon="UsersIcon"       color="purple" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><ClockIcon class="w-5 h-5 text-amber-500" />Prochaines réunions</h2>
            <Link :href="route('admin.reunions.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="r in prochaines_reunions" :key="r.id" class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ new Date(r.date_reunion).getDate() }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ r.titre }}</div>
                <div class="text-xs text-gray-400">{{ formatDate(r.date_reunion) }}{{ r.lieu ? ' · ' + r.lieu : '' }}</div>
              </div>
              <Link :href="route('admin.reunions.edit', r.id)" class="text-xs text-[#0d2f6e] hover:underline flex-shrink-0">Éditer</Link>
            </div>
            <div v-if="!prochaines_reunions?.length" class="text-gray-400 text-sm text-center py-4">Aucune réunion planifiée</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><CheckCircleIcon class="w-5 h-5 text-green-500" />Réunions terminées</h2>
            <Link :href="route('admin.reunions.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="r in dernieres_reunions" :key="r.id" class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-green-100 text-green-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ new Date(r.date_reunion).getDate() }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ r.titre }}</div>
                <div class="text-xs text-gray-400">{{ formatDate(r.date_reunion) }}</div>
              </div>
              <Link :href="route('admin.reunions.show', r.id)" class="text-xs text-green-600 hover:underline flex-shrink-0">PV</Link>
            </div>
            <div v-if="!dernieres_reunions?.length" class="text-gray-400 text-sm text-center py-4">Aucune réunion terminée</div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><BoltIcon class="w-5 h-5 text-yellow-500" />Actions rapides</h2>
        <div class="flex flex-wrap gap-3">
          <Link :href="route('admin.reunions.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition"><ClipboardIcon class="w-4 h-4" />Nouvelle réunion</Link>
          <Link :href="route('admin.reunions.index')" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition"><ClipboardIcon class="w-4 h-4" />Toutes les réunions</Link>
          <button @click="smsSecretaire = true" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition"><DevicePhoneMobileIcon class="w-4 h-4" />Envoyer un SMS</button>
          <Link :href="route('admin.sms.index')" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition"><ClockIcon class="w-4 h-4" />Historique SMS</Link>
        </div>
      </div>

      <SmsModal
        :show="smsSecretaire"
        type="reunion"
        placeholder="Ex : La prochaine réunion aura lieu le... Merci de confirmer votre présence."
        @close="smsSecretaire = false"
      />
    </template>

    <!-- ===== TRÉSORIER ===== -->
    <template v-else-if="isTresorier">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <StatCard label="Total collecté"     :value="formatMontant(stats.total_collecte)"   :icon="BanknotesIcon"             color="green" />
        <StatCard label="Ce mois"            :value="formatMontant(stats.collecte_ce_mois)"  :icon="CalendarDaysIcon"          color="blue" />
        <StatCard label="Cotisants"          :value="stats.nb_cotisants"                     :icon="UsersIcon"                 color="purple" />
        <StatCard label="Collectes actives"  :value="stats.collections_actives"              :icon="ClipboardDocumentListIcon" color="amber" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><BanknotesIcon class="w-5 h-5 text-green-500" />Dernières cotisations</h2>
            <Link :href="route('admin.cotisations.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="c in dernieres_cotisations" :key="c.id" class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-sm font-bold flex-shrink-0">
                {{ c.member?.prenom?.[0] ?? '?' }}{{ c.member?.nom?.[0] ?? '' }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ c.member?.prenom }} {{ c.member?.nom }}</div>
                <div class="text-xs text-gray-400">{{ formatDate(c.date_paiement) }}</div>
              </div>
              <span class="text-sm font-semibold text-green-700">{{ formatMontant(c.montant) }}</span>
            </div>
            <div v-if="!dernieres_cotisations?.length" class="text-gray-400 text-sm text-center py-4">Aucune cotisation</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><ClipboardDocumentListIcon class="w-5 h-5 text-amber-500" />Collectes en cours</h2>
            <Link :href="route('admin.collections.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-4">
            <div v-for="col in collections_actives" :key="col.id">
              <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium text-gray-800 truncate">{{ col.titre }}</span>
                <span class="text-xs text-gray-500 flex-shrink-0 ml-2">{{ formatMontant(col.montant_collecte) }} / {{ formatMontant(col.objectif) }}</span>
              </div>
              <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full transition-all" :style="{ width: Math.min(100, (col.montant_collecte / col.objectif) * 100) + '%' }"></div>
              </div>
            </div>
            <div v-if="!collections_actives?.length" class="text-gray-400 text-sm text-center py-4">Aucune collecte active</div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><BoltIcon class="w-5 h-5 text-yellow-500" />Actions rapides</h2>
        <div class="flex flex-wrap gap-3">
          <Link :href="route('admin.cotisations.create')" class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition"><BanknotesIcon class="w-4 h-4" />Enregistrer une cotisation</Link>
          <Link :href="route('admin.cotisations.index')" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition"><ClipboardDocumentListIcon class="w-4 h-4" />Toutes les cotisations</Link>
          <button @click="smsTresorier = true" class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition"><DevicePhoneMobileIcon class="w-4 h-4" />Rappel cotisation</button>
          <Link :href="route('admin.sms.index')" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition"><ClockIcon class="w-4 h-4" />Historique SMS</Link>
        </div>
      </div>

      <SmsModal
        :show="smsTresorier"
        type="cotisation"
        placeholder="Ex : Rappel : votre cotisation du mois est en attente. Merci de vous en acquitter dès que possible."
        @close="smsTresorier = false"
      />
    </template>

    <!-- ===== GESTIONNAIRE ===== -->
    <template v-else-if="isGestionnaire">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <StatCard label="Total matériels"    :value="stats.total_materiels"      :icon="CubeIcon"               color="blue" />
        <StatCard label="En bon état"        :value="stats.materiels_bon"         :icon="CheckCircleIcon"        color="green" />
        <StatCard label="Emprunts en cours"  :value="stats.emprunts_en_cours"     :icon="ArrowsRightLeftIcon"    color="amber" />
        <StatCard label="En retard"          :value="stats.emprunts_en_retard"    :icon="ExclamationTriangleIcon" color="red" />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><ArrowsRightLeftIcon class="w-5 h-5 text-amber-500" />Emprunts en cours</h2>
            <Link :href="route('admin.emprunts.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="e in emprunts_en_cours" :key="e.id" class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ e.membre?.prenom?.[0] }}{{ e.membre?.nom?.[0] }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ e.materiel?.nom }}</div>
                <div class="text-xs text-gray-400">{{ e.membre?.prenom }} {{ e.membre?.nom }} · retour {{ formatDate(e.date_retour_prevue) }}</div>
              </div>
              <span class="px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0"
                :class="e.statut === 'en_retard' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'">
                {{ e.statut === 'en_retard' ? 'En retard' : 'En cours' }}
              </span>
            </div>
            <div v-if="!emprunts_en_cours?.length" class="text-gray-400 text-sm text-center py-4">Aucun emprunt en cours</div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
          <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-gray-800 flex items-center gap-2"><WrenchScrewdriverIcon class="w-5 h-5 text-blue-500" />Maintenances actives</h2>
            <Link :href="route('admin.maintenances.index')" class="text-[#c9973a] text-sm hover:underline flex items-center gap-1">Voir tout <ArrowRightIcon class="w-3 h-3" /></Link>
          </div>
          <div class="space-y-3">
            <div v-for="m in maintenances_actives" :key="m.id" class="flex items-start gap-3">
              <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                <WrenchScrewdriverIcon class="w-4 h-4" />
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ m.titre }}</div>
                <div class="text-xs text-gray-400">{{ m.materiel?.nom }} · {{ formatDate(m.date_maintenance) }}</div>
              </div>
              <span class="px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0"
                :class="m.statut === 'en_cours' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'">
                {{ m.statut === 'en_cours' ? 'En cours' : 'Planifiée' }}
              </span>
            </div>
            <div v-if="!maintenances_actives?.length" class="text-gray-400 text-sm text-center py-4">Aucune maintenance active</div>
          </div>
        </div>
      </div>

      <div class="mt-6 bg-white rounded-2xl shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><BoltIcon class="w-5 h-5 text-yellow-500" />Actions rapides</h2>
        <div class="flex flex-wrap gap-3">
          <Link :href="route('admin.materiels.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition"><CubeIcon class="w-4 h-4" />Nouveau matériel</Link>
          <Link :href="route('admin.emprunts.create')" class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 transition"><ArrowsRightLeftIcon class="w-4 h-4" />Enregistrer emprunt</Link>
          <Link :href="route('admin.maintenances.create')" class="inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition"><WrenchScrewdriverIcon class="w-4 h-4" />Nouvelle maintenance</Link>
          <Link :href="route('admin.materiels.index')" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition"><CubeIcon class="w-4 h-4" />Inventaire</Link>
        </div>
      </div>
    </template>

  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SmsModal from '@/Components/SmsModal.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import {
  UsersIcon, CalendarDaysIcon, MusicalNoteIcon, BanknotesIcon,
  ArrowRightIcon, UserPlusIcon, BoltIcon, ClipboardIcon,
  ClockIcon, CheckCircleIcon, ClipboardDocumentListIcon, DevicePhoneMobileIcon,
  CubeIcon, ArrowsRightLeftIcon, WrenchScrewdriverIcon, ExclamationTriangleIcon,
} from '@heroicons/vue/24/outline'

defineProps({
  stats: Object,
  derniers_membres:       Array,
  prochains_evenements:   Array,
  prochaines_reunions:    Array,
  dernieres_reunions:     Array,
  dernieres_cotisations:  Array,
  collections_actives:    Array,
  emprunts_en_cours:      Array,
  maintenances_actives:   Array,
})

const page = usePage()
const userRole     = computed(() => page.props.auth?.user?.member?.role)
const isAdmin        = computed(() => userRole.value === 'admin')
const isSecretaire   = computed(() => userRole.value === 'secretaire')
const isTresorier    = computed(() => userRole.value === 'tresorier')
const isGestionnaire = computed(() => userRole.value === 'gestionnaire')

const smsSecretaire = ref(false)
const smsTresorier  = ref(false)

const colorMap = {
  blue:   { bg: 'bg-blue-50',   text: 'text-blue-600',   icon: 'text-blue-500' },
  amber:  { bg: 'bg-amber-50',  text: 'text-amber-600',  icon: 'text-amber-500' },
  purple: { bg: 'bg-purple-50', text: 'text-purple-600', icon: 'text-purple-500' },
  green:  { bg: 'bg-green-50',  text: 'text-green-600',  icon: 'text-green-500' },
}

const StatCard = {
  props: ['label', 'value', 'icon', 'color'],
  setup(props) { return { c: colorMap[props.color] ?? colorMap.blue } },
  template: `
    <div class="bg-white rounded-2xl shadow-sm p-5">
      <div class="flex items-center justify-between mb-3">
        <div class="p-2 rounded-lg" :class="c.bg"><component :is="icon" class="w-5 h-5" :class="c.icon" /></div>
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
