<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0d2f6e] text-white flex flex-col fixed h-full z-40">
      <div class="p-5 border-b border-blue-800">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-full border-2 border-[#c9973a] overflow-hidden flex-shrink-0">
            <img src="/images/logo.jpeg" alt="Logo" class="w-full h-full object-cover" />
          </div>
          <div>
            <div class="text-xs font-bold text-white">Panel Admin</div>
            <div class="text-blue-300 text-xs">D.CH.KH.</div>
          </div>
        </div>
      </div>

      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <AdminNavLink :href="route('admin.dashboard')"            :icon="ChartBarIcon">Tableau de bord</AdminNavLink>
        <AdminNavLink :href="route('admin.inscriptions.index')" :icon="UserPlusIcon">
          Inscriptions
          <span v-if="$page.props.nb_inscriptions > 0" class="ml-auto bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none">
            {{ $page.props.nb_inscriptions }}
          </span>
        </AdminNavLink>
        <AdminNavLink :href="route('admin.membres.index')"      :icon="UsersIcon">Membres</AdminNavLink>
        <AdminNavLink :href="route('admin.evenements.index')"   :icon="CalendarDaysIcon">Événements</AdminNavLink>
        <AdminNavLink :href="route('admin.khassaides.index')"   :icon="MusicalNoteIcon">Khassaïdes</AdminNavLink>
        <AdminNavLink :href="route('admin.galerie.index')"      :icon="PhotoIcon">Galerie</AdminNavLink>
        <AdminNavLink :href="route('admin.collections.index')"  :icon="BanknotesIcon">Collections</AdminNavLink>
        <AdminNavLink :href="route('admin.cotisations.index')"  :icon="ClipboardDocumentListIcon">Cotisations</AdminNavLink>
      </nav>

      <div class="p-4 border-t border-blue-800 space-y-2">
        <Link :href="route('home')" class="flex items-center gap-2 text-blue-300 text-xs hover:text-white transition">
          <ArrowLeftIcon class="w-4 h-4" />
          Retour au site
        </Link>
        <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-2 text-red-400 text-xs hover:text-red-300 transition">
          <ArrowRightOnRectangleIcon class="w-4 h-4" />
          Déconnexion
        </Link>
      </div>
    </aside>

    <!-- Contenu principal -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">
      <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-30">
        <h1 class="text-lg font-semibold text-gray-800">{{ title }}</h1>
        <div class="flex items-center gap-2 text-sm text-gray-500">
          <UserCircleIcon class="w-5 h-5 text-gray-400" />
          {{ $page.props.auth.user?.name }}
        </div>
      </header>

      <main class="flex-1 p-8">
        <slot />
      </main>
    </div>
  </div>
  <Toast />
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminNavLink from '@/Components/AdminNavLink.vue'
import Toast from '@/Components/Toast.vue'
import {
  ChartBarIcon,
  UsersIcon,
  UserPlusIcon,
  CalendarDaysIcon,
  MusicalNoteIcon,
  PhotoIcon,
  BanknotesIcon,
  ClipboardDocumentListIcon,
  ArrowLeftIcon,
  ArrowRightOnRectangleIcon,
  UserCircleIcon,
} from '@heroicons/vue/24/outline'

defineProps({ title: { type: String, default: 'Administration' } })
</script>
