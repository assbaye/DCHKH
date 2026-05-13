<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    telephone: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="Inscription — D.CH.KH." />

        <h2 class="text-xl font-bold text-[#0d2f6e] mb-6 text-center">Rejoindre le Dahira</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Prénom Nom"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                <input
                    id="telephone"
                    v-model="form.telephone"
                    type="tel"
                    required
                    autocomplete="tel"
                    placeholder="Ex: 77 123 45 67"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.telephone" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    placeholder="votre@email.com"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimum 8 caractères"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Répétez le mot de passe"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#0d2f6e] focus:border-transparent"
                />
                <InputError class="mt-1" :message="form.errors.password_confirmation" />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-[#0d2f6e] hover:bg-blue-900 text-white font-semibold py-3 rounded-lg transition text-sm disabled:opacity-60 mt-2"
            >
                {{ form.processing ? 'Inscription...' : "S'inscrire" }}
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Déjà membre ?
                <Link :href="route('login')" class="text-[#c9973a] font-medium hover:underline">Se connecter</Link>
            </p>
        </form>
    </GuestLayout>
</template>
