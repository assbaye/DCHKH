<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6 print:bg-white print:p-0 print:block">

    <!-- Boutons actions (masqués à l'impression) -->
    <div class="flex gap-3 mb-8 print:hidden">
      <button @click="goBack" class="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-200 bg-white px-4 py-2 rounded-lg hover:bg-gray-50 transition">
        <ArrowLeftIcon class="w-4 h-4" /> Retour
      </button>
      <button @click="imprimer" class="inline-flex items-center gap-2 text-sm text-white bg-[#0d2f6e] px-4 py-2 rounded-lg hover:bg-blue-900 transition">
        <PrinterIcon class="w-4 h-4" /> Imprimer la carte
      </button>
    </div>

    <!-- Carte recto -->
    <div class="card-container print:m-0">

      <!-- RECTO -->
      <div class="card card-recto">
        <!-- Bandeau haut -->
        <div class="card-header">
          <div class="card-header-logo">
            <span class="font-black text-white text-[10px] tracking-widest leading-none">D.CH.KH</span>
          </div>
          <div class="text-right">
            <p class="text-white/60 text-[7px] uppercase tracking-widest">Carte de membre</p>
            <p class="text-white font-bold text-[8px] tracking-wider">DAHIRA DIELERLOU SYLL</p>
          </div>
        </div>

        <!-- Corps -->
        <div class="card-body">
          <!-- Photo -->
          <div class="card-photo">
            <img v-if="member.photo" :src="`/storage/${member.photo}`" class="w-full h-full object-cover" :alt="member.prenom" />
            <div v-else class="w-full h-full flex items-center justify-center text-xl font-bold text-white bg-[#0d2f6e]">
              {{ initiales }}
            </div>
          </div>

          <!-- Infos -->
          <div class="card-info">
            <p class="card-name">{{ member.prenom }} {{ member.nom }}</p>
            <div class="card-details">
              <div class="card-field" v-if="member.telephone">
                <span class="card-label">Tél</span>
                <span class="card-value">{{ member.telephone }}</span>
              </div>
              <div class="card-field" v-if="member.ville">
                <span class="card-label">Ville</span>
                <span class="card-value">{{ member.ville }}</span>
              </div>
              <div class="card-field" v-if="member.date_adhesion">
                <span class="card-label">Membre depuis</span>
                <span class="card-value">{{ new Date(member.date_adhesion).getFullYear() }}</span>
              </div>
              <div class="card-field">
                <span class="card-label">Rôle</span>
                <span class="card-value capitalize">{{ member.role }}</span>
              </div>
            </div>
          </div>

          <!-- QR Code -->
          <div class="card-qr">
            <canvas ref="qrCanvas"></canvas>
            <p class="text-[6px] text-gray-400 mt-1 text-center leading-tight">Scannez pour<br>accéder au profil</p>
          </div>
        </div>

        <!-- Pied -->
        <div class="card-footer">
          <span class="text-white/50 text-[6px]">Louga · Sénégal</span>
          <span class="text-white/70 text-[6px] font-medium">#{{ String(member.id).padStart(4, '0') }}</span>
        </div>
      </div>

      <!-- Séparateur -->
      <div class="w-full flex items-center gap-3 my-5 print:my-3">
        <div class="flex-1 border-t border-dashed border-gray-300"></div>
        <span class="text-[10px] text-gray-400 print:hidden">Découpez ici</span>
        <div class="flex-1 border-t border-dashed border-gray-300"></div>
      </div>

      <!-- VERSO -->
      <div class="card card-verso">
        <div class="verso-stripe"></div>
        <div class="verso-body">
          <div class="verso-left">
            <p class="text-[7px] text-white/50 uppercase tracking-widest mb-2">À propos</p>
            <p class="text-[8px] text-white/80 leading-relaxed">
              Cette carte atteste que son titulaire est membre actif du Dahira Dielerlou Syll – Cheikh Khalifa, basé à Louga, Sénégal.
            </p>
            <p class="text-[7px] text-white/40 mt-3">En cas de perte, contacter l'administration.</p>
          </div>
          <div class="verso-right">
            <canvas ref="qrCanvasVerso"></canvas>
            <p class="text-[6px] text-white/50 mt-1 text-center">dckh.org</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import QRCode from 'qrcode'
import { ArrowLeftIcon, PrinterIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ member: Object })

const qrCanvas = ref(null)
const qrCanvasVerso = ref(null)

const initiales = computed(() =>
  ((props.member.prenom?.[0] || '') + (props.member.nom?.[0] || '')).toUpperCase()
)

const profileUrl = computed(() => {
  const base = window.location.origin
  return `${base}/membres/${props.member.id}`
})

onMounted(async () => {
  const opts = { width: 80, margin: 1, color: { dark: '#0d2f6e', light: '#ffffff' } }
  await QRCode.toCanvas(qrCanvas.value, profileUrl.value, opts)
  await QRCode.toCanvas(qrCanvasVerso.value, profileUrl.value, { ...opts, color: { dark: '#ffffff', light: '#1a3a7a' } })
})

function imprimer() {
  window.print()
}

function goBack() {
  window.history.back()
}
</script>

<style scoped>
/* --- Carte format crédit : 85.6 × 53.98 mm --- */
.card-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.card {
  width: 323px;   /* 85.6mm @ 96dpi */
  height: 204px;  /* 53.98mm @ 96dpi */
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(13, 47, 110, 0.18);
  position: relative;
}

/* --- RECTO --- */
.card-recto {
  background: linear-gradient(135deg, #0d2f6e 0%, #1a4db5 60%, #1e5bd4 100%);
  display: flex;
  flex-direction: column;
}

.card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 12px 6px;
  background: rgba(0,0,0,0.15);
}

.card-header-logo {
  background: rgba(201,151,58,0.9);
  border-radius: 4px;
  padding: 3px 7px;
}

.card-body {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 12px;
}

.card-photo {
  width: 56px;
  height: 68px;
  border-radius: 6px;
  overflow: hidden;
  border: 2px solid rgba(201,151,58,0.7);
  flex-shrink: 0;
  background: rgba(255,255,255,0.1);
}

.card-info {
  flex: 1;
  min-width: 0;
}

.card-name {
  font-size: 11px;
  font-weight: 700;
  color: #ffffff;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 5px;
}

.card-details {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.card-field {
  display: flex;
  gap: 4px;
  align-items: baseline;
}

.card-label {
  font-size: 6.5px;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: rgba(255,255,255,0.5);
  flex-shrink: 0;
  min-width: 44px;
}

.card-value {
  font-size: 7.5px;
  color: rgba(255,255,255,0.9);
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.card-qr {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}

.card-qr canvas {
  width: 60px !important;
  height: 60px !important;
  border-radius: 4px;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 4px 12px;
  background: rgba(0,0,0,0.2);
}

/* --- VERSO --- */
.card-verso {
  background: linear-gradient(135deg, #0f3575 0%, #0d2f6e 100%);
  display: flex;
  flex-direction: column;
}

.verso-stripe {
  height: 28px;
  background: #1a1a1a;
  width: 100%;
}

.verso-body {
  flex: 1;
  display: flex;
  gap: 10px;
  padding: 8px 12px;
  align-items: center;
}

.verso-left {
  flex: 1;
}

.verso-right {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}

.verso-right canvas {
  width: 56px !important;
  height: 56px !important;
  border-radius: 4px;
  border: 2px solid rgba(255,255,255,0.1);
}

/* --- Impression --- */
@media print {
  @page {
    size: 85.6mm 120mm;
    margin: 0;
  }

  body {
    margin: 0;
    background: white;
  }

  .print\:hidden {
    display: none !important;
  }

  .card {
    width: 85.6mm;
    height: 53.98mm;
    border-radius: 6px;
    box-shadow: none;
    page-break-after: avoid;
  }

  .card-qr canvas,
  .verso-right canvas {
    width: 16mm !important;
    height: 16mm !important;
  }
}
</style>
