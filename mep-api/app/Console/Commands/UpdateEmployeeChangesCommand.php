<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\EmployeeChange;
use App\Models\LoadProfile;
use App\Models\ProfileChange;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateEmployeeChangesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'akq:update-employee-changes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Employee Changes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->warn('Look up Employee Changes...');

        $oldChanges = ProfileChange::where('end_date', '<=', date("Y-m-d"))->get()->toArray();
        foreach($oldChanges as $old){
            $profile = LoadProfile::findOrFail($old['load_profile_id']);
            $profile->full_time_employees += ($old['fte_change'] * -1);
            $profile->save();

            ProfileChange::where('id', $old['id'])->delete();
            $this->info("Deleted old Change from ".$old['start_date']." to ".$old['end_date'].": ".$old['reason']);
        }
        $newChanges = ProfileChange::where('start_date', date("Y-m-d"))->where('updated_at', '<', date("Y-m-d"))->get()->toArray();
        foreach($newChanges as $new){
            $profile = LoadProfile::findOrFail($new['load_profile_id']);
            $profile->full_time_employees += $new['fte_change'];
            $profile->save();

            $this->info("Updated Profile FTEs of ".$profile['name']." with ".$new['fte_change']." ,because ".$new['reason']);
        }
    }
}
