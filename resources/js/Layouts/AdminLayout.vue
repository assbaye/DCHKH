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
        <AdminNavLink :href="route('admin.dashboard')" :icon="ChartBarIcon" @click="sidebarOpen = false">Tableau de bord</AdminNavLink>
        <AdminNavLink :href="route('admin.inscriptions.index')" :icon="UserPlusIcon" @click="sidebarOpen = false">
          Inscriptions
          <span v-if="$page.props.nb_inscriptions > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none">
            {{ $page.props.nb_inscriptions }}
          </span>
        </AdminNavLink>
        <AdminNavLink :href="route('admin.membres.index')"     :icon="UsersIcon"                   @click="sidebarOpen = false">Membres</AdminNavLink>
        <AdminNavLink :href="route('admin.evenements.index')"  :icon="CalendarDaysIcon"            @click="sidebarOpen = false">Événements</AdminNavLink>
        <AdminNavLink :href="route('admin.khassaides.index')"  :icon="MusicalNoteIcon"             @click="sidebarOpen = false">Khassaïdes</AdminNavLink>
        <AdminNavLink :href="route('admin.albums.index')"     :icon="FolderOpenIcon"               @click="sidebarOpen = false">Albums</AdminNavLink>
        <AdminNavLink :href="route('admin.galerie.index')"     :icon="PhotoIcon"                   @click="sidebarOpen = false">Galerie</AdminNavLink>
        <AdminNavLink :href="route('admin.collections.index')" :icon="BanknotesIcon"               @click="sidebarOpen = false">Collections</AdminNavLink>
        <AdminNavLink :href="route('admin.cotisations.index')" :icon="ClipboardDocumentListIcon"   @click="sidebarOpen = false">Cotisations</AdminNavLink>
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
        <!-- Burger mobile -->
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
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import AdminNavLink from '@/Components/AdminNavLink.vue'
import Toast from '@/Components/Toast.vue'
import {
  ChartBarIcon, UsersIcon, UserPlusIcon, CalendarDaysIcon, MusicalNoteIcon,
  PhotoIcon, BanknotesIcon, ClipboardDocumentListIcon, ArrowLeftIcon,
  ArrowRightOnRectangleIcon, UserCircleIcon, Bars3Icon, XMarkIcon, FolderOpenIcon,
} from '@heroicons/vue/24/outline'

defineProps({ title: { type: String, default: 'Administration' } })

const sidebarOpen = ref(false)
</script>
