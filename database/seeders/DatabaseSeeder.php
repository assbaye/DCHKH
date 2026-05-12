<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Compte admin
        $admin = User::create([
            'name'        => 'Administrateur DCHKH',
            'email'       => 'admin@dchkh.sn',
            'password'    => \Illuminate\Support\Facades\Hash::make('Admin@dchkh2024!'),
            'is_approved' => true,
        ]);

        \App\Models\Member::create([
            'user_id'       => $admin->id,
            'nom'           => 'Sylla',
            'prenom'        => 'Administrateur',
            'telephone'     => '+221 77 000 00 00',
            'ville'         => 'Dielerlou Syll',
            'date_adhesion' => '2010-01-01',
            'statut'        => 'actif',
            'role'          => 'admin',
        ]);

        // Quelques membres
        $membres = [
            ['nom' => 'Diallo', 'prenom' => 'Mamadou', 'telephone' => '+221 77 111 22 33'],
            ['nom' => 'Ndiaye', 'prenom' => 'Fatou',   'telephone' => '+221 76 222 33 44'],
            ['nom' => 'Sow',    'prenom' => 'Ibrahima','telephone' => '+221 70 333 44 55'],
            ['nom' => 'Fall',   'prenom' => 'Aissatou','telephone' => '+221 78 444 55 66'],
            ['nom' => 'Gueye',  'prenom' => 'Abdoulaye','telephone' => '+221 77 555 66 77'],
        ];
        foreach ($membres as $m) {
            \App\Models\Member::create(array_merge($m, [
                'ville'         => 'Dielerlou Syll',
                'date_adhesion' => now()->subMonths(rand(1, 24)),
                'statut'        => 'actif',
                'role'          => 'membre',
            ]));
        }

        // Événements
        $events = [
            ['titre' => 'Grand Magal de Touba 2026',    'categorie' => 'Magal',    'date_event' => '2026-06-15', 'lieu' => 'Touba',         'publie' => true],
            ['titre' => 'Réunion mensuelle du Dahira',   'categorie' => 'Reunion',  'date_event' => '2026-05-20', 'lieu' => 'Dielerlou Syll','publie' => true],
            ['titre' => 'Collecte de fonds Ramadan',     'categorie' => 'Collecte', 'date_event' => '2026-07-01', 'lieu' => 'Louga',         'publie' => true],
            ['titre' => 'Soirée Khassaïdes',             'categorie' => 'Khassaides','date_event'=> '2026-08-10', 'lieu' => 'Dielerlou Syll','publie' => true],
            ['titre' => 'Assemblée Générale Annuelle',   'categorie' => 'Reunion',  'date_event' => '2025-12-01', 'lieu' => 'Dielerlou Syll','publie' => true],
        ];
        foreach ($events as $e) {
            \App\Models\Event::create(array_merge($e, [
                'description' => 'Cet événement est organisé par le Dahira D.CH.KH. Tous les membres sont invités à participer.',
                'created_by'  => $admin->id,
            ]));
        }

        // Khassaïdes
        $khassaides = [
            ['titre' => 'Jazbul Qulub',     'auteur' => 'Cheikh Ahmadou Bamba', 'langue' => 'arabe'],
            ['titre' => 'Matlabul Fawzayni','auteur' => 'Cheikh Ahmadou Bamba', 'langue' => 'arabe'],
            ['titre' => 'Burdatoul Mourid', 'auteur' => 'Cheikh Ahmadou Bamba', 'langue' => 'arabe'],
            ['titre' => 'Xassida bi wolof', 'auteur' => 'Cheikh Ahmadou Bamba', 'langue' => 'wolof'],
        ];
        foreach ($khassaides as $k) {
            \App\Models\Khassaide::create(array_merge($k, [
                'description' => 'Un des grands poèmes spirituels de Cheikh Ahmadou Bamba Mbacké.',
                'telechargeable' => true,
                'publie'         => true,
                'created_by'     => $admin->id,
            ]));
        }

        // Collection active
        $collection = \App\Models\Collection::create([
            'titre'          => 'Collecte Magal 2026',
            'description'    => 'Collecte pour l\'organisation du Grand Magal de Touba 2026.',
            'objectif'       => 500000,
            'montant_collecte'=> 185000,
            'date_debut'     => '2026-04-01',
            'date_fin'       => '2026-06-10',
            'active'         => true,
            'created_by'     => $admin->id,
        ]);

        \App\Models\Collection::create([
            'titre'           => 'Collecte Ramadan 2025',
            'description'     => 'Collecte pour les activités du Ramadan.',
            'objectif'        => 300000,
            'montant_collecte'=> 320000,
            'date_debut'      => '2025-03-01',
            'date_fin'        => '2025-04-10',
            'active'          => false,
            'created_by'      => $admin->id,
        ]);
    }
}
