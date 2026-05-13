<template>
  <MainLayout>
    <!-- Hero -->
    <section class="bg-[#1a4db5] text-white relative overflow-hidden">
      <div class="absolute inset-0 opacity-10 hero-pattern"></div>
      <div class="max-w-4xl mx-auto px-4 py-20 text-center relative z-10">
        <p class="text-2xl mb-4 tracking-widest opacity-80">بِسْمِ اللهِ الرَّحْمَنِ الرَّحِيمِ</p>
        <div class="w-28 h-28 rounded-full border-4 border-[#c9973a] mx-auto mb-6 overflow-hidden">
          <img src="/images/logo.jpeg" alt="Logo DCHKH" class="w-full h-full object-cover" />
        </div>
        <h1 class="text-3xl md:text-4xl font-bold mb-2">Daaray Cheikhoul Khadim</h1>
        <h2 class="text-xl md:text-2xl font-semibold text-[#c9973a] mb-2">Touba Diellerlou Sylla</h2>
        <p class="text-blue-200 text-sm mb-2">دائرة الشيخ الخديم · Louga, Sénégal</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('register')" class="bg-[#c9973a] hover:bg-yellow-600 text-white font-semibold px-8 py-3 rounded-lg transition">
            Rejoindre le Dahira
          </Link>
          <Link :href="route('events.index')" class="border border-white/50 hover:bg-white/10 text-white px-8 py-3 rounded-lg transition">
            Voir les événements
          </Link>
        </div>
      </div>
    </section>

    <!-- Statistiques -->
    <section class="bg-white shadow-sm py-8">
      <div class="max-w-4xl mx-auto px-4 grid grid-cols-3 gap-6 text-center">
        <div>
          <div class="text-3xl font-bold text-[#0d2f6e]">{{ stats.membres }}</div>
          <div class="text-gray-500 text-sm mt-1">Membres actifs</div>
        </div>
        <div>
          <div class="text-3xl font-bold text-[#0d2f6e]">{{ stats.annees }}+</div>
          <div class="text-gray-500 text-sm mt-1">Années d'existence</div>
        </div>
        <div>
          <div class="text-3xl font-bold text-[#0d2f6e]">{{ stats.evenements }}</div>
          <div class="text-gray-500 text-sm mt-1">Événements organisés</div>
        </div>
      </div>
    </section>

    <!-- Prochains événements -->
    <section class="max-w-7xl mx-auto px-4 py-12">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-[#0d2f6e]">Prochains Événements</h2>
        <Link :href="route('events.index')" class="text-[#c9973a] text-sm hover:underline">Voir tout →</Link>
      </div>

      <div v-if="prochains_evenements.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="event in prochains_evenements"
          :key="event.id"
          class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 border border-gray-100"
        >
          <!-- Image ou placeholder -->
          <div class="relative">
            <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.titre" class="w-full h-40 object-cover" />
            <div v-else class="w-full h-40 flex items-center justify-center" :class="categorieBg(event.categorie)">
              <component :is="categorieIcone(event.categorie)" class="w-12 h-12" :class="categorieIconColor(event.categorie)" />
            </div>
            <span class="absolute top-3 left-3 inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full" :class="categorieCouleur(event.categorie)">
              <component :is="categorieIcone(event.categorie)" class="w-3 h-3" />
              {{ event.categorie }}
            </span>
          </div>
          <div class="p-5">
            <h3 class="font-bold text-gray-800 mb-3 line-clamp-2">{{ event.titre }}</h3>
            <div class="space-y-1.5">
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <CalendarDaysIcon class="w-4 h-4 text-[#0d2f6e] flex-shrink-0" />
                <span>{{ formatDate(event.date_event) }}</span>
              </div>
              <div v-if="event.lieu" class="flex items-center gap-2 text-sm text-gray-500">
                <MapPinIcon class="w-4 h-4 text-[#0d2f6e] flex-shrink-0" />
                <span>{{ event.lieu }}</span>
              </div>
            </div>
            <Link :href="route('events.show', event.id)" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-[#0d2f6e] hover:gap-2.5 transition-all">
              En savoir plus <ArrowRightIcon class="w-4 h-4" />
            </Link>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-400 py-10">
        Aucun événement à venir pour le moment.
      </div>
    </section>

    <!-- À propos -->
    <section class="bg-[#f5f0e8] py-12">
      <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold text-[#0d2f6e] mb-4">À propos du Dahira</h2>
        <p class="text-gray-600 leading-relaxed">
          Le Dahira Daaray Cheikhoul Khadim Touba Diellerlou Sylla (D.CH.KH.) est une association religieuse islamique
          mouride établie à Dielerlou Syll, dans la région de Louga, au Sénégal. La communauté réunit des fidèles
          talibés dévoués à la mémoire et à l'enseignement de <strong>Cheikh Ahmadou Bamba Mbacké</strong> (Seexul Xadiim).
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center mt-8">
          <Link :href="route('khassaides.index')" class="bg-[#0d2f6e] text-white px-6 py-3 rounded-lg hover:bg-blue-900 transition">
            Écouter les Khassaïdes
          </Link>
          <Link :href="route('cotisations.index')" class="border-2 border-[#0d2f6e] text-[#0d2f6e] px-6 py-3 rounded-lg hover:bg-[#0d2f6e] hover:text-white transition">
            Contribuer
          </Link>
        </div>
      </div>
    </section>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import { CalendarDaysIcon, MapPinIcon, ArrowRightIcon, MusicalNoteIcon, UserGroupIcon, StarIcon, ChatBubbleLeftRightIcon } from '@heroicons/vue/24/outline'

defineProps({
  stats: Object,
  prochains_evenements: Array,
  collections_actives: Array,
})

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}

function categorieIcone(cat) {
  const map = { 'Journees Khassaides': MusicalNoteIcon, 'Dahira': UserGroupIcon, 'Ziar annuelle': StarIcon, 'Reunion': ChatBubbleLeftRightIcon }
  return map[cat] ?? CalendarDaysIcon
}
function categorieCouleur(cat) {
  const map = { 'Journees Khassaides': 'bg-purple-100 text-purple-700', 'Dahira': 'bg-yellow-100 text-yellow-700', 'Ziar annuelle': 'bg-green-100 text-green-700', 'Reunion': 'bg-blue-100 text-blue-700' }
  return map[cat] ?? 'bg-gray-100 text-gray-600'
}
function categorieBg(cat) {
  const map = { 'Journees Khassaides': 'bg-purple-50', 'Dahira': 'bg-yellow-50', 'Ziar annuelle': 'bg-green-50', 'Reunion': 'bg-blue-50' }
  return map[cat] ?? 'bg-gray-50'
}
function categorieIconColor(cat) {
  const map = { 'Journees Khassaides': 'text-purple-300', 'Dahira': 'text-yellow-300', 'Ziar annuelle': 'text-green-300', 'Reunion': 'text-blue-300' }
  return map[cat] ?? 'text-gray-300'
}
</script>

<style scoped>
.hero-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpolygon points='40,5 75,20 75,60 40,75 5,60 5,20'/%3E%3C/g%3E%3C/svg%3E");
  background-size: 80px;
}
</style>
