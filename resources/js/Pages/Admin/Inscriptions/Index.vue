<template>
  <AdminLayout title="Inscriptions en attente">
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div v-if="en_attente.length" class="bg-red-100 text-red-700 text-sm font-bold px-3 py-1 rounded-full flex items-center gap-1.5">
          <ExclamationCircleIcon class="w-4 h-4" />
          {{ en_attente.length }} en attente
        </div>
        <p v-else class="text-gray-500 text-sm">Aucune inscription en attente</p>
      </div>
    </div>

    <div v-if="en_attente.length" class="space-y-4">
      <div
        v-for="user in en_attente"
        :key="user.id"
        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4"
      >
        <!-- Avatar initiales -->
        <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-lg font-bold flex-shrink-0">
          {{ user.name[0].toUpperCase() }}
        </div>

        <div class="flex-1 min-w-0">
          <h3 class="font-bold text-gray-800">{{ user.name }}</h3>
          <p class="text-sm text-gray-500">{{ user.email }}</p>
          <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
            <ClockIcon class="w-3.5 h-3.5" />
            Inscrit le {{ formatDate(user.created_at) }}
          </p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 flex-shrink-0">
          <Link
            :href="route('admin.inscriptions.approuver', user.id)"
            method="patch"
            as="button"
            class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
          >
            <CheckCircleIcon class="w-4 h-4" />
            Approuver
          </Link>
          <Link
            :href="route('admin.inscriptions.rejeter', user.id)"
            method="delete"
            as="button"
            @click.prevent="rejeter(user)"
            class="inline-flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition"
          >
            <XCircleIcon class="w-4 h-4" />
            Rejeter
          </Link>
        </div>
      </div>
    </div>

    <!-- État vide -->
    <div v-else class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
      <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
        <CheckCircleIcon class="w-8 h-8 text-green-500" />
      </div>
      <h3 class="text-lg font-bold text-gray-700 mb-2">Tout est à jour !</h3>
      <p class="text-gray-400 text-sm">Aucune inscription en attente de validation.</p>
    </div>

    <ConfirmModal
      :show="confirmModal"
      title="Rejeter l'inscription"
      :message="`Êtes-vous sûr de vouloir rejeter et supprimer l'inscription de ${itemASupprimer?.name} ?`"
      confirmLabel="Rejeter"
      @confirm="confirmerRejet"
      @cancel="confirmModal = false"
    />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { CheckCircleIcon, XCircleIcon, ClockIcon, ExclamationCircleIcon } from '@heroicons/vue/24/outline'

defineProps({ en_attente: Array })

function formatDate(d) {
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })
}

const confirmModal = ref(false)
const itemASupprimer = ref(null)
function rejeter(user) { itemASupprimer.value = user; confirmModal.value = true }
function confirmerRejet() {
  router.delete(route('admin.inscriptions.rejeter', itemASupprimer.value.id))
  confirmModal.value = false
}
</script>
