<template>
  <div class="min-h-screen bg-gray-100 flex">

    <!-- Overlay mobile -->
    <div v-if="sidebarOpen" class="fixed inset-0 bg-black/50 z-30 lg:hidden" @click="sidebarOpen = false" />

    <!-- Sidebar -->
    <aside
      class="fixed h-full z-40 bg-[#0d2f6e] text-white flex flex-col transition-transform duration-300"
      :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64 lg:translate-x-0 lg:w-64'"
    >
      <div class="p-5 border-b border-blue-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full border-2 border-[#c9973a] overflow-hidden flex-shrink-0">
            <img src="/images/logo.jpeg" alt="Logo" class="w-full h-full object-cover" />
          </div>
          <div>
            <div class="text-xs font-bold text-white">Panel Admin</div>
            <div class="text-blue-300 text-xs">D.CH.KH.</div>
          </div>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-blue-300 hover:text-white">
          <XMarkIcon class="w-5 h-5" />
        </button>
      </div>

      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

        <!-- Tableau de bord -->
        <AdminNavLink :href="route('admin.dashboard')" :icon="ChartBarIcon" @click="sidebarOpen = false">
          Tableau de bord
        </AdminNavLink>

        <!-- Membres (admin) -->
        <template v-if="isAdmin">
          <SidebarGroup label="Membres" :icon="UsersIcon" :open="groupOuvert === 'membres'" @toggle="toggleGroupe('membres')">
            <AdminNavLink :href="route('admin.inscriptions.index')" :icon="UserPlusIcon" @click="sidebarOpen = false" :indent="true">
              Inscriptions
              <span v-if="$page.props.nb_inscriptions > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none">
                {{ $page.props.nb_inscriptions }}
              </span>
            </AdminNavLink>
            <AdminNavLink :href="route('admin.membres.index')" :icon="UsersIcon" @click="sidebarOpen = false" :indent="true">Membres</AdminNavLink>
          </SidebarGroup>
        </template>

        <!-- Contenu (admin) -->
        <template v-if="isAdmin">
          <SidebarGroup label="Contenu" :icon="FolderOpenIcon" :open="groupOuvert === 'contenu'" @toggle="toggleGroupe('contenu')">
            <AdminNavLink :href="route('admin.evenements.index')"  :icon="CalendarDaysIcon" @click="sidebarOpen = false" :indent="true">Événements</AdminNavLink>
            <AdminNavLink :href="route('admin.khassaides.index')"  :icon="MusicalNoteIcon"  @click="sidebarOpen = false" :indent="true">Khassaïdes</AdminNavLink>
            <AdminNavLink :href="route('admin.albums.index')"      :icon="FolderOpenIcon"   @click="sidebarOpen = false" :indent="true">Albums</AdminNavLink>
            <AdminNavLink :href="route('admin.galerie.index')"     :icon="PhotoIcon"        @click="sidebarOpen = false" :indent="true">Galerie</AdminNavLink>
          </SidebarGroup>
        </template>

        <!-- Réunions (admin + secrétaire) -->
        <AdminNavLink v-if="isAdmin || isSecretaire" :href="route('admin.reunions.index')" :icon="ClipboardIcon" @click="sidebarOpen = false">
          Réunions
        </AdminNavLink>

        <!-- Finances (admin + trésorier) -->
        <template v-if="isAdmin || isTresorier">
          <SidebarGroup label="Finances" :icon="BanknotesIcon" :open="groupOuvert === 'finances'" @toggle="toggleGroupe('finances')">
            <AdminNavLink :href="route('admin.cotisations.index')" :icon="ClipboardDocumentListIcon" @click="sidebarOpen = false" :indent="true">Cotisations</AdminNavLink>
            <AdminNavLink v-if="isAdmin" :href="route('admin.collections.index')" :icon="BanknotesIcon" @click="sidebarOpen = false" :indent="true">Collectes</AdminNavLink>
            <AdminNavLink :href="route('admin.depenses.index')" :icon="ArrowTrendingDownIcon" @click="sidebarOpen = false" :indent="true">Dépenses</AdminNavLink>
          </SidebarGroup>
        </template>

        <!-- Matériels (admin + gestionnaire) -->
        <template v-if="isAdmin || isGestionnaire">
          <SidebarGroup label="Matériels" :icon="CubeIcon" :open="groupOuvert === 'materiels'" @toggle="toggleGroupe('materiels')">
            <AdminNavLink :href="route('admin.materiels.index')"    :icon="CubeIcon"            @click="sidebarOpen = false" :indent="true">Inventaire</AdminNavLink>
            <AdminNavLink :href="route('admin.emprunts.index')"     :icon="ArrowsRightLeftIcon"  @click="sidebarOpen = false" :indent="true">Emprunts</AdminNavLink>
            <AdminNavLink :href="route('admin.maintenances.index')" :icon="WrenchScrewdriverIcon" @click="sidebarOpen = false" :indent="true">Maintenances</AdminNavLink>
          </SidebarGroup>
        </template>

        <!-- SMS -->
        <AdminNavLink :href="route('admin.sms.index')" :icon="DevicePhoneMobileIcon" @click="sidebarOpen = false">SMS</AdminNavLink>

      </nav>

      <div class="p-4 border-t border-blue-800 space-y-2">
        <Link :href="route('home')" class="flex items-center gap-2 text-blue-300 text-xs hover:text-white transition">
          <ArrowLeftIcon class="w-4 h-4" /> Retour au site
        </Link>
        <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-2 text-red-400 text-xs hover:text-red-300 transition">
          <ArrowRightOnRectangleIcon class="w-4 h-4" /> Déconnexion
        </Link>
      </div>
    </aside>

    <!-- Contenu principal -->
    <div class="lg:ml-64 flex-1 flex flex-col min-h-screen">
      <header class="bg-white shadow-sm px-4 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
          <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 p-1">
            <Bars3Icon class="w-6 h-6" />
          </button>
          <h1 class="text-base lg:text-lg font-semibold text-gray-800">{{ title }}</h1>
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <UserCircleIcon class="w-5 h-5 text-gray-400" />
          <span class="hidden sm:inline">{{ $page.props.auth.user?.name }}</span>
        </div>
      </header>

      <main class="flex-1 p-4 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
  <Toast />
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AdminNavLink from '@/Components/AdminNavLink.vue'
import SidebarGroup from '@/Components/SidebarGroup.vue'
import Toast from '@/Components/Toast.vue'
import {
  ChartBarIcon, UsersIcon, UserPlusIcon, CalendarDaysIcon, MusicalNoteIcon,
  PhotoIcon, BanknotesIcon, ClipboardDocumentListIcon, ArrowLeftIcon,
  ArrowRightOnRectangleIcon, UserCircleIcon, Bars3Icon, XMarkIcon, FolderOpenIcon,
  ClipboardIcon, DevicePhoneMobileIcon, CubeIcon, ArrowsRightLeftIcon, WrenchScrewdriverIcon,
  ArrowTrendingDownIcon,
} from '@heroicons/vue/24/outline'

defineProps({ title: { type: String, default: 'Administration' } })

const page = usePage()
const userRole       = computed(() => page.props.auth?.user?.member?.role)
const isAdmin        = computed(() => userRole.value === 'admin')
const isSecretaire   = computed(() => userRole.value === 'secretaire')
const isTresorier    = computed(() => userRole.value === 'tresorier')
const isGestionnaire = computed(() => userRole.value === 'gestionnaire')

// Détecter le groupe actif selon la page courante
const groupeActif = computed(() => {
  const c = page.component
  if (c.startsWith('Admin/Inscriptions') || c.startsWith('Admin/Members')) return 'membres'
  if (['Admin/Events', 'Admin/Khassaides', 'Admin/Albums', 'Admin/Gallery'].some(p => c.startsWith(p))) return 'contenu'
  if (['Admin/Cotisations', 'Admin/Collections', 'Admin/Depenses'].some(p => c.startsWith(p))) return 'finances'
  if (['Admin/Materiels', 'Admin/Emprunts', 'Admin/Maintenances'].some(p => c.startsWith(p))) return 'materiels'
  return null
})

const groupOuvert = ref(groupeActif.value)

function toggleGroupe(nom) {
  groupOuvert.value = groupOuvert.value === nom ? null : nom
}

const sidebarOpen = ref(false)
</script>
