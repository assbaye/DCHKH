<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { EnvelopeIcon, ArrowLeftIcon, PaperAirplaneIcon } from '@heroicons/vue/24/outline'

defineProps({ status: String })

const form = useForm({ email: '' })
const submit = () => form.post(route('password.email'))
</script>

<template>
    <GuestLayout>
        <Head title="Mot de passe oublié — D.CH.KH." />

        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3">
                <EnvelopeIcon class="w-7 h-7 text-[#0d2f6e]" />
            </div>
            <h2 class="text-xl font-bold text-[#0d2f6e]">Mot de passe oublié ?</h2>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                Pas de problème. Indiquez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
        </div>

        <!-- Message de confirmation -->
        <div v-if="status" class="mb-5 bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg flex items-start gap-2">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="votre@email.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full inline-flex items-center justify-center gap-2 bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition text-sm disabled:opacity-60"
            >
                <PaperAirplaneIcon class="w-4 h-4" />
                {{ form.processing ? 'Envoi en cours...' : 'Envoyer le lien de réinitialisation' }}
            </button>

            <p class="text-center">
                <Link :href="route('login')" class="inline-flex items-center gap-1.5 text-sm text-[#c9973a] hover:underline">
                    <ArrowLeftIcon class="w-4 h-4" />
                    Retour à la connexion
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
