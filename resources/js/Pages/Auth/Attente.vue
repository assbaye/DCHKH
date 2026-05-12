<template>
  <div class="min-h-screen bg-[#0d2f6e] flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      <!-- Logo -->
      <div class="w-20 h-20 rounded-full border-4 border-[#c9973a] mx-auto mb-6 overflow-hidden">
        <img src="/images/logo.jpeg" alt="Logo DCHKH" class="w-full h-full object-cover" />
      </div>

      <!-- Icône attente -->
      <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-6">
        <ClockIcon class="w-8 h-8 text-amber-500" />
      </div>

      <h1 class="text-2xl font-bold text-white mb-3">Inscription reçue !</h1>
      <p class="text-blue-200 mb-2">
        Votre demande d'adhésion au Dahira <strong class="text-white">D.CH.KH.</strong> a bien été enregistrée.
      </p>
      <p class="text-blue-300 text-sm mb-8">
        Un administrateur va examiner votre demande et vous donner accès prochainement.
        Vous pouvez vous connecter dès que votre compte est approuvé.
      </p>

      <!-- Séparateur doré -->
      <div class="flex items-center gap-3 mb-8">
        <div class="flex-1 h-px bg-[#c9973a]/30"></div>
        <span class="text-[#c9973a] text-xs">بِسْمِ اللهِ</span>
        <div class="flex-1 h-px bg-[#c9973a]/30"></div>
      </div>

      <div class="bg-white/10 rounded-2xl p-5 text-left mb-8">
        <h3 class="text-white font-semibold text-sm mb-3 flex items-center gap-2">
          <InformationCircleIcon class="w-4 h-4 text-[#c9973a]" />
          Prochaines étapes
        </h3>
        <ul class="space-y-2 text-sm text-blue-200">
          <li class="flex items-start gap-2">
            <CheckIcon class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
            Votre inscription a été soumise
          </li>
          <li class="flex items-start gap-2">
            <ClockIcon class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" />
            En attente de validation par l'admin
          </li>
          <li class="flex items-start gap-2">
            <LockOpenIcon class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" />
            Vous pourrez vous connecter une fois approuvé
          </li>
        </ul>
      </div>

      <Link :href="route('login')" class="inline-flex items-center gap-2 bg-[#c9973a] text-white px-6 py-3 rounded-lg hover:bg-yellow-600 transition text-sm font-medium">
        <ArrowLeftIcon class="w-4 h-4" />
        Retour à la connexion
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ClockIcon, CheckIcon, LockOpenIcon, InformationCircleIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline'
</script>
