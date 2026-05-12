<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="min-h-screen bg-[#0d2f6e] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo + Nom -->
            <div class="text-center mb-8">
                <Link href="/" class="inline-block">
                    <div class="w-20 h-20 rounded-full border-4 border-[#c9973a] bg-[#1a4db5] mx-auto mb-4 flex items-center justify-center overflow-hidden">
                        <img src="/images/logo.jpeg" alt="Logo DCHKH" class="w-full h-full object-cover" />
                    </div>
                </Link>
                <h1 class="text-white font-bold text-lg leading-tight">Daaray Cheikhoul Khadim</h1>
                <p class="text-[#c9973a] text-sm">Touba Diellerlou Sylla</p>
                <p class="text-blue-300 text-xs mt-1">دائرة الشيخ الخديم</p>
            </div>

            <!-- Carte formulaire -->
            <div class="bg-white rounded-2xl shadow-2xl px-8 py-8">
                <slot />
            </div>

            <p class="text-center text-blue-300 text-xs mt-6">
                <Link :href="route('home')" class="hover:text-white transition">← Retour au site</Link>
            </p>
        </div>
    </div>
</template>
