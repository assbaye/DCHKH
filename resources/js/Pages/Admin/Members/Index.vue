<template>
  <AdminLayout title="Gestion des membres">
    <!-- Barre d'outils -->
    <div class="flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center mb-6">
      <form @submit.prevent="filtrer" class="flex gap-2 flex-1 max-w-md">
        <div class="relative flex-1">
          <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input v-model="search" type="text" placeholder="Rechercher un membre..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]" />
        </div>
        <select v-model="statut" @change="filtrer" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e]">
          <option value="">Tous</option>
          <option value="actif">Actifs</option>
          <option value="inactif">Inactifs</option>
        </select>
      </form>
      <Link :href="route('admin.membres.create')" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition flex-shrink-0">
        <UserPlusIcon class="w-4 h-4" /> Nouveau membre
      </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide border-b border-gray-100">
          <tr>
            <th class="text-left px-5 py-3">Membre</th>
            <th class="text-left px-5 py-3">Téléphone</th>
            <th class="text-left px-5 py-3">Ville</th>
            <th class="text-left px-5 py-3">Adhésion</th>
            <th class="text-center px-5 py-3">Rôle</th>
            <th class="text-center px-5 py-3">Statut</th>
            <th class="text-right px-5 py-3">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <tr v-for="m in membres.data" :key="m.id" class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#0d2f6e] text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                  {{ m.prenom[0] }}{{ m.nom[0] }}
                </div>
                <div>
                  <div class="font-medium text-gray-800">{{ m.prenom }} {{ m.nom }}</div>
                  <div v-if="m.user" class="text-xs text-gray-400">{{ m.user.email }}</div>
                </div>
              </div>
            </td>
            <td class="px-5 py-4 text-gray-500">{{ m.telephone || '—' }}</td>
            <td class="px-5 py-4 text-gray-500">{{ m.ville || '—' }}</td>
            <td class="px-5 py-4 text-gray-500">{{ formatDate(m.date_adhesion) }}</td>
            <td class="px-5 py-4 text-center">
              <span class="text-xs px-2 py-1 rounded-full font-medium" :class="roleBadge(m.role)">{{ m.role }}</span>
            </td>
            <td class="px-5 py-4 text-center">
              <span class="text-xs px-2 py-1 rounded-full font-medium" :class="m.statut === 'actif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                {{ m.statut }}
              </span>
            </td>
            <td class="px-5 py-4">
              <div class="flex justify-end items-center gap-2">
                <Link :href="route('admin.membres.edit', m.id)" class="inline-flex items-center gap-1.5 text-xs bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg hover:bg-amber-100 transition">
                  <PencilSquareIcon class="w-3.5 h-3.5" /> Modifier
                </Link>
                <button @click="supprimer(m)" class="inline-flex items-center gap-1.5 text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition">
                  <TrashIcon class="w-3.5 h-3.5" /> Supprimer
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="!membres.data.length" class="text-center py-16 text-gray-400">
        <UsersIcon class="w-12 h-12 mx-auto mb-3 text-gray-200" />
        Aucun membre trouvé.
      </div>
    </div>

    <div v-if="membres.last_page > 1" class="flex justify-center gap-2 mt-6">
      <Link v-for="p in membres.links" :key="p.label" :href="p.url || '#'" v-html="p.label"
        class="px-3 py-2 rounded-lg text-sm border transition"
        :class="p.active ? 'bg-[#0d2f6e] text-white border-[#0d2f6e]' : 'bg-white text-gray-600 hover:bg-gray-50'" />
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { MagnifyingGlassIcon, UserPlusIcon, PencilSquareIcon, TrashIcon, UsersIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ membres: Object, filters: Object })
const search = ref(props.filters?.search || '')
const statut = ref(props.filters?.statut || '')

function filtrer() {
  router.get(route('admin.membres.index'), { search: search.value, statut: statut.value }, { preserveState: true })
}
function formatDate(d) { return d ? new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' }) : '—' }
function roleBadge(r) {
  return { admin: 'bg-red-100 text-red-700', moderateur: 'bg-purple-100 text-purple-700', membre: 'bg-blue-100 text-blue-700' }[r] ?? 'bg-gray-100 text-gray-600'
}
function supprimer(m) {
  if (confirm(`Supprimer ${m.prenom} ${m.nom} ?`)) router.delete(route('admin.membres.destroy', m.id))
}
</script>
