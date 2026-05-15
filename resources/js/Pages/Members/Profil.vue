<template>
  <MainLayout>
    <div class="max-w-4xl mx-auto px-4 py-10">

      <!-- En-tête profil -->
      <div class="bg-gradient-to-r from-[#0d2f6e] to-[#1a4db5] rounded-2xl p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-5 hero-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6">
          <!-- Avatar -->
          <div class="w-24 h-24 rounded-full border-4 border-[#c9973a] overflow-hidden flex-shrink-0 bg-[#0d2f6e]">
            <img v-if="member?.photo" :src="`/storage/${member.photo}`" class="w-full h-full object-cover" alt="Photo profil" />
            <div v-else class="w-full h-full flex items-center justify-center text-3xl font-bold text-white">
              {{ initiales }}
            </div>
          </div>

          <div class="text-center sm:text-left flex-1">
            <div v-if="member">
              <h1 class="text-2xl font-bold">{{ member.prenom }} {{ member.nom }}</h1>
              <p class="text-blue-200 text-sm mt-1">{{ $page.props.auth.user.email }}</p>
              <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                <span class="text-xs px-3 py-1 rounded-full font-medium bg-white/20">{{ member.role }}</span>
                <span class="text-xs px-3 py-1 rounded-full font-medium" :class="member.statut === 'actif' ? 'bg-green-500/80' : 'bg-gray-500/80'">
                  {{ member.statut }}
                </span>
                <span class="text-xs px-3 py-1 rounded-full bg-[#c9973a]/80">
                  Membre depuis {{ formatDate(member.date_adhesion) }}
                </span>
              </div>
            </div>
            <div v-else>
              <h1 class="text-2xl font-bold">{{ $page.props.auth.user.name }}</h1>
              <p class="text-blue-200 text-sm mt-1">{{ $page.props.auth.user.email }}</p>
              <p class="text-amber-300 text-sm mt-3 flex items-center gap-2">
                <ExclamationTriangleIcon class="w-4 h-4" />
                Votre profil membre n'est pas encore complété.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Infos personnelles -->
        <div class="md:col-span-2 space-y-6">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
              <UserIcon class="w-5 h-5 text-[#0d2f6e]" />
              Informations personnelles
            </h2>

            <div v-if="member" class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Prénom</p>
                <p class="text-gray-800 font-medium">{{ member.prenom }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Nom</p>
                <p class="text-gray-800 font-medium">{{ member.nom }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Téléphone</p>
                <p class="text-gray-800 font-medium">{{ member.telephone || '—' }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Ville</p>
                <p class="text-gray-800 font-medium">{{ member.ville || '—' }}</p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Adresse</p>
                <p class="text-gray-800 font-medium">{{ member.adresse || '—' }}</p>
              </div>
            </div>

            <div v-else class="text-center py-6 text-gray-400">
              <UserIcon class="w-10 h-10 mx-auto mb-2 text-gray-200" />
              <p class="text-sm">Profil non encore créé par l'administration.</p>
            </div>
          </div>

          <!-- Cotisations -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
              <BanknotesIcon class="w-5 h-5 text-[#0d2f6e]" />
              Historique des cotisations
            </h2>

            <div v-if="member?.cotisations?.length">
              <div class="space-y-3">
                <div v-for="cot in member.cotisations" :key="cot.id" class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                  <div>
                    <p class="text-sm font-medium text-gray-800">{{ cot.collection?.titre || 'Cotisation libre' }}</p>
                    <p class="text-xs text-gray-400">{{ formatDate(cot.date_paiement) }} · {{ cot.mode_paiement }}</p>
                  </div>
                  <span class="text-green-600 font-bold text-sm">{{ formatMontant(cot.montant) }}</span>
                </div>
              </div>
              <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between">
                <span class="text-sm text-gray-500 font-medium">Total contribué</span>
                <span class="font-bold text-[#0d2f6e]">{{ formatMontant(totalCotisations) }}</span>
              </div>
            </div>

            <div v-else class="text-center py-8 text-gray-400">
              <BanknotesIcon class="w-10 h-10 mx-auto mb-2 text-gray-200" />
              <p class="text-sm">Aucune cotisation enregistrée.</p>
            </div>
          </div>
        </div>

        <!-- Colonne droite -->
        <div class="space-y-6">

          <!-- Photo de profil -->
          <div v-if="member" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <CameraIcon class="w-5 h-5 text-[#0d2f6e]" />
              Photo de profil
            </h2>
            <div class="flex items-center gap-5">
              <!-- Aperçu -->
              <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-[#c9973a] flex-shrink-0 bg-[#0d2f6e]">
                <img v-if="croppedPreview" :src="croppedPreview" class="w-full h-full object-cover" />
                <img v-else-if="member.photo" :src="`/storage/${member.photo}`" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-xl font-bold text-white">
                  {{ initiales }}
                </div>
              </div>
              <!-- Upload -->
              <div class="flex-1">
                <label class="cursor-pointer inline-flex items-center gap-2 border border-dashed border-gray-300 text-gray-500 hover:border-[#0d2f6e] hover:text-[#0d2f6e] px-4 py-2 rounded-lg text-sm transition">
                  <ArrowUpTrayIcon class="w-4 h-4" />
                  Choisir une photo
                  <input type="file" accept="image/*" class="hidden" @change="onPhotoChange" />
                </label>
                <p class="text-xs text-gray-400 mt-1.5">JPG, PNG, WEBP — max 2 Mo</p>
                <button
                  v-if="croppedBlob"
                  @click="uploadPhoto"
                  :disabled="photoForm.processing"
                  class="mt-2 inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-4 py-1.5 rounded-lg text-sm hover:bg-blue-900 transition disabled:opacity-60"
                >
                  <CheckIcon class="w-4 h-4" />
                  {{ photoForm.processing ? 'Envoi...' : 'Enregistrer la photo' }}
                </button>
                <p v-if="photoForm.errors.photo" class="text-red-500 text-xs mt-1">{{ photoForm.errors.photo }}</p>
              </div>
            </div>
          </div>

          <!-- Modal recadrage -->
          <div v-if="showCropper" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
              <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <CameraIcon class="w-5 h-5 text-[#0d2f6e]" />
                Recadrer la photo
              </h3>

              <!-- Zone de recadrage -->
              <div
                ref="cropArea"
                class="relative mx-auto bg-gray-900 select-none overflow-hidden"
                style="width:280px; height:280px; border-radius:50%; cursor:grab;"
                @mousedown.prevent="startDrag"
                @mousemove.prevent="onDrag"
                @mouseup="stopDrag"
                @mouseleave="stopDrag"
                @touchstart.prevent="startDrag"
                @touchmove.prevent="onDrag"
                @touchend="stopDrag"
              >
                <img
                  v-if="rawPreview"
                  :src="rawPreview"
                  draggable="false"
                  :style="{
                    position: 'absolute',
                    left: imgX + 'px',
                    top: imgY + 'px',
                    width: imgW + 'px',
                    height: imgH + 'px',
                    pointerEvents: 'none',
                    userSelect: 'none',
                  }"
                />
              </div>

              <!-- Zoom -->
              <div class="mt-4 flex items-center gap-3">
                <span class="text-xs text-gray-400">Zoom</span>
                <input type="range" v-model.number="zoomLevel" min="1" max="3" step="0.05"
                  class="flex-1 accent-[#0d2f6e]" @input="applyZoom" />
              </div>
              <p class="text-xs text-gray-400 mt-1 text-center">Glissez l'image pour cadrer votre visage</p>

              <div class="flex gap-3 mt-4">
                <button @click="annulerCrop" class="flex-1 border border-gray-200 text-gray-600 py-2 rounded-xl text-sm hover:bg-gray-50 transition">
                  Annuler
                </button>
                <button @click="validerCrop" class="flex-1 bg-[#0d2f6e] text-white py-2 rounded-xl text-sm font-semibold hover:bg-blue-900 transition">
                  Valider
                </button>
              </div>
            </div>
          </div>

          <!-- Compte -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <Cog6ToothIcon class="w-5 h-5 text-[#0d2f6e]" />
              Mon compte
            </h2>
            <div class="space-y-3 text-sm">
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Nom d'utilisateur</p>
                <p class="text-gray-800 font-medium">{{ $page.props.auth.user.name }}</p>
              </div>
              <div>
                <p class="text-gray-400 text-xs uppercase tracking-wide mb-1">Email</p>
                <p class="text-gray-800 font-medium break-all">{{ $page.props.auth.user.email }}</p>
              </div>
            </div>
            <div class="mt-5 flex flex-col gap-2">
              <Link :href="route('profile.edit')" class="w-full inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                <PencilSquareIcon class="w-4 h-4" />
                Modifier le compte
              </Link>
              <Link v-if="member" :href="route('member.card')" class="w-full inline-flex items-center justify-center gap-2 border border-[#0d2f6e]/30 text-[#0d2f6e] px-4 py-2 rounded-lg text-sm hover:bg-[#0d2f6e] hover:text-white transition">
                <IdentificationIcon class="w-4 h-4" />
                Ma carte membre
              </Link>
            </div>
          </div>

          <!-- Stat cotisation -->
          <div v-if="member" class="bg-gradient-to-br from-[#c9973a] to-yellow-600 rounded-2xl p-5 text-white">
            <p class="text-xs font-medium opacity-80 mb-1">Total contribué au Dahira</p>
            <p class="text-2xl font-bold">{{ formatMontant(totalCotisations) }}</p>
            <p class="text-xs opacity-70 mt-2">{{ member.cotisations?.length || 0 }} paiement(s)</p>
          </div>

          <!-- Liens rapides -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Accès rapide</h3>
            <div class="space-y-2">
              <Link :href="route('events.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <CalendarDaysIcon class="w-4 h-4 text-gray-400" /> Événements
              </Link>
              <Link :href="route('khassaides.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <MusicalNoteIcon class="w-4 h-4 text-gray-400" /> Khassaïdes
              </Link>
              <Link :href="route('cotisations.index')" class="flex items-center gap-2 text-sm text-gray-600 hover:text-[#0d2f6e] transition py-1">
                <BanknotesIcon class="w-4 h-4 text-gray-400" /> Collectes en cours
              </Link>
            </div>
          </div>
        </div>

      </div>
    </div>
  </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue'
import { Link, usePage, useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import {
  UserIcon, BanknotesIcon, Cog6ToothIcon, PencilSquareIcon,
  CalendarDaysIcon, MusicalNoteIcon, ExclamationTriangleIcon, IdentificationIcon,
  CameraIcon, ArrowUpTrayIcon, CheckIcon,
} from '@heroicons/vue/24/outline'

const props = defineProps({ member: Object })
const page = usePage()

const initiales = computed(() => {
  const name = page.props.auth.user?.name || ''
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const CROP_SIZE = 280

const showCropper = ref(false)
const rawPreview = ref(null)
const croppedPreview = ref(null)
const croppedBlob = ref(null)
const photoForm = useForm({ photo: null })
const cropArea = ref(null)

// Position et taille de l'image dans la zone de recadrage
const imgX = ref(0)
const imgY = ref(0)
const imgW = ref(0)
const imgH = ref(0)
const zoomLevel = ref(1)
let baseW = 0
let baseH = 0

// Drag
let dragging = false
let dragStartX = 0
let dragStartY = 0
let dragStartImgX = 0
let dragStartImgY = 0

function onPhotoChange(e) {
  const file = e.target.files[0]
  if (!file) return
  const url = URL.createObjectURL(file)
  rawPreview.value = url
  // Charger les dimensions de l'image
  const img = new Image()
  img.onload = () => {
    const ratio = img.naturalWidth / img.naturalHeight
    if (ratio >= 1) {
      baseH = CROP_SIZE
      baseW = baseH * ratio
    } else {
      baseW = CROP_SIZE
      baseH = baseW / ratio
    }
    imgW.value = baseW
    imgH.value = baseH
    imgX.value = (CROP_SIZE - baseW) / 2
    imgY.value = (CROP_SIZE - baseH) / 2
    zoomLevel.value = 1
    showCropper.value = true
  }
  img.src = url
}

function applyZoom() {
  const newW = baseW * zoomLevel.value
  const newH = baseH * zoomLevel.value
  const cx = imgX.value + imgW.value / 2
  const cy = imgY.value + imgH.value / 2
  imgX.value = cx - newW / 2
  imgY.value = cy - newH / 2
  imgW.value = newW
  imgH.value = newH
}

function getEventPos(e) {
  if (e.touches) return { x: e.touches[0].clientX, y: e.touches[0].clientY }
  return { x: e.clientX, y: e.clientY }
}

function startDrag(e) {
  dragging = true
  const pos = getEventPos(e)
  dragStartX = pos.x
  dragStartY = pos.y
  dragStartImgX = imgX.value
  dragStartImgY = imgY.value
  if (cropArea.value) cropArea.value.style.cursor = 'grabbing'
}

function onDrag(e) {
  if (!dragging) return
  const pos = getEventPos(e)
  imgX.value = dragStartImgX + (pos.x - dragStartX)
  imgY.value = dragStartImgY + (pos.y - dragStartY)
}

function stopDrag() {
  dragging = false
  if (cropArea.value) cropArea.value.style.cursor = 'grab'
}

function validerCrop() {
  const canvas = document.createElement('canvas')
  canvas.width = 400
  canvas.height = 400
  const ctx = canvas.getContext('2d')
  const img = new Image()
  img.onload = () => {
    const scaleX = img.naturalWidth / imgW.value
    const scaleY = img.naturalHeight / imgH.value
    const sx = -imgX.value * scaleX
    const sy = -imgY.value * scaleY
    const sw = CROP_SIZE * scaleX
    const sh = CROP_SIZE * scaleY
    ctx.drawImage(img, sx, sy, sw, sh, 0, 0, 400, 400)
    canvas.toBlob(blob => {
      croppedBlob.value = blob
      croppedPreview.value = URL.createObjectURL(blob)
      showCropper.value = false
    }, 'image/jpeg', 0.9)
  }
  img.src = rawPreview.value
}

function annulerCrop() {
  showCropper.value = false
}

function uploadPhoto() {
  const file = new File([croppedBlob.value], 'photo.jpg', { type: 'image/jpeg' })
  photoForm.photo = file
  photoForm.post(route('member.photo.update'), {
    forceFormData: true,
    onSuccess: () => { croppedBlob.value = null },
  })
}

const totalCotisations = computed(() =>
  props.member?.cotisations?.reduce((sum, c) => sum + parseFloat(c.montant || 0), 0) ?? 0
)

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
function formatMontant(v) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF', minimumFractionDigits: 0 }).format(v ?? 0)
}
</script>

<style scoped>
.hero-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23ffffff' stroke-width='1'%3E%3Cpolygon points='40,5 75,20 75,60 40,75 5,60 5,20'/%3E%3C/g%3E%3C/svg%3E");
  background-size: 80px;
}
</style>

<style>
/* Cropperjs styles */
.cropper-container{direction:ltr;font-size:0;line-height:0;position:relative;-ms-touch-action:none;touch-action:none;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none}.cropper-container img{display:block;height:100%;image-orientation:0deg;max-height:none!important;max-width:none!important;min-height:0!important;min-width:0!important;width:100%}.cropper-wrap-box,.cropper-canvas,.cropper-drag-box,.cropper-crop-box,.cropper-modal{bottom:0;left:0;position:absolute;right:0;top:0}.cropper-wrap-box,.cropper-canvas{overflow:hidden}.cropper-drag-box{background-color:#fff;opacity:0}.cropper-modal{background-color:#000;opacity:.5}.cropper-view-box{display:block;height:100%;outline:1px solid #39f;outline-color:rgba(51,153,255,.75);overflow:hidden;width:100%}.cropper-dashed{border:0 dashed #eee;display:block;opacity:.5;position:absolute}.cropper-dashed.dashed-h{border-bottom-width:1px;border-top-width:1px;height:calc(100% / 3);left:0;top:calc(100% / 3);width:100%}.cropper-dashed.dashed-v{border-left-width:1px;border-right-width:1px;height:100%;left:calc(100% / 3);top:0;width:calc(100% / 3)}.cropper-center{display:block;height:0;left:50%;opacity:.75;position:absolute;top:50%;width:0}.cropper-center::before,.cropper-center::after{background-color:#eee;content:" ";display:block;position:absolute}.cropper-center::before{height:1px;left:-3px;top:0;width:7px}.cropper-center::after{height:7px;left:0;top:-3px;width:1px}.cropper-face,.cropper-line,.cropper-point{display:block;height:100%;opacity:.1;position:absolute;width:100%}.cropper-face{background-color:#fff;left:0;top:0}.cropper-line{background-color:#39f}.cropper-line.line-e{cursor:ew-resize;right:-3px;top:0;width:5px}.cropper-line.line-n{cursor:ns-resize;height:5px;left:0;top:-3px}.cropper-line.line-w{cursor:ew-resize;left:-3px;top:0;width:5px}.cropper-line.line-s{bottom:-3px;cursor:ns-resize;height:5px;left:0}.cropper-point{background-color:#39f;height:5px;opacity:.75;width:5px}.cropper-point.point-e{cursor:ew-resize;margin-top:-3px;right:-3px;top:50%}.cropper-point.point-n{cursor:ns-resize;left:50%;margin-left:-3px;top:-3px}.cropper-point.point-w{cursor:ew-resize;left:-3px;margin-top:-3px;top:50%}.cropper-point.point-s{bottom:-3px;cursor:s-resize;left:50%;margin-left:-3px}.cropper-point.point-ne{cursor:nesw-resize;right:-3px;top:-3px}.cropper-point.point-nw{cursor:nwse-resize;left:-3px;top:-3px}.cropper-point.point-sw{bottom:-3px;cursor:nesw-resize;left:-3px}.cropper-point.point-se{bottom:-3px;cursor:nwse-resize;height:20px;opacity:1;right:-3px;width:20px}@media (min-width:768px){.cropper-point.point-se{height:15px;width:15px}}@media (min-width:992px){.cropper-point.point-se{height:10px;width:10px}}@media (min-width:1200px){.cropper-point.point-se{height:5px;opacity:.75;width:5px}}.cropper-invisible{opacity:0}.cropper-bg{background-image:url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAAA3NCSVQICAjb4U/gAAAABlBMVEXMzMz////TjRV2AAAADklEQVQI12P4z8BQDwADhQGAWjR9awAAAABJRU5ErkJggg==")}.cropper-hide{display:block;height:1px;position:absolute;width:1px}.cropper-hidden{display:none!important}.cropper-move{cursor:move}.cropper-crop{cursor:crosshair}.cropper-disabled .cropper-drag-box,.cropper-disabled .cropper-face,.cropper-disabled .cropper-line,.cropper-disabled .cropper-point{cursor:not-allowed}
</style>
