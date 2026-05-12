<template>
  <MainLayout>
    <div class="max-w-7xl mx-auto px-4 py-10">
      <h1 class="text-3xl font-bold text-[#0d2f6e] mb-2">Bibliothèque Khassaïdes</h1>
      <p class="text-gray-500 mb-8">Poèmes et chants religieux de Cheikh Ahmadou Bamba Mbacké</p>

      <!-- Recherche + filtre -->
      <div class="flex flex-col sm:flex-row gap-4 mb-8">
        <form @submit.prevent="rechercher" class="flex gap-2 flex-1">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Rechercher un khassaïde..."
            class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]"
          />
          <button type="submit" class="bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition">
            Rechercher
          </button>
        </form>
        <select v-model="langueQuery" @change="filtrerLangue" class="border rounded-lg px-4 py-2 text-sm focus:outline-none">
          <option value="">Toutes les langues</option>
          <option v-for="lang in langues" :key="lang" :value="lang">{{ lang }}</option>
        </select>
      </div>

      <!-- Liste -->
      <div v-if="khassaides.data.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          v-for="k in khassaides.data"
          :key="k.id"
          class="bg-white rounded-xl shadow-sm p-5 flex gap-4 hover:shadow-md transition"
        >
          <!-- Bouton play -->
          <div class="flex-shrink-0">
            <button
              v-if="k.fichier_audio"
              @click="jouer(k)"
              class="w-12 h-12 rounded-full bg-[#0d2f6e] text-white flex items-center justify-center hover:bg-blue-800 transition"
            >
              <span v-if="playing === k.id">⏸</span>
              <span v-else>▶</span>
            </button>
            <div v-else class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-2xl">🎵</div>
          </div>

          <div class="flex-1 min-w-0">
            <h3 class="font-bold text-gray-800 truncate">{{ k.titre }}</h3>
            <p class="text-gray-500 text-sm">{{ k.auteur }}</p>
            <div class="flex items-center gap-3 mt-2">
              <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded">{{ k.langue }}</span>
              <Link :href="route('khassaides.show', k.id)" class="text-xs text-[#c9973a] hover:underline">Voir les paroles →</Link>
              <a v-if="k.telechargeable && k.fichier_audio" :href="`/storage/${k.fichier_audio}`" download class="text-xs text-gray-500 hover:text-gray-700">⬇ Télécharger</a>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center text-gray-400 py-16">Aucun khassaïde trouvé.</div>

      <!-- Audio player global -->
      <div v-if="currentKhassaide" class="fixed bottom-0 left-0 right-0 bg-[#0d2f6e] text-white px-6 py-4 flex items-center gap-4 shadow-2xl">
        <div class="flex-1">
          <div class="font-semibold text-sm">{{ currentKhassaide.titre }}</div>
          <div class="text-blue-300 text-xs">{{ currentKhassaide.auteur }}</div>
        </div>
        <audio ref="audioRef" :src="`/storage/${currentKhassaide.fichier_audio}`" @ended="playing = null" controls class="flex-1 max-w-md"></audio>
        <button @click="fermerPlayer" class="text-blue-300 hover:text-white text-xl">✕</button>
      </div>

      <!-- Pagination -->
      <div v-if="khassaides.last_page > 1" class="flex justify-center gap-2 mt-10">
        <Link
          v-for="page in khassaides.links"
          :key="page.label"
          :href="page.url || '#'"
          v-html="page.label"
          class="px-3 py-2 rounded text-sm border transition"
          :class="page.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'"
        />
      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  khassaides: Object,
  langues: Array,
  search: String,
  filtre_langue: String,
})

const searchQuery = ref(props.search || '')
const langueQuery = ref(props.filtre_langue || '')
const playing = ref(null)
const currentKhassaide = ref(null)
const audioRef = ref(null)

function rechercher() {
  router.get(route('khassaides.index'), { search: searchQuery.value, langue: langueQuery.value }, { preserveState: true })
}

function filtrerLangue() {
  router.get(route('khassaides.index'), { search: searchQuery.value, langue: langueQuery.value }, { preserveState: true })
}

function jouer(k) {
  if (playing.value === k.id) {
    audioRef.value?.pause()
    playing.value = null
    currentKhassaide.value = null
  } else {
    currentKhassaide.value = k
    playing.value = k.id
    setTimeout(() => audioRef.value?.play(), 100)
  }
}

function fermerPlayer() {
  audioRef.value?.pause()
  playing.value = null
  currentKhassaide.value = null
}
</script>
