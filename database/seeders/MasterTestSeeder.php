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
use Illuminate\Support\Facades\Hash;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CLEAR TABLES TO ENSURE CLEAN STATE
        User::query()->delete();
        Group::query()->delete();
        Post::query()->delete();
        Snippet::query()->delete();
        PostComment::query()->delete();
        FullReview::query()->delete();
        Reaction::query()->delete();
        GroupMessage::query()->delete();

        // 2. CREATE ELITE USERS (NO SPACES IN IDENTIFIERS)
        $celestin = User::create([
            'name' => 'celestin_dev',
            'email' => 'celestin@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 7500,
            'bio' => 'Lead Fullstack Engineer & Architect. Passionate about Laravel internals, React performance, and building high-fidelity developer tools.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=celestin&backgroundColor=1a1b26',
        ]);

        $sarah = User::create([
            'name' => 'sarah_arch',
            'email' => 'sarah@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 9200,
            'bio' => 'Senior Infrastructure Architect specializing in Go, K8s, and high-available distributed systems. I live for clean abstractions.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=sarah&backgroundColor=24283b',
        ]);

        $marcus = User::create([
            'name' => 'marcus_sec',
            'email' => 'marcus@reviewme.io',
            'password' => Hash::make('password'),
            'reputation_score' => 4500,
            'bio' => 'Security Researcher & Penetration Tester. My goal is to find the one line of code that breaks everything.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=marcus&backgroundColor=1a1b26',
        ]);

        $lea = User::create([
            'name' => 'lea_pixel',
            'email' => 'lea@pixel.io',
            'password' => Hash::make('password'),
            'reputation_score' => 3800,
            'bio' => 'Lead Frontend Engineer. Obsessed with CSS performance, motion design, and semantic HTML5.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=lea&backgroundColor=f5c2e7',
        ]);

        $david = User::create([
            'name' => 'david_optim',
            'email' => 'david@performance.com',
            'password' => Hash::make('password'),
            'reputation_score' => 8100,
            'bio' => 'Performance Engineer. HFM background. I measure execution time in microseconds. C++, Rust, and low-level PHP optimization.',
            'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=david&backgroundColor=a6adc8',
        ]);

        $users = collect([$celestin, $sarah, $marcus, $lea, $david]);

        // 3. CREATE SPECIALIZED GROUPS
        $groups = collect([
            Group::create([
                'name' => 'Core_Architecture',
                'slug' => 'core-architecture',
                'description' => 'Discussions on design patterns, scalability, and structural integrity of large-scale applications.',
                'owner_id' => $sarah->id,
            ]),
            Group::create([
                'name' => 'Sec_Audit_Lab',
                'slug' => 'sec-audit-lab',
                'description' => 'A workshop for peer-reviewing security-sensitive endpoints and looking for vulnerabilities.',
                'owner_id' => $marcus->id,
            ]),
            Group::create([
                'name' => 'Optimization_Forge',
                'slug' => 'optimization-forge',
                'description' => 'The place where we shave off every unnecessary millisecond from our payloads.',
                'owner_id' => $david->id,
            ]),
        ]);

        // Join users to groups
        foreach ($groups as $group) {
            foreach ($users as $user) {
                $group->members()->attach($user->id, ['role' => ($user->id === $group->owner_id) ? 'admin' : 'member']);
            }
        }

        // 4. REAL WORLD POSTS & CODE SNIPPETS

        // Scenario 1: Rust Mutex Safety (David)
        $post1 = Post::create([
            'user_id' => $david->id,
            'group_id' => $groups[2]->id,
            'title' => 'Preventing Data Race in Concurrent Shared State',
            'short_description' => 'Implementing Arc<Mutex<T>> for a shared inventory counter in Rust.',
            'description' => "I'm working on a high-concurrency shared state for a marketplace inventory. I used Arc and Mutex but the performance in high contention is lower than expected. Any tips for reducing lock holding time?",
            'review_goals' => 'Review the locking strategy and suggest lock-free alternatives if applicable.',
            'improvement_goals' => 'Improve throughput while maintaining absolute thread safety.',
            'context' => 'Part of the new Real-time Bidding Engine.',
            'visibility' => 'public',
            'lens' => 'performance',
        ]);

        Snippet::create([
            'post_id' => $post1->id,
            'version_number' => 1,
            'filename' => 'inventory.rs',
            'language' => 'rust',
            'code_content' => "use std::sync::{Arc, Mutex};\nuse std::thread;\n\nstruct Inventory {\n    items: u32,\n}\n\nfn main() {\n    let inventory = Arc::new(Mutex::new(Inventory { items: 1000 }));\n    let mut handles = vec![];\n\n    for _ in 0..10 {\n        let inv = Arc::clone(&inventory);\n        let handle = thread::spawn(move || {\n            // CONTENTION POINT: Locking the whole struct for increment\n            let mut data = inv.lock().unwrap();\n            data.items += 1;\n        });\n        handles.push(handle);\n    }\n\n    for handle in handles {\n        handle.join().unwrap();\n    }\n}",
            'sort_order' => 1,
        ]);

        // Scenario 2: PHP Early Return & DTO (Celestin)
        $post2 = Post::create([
            'user_id' => $celestin->id,
            'group_id' => $groups[0]->id,
            'title' => 'Refactoring Business Logic into DTOs and Services',
            'short_description' => 'Moving from massive controllers to specialized data objects.',
            'description' => 'Just finished refactoring our invitation system. I moved all validation logic into a DTO and the registration into a dedicated Service. Looking for feedback on the abstraction level.',
            'review_goals' => 'Is the DTO too verbose? Should I use PHP 8.2 readonly classes on everything?',
            'visibility' => 'public',
            'lens' => 'logic',
        ]);

        Snippet::create([
            'post_id' => $post2->id,
            'version_number' => 1,
            'filename' => 'UserInviteDTO.php',
            'language' => 'php',
            'code_content' => "<?php\n\nnamespace App\\DTOs;\n\nreadonly class UserInviteDTO\n{\n    public function __construct(\n        public string \$email,\n        public array \$roles = ['member'],\n        public ?int \$groupId = null,\n    ) {}\n\n    public static function fromRequest(array \$data): self\n    {\n        return new self(\n            email: \$data['email'],\n            roles: \$data['roles'] ?? ['member'],\n            groupId: \$data['group_id'] ?? null\n        );\n    }\n}",
            'sort_order' => 1,
        ]);

        // Scenario 3: SQL Recursive CTE (Sarah)
        $post3 = Post::create([
            'user_id' => $sarah->id,
            'group_id' => $groups[0]->id,
            'title' => 'Querying Hierarchical Comment Threads',
            'short_description' => 'Using Recursive CTE to fetch unlimited levels of nested comments.',
            'description' => "Our comment system is getting slow with multi-level nesting. I'm testing a recursive Common Table Expression (CTE) to fetch the entire tree in a single query. Is there a depth-limit I should worry about in Postgres?",
            'review_goals' => 'Check the recursion logic and the performance impact of the sorting field.',
            'visibility' => 'public',
            'lens' => 'performance',
        ]);

        Snippet::create([
            'post_id' => $post3->id,
            'version_number' => 1,
            'filename' => 'comments_tree.sql',
            'language' => 'sql',
            'code_content' => "WITH RECURSIVE comment_tree AS (\n    -- Base case: Top level comments\n    SELECT id, parent_id, content, 0 as depth, ARRAY[id] as path\n    FROM post_comments\n    WHERE parent_id IS NULL AND post_id = :post_id\n\n    UNION ALL\n\n    -- Recursive case\n    SELECT c.id, c.parent_id, c.content, ct.depth + 1, ct.path || c.id\n    FROM post_comments c\n    JOIN comment_tree ct ON c.parent_id = ct.id\n)\nSELECT * FROM comment_tree ORDER BY path;",
            'sort_order' => 1,
        ]);

        // Scenario 4: Security - Middleware Validation (Marcus)
        $post4 = Post::create([
            'user_id' => $marcus->id,
            'group_id' => $groups[1]->id,
            'title' => 'Preventing IDOR on Sensitive API Endpoints',
            'short_description' => 'Custom Middleware to verify resource ownership before processing.',
            'description' => 'Found a potential IDOR vulnerability yesterday. I wrote this Middleware to force ownership checks on all private resources. Does this look robust enough to you?',
            'review_goals' => 'Can this be bypassed if multiple resources are requested in the same payload?',
            'visibility' => 'public',
            'lens' => 'security',
        ]);

        Snippet::create([
            'post_id' => $post4->id,
            'version_number' => 1,
            'filename' => 'VerifyOwnership.php',
            'language' => 'php',
            'code_content' => "<?php\n\nnamespace App\\Http\\Middleware;\n\nclass VerifyOwnership\n{\n    public function handle(\$request, \$next, \$model)\n    {\n        \$resourceId = \$request->route(\$model);\n        \$userId = auth()->id();\n\n        // VULNERABILITY CHECK: Verifying if current user owns the resource\n        if (!DB::table(\$model)->where('id', \$resourceId)->where('user_id', \$userId)->exists()) {\n            abort(403, 'Unauthorized access attempt.');\n        }\n\n        return \$next(\$request);\n    }\n}",
            'sort_order' => 1,
        ]);

        // 5. REVIEWS & COMMENTS (REAL CONTENT)

        // David reviews Celestin's DTO
        $rev1 = FullReview::create([
            'user_id' => $david->id,
            'post_id' => $post2->id,
            'description' => 'Excellent move towards DTOs. PHP 8.2 readonly classes are perfect here. One optimization: using `public readonly` on the constructor params is enough, no need for the extra class flag if using the constructor promotion pattern.',
            'score' => 10,
        ]);

        FullReviewSnippet::create([
            'full_review_id' => $rev1->id,
            'snippet_id' => $post2->snippets->first()->id,
            'modified_content' => "<?php\n\nnamespace App\\DTOs;\n\nclass UserInviteDTO\n{\n    public function __construct(\n        public readonly string \$email,\n        public readonly array \$roles = ['member'],\n        public readonly ?int \$groupId = null,\n    ) {}\n}",
            'description' => 'Constructor property promotion is cleaner and as effective.',
        ]);

        // Marcus reviews Sarah's SQL
        $rev2 = FullReview::create([
            'user_id' => $marcus->id,
            'post_id' => $post3->id,
            'description' => 'Recursive CTEs are great. SEC TIP: Make sure `:post_id` is properly cast to an integer in the driver layer, Postgres is strict and it prevents type-juggling injection attempts.',
            'score' => 5,
        ]);

        // Global Comments
        PostComment::create([
            'user_id' => $lea->id,
            'post_id' => $post2->id,
            'content' => 'Love this refactor! Are you planning to add a `ValidationRequest` as well to separate the HTTP layer from the data object?',
        ]);

        // Interactions with celestin
        PostComment::create([
            'user_id' => $celestin->id,
            'post_id' => $post1->id,
            'content' => 'Hey David, if contention is the issue, check out `dashmap` crate for Rust. It uses sharding to avoid global locks. Also, is your `items` counter an `AtomicU32`? That would be much faster than a Mutex for just an increment.',
        ]);

        // 7. CHAT MESSAGES (REAL CHAT)
        $archGroup = $groups[0];
        $chatData = [
            ['u' => $sarah->id, 'm' => 'Welcome to Core Architecture. Let\'s keep the discussion focused on structural integrity.'],
            ['u' => $celestin->id, 'm' => 'Thanks Sarah. Just posted the DTO refactor. Interested to see if we can generalize it for the whole API layer.'],
            ['u' => $sarah->id, 'm' => 'I just reviewed it. Check the point about property promotion. It simplifies the payload signature significantly.'],
            ['u' => $david->id, 'm' => 'Sarah, what about the Recursive CTE depth? I\'m worried it might blow up on deep threads.'],
            ['u' => $sarah->id, 'm' => 'David, Postgres default max recursion is 1000. We\'ll never hit it with our current metadata limit, but I\'ll add a guard clause in the CTE base case anyway.'],
        ];

        foreach ($chatData as $data) {
            GroupMessage::create([
                'group_id' => $archGroup->id,
                'user_id' => $data['u'],
                'content' => $data['m'],
                'created_at' => now()->subMinutes(60 - (count($chatData) * 5)),
            ]);
        }

        // 8. REACTIONS (Post & Reviews)
        foreach (Post::all() as $post) {
            foreach ($users->random(rand(2, 4)) as $reactor) {
                if ($reactor->id !== $post->user_id) {
                    Reaction::create([
                        'user_id' => $reactor->id,
                        'reactable_id' => $post->id,
                        'reactable_type' => Post::class,
                        'type' => collect(['up', 'mindblown', 'clean'])->random(),
                    ]);
                }
            }
        }
    }
}
