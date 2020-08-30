<?php

namespace App\Infrastructure\Repositories;

use App\Artifact;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class PrRepository
{

    /**
     * Get all the artifacts
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getArtifacts() {
        return Artifact::orderBy('artifact_updated_at', 'desc')
            ->paginate(10);
    }

    /**
     * Store all of the artifacts from github
     *
     * @return void
     */
    public function storeArtifactsFromGH() {
        $url = 'https://api.github.com/repos/CashCalc/cashcalc/actions/artifacts';
        $artifacts = (new Client(
        [
            'headers' => [
                'User-Agent' => 'CashCalc',
                'Authorization' => 'token ' . env('GITHUB_TOKEN')
            ]
        ]))
            ->get($url)
            ->getBody()
            ->getContents();

        $artifacts = collect(json_decode($artifacts, true))->get('artifacts');

        // If the artifacts haven't been stored in the DB, then store each artifact.
        if (Artifact::count() === 0 ) {
            foreach($artifacts as $artifact) {
                app(Artifact::class)->store($artifact);
            }
            return;
        }

        $storedArtifacts = collect(Artifact::all()->toArray())->flatten()->toArray();
        //30 artifacts atm
        collect($artifacts)->map(function($artifact) use ($storedArtifacts) {
            if (!in_array($artifact['id'], $storedArtifacts)) {
                app(Artifact::class)->store($artifact);
            }
        });
    }

    /**
     * Pull the PR
     *
     * @param  string $archiveDownloadUrl
     * @param  string $artifactID
     *
     * @return $this
     */
    public function pull(string $archiveDownloadUrl, string $artifactID) {
        shell_exec(base_path() . '/deploy/scripts/pull_pr' . ' ' .  $archiveDownloadUrl . ' ' . base_path() . '/deploy/tmpFiles' . ' ' . $artifactID .' 2>&1');
        return $this;
    }

    /**
     * Deploy the PR
     *
     * @param  string $artifactID
     * @return string
     */
    public function deploy(string $artifactID) {
        $output = shell_exec(base_path() . '/deploy/scripts/deploy_pr' . ' ' . base_path() . "/deploy/tmpFiles/" . ' ' . $artifactID . ' 2>&1');
        return $output;
    }

    /**
     * Remove the tmp files
     *
     * @param  string $artifactID
     * @return
     */
    public function removeTmpFiles(string $artifactID) {
        $output = shell_exec(base_path() . '/deploy/scripts/remove_tmp_files' . ' ' . base_path() . ' ' . $artifactID . ' 2>&1');
        return $this;
    }

    /**
     * Remove the tmp files
     *
     * @param string $pr
     * @return
     */
    public function removeNameSpace(string $pr) {
        $output = shell_exec(base_path() . '/deploy/scripts/delete_pr_namespace' . ' ' . $pr . ' 2>&1');
        return $output;
    }
}
