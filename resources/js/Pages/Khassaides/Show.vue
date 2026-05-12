<template>
  <MainLayout>
    <div class="max-w-3xl mx-auto px-4 py-10">

      <!-- Back -->
      <Link :href="route('khassaides.index')" class="inline-flex items-center gap-1.5 text-sm text-[#c9973a] hover:underline mb-6">
        <ArrowLeftIcon class="w-4 h-4" />
        Retour aux khassaïdes
      </Link>

      <!-- Header -->
      <div class="bg-gradient-to-r from-[#0d2f6e] to-[#1a4db5] rounded-2xl p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 hero-pattern"></div>
        <div class="relative z-10">
          <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
              <MusicalNoteIcon class="w-7 h-7 text-white" />
            </div>
            <div class="flex-1">
              <h1 class="text-2xl font-bold leading-tight">{{ khassaide.titre }}</h1>
              <p class="text-blue-200 text-sm mt-1">{{ khassaide.auteur || 'Auteur inconnu' }}</p>
              <div class="flex flex-wrap gap-2 mt-3">
                <span class="text-xs px-3 py-1 rounded-full bg-white/20 font-medium">{{ khassaide.langue }}</span>
                <span v-if="khassaide.telechargeable" class="text-xs px-3 py-1 rounded-full bg-[#c9973a]/80 font-medium flex items-center gap-1">
                  <ArrowDownTrayIcon class="w-3 h-3" /> Téléchargeable
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Audio player -->
      <div v-if="khassaide.fichier_audio" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <SpeakerWaveIcon class="w-5 h-5 text-[#0d2f6e]" />
          Écouter
        </h2>
        <audio
          controls
          class="w-full"
          :src="`/storage/${khassaide.fichier_audio}`"
        ></audio>
        <a
          v-if="khassaide.telechargeable"
          :href="`/storage/${khassaide.fichier_audio}`"
          download
          class="mt-4 inline-flex items-center gap-2 text-sm text-[#0d2f6e] hover:underline font-medium"
        >
          <ArrowDownTrayIcon class="w-4 h-4" />
          Télécharger le fichier audio
        </a>
      </div>

      <!-- Description -->
      <div v-if="khassaide.description" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
          <InformationCircleIcon class="w-5 h-5 text-[#0d2f6e]" />
          Description
        </h2>
        <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">{{ khassaide.description }}</p>
      </div>

      <!-- Paroles -->
      <div v-if="khassaide.paroles" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
          <DocumentTextIcon class="w-5 h-5 text-[#0d2f6e]" />
          Paroles
        </h2>
        <div class="text-gray-700 text-sm leading-loose whitespace-pre-line font-[serif]">{{ khassaide.paroles }}</div>
      </div>

    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link } from '@inertiajs/vue3'
import {
  MusicalNoteIcon, ArrowLeftIcon, ArrowDownTrayIcon,
  SpeakerWaveIcon, InformationCircleIcon, DocumentTextIcon,
} from '@heroicons/vue/24/outline'

defineProps({ khassaide: Object })
</script>

<style scoped>
.hero-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpolygon points='40,5 75,20 75,60 40,75 5,60 5,20'/%3E%3C/g%3E%3C/svg%3E");
  background-size: 80px;
}
</style>
