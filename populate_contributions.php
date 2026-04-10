<?php

use App\Models\User;
use App\Models\UserContribution;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Populating user_contributions table...\n";

$users = User::all();

foreach ($users as $user) {
    echo "Processing user: {$user->name} ({$user->id})\n";
    
    // Aggregate all historical contributions
    $posts = DB::table('posts')->where('user_id', $user->id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->groupBy('date')->get();
    $reviews = DB::table('full_reviews')->where('user_id', $user->id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->groupBy('date')->get();
    $comments = DB::table('post_comments')->where('user_id', $user->id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->groupBy('date')->get();
    $suggestions = DB::table('inline_suggestions')->where('user_id', $user->id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))->groupBy('date')->get();

    $all = collect()
        ->concat($posts)
        ->concat($reviews)
        ->concat($comments)
        ->concat($suggestions)
        ->groupBy('date')
        ->map(fn($group) => $group->sum('count'));

    foreach ($all as $date => $count) {
        UserContribution::updateOrCreate(
            ['user_id' => $user->id, 'date' => $date],
            ['count' => $count]
        );
    }
}

echo "Done!\n";
