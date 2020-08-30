<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Infrastructure\Repositories\PrRepository;

class PrController
{

    /**
     * Get all the artifacts
     *
     * @return \Illuminate\View\Factory
     */
    public function getArtifacts() {
        $artifacts = app(PrRepository::class)->getArtifacts();
        return view('artifacts', compact('artifacts'));
    }

    /**
     * Get all the new artifacts from github
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshArtifacts() {
        app(PrRepository::class)->storeArtifactsFromGH();
        return redirect()->back();
    }
}
