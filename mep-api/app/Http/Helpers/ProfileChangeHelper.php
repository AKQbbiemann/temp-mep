<?php

namespace App\Http\Helpers;


use App\Http\Requests\Cluster\CreateOrUpdateProfileChange;
use App\Models\LoadProfile;
use App\Models\ProfileChange;

class ProfileChangeHelper
{
    public static function createOrUpdateProfileChange(CreateOrUpdateProfileChange $request, LoadProfile $profile): ProfileChange {

        // Update process
        if($request->has('profile_change_id')) {
            $profileChange = ProfileChange::findOrFail($request->get('profile_change_id'));

            //Startdatum wird aus der Zukunft nach Heute gesetzt
            if($request->get('start_date') == date("Y-m-d") &&
                $profileChange->start_date > date("Y-m-d")) {
                if($request->has('fte_change') && $request->get('fte_change') > 0) {
                    $profile->full_time_employees += $request->get('fte_change');
                }
                if($request->has('local_load') &&
                    $request->has('organisation_load') &&
                    $request->has('comprehensive_load') &&
                    $request->has('base_load')) {

                    $profile->local_load = $request->get('local_load');
                    $profile->organisation_load = $request->get('organisation_load');
                    $profile->base_load = $request->get('base_load');
                    $profile->comprehensive = $request->get('comprehensive_load');
                }

                // finally update the resource
                $profile->save();
            }
            // Enddatum wird von Zukunft auf Heute gesetzt oder Startdatum wird von Vergangenheit/Heute
            // (also bereits draufgerechnet) auf Zukunft gesetzt
            if(
                ($request->get('end_date') == date("Y-m-d") && $profileChange->end_date > date("Y-m-d")) ||
                ($request->get('start_date') > date("Y-m-d") && $profileChange->start_date <= date("Y-m-d"))
            ) {
                if($request->has('fte_change') && $request->get('fte_change') > 0) {
                    $profile->full_time_employees += ($request->get('fte_change') * -1);
                }
                if($request->has('local_load') &&
                    $request->has('organisation_load') &&
                    $request->has('comprehensive_load') &&
                    $request->has('base_load')) {

                    $profile->local_load = $request->get('local_load');
                    $profile->organisation_load = $request->get('organisation_load');
                    $profile->base_load = $request->get('base_load');
                    $profile->comprehensive_load = $request->get('comprehensive_load');
                }
                $profile->save();
            }

            $profileChange->fill($request->validated());
            $profileChange->save();

        } else { // New profile change
            // start date = today -> direct change
            if($request->get('start_date') == date("Y-m-d")) {
                if($request->has('fte_change') && $request->get('fte_change') > 0) {
                    $profile->full_time_employees += $request->get('fte_change');
                }
                if($request->has('local_load') &&
                    $request->has('organisation_load') &&
                    $request->has('comprehensive_load') &&
                    $request->has('base_load')) {

                    $profile->local_load = $request->get('local_load');
                    $profile->organisation_load = $request->get('organisation_load');
                    $profile->base_load = $request->get('base_load');
                    $profile->comprehensive_load = $request->get('comprehensive_load');
                }
                $profile->save();
            }

            $profileChange = $profile->profileChanges()->create($request->validated());
        }


        return $profileChange;
    }

}
