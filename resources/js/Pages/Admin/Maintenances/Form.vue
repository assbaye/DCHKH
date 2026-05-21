<template>
  <AdminLayout :title="maintenance ? 'Modifier la maintenance' : 'Nouvelle maintenance'">
    <div class="max-w-xl mx-auto">
      <div class="flex items-center gap-3 mb-6">
        <Link :href="route('admin.maintenances.index')" class="text-gray-400 hover:text-gray-600"><ArrowLeftIcon class="w-5 h-5" /></Link>
        <h1 class="text-xl font-bold text-gray-800">{{ maintenance ? 'Modifier la maintenance' : 'Nouvelle maintenance' }}</h1>
      </div>

      <form @submit.prevent="soumettre" class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Matériel <span class="text-red-500">*</span></label>
          <select v-model="form.materiel_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
            <option value="">Choisir un matériel...</option>
            <option v-for="m in materiels" :key="m.id" :value="m.id">{{ m.nom }}</option>
          </select>
          <p v-if="form.errors.materiel_id" class="text-red-500 text-xs mt-1">{{ form.errors.materiel_id }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
          <input v-model="form.titre" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" placeholder="Ex : Réparation micro, nettoyage sono..." />
          <p v-if="form.errors.titre" class="text-red-500 text-xs mt-1">{{ form.errors.titre }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
            <input v-model="form.date_maintenance" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-500">*</span></label>
            <select v-model="form.statut" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
              <option value="planifiee">Planifiée</option>
              <option value="en_cours">En cours</option>
              <option value="terminee">Terminée</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Coût (XOF)</label>
          <input v-model="form.cout" type="number" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" placeholder="Optionnel" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] resize-none"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <Link :href="route('admin.maintenances.index')" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60">
            <CheckIcon class="w-4 h-4" />{{ maintenance ? 'Enregistrer' : 'Créer' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  maintenance: { type: Object, default: null },
  materiels:   Array,
})

const today = new Date().toISOString().split('T')[0]

const form = useForm({
  materiel_id:      props.maintenance?.materiel_id ?? '',
  titre:            props.maintenance?.titre ?? '',
  description:      props.maintenance?.description ?? '',
  cout:             props.maintenance?.cout ?? '',
  date_maintenance: props.maintenance?.date_maintenance ?? today,
  statut:           props.maintenance?.statut ?? 'planifiee',
})

function soumettre() {
  if (props.maintenance) {
    form.transform(data => ({ ...data, _method: 'PUT' }))
      .post(route('admin.maintenances.update', props.maintenance.id))
  } else {
    form.post(route('admin.maintenances.store'))
  }
}
</script>
