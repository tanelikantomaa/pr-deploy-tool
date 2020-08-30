<?php

namespace App\Http\Controllers;

use App\Artifact;
use App\Infrastructure\Repositories\PrRepository;

class UnDeployController
{


    /**
     * Undeploy the PR
     *
     * @param  string $pr
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function undeploy($pr, $id) {

        try {
            app(PrRepository::class)
                ->removeTmpFiles($id)
                ->removeNameSpace($pr);

            Artifact::where('artifact_id', $id)
                ->update([
                    'deployed' => false
            ]);

        } catch(\Exception $e) {
            throw new \Exception("Something went wrong when trying to undeploy $pr");
        }

        return redirect()->back();
    }
}
