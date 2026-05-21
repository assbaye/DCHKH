<template>
  <AdminLayout :title="reunion ? 'Modifier la réunion' : 'Nouvelle réunion'">
    <FormCard
      :title="reunion ? 'Modifier la réunion' : 'Nouvelle réunion'"
      subtitle="Planification et compte-rendu des réunions du Dahira"
      :back="route('admin.reunions.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">

        <FormField label="Titre" :error="form.errors.titre" required>
          <input v-model="form.titre" type="text" class="input" placeholder="Ex: Réunion mensuelle de mai" />
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Date" :error="form.errors.date_reunion" required>
            <input v-model="form.date_reunion" type="date" class="input" />
          </FormField>
          <FormField label="Lieu" :error="form.errors.lieu">
            <input v-model="form.lieu" type="text" class="input" placeholder="Ex: Dielerlou Syll" />
          </FormField>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Heure de début" :error="form.errors.heure_debut">
            <input v-model="form.heure_debut" type="time" class="input" />
          </FormField>
          <FormField label="Heure de fin" :error="form.errors.heure_fin">
            <input v-model="form.heure_fin" type="time" class="input" />
          </FormField>
        </div>

        <FormField label="Ordre du jour" :error="form.errors.ordre_du_jour">
          <textarea v-model="form.ordre_du_jour" rows="4" class="input" placeholder="Points à aborder..."></textarea>
        </FormField>

        <FormField label="Compte-rendu / PV" :error="form.errors.compte_rendu">
          <textarea v-model="form.compte_rendu" rows="5" class="input" placeholder="Résumé des décisions prises..."></textarea>
        </FormField>

        <FormField label="Statut" :error="form.errors.statut">
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
              :class="form.statut === 'planifiee' ? 'border-[#0d2f6e] bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
              <input v-model="form.statut" type="radio" value="planifiee" class="accent-[#0d2f6e]" />
              <span class="text-sm font-medium">Planifiée</span>
            </label>
            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
              :class="form.statut === 'terminee' ? 'border-green-600 bg-green-50' : 'border-gray-200 hover:border-gray-300'">
              <input v-model="form.statut" type="radio" value="terminee" class="accent-green-600" />
              <span class="text-sm font-medium">Terminée</span>
            </label>
          </div>
        </FormField>

        <!-- Participants -->
        <FormField label="Membres présents" :error="form.errors.participant_ids">
          <div class="border border-gray-200 rounded-xl overflow-hidden">
            <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex items-center justify-between">
              <span class="text-xs font-medium text-gray-500">{{ form.participant_ids.length }} membre(s) sélectionné(s)</span>
              <div class="flex gap-3">
                <button type="button" @click="toutSelectionner" class="text-xs text-[#0d2f6e] hover:underline">Tout sélectionner</button>
                <button type="button" @click="form.participant_ids = []" class="text-xs text-red-500 hover:underline">Effacer</button>
              </div>
            </div>
            <div class="max-h-56 overflow-y-auto divide-y divide-gray-50">
              <label
                v-for="membre in membres"
                :key="membre.id"
                class="flex items-center gap-3 px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition"
              >
                <input
                  type="checkbox"
                  :value="membre.id"
                  v-model="form.participant_ids"
                  class="w-4 h-4 accent-[#0d2f6e]"
                />
                <span class="text-sm text-gray-700">{{ membre.prenom }} {{ membre.nom }}</span>
              </label>
            </div>
          </div>
        </FormField>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.reunions.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            <span v-if="form.processing">Enregistrement…</span>
            <span v-else-if="reunion">Mettre à jour</span>
            <span v-else>Créer la réunion</span>
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

const props = defineProps({
  reunion:        Object,
  membres:        Array,
  participantIds: Array,
})

const form = useForm({
  titre:           props.reunion?.titre ?? '',
  date_reunion:    props.reunion?.date_reunion?.split('T')[0] ?? '',
  heure_debut:     props.reunion?.heure_debut ?? '',
  heure_fin:       props.reunion?.heure_fin ?? '',
  lieu:            props.reunion?.lieu ?? '',
  ordre_du_jour:   props.reunion?.ordre_du_jour ?? '',
  compte_rendu:    props.reunion?.compte_rendu ?? '',
  statut:          props.reunion?.statut ?? 'planifiee',
  participant_ids: props.participantIds ?? [],
})

function toutSelectionner() {
  form.participant_ids = props.membres.map(m => m.id)
}

function soumettre() {
  if (props.reunion) {
    form.transform(data => ({ ...data, _method: 'PUT' }))
        .post(route('admin.reunions.update', props.reunion.id))
  } else {
    form.post(route('admin.reunions.store'))
  }
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
