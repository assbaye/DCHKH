<template>
  <AdminLayout :title="materiel ? 'Modifier le matériel' : 'Nouveau matériel'">
    <div class="max-w-2xl mx-auto">
      <div class="flex items-center gap-3 mb-6">
        <Link :href="route('admin.materiels.index')" class="text-gray-400 hover:text-gray-600 transition">
          <ArrowLeftIcon class="w-5 h-5" />
        </Link>
        <h1 class="text-xl font-bold text-gray-800">{{ materiel ? 'Modifier le matériel' : 'Nouveau matériel' }}</h1>
      </div>

      <form @submit.prevent="soumettre" class="bg-white rounded-2xl shadow-sm p-6 space-y-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
            <input v-model="form.nom" type="text" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
            <p v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie <span class="text-red-500">*</span></label>
            <select v-model="form.categorie" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
              <option value="">Choisir...</option>
              <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
            </select>
            <p v-if="form.errors.categorie" class="text-red-500 text-xs mt-1">{{ form.errors.categorie }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Quantité <span class="text-red-500">*</span></label>
            <input v-model="form.quantite" type="number" min="1" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
            <p v-if="form.errors.quantite" class="text-red-500 text-xs mt-1">{{ form.errors.quantite }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">État <span class="text-red-500">*</span></label>
            <select v-model="form.etat" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
              <option value="bon">Bon état</option>
              <option value="usage">Usagé</option>
              <option value="hors_service">Hors service</option>
            </select>
            <p v-if="form.errors.etat" class="text-red-500 text-xs mt-1">{{ form.errors.etat }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Valeur estimée (XOF)</label>
            <input v-model="form.valeur" type="number" min="0" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" placeholder="Optionnel" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
            <input type="file" accept="image/*" @change="e => form.photo = e.target.files[0]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none" />
            <p v-if="form.errors.photo" class="text-red-500 text-xs mt-1">{{ form.errors.photo }}</p>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
          <textarea v-model="form.description" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] resize-none" placeholder="Détails, numéro de série, remarques..."></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <Link :href="route('admin.materiels.index')" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60">
            <CheckIcon class="w-4 h-4" />
            {{ materiel ? 'Enregistrer' : 'Ajouter' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { ArrowLeftIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  materiel:   { type: Object, default: null },
  categories: Array,
})

const form = useForm({
  nom:         props.materiel?.nom ?? '',
  categorie:   props.materiel?.categorie ?? '',
  quantite:    props.materiel?.quantite ?? 1,
  etat:        props.materiel?.etat ?? 'bon',
  description: props.materiel?.description ?? '',
  valeur:      props.materiel?.valeur ?? '',
  photo:       null,
})

function soumettre() {
  if (props.materiel) {
    form.transform(data => ({ ...data, _method: 'PUT' }))
      .post(route('admin.materiels.update', props.materiel.id))
  } else {
    form.post(route('admin.materiels.store'))
  }
}
</script>
