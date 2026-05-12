<template>
  <AdminLayout :title="item ? 'Modifier un média' : 'Ajouter des médias'">
    <FormCard
      :title="item ? 'Modifier le média' : 'Ajouter des médias'"
      subtitle="Photos ou vidéo pour la galerie du Dahira"
      :back="route('admin.galerie.index')"
    >
      <form @submit.prevent="soumettre" class="space-y-5">

        <!-- Type -->
        <FormField label="Type de média" :error="form.errors.type" required>
          <div class="grid grid-cols-2 gap-3">
            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
              :class="form.type === 'photo' ? 'border-[#0d2f6e] bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
              <input v-model="form.type" type="radio" value="photo" class="accent-[#0d2f6e]" />
              <div class="flex items-center gap-2">
                <PhotoIcon class="w-5 h-5 text-blue-600" />
                <span class="text-sm font-medium">Photo(s)</span>
              </div>
            </label>
            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition"
              :class="form.type === 'video' ? 'border-[#0d2f6e] bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
              <input v-model="form.type" type="radio" value="video" class="accent-[#0d2f6e]" />
              <div class="flex items-center gap-2">
                <VideoCameraIcon class="w-5 h-5 text-purple-600" />
                <span class="text-sm font-medium">Vidéo</span>
              </div>
            </label>
          </div>
        </FormField>

        <!-- Upload multiple photos -->
        <div v-if="form.type === 'photo' && !item">
          <div
            class="border-2 border-dashed border-gray-200 rounded-xl p-6 hover:border-[#0d2f6e]/40 transition text-center cursor-pointer"
            @dragover.prevent
            @drop.prevent="onDrop"
            @click="$refs.fileInput.click()"
          >
            <PhotoIcon class="w-10 h-10 mx-auto text-gray-300 mb-2" />
            <p class="text-sm font-medium text-gray-600">Clique ou glisse tes photos ici</p>
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — plusieurs fichiers à la fois — max 10 Mo/photo</p>
            <input
              ref="fileInput"
              type="file"
              accept="image/*"
              multiple
              class="hidden"
              @change="onFilesChange"
            />
          </div>
          <p v-if="form.errors.fichiers" class="text-red-500 text-xs mt-1">{{ form.errors.fichiers }}</p>

          <!-- Prévisualisations -->
          <div v-if="previews.length" class="mt-4">
            <div class="flex items-center justify-between mb-2">
              <p class="text-sm font-medium text-gray-700">{{ previews.length }} photo(s) sélectionnée(s)</p>
              <button type="button" @click="clearFiles" class="text-xs text-red-500 hover:underline">Tout effacer</button>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-2">
              <div v-for="(p, i) in previews" :key="i" class="relative group aspect-square">
                <img :src="p" class="w-full h-full object-cover rounded-lg" />
                <button
                  type="button"
                  @click.stop="removeFile(i)"
                  class="absolute top-1 right-1 w-5 h-5 rounded-full bg-red-500 text-white hidden group-hover:flex items-center justify-center text-xs"
                >
                  <XMarkIcon class="w-3 h-3" />
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Photo existante (mode édition) -->
        <FormField v-if="form.type === 'photo' && item" label="Remplacer la photo" :error="form.errors.fichiers" hint="JPG, PNG — max 10 Mo">
          <input type="file" accept="image/*" @change="onFilesChange" class="file-input" />
          <img v-if="item?.fichier" :src="`/storage/${item.fichier}`" class="mt-2 h-24 rounded-lg object-cover" />
        </FormField>

        <!-- Vidéo -->
        <FormField v-if="form.type === 'video'" label="URL de la vidéo" :error="form.errors.url_video" hint="YouTube ou Dailymotion">
          <input v-model="form.url_video" type="url" class="input" placeholder="https://www.youtube.com/watch?v=..." />
        </FormField>

        <div class="grid grid-cols-2 gap-4">
          <FormField label="Titre" :error="form.errors.titre">
            <input v-model="form.titre" type="text" class="input" placeholder="Titre (optionnel)" />
          </FormField>
          <FormField label="Album" :error="form.errors.album">
            <input v-model="form.album" type="text" class="input" placeholder="Ex: Magal 2025" />
          </FormField>
        </div>

        <FormField label="Description" :error="form.errors.description">
          <textarea v-model="form.description" rows="2" class="input" placeholder="Description courte..."></textarea>
        </FormField>

        <FormField label="Événement lié" :error="form.errors.event_id">
          <select v-model="form.event_id" class="input">
            <option value="">— Aucun —</option>
            <option v-for="e in events" :key="e.id" :value="e.id">{{ e.titre }}</option>
          </select>
        </FormField>

        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
          <input v-model="form.publie" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
          <div>
            <div class="text-sm font-medium text-gray-700">Publier</div>
            <div class="text-xs text-gray-400">Visible dans la galerie publique</div>
          </div>
        </label>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <Link :href="route('admin.galerie.index')" class="btn-secondary">Annuler</Link>
          <button type="submit" :disabled="form.processing" class="btn-primary">
            <CheckIcon class="w-4 h-4" />
            <span v-if="form.processing">Envoi en cours…</span>
            <span v-else-if="item">Mettre à jour</span>
            <span v-else>Ajouter {{ previews.length > 1 ? previews.length + ' photos' : '' }}</span>
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
import { CheckIcon, PhotoIcon, VideoCameraIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ item: Object, events: Array })

const fileInput = ref(null)
const previews = ref([])
const selectedFiles = ref([])

const form = useForm({
  titre:       props.item?.titre ?? '',
  description: props.item?.description ?? '',
  type:        props.item?.type ?? 'photo',
  fichiers:    [],
  url_video:   props.item?.url_video ?? '',
  album:       props.item?.album ?? '',
  event_id:    props.item?.event_id ?? '',
  publie:      props.item?.publie ?? true,
})

function onFilesChange(e) {
  addFiles(Array.from(e.target.files))
}

function onDrop(e) {
  addFiles(Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')))
}

function addFiles(files) {
  files.forEach(file => {
    selectedFiles.value.push(file)
    const reader = new FileReader()
    reader.onload = e => previews.value.push(e.target.result)
    reader.readAsDataURL(file)
  })
  form.fichiers = selectedFiles.value
}

function removeFile(i) {
  selectedFiles.value.splice(i, 1)
  previews.value.splice(i, 1)
  form.fichiers = selectedFiles.value
}

function clearFiles() {
  selectedFiles.value = []
  previews.value = []
  form.fichiers = []
  if (fileInput.value) fileInput.value.value = ''
}

function soumettre() {
  if (props.item) {
    form.post(route('admin.galerie.update', props.item.id), { forceFormData: true, _method: 'put' })
  } else {
    form.post(route('admin.galerie.store'), { forceFormData: true })
  }
}
</script>

<style scoped>
.input { @apply w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition; }
.file-input { @apply w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#0d2f6e] file:text-white hover:file:bg-blue-900 cursor-pointer; }
.btn-primary { @apply inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60; }
.btn-secondary { @apply inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition; }
</style>
