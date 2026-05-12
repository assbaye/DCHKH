<template>
  <MainLayout>
    <div class="max-w-3xl mx-auto px-4 py-10">

      <!-- Header -->
      <div class="mb-8">
        <Link :href="route('member.profil')" class="inline-flex items-center gap-1.5 text-sm text-[#c9973a] hover:underline mb-4">
          <ArrowLeftIcon class="w-4 h-4" />
          Retour au profil
        </Link>
        <h1 class="text-2xl font-bold text-[#0d2f6e] flex items-center gap-2">
          <Cog6ToothIcon class="w-6 h-6" />
          Modifier mon compte
        </h1>
        <p class="text-gray-400 text-sm mt-1">Gérez vos informations de connexion</p>
      </div>

      <div class="space-y-6">

        <!-- Infos du compte -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
            <UserIcon class="w-5 h-5 text-[#0d2f6e]" />
            Informations du compte
          </h2>

          <form @submit.prevent="profileForm.patch(route('profile.update'))" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom d'utilisateur</label>
              <input
                v-model="profileForm.name"
                type="text"
                required
                autofocus
                autocomplete="name"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
              />
              <p v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
              <input
                v-model="profileForm.email"
                type="email"
                required
                autocomplete="username"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
              />
              <p v-if="profileForm.errors.email" class="text-red-500 text-xs mt-1">{{ profileForm.errors.email }}</p>
            </div>

            <div class="flex items-center gap-4 pt-2">
              <button
                type="submit"
                :disabled="profileForm.processing"
                class="inline-flex items-center gap-2 bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition disabled:opacity-60"
              >
                <CheckIcon class="w-4 h-4" />
                {{ profileForm.processing ? 'Enregistrement...' : 'Enregistrer' }}
              </button>
              <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" enter-active-class="transition" leave-active-class="transition">
                <p v-if="profileForm.recentlySuccessful" class="text-sm text-green-600 font-medium">Modifications enregistrées.</p>
              </Transition>
            </div>
          </form>
        </div>

        <!-- Mot de passe -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
          <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
            <LockClosedIcon class="w-5 h-5 text-[#0d2f6e]" />
            Changer le mot de passe
          </h2>

          <form @submit.prevent="updatePassword" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe actuel</label>
              <input
                ref="currentPasswordInput"
                v-model="passwordForm.current_password"
                type="password"
                autocomplete="current-password"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
              />
              <p v-if="passwordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.current_password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Nouveau mot de passe</label>
              <input
                ref="passwordInput"
                v-model="passwordForm.password"
                type="password"
                autocomplete="new-password"
                placeholder="Minimum 8 caractères"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
              />
              <p v-if="passwordForm.errors.password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
              <input
                v-model="passwordForm.password_confirmation"
                type="password"
                autocomplete="new-password"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]/30 focus:border-[#0d2f6e] transition bg-gray-50"
              />
              <p v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password_confirmation }}</p>
            </div>

            <div class="flex items-center gap-4 pt-2">
              <button
                type="submit"
                :disabled="passwordForm.processing"
                class="inline-flex items-center gap-2 bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition disabled:opacity-60"
              >
                <LockClosedIcon class="w-4 h-4" />
                {{ passwordForm.processing ? 'Mise à jour...' : 'Mettre à jour' }}
              </button>
              <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" enter-active-class="transition" leave-active-class="transition">
                <p v-if="passwordForm.recentlySuccessful" class="text-sm text-green-600 font-medium">Mot de passe mis à jour.</p>
              </Transition>
            </div>
          </form>
        </div>

        <!-- Supprimer le compte -->
        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
          <h2 class="font-bold text-red-700 mb-2 flex items-center gap-2">
            <TrashIcon class="w-5 h-5" />
            Supprimer mon compte
          </h2>
          <p class="text-sm text-gray-500 mb-4">Une fois supprimé, toutes vos données seront définitivement effacées.</p>

          <button
            @click="showDeleteModal = true"
            class="inline-flex items-center gap-2 border border-red-300 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-xl text-sm transition"
          >
            <TrashIcon class="w-4 h-4" />
            Supprimer mon compte
          </button>

          <!-- Modal confirmation -->
          <div v-if="showDeleteModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-2xl shadow-xl p-6 max-w-sm w-full">
              <h3 class="font-bold text-gray-800 mb-2">Confirmer la suppression</h3>
              <p class="text-sm text-gray-500 mb-4">Entrez votre mot de passe pour confirmer.</p>
              <input
                v-model="deleteForm.password"
                type="password"
                placeholder="Mot de passe"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 mb-1 bg-gray-50"
              />
              <p v-if="deleteForm.errors.password" class="text-red-500 text-xs mb-3">{{ deleteForm.errors.password }}</p>
              <div class="flex gap-3 mt-4">
                <button @click="showDeleteModal = false" class="flex-1 border border-gray-200 text-gray-600 py-2 rounded-xl text-sm hover:bg-gray-50 transition">Annuler</button>
                <button @click="deleteAccount" :disabled="deleteForm.processing" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl text-sm font-semibold transition disabled:opacity-60">Supprimer</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import {
  Cog6ToothIcon, UserIcon, LockClosedIcon, TrashIcon,
  ArrowLeftIcon, CheckIcon,
} from '@heroicons/vue/24/outline'

const user = usePage().props.auth.user

const profileForm = useForm({
  name: user.name,
  email: user.email,
})

const passwordInput = ref(null)
const currentPasswordInput = ref(null)
const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

function updatePassword() {
  passwordForm.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => passwordForm.reset(),
    onError: () => {
      if (passwordForm.errors.password) {
        passwordForm.reset('password', 'password_confirmation')
        passwordInput.value?.focus()
      }
      if (passwordForm.errors.current_password) {
        passwordForm.reset('current_password')
        currentPasswordInput.value?.focus()
      }
    },
  })
}

const showDeleteModal = ref(false)
const deleteForm = useForm({ password: '' })

function deleteAccount() {
  deleteForm.delete(route('profile.destroy'), {
    preserveScroll: true,
    onError: () => {},
  })
}
</script>
