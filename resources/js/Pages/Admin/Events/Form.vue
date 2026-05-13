<template>
  <AdminLayout :title="event ? 'Modifier un événement' : 'Nouvel événement'">
    <FormCard
      :title="event ? 'Modifier l\'événement' : 'Créer un événement'"
      :subtitle="event ? event.titre : 'Remplissez les informations de l\'événement'"
      :back="route('admin.evenements.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">
        <FormField label="Titre" :error="form.errors.titre" required>
          <input v-model="form.titre" type="text" required class="input" placeholder="Titre de l'événement" />
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Catégorie" :error="form.errors.categorie" required>
            <select v-model="form.categorie" required class="input">
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </FormField>
          <FormField label="Date" :error="form.errors.date_event" required>
            <input v-model="form.date_event" type="date" required class="input" />
          </FormField>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Heure" :error="form.errors.heure_event" hint="Optionnel">
            <input v-model="form.heure_event" type="time" class="input" />
          </FormField>
          <FormField label="Lieu" :error="form.errors.lieu" hint="Optionnel">
            <input v-model="form.lieu" type="text" class="input" placeholder="Ex: Dielerlou Syll" />
          </FormField>
        </div>

        <FormField label="Description" :error="form.errors.description">
          <textarea v-model="form.description" rows="5" class="input" placeholder="Description complète de l'événement..."></textarea>
        </FormField>

        <FormField label="Image de couverture" :error="form.errors.image" hint="JPG, PNG — max 2 Mo">
          <input type="file" accept="image/*" @change="e => form.image = e.target.files[0]" class="file-input" />
          <img v-if="event?.image" :src="`/storage/${event.image}`" class="mt-2 h-28 rounded-xl object-cover" />
        </FormField>

        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
          <input v-model="form.publie" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
          <div>
            <div class="text-sm font-medium text-gray-700">Publier cet événement</div>
            <div class="text-xs text-gray-400">Visible sur la page publique des événements</div>
          </div>
        </label>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.evenements.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            {{ event ? 'Mettre à jour' : 'Créer l\'événement' }}
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
import { CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ event: Object, categories: Array })
const form = useForm({
  titre: props.event?.titre ?? '',
  description: props.event?.description ?? '',
  categorie: props.event?.categorie ?? 'Autre',
  date_event: props.event?.date_event?.split('T')[0] ?? '',
  heure_event: props.event?.heure_event ?? '',
  lieu: props.event?.lieu ?? '',
  image: null,
  publie: props.event?.publie ?? true,
})

function soumettre() {
  if (props.event) form.put(route('admin.evenements.update', props.event.id), { forceFormData: true })
  else form.post(route('admin.evenements.store'))
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.file-input { @apply w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#0d2f6e] file:text-white hover:file:bg-blue-900 cursor-pointer; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
