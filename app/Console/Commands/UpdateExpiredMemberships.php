<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateExpiredMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de las membresías expiradas a "expirado"';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $affected = DB::table('user_memberships')
            ->where('fecha_expiracion', '<', Carbon::today()->toDateString())
            ->where('estado', 'activo')
            ->update(['estado' => 'expirado']);

        $this->info("Membresías actualizadas: $affected");

        return 0;
    }
}
