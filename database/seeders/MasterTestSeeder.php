<?php

namespace Database\Seeders;

use App\Models\FullReview;
use App\Models\FullReviewSnippet;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Reaction;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. NETTOYAGE RADICAL (Compatibilité SQLite)
        DB::statement('PRAGMA foreign_keys = OFF;');
        User::query()->delete();
        Group::query()->delete();
        Post::query()->delete();
        Snippet::query()->delete();
        PostComment::query()->delete();
        FullReview::query()->delete();
        Reaction::query()->delete();
        GroupMessage::query()->delete();
        DB::table('group_user')->delete();
        DB::statement('PRAGMA foreign_keys = ON;');

        // 2. CRÉATION DES PERSONAS (SOCIÉTÉ DE DÉVELOPPEURS)
        // Ajout de ton profil personnel pour éviter les 404
        $me = User::updateOrCreate(
            ['handle' => 'enriquep01'],
            [
                'name' => 'Enrique P.',
                'email' => 'enrique@reviewme.io',
                'password' => Hash::make('password'),
                'bio' => 'Fullstack Developer & Platform Owner.',
                'reputation_score' => 5000,
                'github_id' => 'dummy_github_id',
            ]
        );

        $thomas = User::create([
            'name' => 'Thomas Architect',
            'handle' => 'thomas_arch',
            'email' => 'thomas@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 8450,
            'bio' => 'Lead Architect. Je vis pour le Clean Code et le Domain Driven Design. Si ton constructeur a 12 paramètres, on va avoir un problème.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=thomas&backgroundColor=1a1b26',
        ]);

        $julie = User::create([
            'name' => 'Julie Security',
            'handle' => 'julie_sec',
            'email' => 'julie@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 7200,
            'bio' => 'Security Researcher. Je ne cherche pas des bugs, je cherche des portes ouvertes. Rappel : ne faites JAMAIS confiance à l\'input utilisateur.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=julie&backgroundColor=f7768e',
        ]);

        $kevin = User::create([
            'name' => 'Kevin Front',
            'handle' => 'kevin_pixel',
            'email' => 'kevin@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 5100,
            'bio' => 'Senior Frontend dev. Expert Tailwind & Framer Motion. Mon but ? Que ton UI soit aussi fluide que du beurre et accessible à tous.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=kevin&backgroundColor=7aa2f7',
        ]);

        $sophie = User::create([
            'name' => 'Sophie Performance',
            'handle' => 'sophie_perf',
            'email' => 'sophie@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 9100,
            'bio' => 'Performance Engineer. Rust & low-level PHP. Si ton script met plus de 50ms à répondre, c\'est qu\'il est mal écrit.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=sophie&backgroundColor=9ece6a',
        ]);

        $lucas = User::create([
            'name' => 'Lucas Junior',
            'handle' => 'lucas_dev',
            'email' => 'lucas@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 1200,
            'bio' => 'Apprenti développeur passionné par Laravel. Toujours en soif d\'apprendre et de recevoir des critiques constructives.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=lucas&backgroundColor=e0af68',
        ]);

        $users = collect([$thomas, $julie, $kevin, $sophie, $lucas]);

        // Scenario 4: Thomas publie un manifeste
        Post::create([
            'user_id' => $thomas->id,
            'title' => 'Manifeste : Pourquoi le DDD va sauver votre projet',
            'short_description' => 'Retour d\'expérience sur 10 ans d\'architecture monolithique.',
            'description' => 'Le Domain Driven Design n\'est pas qu\'une mode. C\'est une nécessité quand la complexité métier dépasse la capacité cognitive de l\'équipe.',
            'visibility' => 'public',
            'lens' => 'logic',
            'created_at' => now()->subDays(15),
        ]);

        // 3. GROUPES THÉMATIQUES
        $groups = collect([
            Group::create([
                'name' => 'Forge_Architecture',
                'slug' => 'forge-architecture',
                'description' => 'Discussions sur les patterns complexes, le découplage et la scalabilité des applications Laravel.',
                'owner_id' => $thomas->id,
            ]),
            Group::create([
                'name' => 'Security_Lab',
                'slug' => 'security-lab',
                'description' => 'Audit de code, traque de vulnérabilités et partage de bonnes pratiques de sécurisation.',
                'owner_id' => $julie->id,
            ]),
            Group::create([
                'name' => 'Performance_Hacking',
                'slug' => 'performance-hacking',
                'description' => 'Shaving off milliseconds. SQL optimization, Caching & Engine internals.',
                'owner_id' => $sophie->id,
            ]),
        ]);

        // Adhésions
        foreach ($groups as $group) {
            foreach ($users as $user) {
                $group->members()->attach($user->id, ['role' => ($user->id === $group->owner_id) ? 'admin' : 'member']);
            }
        }

        // 4. SCÉNARIOS RÉALISTES

        // Scenario 1: Lucas pose une question de débutant sur les contrôleurs (LOGIC)
        $postLucas = Post::create([
            'user_id' => $lucas->id,
            'title' => 'Besoin d\'aide : Mon contrôleur est-il trop gros ?',
            'short_description' => 'J\'ai mis toute ma logique de création de commande dans le contrôleur.',
            'description' => 'Salut les experts ! J\'ai ce code qui gère la création de commande, l\'envoi de mail et la mise à jour des stocks. Ça marche mais j\'ai l\'impression que c\'est "sale". Comment je pourrais mieux structurer ça ?',
            'review_goals' => 'Comment extraire la logique métier du contrôleur ? Utilisation de Services ou de Jobs ?',
            'visibility' => 'public',
            'lens' => 'logic',
            'created_at' => now()->subDays(rand(5, 10)),
        ]);

        Snippet::create([
            'post_id' => $postLucas->id,
            'version_number' => 1,
            'filename' => 'OrderController.php',
            'language' => 'php',
            'code_content' => "public function store(Request \$request)\n{\n    \$order = Order::create(\$request->all());\n    \n    foreach(\$request->items as \$item) {\n        \$product = Product::find(\$item['id']);\n        \$product->stock -= \$item['qty'];\n        \$product->save();\n    }\n\n    Mail::to(\$request->user())->send(new OrderConfirmed(\$order));\n    \n    return response()->json(\$order);\n}",
            'sort_order' => 1,
        ]);

        // Feedback de Thomas (Review de pro)
        $revThomas = FullReview::create([
            'user_id' => $thomas->id,
            'post_id' => $postLucas->id,
            'description' => "Salut Lucas ! Effectivement, on est face à ce qu'on appelle un 'Fat Controller'. Tu devrais déléguer ces responsabilités à une Action ou un Service, et utiliser des transactions DB pour garantir que si le mail échoue, le stock ne soit pas décompté pour rien.",
            'score' => 20,
            'created_at' => now()->subDays(rand(1, 5)),
        ]);

        FullReviewSnippet::create([
            'full_review_id' => $revThomas->id,
            'snippet_id' => $postLucas->snippets->first()->id,
            'modified_content' => "public function store(StoreOrderRequest \$request, CreateOrderAction \$action)\n{\n    \$order = \$action->execute(\$request->validated());\n    return new OrderResource(\$order);\n}",
            'description' => "Utilisation d'une Action atomique et de FormRequests pour la validation.",
        ]);

        // Scenario 2: Sophie optimise un système de lock Rust (PERFORMANCE)
        $postSophie = Post::create([
            'user_id' => $sophie->id,
            'group_id' => $groups[2]->id,
            'title' => 'Lock-free Queue implementation en Rust',
            'short_description' => 'Test d\'une queue MPMC sans Mutex pour haute performance.',
            'description' => 'J\'essaie d\'implémenter une queue très rapide pour notre gestionnaire de logs. Actuellement, le `Mutex` global crée un goulot d\'étranglement sous forte charge (1M+ msg/sec).',
            'review_goals' => 'Vérifier la gestion de la mémoire `Unsafe` et les barrières atomiques.',
            'visibility' => 'public',
            'lens' => 'performance',
            'created_at' => now()->subDays(rand(1, 4)),
        ]);

        Snippet::create([
            'post_id' => $postSophie->id,
            'version_number' => 1,
            'filename' => 'mpmc_queue.rs',
            'language' => 'rust',
            'code_content' => "use std::sync::atomic::{AtomicUsize, Ordering};\n\nstruct Queue<T> {\n    buffer: Vec<Slot<T>>,\n    head: AtomicUsize,\n    tail: AtomicUsize,\n}\n\nimpl<T> Queue<T> {\n    pub fn push(&self, value: T) {\n        let pos = self.tail.fetch_add(1, Ordering::Relaxed);\n        // unsafe implementation here...\n    }\n}",
            'sort_order' => 1,
        ]);

        // Commentaire de Julie sur la sécurité
        PostComment::create([
            'user_id' => $julie->id,
            'post_id' => $postSophie->id,
            'content' => "Sophie, attention à l'Ordering::Relaxed ici. Si tu accèdes à la data juste après, tu pourrais avoir des surprises de visibilité CPU. Je passerai sur du Acquire/Release pour être safe.",
        ]);

        // Scenario 3: Julie trouve une faille IDOR (SECURITY)
        $postJulie = Post::create([
            'user_id' => $julie->id,
            'group_id' => $groups[1]->id,
            'title' => 'Pourquoi ce middleware de téléchargement est dangereux ?',
            'short_description' => 'Analyse d\'une vulnérabilité IDOR classique.',
            'description' => 'J\'ai trouvé ça dans un vieux repo. Saurez-vous identifier comment un utilisateur peut télécharger les factures de n\'importe qui ?',
            'visibility' => 'public',
            'lens' => 'security',
            'created_at' => now()->subDays(rand(12, 20)),
        ]);

        Snippet::create([
            'post_id' => $postJulie->id,
            'filename' => 'DownloadController.php',
            'language' => 'php',
            'code_content' => "public function download(\$id)\n{\n    \$invoice = Invoice::find(\$id);\n    return Storage::download(\$invoice->path);\n}",
            'sort_order' => 1,
        ]);

        // Lucas essaie de répondre
        PostComment::create([
            'user_id' => $lucas->id,
            'post_id' => $postJulie->id,
            'content' => 'Il manque un `if($invoice->user_id !== auth()->id())` avant le download, non ?',
        ]);

        // 5. CHAT DE GROUPE RÉALISTE (Forge_Architecture)
        $gp = $groups[0];
        $chat = [
            [$thomas->id, 'Bienvenue dans la Forge. On est là pour casser du monolithe.'],
            [$lucas->id, 'Est-ce que vous conseillez Livewire pour des dashboards complexes ?'],
            [$kevin->id, "L'UI de ReviewMe prouve que oui. Le secret c'est le 'wire:navigate' et d'abuser de Alpine.js pour tout ce qui est immédiat."],
            [$thomas->id, "Exactement. Et n'oubliez pas de garder vos composants Livewire 'lean'. La logique métier va dans les Actions."],
        ];

        foreach ($chat as $i => $data) {
            GroupMessage::create([
                'group_id' => $gp->id,
                'user_id' => $data[0],
                'content' => $data[1],
                'created_at' => now()->subMinutes(120 - ($i * 10)),
            ]);
        }

        // 6. RÉACTIONS, COMMENTAIRES MASSIFS ET ACTIVITÉ PASSÉE
        foreach ($users as $user) {
            // Chaque utilisateur crée entre 3 et 8 commentaires/réactions aléatoires le mois passé
            for ($i = 0; $i < rand(3, 8); $i++) {
                $randomPost = Post::all()->random();
                $randomDate = now()->subDays(rand(0, 30));

                PostComment::create([
                    'user_id' => $user->id,
                    'post_id' => $randomPost->id,
                    'content' => collect([
                        'Super intéressant comme approche !',
                        "J'aurais fait ça différemment, mais ça se tient.",
                        "Merci pour le partage @{$randomPost->user->handle} !",
                        'Tu as pensé aux performances sur ce bloc ?',
                        'Le clean code est au rendez-vous, bravo.',
                    ])->random(),
                    'created_at' => $randomDate,
                ]);

                if ($user->id !== $randomPost->user_id) {
                    Reaction::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'reactable_id' => $randomPost->id,
                            'reactable_type' => Post::class,
                        ],
                        [
                            'type' => collect(['up', 'mindblown', 'clean', 'opti'])->random(),
                            'created_at' => $randomDate,
                        ]
                    );
                }
            }
        }

        // Simulation de karma pour Lucas (il a reçu une bonne review)
        $lucas->increment('reputation_score', 150);
    }
}
