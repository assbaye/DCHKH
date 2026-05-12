<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="Connexion — D.CH.KH." />

        <h2 class="text-xl font-bold text-[#0d2f6e] mb-6 text-center">Connexion</h2>

        <div v-if="status" class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-3">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="votre@email.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-[#c9973a] hover:underline"
                    >
                        Mot de passe oublié ?
                    </Link>
                </div>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Votre mot de passe"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <label class="flex items-center gap-2 cursor-pointer">
                <input v-model="form.remember" type="checkbox" class="w-4 h-4 accent-[#0d2f6e]" />
                <span class="text-sm text-gray-600">Se souvenir de moi</span>
            </label>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition text-sm disabled:opacity-60"
            >
                {{ form.processing ? 'Connexion...' : 'Se connecter' }}
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Pas encore membre ?
                <Link :href="route('register')" class="text-[#c9973a] font-medium hover:underline">S'inscrire</Link>
            </p>
        </form>
    </GuestLayout>
</template>
