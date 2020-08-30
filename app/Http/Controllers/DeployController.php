<?php

namespace App\Http\Controllers;

use App\Artifact;
use App\Infrastructure\Repositories\PrRepository;

class DeployController
{

    /**
     * Deploy the PR
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deploy($id) {
        $url = "https://api.github.com/repos/CashCalc/cashcalc/actions/artifacts/{$id}/zip";

        try {
            $output = app(PrRepository::class)
                ->pull($url, $id)
                ->deploy($id);

        } catch(\Exception $e) {
            throw new \Exception("Something went wrong when trying to deploy $pr");
        }

        $info = explode(" ", $output);
        $pr = str_replace('namespace/', '', $info[0]);
        $prUrl = 'https://' . $pr . '.cashcalc.dev';

        Artifact::where('artifact_id', $id)
            ->update([
                'deployed' => true,
                'pull_request_id' => $pr
            ]);

        return redirect()->back();
    }
}
