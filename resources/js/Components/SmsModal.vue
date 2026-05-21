<template>
  <div v-if="show" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="$emit('close')">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-gray-800 flex items-center gap-2">
          <DevicePhoneMobileIcon class="w-5 h-5 text-[#0d2f6e]" />
          Envoyer un SMS
        </h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 transition">
          <XMarkIcon class="w-5 h-5" />
        </button>
      </div>

      <!-- Résultat précédent -->
      <div v-if="result" class="mb-4 p-3 rounded-xl text-sm" :class="result.echecs === 0 ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700'">
        <p class="font-medium">SMS envoyés</p>
        <p>{{ result.succes }} envoyé(s) avec succès sur {{ result.total }} destinataires</p>
        <p v-if="result.echecs > 0">{{ result.echecs }} échec(s)</p>
      </div>

      <form @submit.prevent="envoyer" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
          <textarea
            v-model="form.message"
            rows="4"
            maxlength="160"
            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] transition resize-none"
            :placeholder="placeholder"
          ></textarea>
          <div class="flex justify-between mt-1">
            <p v-if="form.errors?.message" class="text-red-500 text-xs">{{ form.errors.message }}</p>
            <span class="text-xs text-gray-400 ml-auto">{{ form.message.length }}/160</span>
          </div>
        </div>

        <div class="flex items-center gap-2 p-3 bg-blue-50 rounded-xl text-sm text-blue-700">
          <UsersIcon class="w-4 h-4 flex-shrink-0" />
          Sera envoyé à <strong class="mx-1">tous les membres actifs</strong> ayant un numéro
        </div>

        <div class="flex justify-end gap-3 pt-2">
          <button type="button" @click="$emit('close')" class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
            Annuler
          </button>
          <button type="submit" :disabled="form.processing || !form.message.trim()" class="inline-flex items-center gap-2 bg-[#0d2f6e] text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-900 transition disabled:opacity-60">
            <PaperAirplaneIcon class="w-4 h-4" />
            <span v-if="form.processing">Envoi…</span>
            <span v-else>Envoyer</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { computed, watch } from 'vue'
import { DevicePhoneMobileIcon, XMarkIcon, UsersIcon, PaperAirplaneIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  show:       Boolean,
  type:       { type: String, default: 'autre' },
  reunionId:  { type: Number, default: null },
  placeholder:{ type: String, default: 'Votre message...' },
  messageInitial: { type: String, default: '' },
})

const emit = defineEmits(['close'])

const page = usePage()
const result = computed(() => page.props.flash?.sms_result)

const form = useForm({
  message:    props.messageInitial,
  type:       props.type,
  reunion_id: props.reunionId,
})

watch(() => props.messageInitial, (val) => { form.message = val })
watch(() => props.show, (val) => { if (val) form.clearErrors() })

function envoyer() {
  form.post(route('admin.sms.envoyer'), {
    onSuccess: () => emit('close'),
  })
}
</script>
