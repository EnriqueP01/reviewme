<?php

namespace App\Console\Commands;

use App\Models\KarmaTransaction;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RebuildKarmaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'karma:rebuild {--user= : ID de l\'utilisateur spécifique}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Reconstruit intégralement les scores de réputation à partir de l\'historique des transactions.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Début de la reconstruction du Karma...');

        DB::transaction(function () {
            $userId = $this->option('user');

            // 1. Reset des scores
            $userQuery = User::query();
            $skillQuery = UserSkill::query();
            if ($userId) {
                $userQuery->where('id', $userId);
                $skillQuery->where('user_id', $userId);
            }

            $userQuery->update(['reputation_score' => 0]);
            $skillQuery->delete();

            // 2. Reparcourir les transactions
            $transactions = KarmaTransaction::query();
            if ($userId) {
                $transactions->where('user_id', $userId);
            }

            $count = $transactions->count();
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $transactions->chunkById(100, function ($txs) use ($bar) {
                foreach ($txs as $tx) {
                    $user = User::find($tx->user_id);
                    if ($user) {
                        $user->increment('reputation_score', $tx->points);

                        if ($tx->metadata && isset($tx->metadata['lens'])) {
                            $skill = UserSkill::firstOrCreate(
                                ['user_id' => $tx->user_id, 'lens' => $tx->metadata['lens']],
                                ['score' => 0]
                            );
                            $skill->increment('score', $tx->points);
                        }
                    }
                    $bar->advance();
                }
            });

            $bar->finish();
        });

        $this->newLine();
        $this->info('Reconstruction terminée avec succès !');

        return self::SUCCESS;
    }
}
