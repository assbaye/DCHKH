<template>
  <AdminLayout :title="emprunt ? 'Modifier l\'emprunt' : 'Nouvel emprunt'">
    <div class="max-w-xl mx-auto">
      <div class="flex items-center gap-3 mb-6">
        <Link :href="route('admin.emprunts.index')" class="text-gray-400 hover:text-gray-600"><ArrowLeftIcon class="w-5 h-5" /></Link>
        <h1 class="text-xl font-bold text-gray-800">{{ emprunt ? 'Modifier l\'emprunt' : 'Nouvel emprunt' }}</h1>
      </div>

      <form @submit.prevent="soumettre" class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div v-if="!emprunt">
          <label class="block text-sm font-medium text-gray-700 mb-1">Matériel <span class="text-red-500">*</span></label>
          <select v-model="form.materiel_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
            <option value="">Choisir un matériel...</option>
            <option v-for="m in materiels" :key="m.id" :value="m.id">{{ m.nom }} ({{ m.quantite_disponible }} dispo)</option>
          </select>
          <p v-if="form.errors.materiel_id" class="text-red-500 text-xs mt-1">{{ form.errors.materiel_id }}</p>
        </div>

        <div v-if="!emprunt">
          <label class="block text-sm font-medium text-gray-700 mb-1">Membre <span class="text-red-500">*</span></label>
          <select v-model="form.member_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
            <option value="">Choisir un membre...</option>
            <option v-for="m in membres" :key="m.id" :value="m.id">{{ m.prenom }} {{ m.nom }}</option>
          </select>
          <p v-if="form.errors.member_id" class="text-red-500 text-xs mt-1">{{ form.errors.member_id }}</p>
        </div>

        <div v-if="!emprunt">
          <label class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
          <input v-model="form.quantite" type="number" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
          <p v-if="form.errors.quantite" class="text-red-500 text-xs mt-1">{{ form.errors.quantite }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4" v-if="!emprunt">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date d'emprunt <span class="text-red-500">*</span></label>
            <input v-model="form.date_emprunt" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Retour prévu <span class="text-red-500">*</span></label>
            <input v-model="form.date_retour_prevue" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
          </div>
        </div>

        <div v-if="emprunt">
          <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau retour prévu <span class="text-red-500">*</span></label>
          <input v-model="form.date_retour_prevue" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
          <textarea v-model="form.notes" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] resize-none"></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <Link :href="route('admin.emprunts.index')" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60">
            <CheckIcon class="w-4 h-4" />{{ emprunt ? 'Enregistrer' : 'Créer l\'emprunt' }}
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
  emprunt:   { type: Object, default: null },
  materiels: Array,
  membres:   Array,
})

const today = new Date().toISOString().split('T')[0]

const form = useForm({
  materiel_id:        props.emprunt?.materiel_id ?? '',
  member_id:          props.emprunt?.member_id ?? '',
  quantite:           props.emprunt?.quantite ?? 1,
  date_emprunt:       props.emprunt?.date_emprunt ?? today,
  date_retour_prevue: props.emprunt?.date_retour_prevue ?? '',
  notes:              props.emprunt?.notes ?? '',
})

function soumettre() {
  if (props.emprunt) {
    form.transform(data => ({ ...data, _method: 'PUT' }))
      .post(route('admin.emprunts.update', props.emprunt.id))
  } else {
    form.post(route('admin.emprunts.store'))
  }
}
</script>
