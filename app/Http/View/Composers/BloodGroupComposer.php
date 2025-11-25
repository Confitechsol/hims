<?php

namespace App\Http\View\Composers;
use Illuminate\View\View;
use App\Models\BloodBankProduct; 

class BloodGroupComposer
{
    /**
     * Create a new class instance.
     */
    public function compose(View $view)
    {
        $bloodGroups = BloodBankProduct::where('is_blood_group', 1)->get();
        $view->with('bloodGroups', $bloodGroups);
    }
    public function __construct()
    {
        //
    }
}
