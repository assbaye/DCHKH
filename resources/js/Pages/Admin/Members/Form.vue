<template>
  <AdminLayout :title="membre ? 'Modifier un membre' : 'Nouveau membre'">
    <FormCard
      :title="membre ? 'Modifier le membre' : 'Ajouter un membre'"
      :subtitle="membre ? `${membre.prenom} ${membre.nom}` : 'Remplissez les informations du nouveau membre'"
      :back="route('admin.membres.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
          <FormField label="Prénom" id="prenom" :error="form.errors.prenom" required>
            <input id="prenom" v-model="form.prenom" type="text" required class="input" placeholder="Prénom" />
          </FormField>
          <FormField label="Nom" id="nom" :error="form.errors.nom" required>
            <input id="nom" v-model="form.nom" type="text" required class="input" placeholder="Nom de famille" />
          </FormField>
        </div>

        <FormField label="Téléphone" id="telephone" :error="form.errors.telephone">
          <input id="telephone" v-model="form.telephone" type="tel" class="input" placeholder="+221 77 000 00 00" />
        </FormField>

        <FormField label="Adresse" id="adresse" :error="form.errors.adresse">
          <input id="adresse" v-model="form.adresse" type="text" class="input" placeholder="Adresse complète" />
        </FormField>

        <FormField label="Ville" id="ville" :error="form.errors.ville">
          <input id="ville" v-model="form.ville" type="text" class="input" placeholder="Ville" />
        </FormField>

        <div class="grid grid-cols-3 gap-4">
          <FormField label="Date d'adhésion" id="date_adhesion" :error="form.errors.date_adhesion" required>
            <input id="date_adhesion" v-model="form.date_adhesion" type="date" required class="input" />
          </FormField>
          <FormField label="Statut" id="statut" :error="form.errors.statut" required>
            <select id="statut" v-model="form.statut" class="input">
              <option value="actif">Actif</option>
              <option value="inactif">Inactif</option>
            </select>
          </FormField>
          <FormField label="Rôle" id="role" :error="form.errors.role" required>
            <select id="role" v-model="form.role" class="input">
              <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
            </select>
          </FormField>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.membres.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            {{ membre ? 'Mettre à jour' : 'Ajouter le membre' }}
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

const props = defineProps({ membre: Object, roles: Array })

const form = useForm({
  prenom: props.membre?.prenom ?? '',
  nom: props.membre?.nom ?? '',
  telephone: props.membre?.telephone ?? '',
  adresse: props.membre?.adresse ?? '',
  ville: props.membre?.ville ?? 'Dielerlou Syll',
  date_adhesion: props.membre?.date_adhesion?.split('T')[0] ?? new Date().toISOString().split('T')[0],
  statut: props.membre?.statut ?? 'actif',
  role: props.membre?.role ?? 'membre',
})

function soumettre() {
  if (props.membre) form.put(route('admin.membres.update', props.membre.id))
  else form.post(route('admin.membres.store'))
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
