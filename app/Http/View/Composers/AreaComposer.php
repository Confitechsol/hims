<?php

namespace App\Http\View\Composers;
use Illuminate\View\View;
use App\Models\Area;

class AreaComposer
{
    public function compose(View $view)
    {
        // Fetch all areas (you can cache if needed)
        $areas = Area::all();

        // Share with the view
        $view->with('areas', $areas);
    }
}