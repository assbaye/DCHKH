<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { LockClosedIcon, CheckIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    email: { type: String, required: true },
    token: { type: String, required: true },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="Nouveau mot de passe — D.CH.KH." />

        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-3">
                <LockClosedIcon class="w-7 h-7 text-[#0d2f6e]" />
            </div>
            <h2 class="text-xl font-bold text-[#0d2f6e]">Nouveau mot de passe</h2>
            <p class="text-sm text-gray-500 mt-2">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Adresse email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Nouveau mot de passe</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autofocus
                    autocomplete="new-password"
                    placeholder="Minimum 8 caractères"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Répétez le mot de passe"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent transition"
                />
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full inline-flex items-center justify-center gap-2 bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition text-sm disabled:opacity-60"
            >
                <CheckIcon class="w-4 h-4" />
                {{ form.processing ? 'Enregistrement...' : 'Réinitialiser le mot de passe' }}
            </button>
        </form>
    </GuestLayout>
</template>
