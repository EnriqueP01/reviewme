<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Snippet;
use App\Models\User;
use Illuminate\Database\Seeder;

final class GroupCollaborationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Création du propriétaire et des membres
        $owner = User::firstOrCreate(
            ['email' => 'tech-lead@reviewme.io'],
            [
                'name' => 'Alexandre (Lead)',
                'password' => bcrypt('password'),
                'github_id' => '123456',
            ]
        );

        $members = [
            ['name' => 'Léo Dev', 'email' => 'leo@reviewme.io'],
            ['name' => 'Sarah Security', 'email' => 'sarah@reviewme.io'],
            ['name' => 'Thomas Frontend', 'email' => 'thomas@reviewme.io'],
        ];

        $memberUsers = collect($members)->map(function ($m) {
            return User::firstOrCreate(
                ['email' => $m['email']],
                [
                    'name' => $m['name'],
                    'password' => bcrypt('password'),
                    'github_id' => (string) rand(100000, 999999),
                ]
            );
        });

        // 2. Création du groupe
        $group = Group::updateOrCreate(
            ['slug' => 'shadow-hackers-lab'],
            [
                'name' => 'Shadow Hackers Lab',
                'description' => 'Un espace privé pour auditer les vulnérabilités les plus critiques avant déploiement.',
                'owner_id' => $owner->id,
            ]
        );

        // Ajout des membres au groupe
        $group->members()->syncWithoutDetaching($memberUsers->pluck('id')->toArray());
        $group->members()->syncWithoutDetaching([$owner->id]);

        // 3. Création de Posts privés
        $post1 = Post::create([
            'user_id' => $owner->id,
            'group_id' => $group->id,
            'title' => 'Refactoring Middleware Authentification',
            'description' => "J'ai réécrit le middleware pour mieux gérer les scopes JWT. Le but est de centraliser la vérification des droits applicatifs directement dans l'injection du token. Quelqu'un peut vérifier si j'ai oublié un edge case important ?",
            'short_description' => 'Audit du middleware Auth & JWT Scopes.',
            'visibility' => 'private',
        ]);

        Snippet::create([
            'post_id' => $post1->id,
            'filename' => 'Authenticate.php',
            'code_content' => "namespace App\Http\Middleware;\n\nuse Closure;\nuse Illuminate\Http\Request;\nuse Symfony\Component\HttpFoundation\Response;\n\nclass Authenticate\n{\n    public function handle(Request \$request, Closure \$next): Response\n    {\n        // Vérification du token Bearer\n        if (!\$request->bearerToken()) {\n             return response()->json(['error' => 'Token missing'], 401);\n        }\n\n        // Extraction des scopes pour validation fine\n        \$scopes = explode(',', \$request->header('X-App-Scopes', ''));\n\n        if (empty(\$scopes) || !in_array('admin', \$scopes)) {\n             // LOGIQUE À VÉRIFIER ICI\n             Log::warning('Unauthorized scope access attempt');\n             return response()->json(['error' => 'Insufficient scopes'], 403);\n        }\n\n        return \$next(\$request);\n    }\n}",
            'language' => 'php',
            'version_number' => 1,
        ]);

        $post2 = Post::create([
            'user_id' => $memberUsers[1]->id, // Sarah
            'group_id' => $group->id,
            'title' => 'POC: SQL Injection sur la recherche globale',
            'description' => "En auditant la classe SearchPostsAction, j'ai remarqué que le paramètre 'order_by' est passé directement à la clause orderByRaw(). C'est une porte ouverte à l'injection.",
            'short_description' => 'Faille SQL critique sur orderByRaw.',
            'visibility' => 'private',
        ]);

        Snippet::create([
            'post_id' => $post2->id,
            'filename' => 'SearchPostsAction.php',
            'code_content' => "public function execute(string \$search = null, string \$orderBy = 'created_at')\n{\n    \$query = Post::query();\n\n    if (\$search) {\n        \$query->where('title', 'LIKE', '%' . \$search . '%');\n    }\n\n    // VULNÉRABLE : Injection via \$orderBy\n    return \$query->orderByRaw(\$orderBy . ' DESC')->paginate(10);\n}",
            'language' => 'php',
            'version_number' => 1,
        ]);

        // 4. Ajout de feedbacks (commentaires)
        PostComment::create([
            'post_id' => $post2->id,
            'user_id' => $owner->id,
            'content' => 'Bien vu Sarah ! Je m\'en occupe tout de suite. On va utiliser une liste blanche de colonnes autorisées pour le tri.',
        ]);

        PostComment::create([
            'post_id' => $post1->id,
            'user_id' => $memberUsers[0]->id, // Léo
            'content' => 'Ça a l\'air propre, j\'ai juste un doute sur le header X-App-Scopes. Est-il bien signé par la gateway ?',
        ]);

        // 5. Simulation d'une conversation de chat cohérente
        $chatData = [
            ['u' => $owner->id, 'm' => 'Salut l\'équipe ! Bienvenue dans le lab secret.'],
            ['u' => $memberUsers[0]->id, 'm' => 'Merci Alex ! Prêt à casser du code.'],
            ['u' => $memberUsers[1]->id, 'm' => 'Je viens de poster un POC critique sur la recherche, jetez un oeil avant que le stagiaire ne push ça en prod.'],
            ['u' => $memberUsers[2]->id, 'm' => 'Je regarde Sarah. Je vais aussi check si le frontend escape bien les erreurs SQL dans le toaster.'],
            ['u' => $owner->id, 'm' => 'Léo, j\'ai répondu sur ton post de refactor. On devrait signer les scopes pour plus de sécu.'],
            ['u' => $memberUsers[1]->id, 'm' => 'Alex, n\'oublie pas de rebuild les containers Docker après le fix SQL.'],
            ['u' => $memberUsers[0]->id, 'm' => 'Je m\'occupe de la PR de correction pour le SQL.'],
            ['u' => $owner->id, 'm' => 'Parfait. On fait un point demain 10h sur Discord/Meet.'],
        ];

        foreach ($chatData as $data) {
            GroupMessage::create([
                'group_id' => $group->id,
                'user_id' => $data['u'],
                'content' => $data['m'],
                'created_at' => now()->subMinutes(60 - (count(GroupMessage::all()) * 5)),
            ]);
        }
    }
}
