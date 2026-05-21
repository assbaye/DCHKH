<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-[#0d2f6e] shadow-lg sticky top-0 z-50 border-b-2 border-[#c9973a]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

          <!-- Logo + Nom -->
          <Link :href="route('home')" class="flex items-center gap-3 flex-shrink-0">
            <div class="w-10 h-10 rounded-full border-2 border-[#c9973a] overflow-hidden flex-shrink-0">
              <img src="/images/logo.jpeg" alt="Logo DCHKH" class="w-full h-full object-cover" />
            </div>
            <div class="hidden sm:block">
              <div class="text-white font-bold text-xs leading-tight">Daaray Cheikhoul Khadim</div>
              <div class="text-blue-300 text-xs">D.CH.KH. · Louga · Sénégal</div>
            </div>
          </Link>

          <!-- Navigation desktop -->
          <div class="hidden md:flex items-center gap-1">
            <NavLink :href="route('home')" :active="$page.component === 'Home'">
              <HomeIcon class="w-4 h-4 mr-1 inline" />Accueil
            </NavLink>
            <NavLink :href="route('events.index')" :active="$page.component.startsWith('Events')">
              <CalendarDaysIcon class="w-4 h-4 mr-1 inline" />Événements
            </NavLink>
            <NavLink :href="route('khassaides.index')" :active="$page.component.startsWith('Khassaides')">
              <MusicalNoteIcon class="w-4 h-4 mr-1 inline" />Khassaïdes
            </NavLink>
            <NavLink :href="route('gallery.index')" :active="$page.component.startsWith('Gallery')">
              <PhotoIcon class="w-4 h-4 mr-1 inline" />Galerie
            </NavLink>
            <NavLink :href="route('cotisations.index')" :active="$page.component.startsWith('Cotisations')">
              <BanknotesIcon class="w-4 h-4 mr-1 inline" />Cotisations
            </NavLink>
            <NavLink v-if="$page.props.auth.user" :href="route('reunions.index')" :active="$page.component.startsWith('Reunions')">
              <ClipboardIcon class="w-4 h-4 mr-1 inline" />Réunions
            </NavLink>
          </div>

          <!-- Auth + burger -->
          <div class="flex items-center gap-2">
            <template v-if="$page.props.auth.user">
              <Link :href="route('member.profil')" class="hidden sm:flex items-center gap-1.5 text-white text-sm hover:text-[#c9973a] transition">
                <UserCircleIcon class="w-5 h-5" />
                <span class="hidden lg:inline">Mon profil</span>
              </Link>
              <Link v-if="isAdmin" :href="route('admin.dashboard')" class="flex items-center gap-1.5 bg-[#c9973a] text-white text-xs px-3 py-1.5 rounded-lg hover:bg-yellow-600 transition">
                <Cog6ToothIcon class="w-4 h-4" />
                <span class="hidden sm:inline">{{ roleLabel }}</span>
              </Link>
              <Link :href="route('logout')" method="post" as="button" class="hidden md:flex items-center gap-1.5 text-blue-300 text-sm hover:text-white transition">
                <ArrowRightOnRectangleIcon class="w-5 h-5" />
              </Link>
            </template>
            <template v-else>
              <Link :href="route('login')" class="hidden md:flex items-center gap-1.5 text-white text-sm hover:text-[#c9973a] transition">
                <ArrowLeftOnRectangleIcon class="w-5 h-5" />
                <span class="hidden sm:inline">Connexion</span>
              </Link>
              <Link :href="route('register')" class="hidden md:flex items-center gap-1.5 bg-[#c9973a] text-white text-xs px-3 py-1.5 rounded-lg hover:bg-yellow-600 transition">
                <UserPlusIcon class="w-4 h-4" />
                <span class="hidden sm:inline">Rejoindre</span>
              </Link>
            </template>

            <!-- Burger mobile -->
            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-white p-1.5 rounded-lg hover:bg-blue-700 transition">
              <XMarkIcon v-if="mobileOpen" class="w-6 h-6" />
              <Bars3Icon v-else class="w-6 h-6" />
            </button>
          </div>

        </div>
      </div>

      <!-- Menu mobile -->
      <div v-if="mobileOpen" class="md:hidden bg-[#0a2558] border-t border-blue-800 px-4 py-3 space-y-1">
        <Link :href="route('home')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component === 'Home' ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <HomeIcon class="w-4 h-4" /> Accueil
        </Link>
        <Link :href="route('events.index')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component.startsWith('Events') ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <CalendarDaysIcon class="w-4 h-4" /> Événements
        </Link>
        <Link :href="route('khassaides.index')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component.startsWith('Khassaides') ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <MusicalNoteIcon class="w-4 h-4" /> Khassaïdes
        </Link>
        <Link :href="route('gallery.index')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component.startsWith('Gallery') ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <PhotoIcon class="w-4 h-4" /> Galerie
        </Link>
        <Link :href="route('cotisations.index')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component.startsWith('Cotisations') ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <BanknotesIcon class="w-4 h-4" /> Cotisations
        </Link>
        <Link v-if="$page.props.auth.user" :href="route('reunions.index')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm transition" :class="$page.component.startsWith('Reunions') ? 'bg-[#0d2f6e] text-white font-semibold' : 'text-blue-200 hover:bg-blue-800 hover:text-white'">
          <ClipboardIcon class="w-4 h-4" /> Réunions
        </Link>

        <div class="border-t border-blue-800 pt-2 mt-2 space-y-1">
          <template v-if="$page.props.auth.user">
            <Link :href="route('member.profil')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-blue-200 hover:bg-blue-800 hover:text-white transition">
              <UserCircleIcon class="w-4 h-4" /> Mon profil
            </Link>
            <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-2 w-full text-left px-3 py-2.5 rounded-lg text-red-400 text-sm hover:bg-blue-800 transition">
              <ArrowRightOnRectangleIcon class="w-4 h-4" /> Déconnexion
            </Link>
          </template>
          <template v-else>
            <Link :href="route('login')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-blue-200 hover:bg-blue-800 hover:text-white transition">
              <ArrowLeftOnRectangleIcon class="w-4 h-4" /> Connexion
            </Link>
            <Link :href="route('register')" @click="mobileOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm text-blue-200 hover:bg-blue-800 hover:text-white transition">
              <UserPlusIcon class="w-4 h-4" /> Rejoindre le Dahira
            </Link>
          </template>
        </div>
      </div>
    </nav>

    <!-- Contenu -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-[#0d2f6e] text-white mt-16">
      <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-full border border-[#c9973a] overflow-hidden">
              <img src="/images/logo.jpeg" alt="Logo" class="w-full h-full object-cover" />
            </div>
            <h3 class="font-bold text-[#c9973a]">Daaray Cheikhoul Khadim</h3>
          </div>
          <p class="text-blue-200 text-sm">Association islamique mouride dédiée à la mémoire et à l'enseignement de Cheikh Ahmadou Bamba Mbacké.</p>
        </div>
        <div>
          <h3 class="font-bold text-[#c9973a] mb-3">Navigation</h3>
          <ul class="space-y-2 text-sm text-blue-200">
            <li class="flex items-center gap-2"><CalendarDaysIcon class="w-4 h-4" /><Link :href="route('events.index')" class="hover:text-white transition">Événements</Link></li>
            <li class="flex items-center gap-2"><MusicalNoteIcon class="w-4 h-4" /><Link :href="route('khassaides.index')" class="hover:text-white transition">Khassaïdes</Link></li>
            <li class="flex items-center gap-2"><PhotoIcon class="w-4 h-4" /><Link :href="route('gallery.index')" class="hover:text-white transition">Galerie</Link></li>
            <li class="flex items-center gap-2"><BanknotesIcon class="w-4 h-4" /><Link :href="route('cotisations.index')" class="hover:text-white transition">Cotisations</Link></li>
          </ul>
        </div>
        <div>
          <h3 class="font-bold text-[#c9973a] mb-3">Contact</h3>
          <div class="space-y-2 text-sm text-blue-200">
            <p class="flex items-center gap-2"><MapPinIcon class="w-4 h-4 flex-shrink-0" />Dielerlou Syll, Louga, Sénégal</p>
            <p class="text-right font-arabic">دائرة الشيخ الخديم</p>
          </div>
        </div>
      </div>
      <div class="border-t border-blue-800 text-center py-4 text-blue-300 text-xs">
        © {{ new Date().getFullYear() }} D.CH.KH. — Daaray Cheikhoul Khadim Touba Diellerlou Sylla
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import NavLink from '@/Components/NavLink.vue'
import {
  HomeIcon, CalendarDaysIcon, MusicalNoteIcon, PhotoIcon, BanknotesIcon,
  UserCircleIcon, UserPlusIcon, Cog6ToothIcon, ArrowRightOnRectangleIcon,
  ArrowLeftOnRectangleIcon, MapPinIcon, Bars3Icon, XMarkIcon, ClipboardIcon,
} from '@heroicons/vue/24/outline'

const page = usePage()
const mobileOpen = ref(false)
const userRole = computed(() => page.props.auth?.user?.member?.role)
const isAdmin = computed(() => ['admin', 'secretaire', 'tresorier', 'gestionnaire'].includes(userRole.value))
const roleLabel = computed(() => {
  const labels = { admin: 'Admin', secretaire: 'Secrétaire', tresorier: 'Trésorier', gestionnaire: 'Gestionnaire' }
  return labels[userRole.value] ?? 'Admin'
})


</script>
