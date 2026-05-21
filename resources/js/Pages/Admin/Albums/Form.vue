<template>
  <AdminLayout :title="album ? 'Modifier l\'album' : 'Créer un album'">
    <FormCard
      :title="album ? 'Modifier l\'album' : 'Créer un album'"
      subtitle="Organisez les médias de la galerie en albums"
      :back="route('admin.albums.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">

        <FormField label="Nom de l'album" :error="form.errors.nom" required>
          <input v-model="form.nom" type="text" class="input" placeholder="Ex: Magal 2025" />
        </FormField>

        <FormField label="Description" :error="form.errors.description">
          <textarea v-model="form.description" rows="3" class="input" placeholder="Courte description de l'album..."></textarea>
        </FormField>

        <FormField label="Image de couverture" :error="form.errors.cover_image" hint="JPG, PNG — max 5 Mo">
          <div
            class="border-2 border-dashed border-gray-200 rounded-xl p-5 hover:border-[#0d2f6e]/40 transition text-center cursor-pointer"
            @dragover.prevent
            @drop.prevent="onDrop"
            @click="$refs.fileInput.click()"
          >
            <div v-if="preview">
              <img :src="preview" class="max-h-40 mx-auto rounded-lg object-contain" />
              <p class="text-xs text-gray-400 mt-2">Cliquer pour changer</p>
            </div>
            <div v-else-if="album?.cover_image">
              <img :src="`/storage/${album.cover_image}`" class="max-h-40 mx-auto rounded-lg object-contain" />
              <p class="text-xs text-gray-400 mt-2">Cliquer pour remplacer</p>
            </div>
            <div v-else>
              <PhotoIcon class="w-10 h-10 mx-auto text-gray-300 mb-2" />
              <p class="text-sm font-medium text-gray-600">Clique ou glisse une image ici</p>
            </div>
            <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
          </div>
        </FormField>

        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
          <input v-model="form.publie" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
          <div>
            <div class="text-sm font-medium text-gray-700">Album visible</div>
            <div class="text-xs text-gray-400">Affiché dans la galerie publique</div>
          </div>
        </label>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.albums.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            <span v-if="form.processing">Enregistrement…</span>
            <span v-else-if="album">Mettre à jour</span>
            <span v-else>Créer l'album</span>
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
import { ref } from 'vue'
import { CheckIcon, PhotoIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ album: Object })

const fileInput = ref(null)
const preview = ref(null)

const form = useForm({
  nom:         props.album?.nom ?? '',
  description: props.album?.description ?? '',
  cover_image: null,
  publie:      props.album?.publie ?? true,
})

function onFileChange(e) {
  const file = e.target.files[0]
  if (!file) return
  form.cover_image = file
  const reader = new FileReader()
  reader.onload = e => preview.value = e.target.result
  reader.readAsDataURL(file)
}

function onDrop(e) {
  const file = e.dataTransfer.files[0]
  if (file?.type.startsWith('image/')) {
    form.cover_image = file
    const reader = new FileReader()
    reader.onload = e => preview.value = e.target.result
    reader.readAsDataURL(file)
  }
}

function soumettre() {
  if (props.album) {
    form.post(route('admin.albums.update', props.album.id), { _method: 'put' })
  } else {
    form.post(route('admin.albums.store'))
  }
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
