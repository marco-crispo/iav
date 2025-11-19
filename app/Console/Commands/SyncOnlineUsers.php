<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OnlineUser;
use Ably\AblyRest;

class SyncOnlineUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-online-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizes the online_users table with Ably presence data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Online users synchronization in progress...');

        try {
            $ably = new AblyRest(env('ABLY_KEY'));
            $channel = $ably->channel('presence:user-location');

            // Recupera i membri attualmente connessi su Ably
            $members = $channel->presence->get();
            $onlineIds = collect($members)->pluck('data.id')->filter()->unique();

            // Rimuovi utenti non più online
            OnlineUser::whereNotIn('user_id', $onlineIds)->delete();

            // Aggiorna/aggiungi quelli presenti
            foreach ($onlineIds as $id) {
                OnlineUser::updateOrCreate(
                    ['user_id' => $id],
                    ['status' => 'online', 'last_seen_at' => now()]
                );
            }

            $this->info("Synchronization completed. Online users: " . $onlineIds->count());
        } catch (\Exception $e) {
            $this->error("Error during synchronization: " . $e->getMessage());
        }
    }
}
