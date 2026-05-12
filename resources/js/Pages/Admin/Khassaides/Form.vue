<template>
  <AdminLayout :title="khassaide ? 'Modifier un khassaïde' : 'Nouveau khassaïde'">
    <FormCard
      :title="khassaide ? 'Modifier le khassaïde' : 'Ajouter un khassaïde'"
      subtitle="Poème ou chant religieux de Cheikh Ahmadou Bamba"
      :back="route('admin.khassaides.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">
        <FormField label="Titre" :error="form.errors.titre" required>
          <input v-model="form.titre" type="text" required class="input" placeholder="Titre du khassaïde" />
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Auteur" :error="form.errors.auteur" required>
            <input v-model="form.auteur" type="text" required class="input" placeholder="Auteur" />
          </FormField>
          <FormField label="Langue" :error="form.errors.langue" required>
            <select v-model="form.langue" class="input">
              <option v-for="l in langues" :key="l" :value="l">{{ l }}</option>
            </select>
          </FormField>
        </div>

        <FormField label="Description" :error="form.errors.description">
          <textarea v-model="form.description" rows="3" class="input" placeholder="Description courte..."></textarea>
        </FormField>

        <FormField label="Paroles / Texte" :error="form.errors.paroles" hint="Copiez les paroles en wolof ou arabe">
          <textarea v-model="form.paroles" rows="6" class="input font-mono text-xs" placeholder="Paroles du khassaïde..."></textarea>
        </FormField>

        <FormField label="Fichier audio (MP3 / WAV)" :error="form.errors.fichier_audio" hint="Max 20 Mo">
          <input type="file" accept=".mp3,.wav,.ogg" @change="e => form.fichier_audio = e.target.files[0]" class="file-input" />
          <div v-if="khassaide?.fichier_audio" class="mt-2 flex items-center gap-2 text-xs text-gray-500">
            <MusicalNoteIcon class="w-4 h-4 text-green-500" />
            Fichier audio existant
          </div>
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
            <input v-model="form.telechargeable" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
            <div>
              <div class="text-sm font-medium text-gray-700">Téléchargeable</div>
              <div class="text-xs text-gray-400">Les membres peuvent télécharger</div>
            </div>
          </label>
          <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
            <input v-model="form.publie" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
            <div>
              <div class="text-sm font-medium text-gray-700">Publier</div>
              <div class="text-xs text-gray-400">Visible sur le site</div>
            </div>
          </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.khassaides.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            {{ khassaide ? 'Mettre à jour' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </FormCard>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import FormCard from '@/Components/FormCard.vue'
import FormField from '@/Components/FormField.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { CheckIcon, MusicalNoteIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ khassaide: Object, langues: Array })
const form = useForm({
  titre: props.khassaide?.titre ?? '',
  auteur: props.khassaide?.auteur ?? 'Cheikh Ahmadou Bamba',
  description: props.khassaide?.description ?? '',
  paroles: props.khassaide?.paroles ?? '',
  langue: props.khassaide?.langue ?? 'arabe',
  fichier_audio: null,
  telechargeable: props.khassaide?.telechargeable ?? true,
  publie: props.khassaide?.publie ?? true,
})

function soumettre() {
  if (props.khassaide) form.post(route('admin.khassaides.update', props.khassaide.id), { method: 'put' })
  else form.post(route('admin.khassaides.store'))
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.file-input { @apply w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#0d2f6e] file:text-white hover:file:bg-blue-900 cursor-pointer; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
