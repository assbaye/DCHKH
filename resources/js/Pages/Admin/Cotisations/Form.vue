<template>
  <AdminLayout :title="cotisation ? 'Modifier une cotisation' : 'Enregistrer une cotisation'">
    <FormCard
      :title="cotisation ? 'Modifier la cotisation' : 'Nouvelle cotisation'"
      subtitle="Saisie manuelle d'une contribution d'un membre"
      :back="route('admin.cotisations.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">
        <FormField label="Membre" :error="form.errors.member_id" required>
          <select v-model="form.member_id" required class="input">
            <option value="">— Sélectionner un membre —</option>
            <option v-for="m in membres" :key="m.id" :value="m.id">{{ m.prenom }} {{ m.nom }}</option>
          </select>
        </FormField>

        <FormField label="Collecte associée" :error="form.errors.collection_id" hint="Optionnel">
          <select v-model="form.collection_id" class="input">
            <option value="">— Cotisation libre —</option>
            <option v-for="col in collections" :key="col.id" :value="col.id">{{ col.titre }}</option>
          </select>
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Montant (FCFA)" :error="form.errors.montant" required>
            <input v-model="form.montant" type="number" min="1" required class="input" placeholder="5000" />
          </FormField>
          <FormField label="Date de paiement" :error="form.errors.date_paiement" required>
            <input v-model="form.date_paiement" type="date" required class="input" />
          </FormField>
        </div>

        <FormField label="Mode de paiement" :error="form.errors.mode_paiement" required>
          <select v-model="form.mode_paiement" class="input">
            <option value="espèces">Espèces</option>
            <option value="wave">Wave</option>
            <option value="orange-money">Orange Money</option>
            <option value="free-money">Free Money</option>
            <option value="virement">Virement bancaire</option>
          </select>
        </FormField>

        <FormField label="Notes" :error="form.errors.notes" hint="Optionnel">
          <textarea v-model="form.notes" rows="2" class="input" placeholder="Remarques éventuelles..."></textarea>
        </FormField>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.cotisations.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            {{ cotisation ? 'Mettre à jour' : 'Enregistrer' }}
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

const props = defineProps({ cotisation: Object, membres: Array, collections: Array })
const form = useForm({
  member_id: props.cotisation?.member_id ?? '',
  collection_id: props.cotisation?.collection_id ?? '',
  montant: props.cotisation?.montant ?? '',
  date_paiement: props.cotisation?.date_paiement?.split('T')[0] ?? new Date().toISOString().split('T')[0],
  mode_paiement: props.cotisation?.mode_paiement ?? 'espèces',
  notes: props.cotisation?.notes ?? '',
})

function soumettre() {
  if (props.cotisation) form.transform(data => ({ ...data, _method: 'PUT' })).post(route('admin.cotisations.update', props.cotisation.id))
  else form.post(route('admin.cotisations.store'))
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
