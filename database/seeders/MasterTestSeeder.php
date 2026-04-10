<?php

namespace Database\Seeders;

use App\Models\FullReview;
use App\Models\FullReviewSnippet;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\InlineSuggestion;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Reaction;
use App\Models\Snippet;
use App\Models\User;
use App\Models\UserContribution;
use App\Models\UserSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterTestSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. NETTOYAGE (préserve le vrai compte GitHub, supprime uniquement les personas fictifs) ---
        DB::statement('PRAGMA foreign_keys = OFF;');
        DB::table('inline_suggestions')->delete();
        DB::table('full_review_snippets')->delete();
        DB::table('full_reviews')->delete();
        DB::table('reactions')->delete();
        DB::table('post_comments')->delete();
        DB::table('snippets')->delete();
        DB::table('posts')->delete();
        DB::table('group_messages')->delete();
        DB::table('group_user')->delete();
        DB::table('groups')->delete();
        DB::table('user_contributions')->delete();
        DB::table('user_skills')->delete();
        // Supprime UNIQUEMENT les personas de test, pas le vrai compte GitHub
        $fakeEmails = ['enrique@reviewme.io', 'thomas@reviewme.io', 'julie@reviewme.io', 'kevin@reviewme.io', 'sophie@reviewme.io', 'lucas@reviewme.io'];
        DB::table('users')->whereIn('email', $fakeEmails)->delete();
        DB::statement('PRAGMA foreign_keys = ON;');

        // --- 2. PERSONAS ---

        // updateOrCreate sur l'email réel GitHub pour éviter les doublons entre runs
        $me = User::updateOrCreate(
            ['email' => 'enriquepuertopro0101@gmail.com'],
            [
                'name'             => 'Enrique P.',
                'handle'           => 'enriquep01',
                'bio'              => 'Fullstack Developer & Platform Owner. Passionné par Laravel, Livewire et l\'architecture propre.',
                'reputation_score' => 5000,
                'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=enrique&backgroundColor=1a1b26',
            ]
        );

        $thomas = User::create([
            'name'             => 'Thomas Architect',
            'handle'           => 'thomas_arch',
            'email'            => 'thomas@reviewme.io',
            'password'         => Hash::make('password'),
            'reputation_score' => 8450,
            'bio'              => 'Lead Architect. Je vis pour le Clean Code et le Domain Driven Design. Si ton constructeur a 12 paramètres, on va avoir un problème.',
            'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=thomas&backgroundColor=1a1b26',
        ]);

        $julie = User::create([
            'name'             => 'Julie Security',
            'handle'           => 'julie_sec',
            'email'            => 'julie@reviewme.io',
            'password'         => Hash::make('password'),
            'reputation_score' => 7200,
            'bio'              => 'Security Researcher. Ne faites JAMAIS confiance à l\'input utilisateur.',
            'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=julie&backgroundColor=f7768e',
        ]);

        $kevin = User::create([
            'name'             => 'Kevin Front',
            'handle'           => 'kevin_pixel',
            'email'            => 'kevin@reviewme.io',
            'password'         => Hash::make('password'),
            'reputation_score' => 5100,
            'bio'              => 'Senior Frontend dev. Expert Tailwind & Alpine.js. Mon but : que ton UI soit aussi fluide que du beurre.',
            'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=kevin&backgroundColor=7aa2f7',
        ]);

        $sophie = User::create([
            'name'             => 'Sophie Performance',
            'handle'           => 'sophie_perf',
            'email'            => 'sophie@reviewme.io',
            'password'         => Hash::make('password'),
            'reputation_score' => 9100,
            'bio'              => 'Performance Engineer. Rust & low-level PHP. Si ton script met plus de 50ms à répondre, il est mal écrit.',
            'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=sophie&backgroundColor=9ece6a',
        ]);

        $lucas = User::create([
            'name'             => 'Lucas Junior',
            'handle'           => 'lucas_dev',
            'email'            => 'lucas@reviewme.io',
            'password'         => Hash::make('password'),
            'reputation_score' => 1200,
            'bio'              => 'Apprenti développeur passionné par Laravel. Toujours en soif d\'apprendre et de recevoir des critiques constructives.',
            'avatar'           => 'https://api.dicebear.com/7.x/avataaars/svg?seed=lucas&backgroundColor=e0af68',
        ]);

        $users = collect([$me, $thomas, $julie, $kevin, $sophie, $lucas]);

        // --- 3. SKILLS PAR PERSONA ---
        $skillsMap = [
            $thomas->id => [['lens' => 'logic', 'score' => 920], ['lens' => 'elegant', 'score' => 780]],
            $julie->id  => [['lens' => 'security', 'score' => 950], ['lens' => 'logic', 'score' => 700]],
            $sophie->id => [['lens' => 'performance', 'score' => 980], ['lens' => 'logic', 'score' => 820]],
            $kevin->id  => [['lens' => 'elegant', 'score' => 890], ['lens' => 'logic', 'score' => 500]],
            $lucas->id  => [['lens' => 'logic', 'score' => 320]],
            $me->id     => [['lens' => 'logic', 'score' => 750], ['lens' => 'performance', 'score' => 680], ['lens' => 'security', 'score' => 550]],
        ];

        foreach ($skillsMap as $userId => $skills) {
            foreach ($skills as $skill) {
                UserSkill::create(array_merge($skill, ['user_id' => $userId]));
            }
        }

        // --- 4. GROUPES ---
        $groupArch = Group::create([
            'name'        => 'Architecture_Guild',
            'slug'        => 'architecture-guild',
            'description' => 'Discussions sur les patterns complexes, le découplage et la scalabilité des applications Laravel.',
            'owner_id'    => $thomas->id,
        ]);

        $groupSec = Group::create([
            'name'        => 'Security_Lab',
            'slug'        => 'security-lab',
            'description' => 'Audit de code, traque de vulnérabilités et partage de bonnes pratiques de sécurisation.',
            'owner_id'    => $julie->id,
        ]);

        $groupPerf = Group::create([
            'name'        => 'Performance_Hackers',
            'slug'        => 'performance-hackers',
            'description' => 'Shaving off milliseconds. SQL optimization, Caching & Engine internals.',
            'owner_id'    => $sophie->id,
        ]);

        $groups = collect([$groupArch, $groupSec, $groupPerf]);

        foreach ($groups as $group) {
            foreach ($users as $user) {
                $role = ($user->id === $group->owner_id) ? 'admin' : 'member';
                $group->members()->attach($user->id, ['role' => $role]);
            }
        }

        // --- 5. POSTS COMPLETS ---

        // POST 1 : Lucas — Fat Controller (LOGIC, public)
        $postLucas = Post::create([
            'user_id'           => $lucas->id,
            'title'             => 'Mon contrôleur est-il trop gros ?',
            'short_description' => 'J\'ai mis toute ma logique de création de commande dans le contrôleur Laravel.',
            'description'       => 'Salut les experts ! Ce contrôleur gère la création de commande, l\'envoi de mail ET la mise à jour des stocks. Ça marche mais ça me paraît "sale". Comment mieux structurer ça ?',
            'review_goals'      => 'Dois-je extraire la logique vers un Service ou une Action ? Comment gérer les rollbacks si l\'email échoue ?',
            'improvement_goals' => 'Rendre le code testable unitairement. Mieux gérer les erreurs.',
            'visibility'        => 'public',
            'lens'              => 'logic',
            'created_at'        => now()->subDays(8),
        ]);

        $snipLucas1 = Snippet::create([
            'post_id'        => $postLucas->id,
            'version_number' => 1,
            'filename'       => 'OrderController.php',
            'language'       => 'php',
            'description'    => 'Contrôleur principal de création de commande — version initiale monolithique.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Crée une commande, met à jour les stocks et envoie le mail de confirmation.
     */
    public function store(Request $request)
    {
        $order = Order::create($request->all());

        foreach ($request->items as $item) {
            $product = Product::find($item['id']);
            $product->stock -= $item['qty'];
            $product->save();
        }

        Mail::to($request->user())->send(new OrderConfirmed($order));

        return response()->json($order);
    }
}
PHP,
        ]);

        $snipLucas2 = Snippet::create([
            'post_id'        => $postLucas->id,
            'version_number' => 1,
            'filename'       => 'StoreOrderRequest.php',
            'language'       => 'php',
            'description'    => 'FormRequest de validation — actuellement vide, à enrichir.',
            'sort_order'     => 2,
            'code_content'   => <<<'PHP'
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // TODO: ajouter les règles de validation
    public function rules(): array
    {
        return [];
    }
}
PHP,
        ]);

        // Version 2 de OrderController (après feedback de Thomas)
        Snippet::create([
            'post_id'        => $postLucas->id,
            'version_number' => 2,
            'filename'       => 'OrderController.php',
            'language'       => 'php',
            'description'    => 'Contrôleur refactorisé — délégation à CreateOrderAction.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CreateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Délègue la création de commande à l'Action dédiée.
     */
    public function store(StoreOrderRequest $request, CreateOrderAction $action)
    {
        $order = $action->execute($request->validated());
        return new OrderResource($order);
    }
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postLucas->id,
            'version_number' => 2,
            'filename'       => 'CreateOrderAction.php',
            'language'       => 'php',
            'description'    => 'Action atomique gérant la création, le stock et le mail dans une transaction.',
            'sort_order'     => 2,
            'code_content'   => <<<'PHP'
<?php

namespace App\Actions\Orders;

use App\Mail\OrderConfirmed;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CreateOrderAction
{
    /**
     * Exécute la création de commande dans une transaction atomique.
     */
    public function execute(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create($data);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['id']);
                $product->decrement('stock', $item['qty']);
            }

            Mail::to($data['email'])->send(new OrderConfirmed($order));

            return $order;
        });
    }
}
PHP,
        ]);

        // POST 2 : Sophie — Lock-free Queue Rust (PERFORMANCE, groupe)
        $postSophie = Post::create([
            'user_id'           => $sophie->id,
            'group_id'          => $groupPerf->id,
            'title'             => 'Lock-free MPMC Queue en Rust',
            'short_description' => 'Implémentation d\'une queue sans Mutex pour haute performance (1M+ msg/sec).',
            'description'       => 'J\'essaie d\'implémenter une queue très rapide pour notre gestionnaire de logs. Le Mutex global crée un goulot d\'étranglement sous forte charge. Je teste une approche atomique MPMC.',
            'review_goals'      => 'Vérifier la gestion de la mémoire Unsafe et les barrières atomiques. Le code est-il correctement memory-safe ?',
            'improvement_goals' => 'Optimiser les orderings Atomic sans sacrifier la correction. Possibilité de backpressure ?',
            'visibility'        => 'public',
            'lens'              => 'performance',
            'created_at'        => now()->subDays(3),
        ]);

        Snippet::create([
            'post_id'        => $postSophie->id,
            'version_number' => 1,
            'filename'       => 'mpmc_queue.rs',
            'language'       => 'rust',
            'description'    => 'Structure principale de la queue lock-free avec AtomicUsize pour head/tail.',
            'sort_order'     => 1,
            'code_content'   => <<<'RUST'
use std::cell::UnsafeCell;
use std::sync::atomic::{AtomicUsize, Ordering};

struct Slot<T> {
    sequence: AtomicUsize,
    value: UnsafeCell<Option<T>>,
}

/// Queue MPMC lock-free basée sur un ring buffer.
pub struct Queue<T> {
    buffer: Box<[Slot<T>]>,
    head: AtomicUsize,
    tail: AtomicUsize,
    capacity: usize,
}

impl<T> Queue<T> {
    pub fn new(capacity: usize) -> Self {
        let buffer: Box<[Slot<T>]> = (0..capacity)
            .map(|i| Slot {
                sequence: AtomicUsize::new(i),
                value: UnsafeCell::new(None),
            })
            .collect::<Vec<_>>()
            .into_boxed_slice();

        Queue { buffer, head: AtomicUsize::new(0), tail: AtomicUsize::new(0), capacity }
    }

    /// Tente d'insérer une valeur. Retourne false si la queue est pleine.
    pub fn push(&self, value: T) -> bool {
        let mut tail = self.tail.load(Ordering::Relaxed);
        loop {
            let slot = &self.buffer[tail % self.capacity];
            let seq = slot.sequence.load(Ordering::Acquire);
            let diff = seq as isize - tail as isize;
            if diff == 0 {
                match self.tail.compare_exchange_weak(tail, tail + 1, Ordering::Relaxed, Ordering::Relaxed) {
                    Ok(_) => {
                        unsafe { *slot.value.get() = Some(value) };
                        slot.sequence.store(tail + 1, Ordering::Release);
                        return true;
                    }
                    Err(t) => tail = t,
                }
            } else if diff < 0 {
                return false; // Queue pleine
            } else {
                tail = self.tail.load(Ordering::Relaxed);
            }
        }
    }
}
RUST,
        ]);

        Snippet::create([
            'post_id'        => $postSophie->id,
            'version_number' => 1,
            'filename'       => 'bench.rs',
            'language'       => 'rust',
            'description'    => 'Benchmark de throughput avec Criterion.',
            'sort_order'     => 2,
            'code_content'   => <<<'RUST'
use criterion::{criterion_group, criterion_main, Criterion};
use std::sync::Arc;
use std::thread;

fn bench_push_pop(c: &mut Criterion) {
    c.bench_function("mpmc_push_1m", |b| {
        b.iter(|| {
            let q = Arc::new(Queue::<u64>::new(1024));
            let producers: Vec<_> = (0..4).map(|_| {
                let q = Arc::clone(&q);
                thread::spawn(move || {
                    for i in 0..250_000u64 {
                        while !q.push(i) {}
                    }
                })
            }).collect();
            for h in producers { h.join().unwrap(); }
        });
    });
}

criterion_group!(benches, bench_push_pop);
criterion_main!(benches);
RUST,
        ]);

        // POST 3 : Julie — IDOR Vulnerability (SECURITY, groupe)
        $postJulie = Post::create([
            'user_id'           => $julie->id,
            'group_id'          => $groupSec->id,
            'title'             => 'Pourquoi ce middleware de téléchargement est dangereux ?',
            'short_description' => 'Un classique IDOR : n\'importe qui peut télécharger les factures d\'un autre utilisateur.',
            'description'       => 'J\'ai trouvé ça dans un vieux repo de production. Saurez-vous identifier la faille avant de lire ma solution ? La vulnérabilité permet à un attaquant authentifié d\'accéder aux documents de tous les autres utilisateurs.',
            'review_goals'      => 'Identifier la vulnérabilité IDOR. Proposer la correction minimale et la correction robuste.',
            'improvement_goals' => 'Ajouter des tests d\'autorisation automatisés pour éviter les régressions.',
            'visibility'        => 'public',
            'lens'              => 'security',
            'created_at'        => now()->subDays(15),
        ]);

        $snipJulie = Snippet::create([
            'post_id'        => $postJulie->id,
            'version_number' => 1,
            'filename'       => 'InvoiceController.php',
            'language'       => 'php',
            'description'    => 'Contrôleur de téléchargement de factures — version vulnérable IDOR.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Télécharge la facture correspondant à l'ID fourni.
     * FAILLE : aucune vérification de l'ownership.
     */
    public function download($id)
    {
        $invoice = Invoice::findOrFail($id);
        return Storage::download($invoice->path);
    }
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postJulie->id,
            'version_number' => 2,
            'filename'       => 'InvoiceController.php',
            'language'       => 'php',
            'description'    => 'Version corrigée : vérification d\'ownership avant téléchargement.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Télécharge la facture — avec vérification de propriété via Gate/Policy.
     */
    public function download(Request $request, Invoice $invoice)
    {
        Gate::authorize('download', $invoice);
        return Storage::download($invoice->path);
    }
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postJulie->id,
            'version_number' => 2,
            'filename'       => 'InvoicePolicy.php',
            'language'       => 'php',
            'description'    => 'Policy Laravel pour sécuriser l\'accès aux factures.',
            'sort_order'     => 2,
            'code_content'   => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    /**
     * Seul le propriétaire de la facture peut la télécharger.
     */
    public function download(User $user, Invoice $invoice): bool
    {
        return $user->id === $invoice->user_id;
    }
}
PHP,
        ]);

        // POST 4 : Thomas — DDD Manifesto (LOGIC, Architecture guild)
        $postThomas = Post::create([
            'user_id'           => $thomas->id,
            'group_id'          => $groupArch->id,
            'title'             => 'Modélisation DDD : Aggregate Root & Value Objects',
            'short_description' => 'Implémentation concrète du DDD dans Laravel sans sur-ingénierie.',
            'description'       => 'Voici comment j\'implémente les Aggregates et les Value Objects dans nos projets Laravel sans tomber dans le piège de la sur-abstraction. L\'objectif est d\'encapsuler le métier sans sacrifier la lisibilité.',
            'review_goals'      => 'L\'implémentation des Value Objects est-elle correcte ? Les invariants de l\'Aggregate sont-ils bien protégés ?',
            'improvement_goals' => 'Explorer l\'event sourcing léger via les Domain Events Laravel.',
            'visibility'        => 'public',
            'lens'              => 'logic',
            'created_at'        => now()->subDays(5),
        ]);

        Snippet::create([
            'post_id'        => $postThomas->id,
            'version_number' => 1,
            'filename'       => 'Order.php',
            'language'       => 'php',
            'description'    => 'Aggregate Root Order avec invariants de domaine encapsulés.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

namespace App\Domain\Orders;

use App\Domain\Orders\ValueObjects\Money;
use App\Domain\Orders\ValueObjects\OrderStatus;

/**
 * Aggregate Root représentant une commande.
 * Protège les invariants métier (on ne peut pas annuler une commande déjà livrée).
 */
final class Order
{
    private OrderStatus $status;
    private Money $total;
    private array $lineItems = [];

    private function __construct(private readonly string $id, Money $total)
    {
        $this->status = OrderStatus::PENDING;
        $this->total  = $total;
    }

    public static function place(string $id, Money $total): self
    {
        return new self($id, $total);
    }

    public function addLineItem(string $productId, int $qty, Money $unitPrice): void
    {
        $this->lineItems[] = ['product_id' => $productId, 'qty' => $qty, 'price' => $unitPrice];
        $this->total = $this->total->add($unitPrice->multiply($qty));
    }

    public function cancel(): void
    {
        if ($this->status->isDelivered()) {
            throw new \DomainException('Cannot cancel an already delivered order.');
        }
        $this->status = OrderStatus::CANCELLED;
    }

    public function total(): Money     { return $this->total; }
    public function status(): OrderStatus { return $this->status; }
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postThomas->id,
            'version_number' => 1,
            'filename'       => 'Money.php',
            'language'       => 'php',
            'description'    => 'Value Object immuable représentant une somme monétaire.',
            'sort_order'     => 2,
            'code_content'   => <<<'PHP'
<?php

namespace App\Domain\Orders\ValueObjects;

/**
 * Value Object immuable pour les montants monétaires.
 * Aucune mutation possible — opérations retournent une nouvelle instance.
 */
final readonly class Money
{
    public function __construct(
        private int $amount,       // En centimes pour éviter les flottants
        private string $currency
    ) {}

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function multiply(int $factor): self
    {
        return new self($this->amount * $factor, $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function formatted(): string
    {
        return number_format($this->amount / 100, 2) . ' ' . $this->currency;
    }

    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new \InvalidArgumentException("Currency mismatch: {$this->currency} vs {$other->currency}");
        }
    }
}
PHP,
        ]);

        // POST 5 : Kevin — CSS Animation Performance (ELEGANT, public)
        $postKevin = Post::create([
            'user_id'           => $kevin->id,
            'title'             => 'Animations CSS sans jank : will-change vs transform',
            'short_description' => 'Comparaison de performance entre will-change et transform pour des animations 60fps.',
            'description'       => 'J\'ai des animations complexes qui droppent à 30fps sur mobile. Après analyse avec DevTools, j\'ai identifié des problèmes de compositing. Voici mon approche actuelle et ce que j\'ai essayé.',
            'review_goals'      => 'Cette approche évite-t-elle correctement les reflows ? Y a-t-il un impact mémoire avec l\'abus de will-change ?',
            'improvement_goals' => 'Rendre les animations accessibles (prefers-reduced-motion). Tester sur iOS Safari.',
            'visibility'        => 'public',
            'lens'              => 'elegant',
            'created_at'        => now()->subDays(1),
        ]);

        Snippet::create([
            'post_id'        => $postKevin->id,
            'version_number' => 1,
            'filename'       => 'animations.css',
            'language'       => 'css',
            'description'    => 'Tentative initiale avec margin et opacity — cause des reflows.',
            'sort_order'     => 1,
            'code_content'   => <<<'CSS'
/* Version initiale — déclenche des reflows (Layout + Paint) */
.card {
    margin-top: 0;
    opacity: 1;
    transition: margin-top 0.3s ease, opacity 0.3s ease;
}

.card:hover {
    margin-top: -8px; /* Mauvais : force recalcul du layout */
    opacity: 0.9;
}

.loader {
    width: 40px;
    height: 40px;
    background: #7aa2f7;
    animation: spin 1s infinite linear;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
CSS,
        ]);

        Snippet::create([
            'post_id'        => $postKevin->id,
            'version_number' => 2,
            'filename'       => 'animations.css',
            'language'       => 'css',
            'description'    => 'Version optimisée GPU — uniquement transform et opacity.',
            'sort_order'     => 1,
            'code_content'   => <<<'CSS'
/* Version optimisée — uniquement les propriétés composées par le GPU */
.card {
    opacity: 1;
    transform: translateY(0);
    /* Indique au navigateur d'isoler le layer AVANT l'animation */
    will-change: transform, opacity;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;
}

.card:hover {
    transform: translateY(-8px); /* Bon : GPU composite layer */
    opacity: 0.92;
}

/* Accessibilité : désactive les animations si l'user le préfère */
@media (prefers-reduced-motion: reduce) {
    .card { transition: none; }
}

.loader {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(122, 162, 247, 0.3);
    border-top-color: #7aa2f7;
    border-radius: 50%;
    animation: spin 0.8s infinite linear;
    will-change: transform;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
CSS,
        ]);

        // POST 6 : Moi — Cache Invalidation strategy (PERFORMANCE)
        $postMe = Post::create([
            'user_id'           => $me->id,
            'title'             => 'Stratégie de Cache Invalidation dans ReviewMe',
            'short_description' => 'Comment on gère l\'invalidation de cache Livewire avec des données temps-réel.',
            'description'       => 'ReviewMe utilise Livewire avec un cache sur les statistiques de profil. Le problème : les stats ne se mettaient pas à jour en temps réel. Voici l\'évolution de notre approche, de 600s à 10s avec un warm-up proactif.',
            'review_goals'      => 'L\'invalidation par tag est-elle la bonne approche avec SQLite en dev ? Le warm-up proactif est-il une bonne pratique ou un anti-pattern ?',
            'improvement_goals' => 'Passer à une invalidation par événement (Observer) plutôt que TTL.',
            'visibility'        => 'public',
            'lens'              => 'performance',
            'created_at'        => now()->subHours(6),
        ]);

        Snippet::create([
            'post_id'        => $postMe->id,
            'version_number' => 1,
            'filename'       => 'Profile.php',
            'language'       => 'php',
            'description'    => 'Version initiale : cache de 600s, trop long pour les stats temps-réel.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

// Dans le composant Livewire Profile

/**
 * Calcule les statistiques de l'utilisateur avec un cache de 10 minutes.
 * Problème : les stats sont périmées pendant 600 secondes après chaque action.
 */
public function getStatsProperty(): array
{
    return Cache::remember("user_stats_{$this->user->id}", 600, function () {
        return [
            'karma'    => $this->user->reputation_score,
            'posts'    => $this->user->posts()->count(),
            'reviews'  => $this->user->fullReviews()->count(),
            'comments' => $this->user->comments()->count(),
        ];
    });
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postMe->id,
            'version_number' => 2,
            'filename'       => 'Profile.php',
            'language'       => 'php',
            'description'    => 'Version optimisée : cache de 10s + invalidation explicite après chaque action.',
            'sort_order'     => 1,
            'code_content'   => <<<'PHP'
<?php

// Dans le composant Livewire Profile

/**
 * Cache court (10s) pour les stats temps-réel.
 * Le cache est invalidé explicitement par UserContribution::record().
 */
public function getStatsProperty(): array
{
    return Cache::remember("user_stats_{$this->user->id}", 10, function () {
        return [
            'karma'    => $this->user->fresh()->reputation_score,
            'posts'    => $this->user->posts()->count(),
            'reviews'  => $this->user->fullReviews()->count(),
            'comments' => $this->user->comments()->count(),
        ];
    });
}

/**
 * Invalide explicitement le cache du profil ciblé.
 */
public static function bustCache(int $userId): void
{
    Cache::forget("user_stats_{$userId}");
}
PHP,
        ]);

        Snippet::create([
            'post_id'        => $postMe->id,
            'version_number' => 2,
            'filename'       => 'UserContribution.php',
            'language'       => 'php',
            'description'    => 'Model d\'enregistrement des contributions avec invalidation de cache intégrée.',
            'sort_order'     => 2,
            'code_content'   => <<<'PHP'
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class UserContribution extends Model
{
    protected $fillable = ['user_id', 'date', 'count'];

    /**
     * Enregistre une contribution et invalide le cache de profil associé.
     */
    public static function record(int $userId): void
    {
        self::updateOrCreate(
            ['user_id' => $userId, 'date' => now()->toDateString()],
            ['count' => \DB::raw('count + 1')]
        );

        // Invalidation du cache pour que les stats soient à jour immédiatement
        Cache::forget("user_stats_{$userId}");
    }
}
PHP,
        ]);

        // --- 6. FULL REVIEWS ---

        // Review de Thomas sur le post de Lucas
        $revThomas = FullReview::create([
            'user_id'     => $thomas->id,
            'post_id'     => $postLucas->id,
            'description' => 'Effectivement, c\'est un Fat Controller classique. Tu cumules 3 responsabilités dans une seule méthode : la persistance, la gestion des stocks et la notification. Sépare ça avec une Action et une transaction DB pour garantir l\'atomicité. Si le mail échoue, le stock ne doit pas avoir été décompté.',
            'score'       => 85,
            'created_at'  => now()->subDays(6),
        ]);

        FullReviewSnippet::create([
            'full_review_id'   => $revThomas->id,
            'snippet_id'       => $snipLucas1->id,
            'modified_content' => <<<'PHP'
<?php

namespace App\Http\Controllers;

use App\Actions\Orders\CreateOrderAction;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, CreateOrderAction $action)
    {
        $order = $action->execute($request->validated());
        return new OrderResource($order);
    }
}
PHP,
            'description' => 'Contrôleur réduit à son rôle : recevoir la requête validée et retourner la ressource.',
        ]);

        // Review de Sophie sur le post de Kevin
        $revSophie = FullReview::create([
            'user_id'     => $sophie->id,
            'post_id'     => $postKevin->id,
            'description' => 'L\'approche est correcte. Cependant, will-change ne doit PAS être appliqué à tous les éléments — il consomme de la VRAM. Crée un layer GPU uniquement pendant l\'animation, pas en état idle.',
            'score'       => 78,
            'created_at'  => now()->subHours(10),
        ]);

        // Review de Julie sur le post de Thomas (DDD)
        FullReview::create([
            'user_id'     => $julie->id,
            'post_id'     => $postThomas->id,
            'description' => 'L\'implémentation est propre. Un point à regarder : dans Order::cancel(), tu lèves une DomainException mais tu ne la logs pas. Dans un contexte distribué, ces exceptions doivent être capturées et publiées comme Domain Events pour permettre à d\'autres bounded contexts de réagir.',
            'score'       => 92,
            'created_at'  => now()->subDays(4),
        ]);

        // --- 7. INLINE SUGGESTIONS ---

        InlineSuggestion::create([
            'user_id'           => $thomas->id,
            'snippet_id'        => $snipLucas2->id,
            'line_number'       => 14,
            'end_line_number'   => 18,
            'original_content'  => "// TODO: ajouter les règles de validation\n    public function rules(): array\n    {\n        return [];\n    }",
            'suggested_content' => "public function rules(): array\n    {\n        return [\n            'items'           => ['required', 'array', 'min:1'],\n            'items.*.id'      => ['required', 'integer', 'exists:products,id'],\n            'items.*.qty'     => ['required', 'integer', 'min:1'],\n        ];\n    }",
            'description'       => 'Le FormRequest doit valider les items en profondeur avec des règles nested. L\'existence des products IDs doit être vérifiée en base.',
        ]);

        InlineSuggestion::create([
            'user_id'           => $julie->id,
            'snippet_id'        => $snipJulie->id,
            'line_number'       => 13,
            'end_line_number'   => 16,
            'original_content'  => "public function download(\$id)\n    {\n        \$invoice = Invoice::findOrFail(\$id);\n        return Storage::download(\$invoice->path);\n    }",
            'suggested_content' => "public function download(Request \$request, Invoice \$invoice)\n    {\n        Gate::authorize('download', \$invoice);\n        return Storage::download(\$invoice->path);\n    }",
            'description'       => 'Utilise le Route Model Binding et une Policy dédiée. Ça centralise la logique d\'autorisation et permet de la tester unitairement.',
        ]);

        // --- 8. COMMENTAIRES THREADÉS ---

        // Thread sur le post de Lucas
        $c1 = PostComment::create([
            'user_id'    => $thomas->id,
            'post_id'    => $postLucas->id,
            'content'    => 'Effectivement, c\'est un Fat Controller. Je t\'ai soumis une review complète ci-dessous avec le code corrigé.',
            'is_pinned'  => true,
            'created_at' => now()->subDays(7),
        ]);

        PostComment::create([
            'user_id'    => $lucas->id,
            'post_id'    => $postLucas->id,
            'parent_id'  => $c1->id,
            'content'    => 'Merci Thomas ! La notion d\'Action est très claire. Je me demandais si on pouvait aussi utiliser un Job pour l\'envoi du mail ?',
            'created_at' => now()->subDays(6),
        ]);

        PostComment::create([
            'user_id'    => $thomas->id,
            'post_id'    => $postLucas->id,
            'parent_id'  => $c1->id,
            'content'    => 'Oui ! Pour le mail tu peux même utiliser `Mail::later()` avec un Job en queue, ça rend le endpoint encore plus rapide.',
            'created_at' => now()->subDays(6),
        ]);

        // Thread sur le post de Julie
        $c2 = PostComment::create([
            'user_id'    => $lucas->id,
            'post_id'    => $postJulie->id,
            'content'    => 'Il manque un `if($invoice->user_id !== auth()->id())` avant le download, non ?',
            'created_at' => now()->subDays(14),
        ]);

        PostComment::create([
            'user_id'    => $julie->id,
            'post_id'    => $postJulie->id,
            'parent_id'  => $c2->id,
            'content'    => 'Exactement Lucas ! Et encore mieux : on utilise une Policy Laravel plutôt d\'une condition inline. Voir ma Version 2.',
            'is_pinned'  => false,
            'created_at' => now()->subDays(13),
        ]);

        // Thread sur le post de Sophie
        PostComment::create([
            'user_id'    => $julie->id,
            'post_id'    => $postSophie->id,
            'content'    => 'Attention à Ordering::Relaxed sur l\'accès à `sequence`. Il faut Acquire sur la lecture et Release sur l\'écriture pour garantir la visibilité inter-thread sur toutes les architectures (ARM inclus).',
            'created_at' => now()->subDays(2),
        ]);

        PostComment::create([
            'user_id'    => $sophie->id,
            'post_id'    => $postSophie->id,
            'content'    => 'Totalement d\'accord Julie, je l\'ai déjà corrigé en local. Sur x86 le modèle mémoire est plus fort mais ça reste un bug potentiel sur ARM.',
            'created_at' => now()->subDays(2),
        ]);

        // Thread sur le post de Kevin
        PostComment::create([
            'user_id'    => $sophie->id,
            'post_id'    => $postKevin->id,
            'content'    => 'will-change: transform sur tous les éléments est contre-productif. Ajoute-le uniquement via JS juste avant l\'animation, et retire-le après.',
            'created_at' => now()->subHours(8),
        ]);

        // --- 9. INLINE SUGGESTIONS (quick reviews sur des parties de code précises) ---

        // Sur OrderController V1 de Lucas — ligne 8 : Order::create sans validation
        InlineSuggestion::create([
            'user_id'          => $thomas->id,
            'snippet_id'       => $snipLucas1->id,
            'line_number'      => 8,
            'end_line_number'  => 8,
            'original_content' => "\$order = Order::create(\$request->all());",
            'suggested_content' => "\$order = Order::create(\$request->validated());",
            'description'      => 'Ne jamais passer `$request->all()` directement au modèle. Utilise `$request->validated()` après déclaration des règles dans un FormRequest pour éviter la mass-assignment.',
        ]);

        // Sur OrderController V1 — boucle de stock sans transaction (lignes 10-14)
        InlineSuggestion::create([
            'user_id'          => $sophie->id,
            'snippet_id'       => $snipLucas1->id,
            'line_number'      => 10,
            'end_line_number'  => 14,
            'original_content' => "foreach(\$request->items as \$item) {\n    \$product = Product::find(\$item['id']);\n    \$product->stock -= \$item['qty'];\n    \$product->save();\n}",
            'suggested_content' => "DB::transaction(function () use (\$request, \$order) {\n    foreach (\$request->validated('items') as \$item) {\n        Product::lockForUpdate()->findOrFail(\$item['id'])->decrement('stock', \$item['qty']);\n    }\n});",
            'description'      => 'Cette boucle n\'est pas atomique. Si `save()` réussit pour le produit A mais échoue pour le produit B, le stock A est décompté sans commande valide. Enveloppe dans `DB::transaction()` et utilise `lockForUpdate()` pour éviter les race conditions.',
        ]);

        // Sur StoreOrderRequest — rules() vide (ligne 14)
        InlineSuggestion::create([
            'user_id'          => $julie->id,
            'snippet_id'       => $snipLucas2->id,
            'line_number'      => 14,
            'end_line_number'  => 17,
            'original_content' => "// TODO: ajouter les règles de validation\n    public function rules(): array\n    {\n        return [];\n    }",
            'suggested_content' => "public function rules(): array\n    {\n        return [\n            'items'           => ['required', 'array', 'min:1'],\n            'items.*.id'      => ['required', 'integer', 'exists:products,id'],\n            'items.*.qty'     => ['required', 'integer', 'min:1', 'max:999'],\n            'email'           => ['required', 'email'],\n        ];\n    }",
            'description'      => 'Un FormRequest avec un tableau de rules vide ne valide rien. Chaque champ attendu doit être déclaré. La règle `exists:products,id` évite les injections d\'IDs fantômes qui feraient planter `findOrFail` silencieusement.',
        ]);

        // Sur mpmc_queue.rs — Ordering::Relaxed sur le load (ligne 31)
        $snipRust = Snippet::where('post_id', $postSophie->id)->where('filename', 'mpmc_queue.rs')->first();
        if ($snipRust) {
            InlineSuggestion::create([
                'user_id'          => $julie->id,
                'snippet_id'       => $snipRust->id,
                'line_number'      => 31,
                'end_line_number'  => 31,
                'original_content' => "let seq = slot.sequence.load(Ordering::Acquire);",
                'suggested_content' => "let seq = slot.sequence.load(Ordering::Acquire); // Acquire correct ici",
                'description'      => 'Ordering::Acquire sur le load de sequence est correct pour synchroniser avec le Release côté writer. Si tu passes à Relaxed ici, la valeur lue peut être périmée sur ARM — tu lirais une séquence ancienne et entrerais dans la branche diff == 0 à tort, corrompant la position.',
            ]);

            // Sur mpmc_queue.rs — compare_exchange_weak sans retry loop (ligne 33-38)
            InlineSuggestion::create([
                'user_id'          => $thomas->id,
                'snippet_id'       => $snipRust->id,
                'line_number'      => 33,
                'end_line_number'  => 38,
                'original_content' => "match self.tail.compare_exchange_weak(tail, tail + 1, Ordering::Relaxed, Ordering::Relaxed) {",
                'suggested_content' => "match self.tail.compare_exchange_weak(tail, tail + 1, Ordering::SeqCst, Ordering::Relaxed) {",
                'description'      => '`compare_exchange_weak` avec `Relaxed` success ordering ne garantit pas que l\'incrément de tail est visible avant l\'écriture dans le slot. Utilise `SeqCst` ou au minimum `AcqRel` pour le success ordering afin d\'ordonner correctement l\'accès au slot par rapport à d\'autres threads.',
            ]);
        }

        // Sur InvoiceController vulnérable — find sans autorisation (lignes 13-15)
        $snipInvoiceVuln = Snippet::where('post_id', $postJulie->id)->where('version_number', 1)->first();
        if ($snipInvoiceVuln) {
            InlineSuggestion::create([
                'user_id'          => $kevin->id,
                'snippet_id'       => $snipInvoiceVuln->id,
                'line_number'      => 13,
                'end_line_number'  => 15,
                'original_content' => "public function download(\$id)\n    {\n        \$invoice = Invoice::findOrFail(\$id);\n        return Storage::download(\$invoice->path);\n    }",
                'suggested_content' => "public function download(Request \$request, Invoice \$invoice): BinaryFileResponse\n    {\n        abort_unless(\$request->user()->can('download', \$invoice), 403);\n        return Storage::download(\$invoice->path);\n    }",
                'description'      => 'L\'IDOR est trivial ici : n\'importe quel utilisateur authentifié peut bruteforcer les IDs entiers pour télécharger toutes les factures. La correction minimale est `abort_unless` ou mieux : Route Model Binding + Policy comme le montre la version 2.',
            ]);
        }

        // Sur animations.css V1 — mauvaise transition sur margin (lignes 5-9)
        $snipCss = Snippet::where('post_id', $postKevin->id)->where('version_number', 1)->first();
        if ($snipCss) {
            InlineSuggestion::create([
                'user_id'          => $sophie->id,
                'snippet_id'       => $snipCss->id,
                'line_number'      => 5,
                'end_line_number'  => 9,
                'original_content' => ".card {\n    margin-top: 0;\n    opacity: 1;\n    transition: margin-top 0.3s ease, opacity 0.3s ease;\n}",
                'suggested_content' => ".card {\n    transform: translateY(0);\n    opacity: 1;\n    will-change: transform, opacity;\n    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.2s ease;\n}",
                'description'      => '`margin-top` en transition force un **reflow** complet à chaque frame : le navigateur doit recalculer le layout de tous les éléments siblings. `transform: translateY()` est composé exclusivement par le GPU — zéro reflow, 60fps stable.',
            ]);
        }

        // Sur Profile.php V1 — cache TTL trop long (ligne 3)
        $snipProfile = Snippet::where('post_id', $postMe->id)->where('version_number', 1)->first();
        if ($snipProfile) {
            InlineSuggestion::create([
                'user_id'          => $thomas->id,
                'snippet_id'       => $snipProfile->id,
                'line_number'      => 3,
                'end_line_number'  => 3,
                'original_content' => "return Cache::remember(\"user_stats_{\$this->user->id}\", 600, function () {",
                'suggested_content' => "return Cache::remember(\"user_stats_{\$this->user->id}\", 10, function () {",
                'description'      => 'TTL de 600 secondes est beaucoup trop long pour des stats affichées en temps réel. Réduire à 10s et coupler à une invalidation explicite via `Cache::forget()` dans `UserContribution::record()` est le compromis optimal.',
            ]);
        }

        // --- 10. RÉACTIONS RÉALISTES ---
        $reactionTypes = ['mindblown', 'optimisable'];
        $allPosts = Post::all();

        foreach ($users as $user) {
            foreach ($allPosts->shuffle()->take(rand(3, 5)) as $post) {
                if ($user->id === $post->user_id) continue;
                Reaction::updateOrCreate(
                    ['user_id' => $user->id, 'reactable_id' => $post->id, 'reactable_type' => Post::class],
                    ['type' => $reactionTypes[array_rand($reactionTypes)], 'created_at' => now()->subDays(rand(0, 10))]
                );
            }
        }

        // Réactions aux Full Reviews
        foreach ([$revThomas, $revSophie] as $review) {
            foreach ($users->shuffle()->take(3) as $user) {
                if ($user->id === $review->user_id) continue;
                Reaction::updateOrCreate(
                    ['user_id' => $user->id, 'reactable_id' => $review->id, 'reactable_type' => FullReview::class],
                    ['type' => 'mindblown', 'created_at' => now()->subDays(rand(0, 5))]
                );
            }
        }

        // --- 10. MESSAGES DE GROUPE ---
        $chat = [
            [$thomas->id,  'Architecture_Guild', 'Bienvenue ! On est là pour casser du monolithe et penser en domaines.'],
            [$lucas->id,   'Architecture_Guild', 'Est-ce que vous conseillez Livewire pour des dashboards complexes ?'],
            [$kevin->id,   'Architecture_Guild', 'L\'UI de ReviewMe prouve que oui. Le secret : wire:navigate + Alpine.js pour tout ce qui est immédiat.'],
            [$thomas->id,  'Architecture_Guild', 'Gardez vos composants Livewire lean — la logique métier va dans les Actions.'],
            [$julie->id,   'Security_Lab',       'Rappel hebdo : toujours valider ET assainir les inputs, même en interne.'],
            [$me->id,      'Security_Lab',       'J\'ai mis en place le rate limiting sur les routes d\'auth, quelqu\'un peut auditer ?'],
            [$sophie->id,  'Performance_Hackers', 'Résultat du bench de la queue Rust : 1.2M msg/sec sans regex. Content.'],
            [$kevin->id,   'Performance_Hackers', 'Impressionnant. Tu as comparé avec Tokio MPSC ?'],
        ];

        $groupMap = [
            'Architecture_Guild'  => $groupArch,
            'Security_Lab'        => $groupSec,
            'Performance_Hackers' => $groupPerf,
        ];

        foreach ($chat as $i => $data) {
            GroupMessage::create([
                'group_id'   => $groupMap[$data[1]]->id,
                'user_id'    => $data[0],
                'content'    => $data[2],
                'created_at' => now()->subMinutes(200 - ($i * 15)),
            ]);
        }

        // --- 11. CONTRIBUTIONS HEATMAP (12 derniers mois) ---
        foreach ($users as $user) {
            $activityDays = match(true) {
                $user->reputation_score > 8000 => 180,
                $user->reputation_score > 5000 => 120,
                default => 60,
            };

            for ($i = 0; $i < $activityDays; $i++) {
                $date = now()->subDays(rand(0, 365))->toDateString();
                // Utilise la méthode existante du modèle qui gère le raw increment correctement
                UserContribution::updateOrCreate(
                    ['user_id' => $user->id, 'date' => $date],
                    ['count' => 1]
                );
                // Incrémente si la date existait déjà
                UserContribution::where(['user_id' => $user->id, 'date' => $date])
                    ->increment('count', rand(0, 3));
            }
        }


        // Bump karma de Lucas après ses reviews reçues
        $lucas->increment('reputation_score', 200);

        $this->command->info('MasterTestSeeder: base de données peuplée avec succès. Aucun post sans code.');
    }
}
