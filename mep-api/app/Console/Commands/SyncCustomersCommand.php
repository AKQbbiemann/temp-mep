<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akq:sync-customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs customers from CMDB';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->warn('Fetching customers from cmdb...');
        $connection = DB::connection('cmdb_db');
        $connection->statement('SET ROLE role_internal_services_ro');
        $customers = $connection->table("acc_akquinet_fakturierungssystem.afs_customer")->select([
            'paf_kundenkuerzel', 'paf_firmenname',
        ])->get();

        foreach($customers as $customer) {
            $this->info("checking Customer: ".$customer->paf_firmenname);
            $existingCustomer = Customer::where('shortcode', $customer->paf_kundenkuerzel)->first();
            if(!$existingCustomer) {
                $this->warn('new customer');
                $existingCustomer = new Customer();
            }

            $existingCustomer->fill([
                'name' => $customer->paf_firmenname,
                'shortcode' => $customer->paf_kundenkuerzel,
            ]);

            $existingCustomer->save();
        }


        DB::disconnect('cmdb_db');

        $this->info("found ".count($customers). ' customers');
    }
}
