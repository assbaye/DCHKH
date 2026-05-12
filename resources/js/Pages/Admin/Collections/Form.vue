<template>
  <AdminLayout :title="collection ? 'Modifier une collecte' : 'Nouvelle collecte'">
    <FormCard
      :title="collection ? 'Modifier la collecte' : 'Créer une collecte'"
      subtitle="Définissez l'objectif et la période de la collecte"
      :back="route('admin.collections.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">
        <FormField label="Titre" :error="form.errors.titre" required>
          <input v-model="form.titre" type="text" required class="input" placeholder="Ex: Collecte Magal 2026" />
        </FormField>

        <FormField label="Description" :error="form.errors.description">
          <textarea v-model="form.description" rows="3" class="input" placeholder="Description de la collecte..."></textarea>
        </FormField>

        <FormField label="Objectif (FCFA)" :error="form.errors.objectif" required>
          <input v-model="form.objectif" type="number" min="0" required class="input" placeholder="500000" />
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Date de début" :error="form.errors.date_debut" required>
            <input v-model="form.date_debut" type="date" required class="input" />
          </FormField>
          <FormField label="Date de fin" :error="form.errors.date_fin" hint="Optionnelle">
            <input v-model="form.date_fin" type="date" class="input" />
          </FormField>
        </div>

        <FormField label="Événement lié" :error="form.errors.event_id" hint="Optionnel">
          <select v-model="form.event_id" class="input">
            <option value="">— Aucun événement —</option>
            <option v-for="e in events" :key="e.id" :value="e.id">{{ e.titre }}</option>
          </select>
        </FormField>

        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
          <input v-model="form.active" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
          <div>
            <div class="text-sm font-medium text-gray-700">Collecte active</div>
            <div class="text-xs text-gray-400">La collecte accepte encore des contributions</div>
          </div>
        </label>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.collections.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            {{ collection ? 'Mettre à jour' : 'Créer la collecte' }}
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

const props = defineProps({ collection: Object, events: Array })
const form = useForm({
  titre: props.collection?.titre ?? '',
  description: props.collection?.description ?? '',
  objectif: props.collection?.objectif ?? '',
  date_debut: props.collection?.date_debut?.split('T')[0] ?? new Date().toISOString().split('T')[0],
  date_fin: props.collection?.date_fin?.split('T')[0] ?? '',
  event_id: props.collection?.event_id ?? '',
  active: props.collection?.active ?? true,
})

function soumettre() {
  if (props.collection) form.put(route('admin.collections.update', props.collection.id))
  else form.post(route('admin.collections.store'))
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
