<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-6 print:bg-white print:p-0">

    <!-- Boutons actions (masqués à l'impression) -->
    <div class="flex gap-3 mb-8 print:hidden">
      <button @click="goBack" class="inline-flex items-center gap-2 text-sm text-gray-600 border border-gray-200 bg-white px-4 py-2 rounded-lg hover:bg-gray-50 transition">
        <ArrowLeftIcon class="w-4 h-4" /> Retour
      </button>
      <button @click="imprimer" class="inline-flex items-center gap-2 text-sm text-white bg-[#0d2f6e] px-4 py-2 rounded-lg hover:bg-blue-900 transition">
        <PrinterIcon class="w-4 h-4" /> Imprimer la carte
      </button>
    </div>

    <!-- Carte -->
    <div class="card">
      <!-- Bandeau haut -->
      <div class="card-header">
        <div class="card-header-logo">
          <span class="font-black text-white text-[10px] tracking-widest leading-none">D.CH.KH</span>
        </div>
        <div class="text-right">
          <p class="text-white/60 text-[7px] uppercase tracking-widest">Carte de membre</p>
          <p class="text-white text-[7.5px] tracking-wide">Daaray Cheikhoul Khadim</p>
          <p class="text-white text-[7.5px] tracking-wide">Touba Diellerlou Sylla</p>
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
          <p class="text-[6px] text-gray-300 mt-1 text-center leading-tight">Scannez pour<br>accéder au profil</p>
        </div>
      </div>

      <!-- Pied -->
      <div class="card-footer">
        <span class="text-white/50 text-[6px]">Louga · Sénégal</span>
        <span class="text-white/70 text-[6px] font-medium">#{{ String(member.id).padStart(4, '0') }}</span>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import QRCode from 'qrcode'
import { ArrowLeftIcon, PrinterIcon } from '@heroicons/vue/24/outline'

const props = defineProps({ member: Object })

const qrCanvas = ref(null)

const initiales = computed(() =>
  ((props.member.prenom?.[0] || '') + (props.member.nom?.[0] || '')).toUpperCase()
)

const profileUrl = computed(() => `${window.location.origin}/membres/${props.member.id}`)

onMounted(async () => {
  await QRCode.toCanvas(qrCanvas.value, profileUrl.value, {
    width: 80, margin: 1, color: { dark: '#0d2f6e', light: '#ffffff' }
  })
})

function imprimer() { window.print() }
function goBack() { window.history.back() }
</script>

<style scoped>
.card {
  width: 323px;
  height: 204px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(13, 47, 110, 0.18);
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

@media print {
  @page {
    size: 85.6mm 53.98mm;
    margin: 0;
  }

  .print\:hidden { display: none !important; }

  .card {
    width: 85.6mm;
    height: 53.98mm;
    border-radius: 6px;
    box-shadow: none;
  }

  .card-qr canvas {
    width: 16mm !important;
    height: 16mm !important;
  }
}
</style>
