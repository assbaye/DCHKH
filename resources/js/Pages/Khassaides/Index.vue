<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto px-4 py-10">

      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0d2f6e] flex items-center gap-2">
          <MusicalNoteIcon class="w-7 h-7" />
          Bibliothèque Khassaïdes
        </h1>
        <p class="text-gray-400 text-sm mt-1">Enregistrements et poèmes du Dahira D.CH.KH.</p>
      </div>

      <!-- Recherche + filtre langue -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
        <div class="relative">
          <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input
            v-model="searchQuery"
            @keyup.enter="filtrer"
            type="text"
            placeholder="Titre, auteur, interprète…"
            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
          />
        </div>
      </div>

      <!-- Liste -->
      <div v-if="khassaides.data.length" class="space-y-3">
        <div
          v-for="k in khassaides.data"
          :key="k.id"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition-all duration-200"
          :class="{ 'border-[#0d2f6e]/30 ring-1 ring-[#0d2f6e]/20': playing === k.id }"
        >
          <!-- Bouton play/pause -->
          <button
            v-if="k.fichier_audio"
            @click="jouer(k)"
            class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-200"
            :class="playing === k.id
              ? 'bg-[#c9973a] hover:bg-amber-600 shadow-lg'
              : 'bg-[#0d2f6e] hover:bg-blue-900'"
          >
            <PauseIcon v-if="playing === k.id" class="w-5 h-5 text-white" />
            <PlayIcon v-else class="w-5 h-5 text-white ml-0.5" />
          </button>
          <div v-else class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
            <MusicalNoteIcon class="w-5 h-5 text-gray-300" />
          </div>

          <!-- Infos -->
          <div class="flex-1 min-w-0">
            <h3 class="font-semibold text-gray-800 truncate">{{ k.titre }}</h3>
            <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 mt-0.5">
              <p class="text-xs text-gray-400">{{ k.auteur }}</p>
              <span v-if="k.interprete" class="text-xs text-[#0d2f6e] font-medium flex items-center gap-1">
                <MicrophoneIcon class="w-3 h-3" />
                {{ k.interprete }}
              </span>
            </div>

            <!-- Barre de progression (si en lecture) -->
            <div v-if="playing === k.id" class="mt-2 flex items-center gap-2">
              <span class="text-[10px] text-gray-400 w-8 text-right">{{ formatDuree(currentTime) }}</span>
              <div class="flex-1 h-1.5 bg-gray-100 rounded-full cursor-pointer" @click="seekTo($event)">
                <div
                  class="h-full bg-[#c9973a] rounded-full transition-all"
                  :style="{ width: duration ? (currentTime / duration * 100) + '%' : '0%' }"
                ></div>
              </div>
              <span class="text-[10px] text-gray-400 w-8">{{ formatDuree(duration) }}</span>
            </div>
          </div>

          <!-- Badges + actions -->
          <div class="flex flex-col items-end gap-2 flex-shrink-0">
            <span class="text-[11px] px-2.5 py-0.5 rounded-full font-medium" :class="langueCouleur(k.langue)">
              {{ k.langue }}
            </span>
            <div class="flex items-center gap-2">
              <Link :href="route('khassaides.show', k.id)" class="text-[11px] text-[#c9973a] hover:underline whitespace-nowrap">
                Paroles
              </Link>
              <a
                v-if="k.telechargeable && k.fichier_audio"
                :href="`/storage/${k.fichier_audio}`"
                :download="nomFichier(k)"
                class="text-gray-400 hover:text-gray-600 transition"
                title="Télécharger"
              >
                <ArrowDownTrayIcon class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-20">
        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
          <MusicalNoteIcon class="w-8 h-8 text-gray-300" />
        </div>
        <p class="font-medium text-gray-500">Aucun khassaïde trouvé.</p>
      </div>

      <!-- Pagination -->
      <div v-if="khassaides.last_page > 1" class="flex justify-center gap-2 mt-10 flex-wrap">
        <Link
          v-for="page in khassaides.links"
          :key="page.label"
          :href="page.url || '#'"
          v-html="page.label"
          class="px-3 py-1.5 rounded-lg text-sm border transition"
          :class="page.active
            ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]'
            : page.url
              ? 'border-gray-200 text-gray-600 hover:bg-gray-50'
              : 'border-gray-100 text-gray-300 cursor-default pointer-events-none'"
        />
      </div>
    </div>

    <!-- Player fixe en bas -->
    <Transition
      enter-from-class="translate-y-full opacity-0"
      enter-active-class="transition-all duration-300"
      leave-to-class="translate-y-full opacity-0"
      leave-active-class="transition-all duration-300"
    >
      <div v-if="currentKhassaide" class="fixed bottom-0 left-0 right-0 bg-[#0d2f6e] text-white px-4 py-3 shadow-2xl z-50">
        <div class="max-w-4xl mx-auto flex items-center gap-4">
          <!-- Play/Pause -->
          <button @click="jouer(currentKhassaide)" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center flex-shrink-0 transition">
            <PauseIcon v-if="!audioRef?.paused" class="w-5 h-5" />
            <PlayIcon v-else class="w-5 h-5 ml-0.5" />
          </button>

          <!-- Infos -->
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-sm truncate">{{ currentKhassaide.titre }}</p>
            <p class="text-blue-300 text-xs truncate">
              {{ currentKhassaide.interprete || currentKhassaide.auteur }}
            </p>
            <!-- Progress -->
            <div class="flex items-center gap-2 mt-1">
              <span class="text-[10px] text-blue-300 w-7">{{ formatDuree(currentTime) }}</span>
              <div class="flex-1 h-1 bg-white/20 rounded-full cursor-pointer" @click="seekTo($event)">
                <div
                  class="h-full bg-[#c9973a] rounded-full transition-all"
                  :style="{ width: duration ? (currentTime / duration * 100) + '%' : '0%' }"
                ></div>
              </div>
              <span class="text-[10px] text-blue-300 w-7">{{ formatDuree(duration) }}</span>
            </div>
          </div>

          <!-- Volume + fermer -->
          <div class="flex items-center gap-3 flex-shrink-0">
            <SpeakerWaveIcon class="w-4 h-4 text-blue-300" />
            <input
              type="range" min="0" max="1" step="0.05"
              v-model="volume"
              @input="setVolume"
              class="w-16 accent-[#c9973a]"
            />
            <button @click="fermerPlayer" class="text-blue-300 hover:text-white transition ml-1">
              <XMarkIcon class="w-5 h-5" />
            </button>
          </div>
        </div>
        <audio
          ref="audioRef"
          :src="currentKhassaide ? `/storage/${currentKhassaide.fichier_audio}` : ''"
          @timeupdate="currentTime = audioRef?.currentTime ?? 0"
          @loadedmetadata="duration = audioRef?.duration ?? 0"
          @ended="playing = null"
        ></audio>
      </div>
    </Transition>

  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import {
  MusicalNoteIcon, MagnifyingGlassIcon, PlayIcon, PauseIcon,
  ArrowDownTrayIcon, MicrophoneIcon, SpeakerWaveIcon, XMarkIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({
  khassaides: Object,
  search: String,
})

const searchQuery  = ref(props.search || '')
const playing      = ref(null)
const currentKhassaide = ref(null)
const audioRef     = ref(null)
const currentTime  = ref(0)
const duration     = ref(0)
const volume       = ref(1)

function filtrer() {
  router.get(route('khassaides.index'), {
    search: searchQuery.value || undefined,
  }, { preserveState: true, replace: true })
}

function jouer(k) {
  if (!k.fichier_audio) return

  if (playing.value === k.id) {
    if (audioRef.value?.paused) audioRef.value.play()
    else audioRef.value?.pause()
    return
  }

  currentKhassaide.value = k
  playing.value = k.id
  setTimeout(() => {
    if (audioRef.value) {
      audioRef.value.load()
      audioRef.value.play()
    }
  }, 50)
}

function fermerPlayer() {
  audioRef.value?.pause()
  playing.value = null
  currentKhassaide.value = null
  currentTime.value = 0
  duration.value = 0
}

function seekTo(e) {
  if (!audioRef.value || !duration.value) return
  const rect = e.currentTarget.getBoundingClientRect()
  const ratio = (e.clientX - rect.left) / rect.width
  audioRef.value.currentTime = ratio * duration.value
}

function setVolume() {
  if (audioRef.value) audioRef.value.volume = volume.value
}

function formatDuree(s) {
  if (!s || isNaN(s)) return '0:00'
  const m = Math.floor(s / 60)
  const sec = Math.floor(s % 60).toString().padStart(2, '0')
  return `${m}:${sec}`
}

function nomFichier(k) {
  const ext = k.fichier_audio.split('.').pop()
  return `${k.titre}${k.interprete ? ' - ' + k.interprete : ''}.${ext}`
}

function langueCouleur(l) {
  if (l === 'arabe')   return 'bg-green-100 text-green-700'
  if (l === 'wolof')   return 'bg-purple-100 text-purple-700'
  if (l === 'français') return 'bg-blue-100 text-blue-700'
  return 'bg-gray-100 text-gray-600'
}
</script>
