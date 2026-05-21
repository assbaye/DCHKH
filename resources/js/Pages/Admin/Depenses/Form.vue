<template>
  <AdminLayout :title="depense ? 'Modifier la dépense' : 'Nouvelle dépense'">
    <div class="max-w-xl mx-auto">
      <div class="flex items-center gap-3 mb-6">
        <Link :href="route('admin.depenses.index')" class="text-gray-400 hover:text-gray-600 transition">
          <ArrowLeftIcon class="w-5 h-5" />
        </Link>
        <h1 class="text-xl font-bold text-gray-800">{{ depense ? 'Modifier la dépense' : 'Nouvelle dépense' }}</h1>
      </div>

      <form @submit.prevent="soumettre" class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
          <input v-model="form.titre" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" placeholder="Ex : Achat chaises, Repas réunion..." />
          <p v-if="form.errors.titre" class="text-red-500 text-xs mt-1">{{ form.errors.titre }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
            <select v-model="form.categorie" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
              <option value="">Choisir...</option>
              <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
            </select>
            <p v-if="form.errors.categorie" class="text-red-500 text-xs mt-1">{{ form.errors.categorie }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Montant (XOF) <span class="text-red-500">*</span></label>
            <input v-model="form.montant" type="number" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
            <p v-if="form.errors.montant" class="text-red-500 text-xs mt-1">{{ form.errors.montant }}</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
          <input v-model="form.date_depense" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
          <p v-if="form.errors.date_depense" class="text-red-500 text-xs mt-1">{{ form.errors.date_depense }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] resize-none" placeholder="Détails supplémentaires..."></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Justificatif <span class="text-gray-400 text-xs">(photo ou PDF)</span></label>
          <input type="file" accept="image/*,.pdf" @change="e => form.justificatif = e.target.files[0]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none" />
          <div v-if="depense?.justificatif && !form.justificatif" class="mt-1.5 flex items-center gap-1.5 text-xs text-[#0d2f6e]">
            <PaperClipIcon class="w-3.5 h-3.5" />
            <a :href="'/storage/' + depense.justificatif" target="_blank" class="hover:underline">Voir le justificatif actuel</a>
          </div>
          <p v-if="form.errors.justificatif" class="text-red-500 text-xs mt-1">{{ form.errors.justificatif }}</p>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <Link :href="route('admin.depenses.index')" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60">
            <CheckIcon class="w-4 h-4" />
            {{ depense ? 'Enregistrer' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon, CheckIcon, PaperClipIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  depense:    { type: Object, default: null },
  categories: Array,
})

const today = new Date().toISOString().split('T')[0]

const form = useForm({
  titre:        props.depense?.titre ?? '',
  categorie:    props.depense?.categorie ?? '',
  montant:      props.depense?.montant ?? '',
  date_depense: props.depense?.date_depense ?? today,
  description:  props.depense?.description ?? '',
  justificatif: null,
})

function soumettre() {
  if (props.depense) {
    form.transform(data => ({ ...data, _method: 'PUT' }))
      .post(route('admin.depenses.update', props.depense.id))
  } else {
    form.post(route('admin.depenses.store'))
  }
}
</script>
