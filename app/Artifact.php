<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artifact extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'artifact_id', 'node_id', 'pull_request_id',
        'name', 'size_in_bytes', 'url',
        'archive_download_url', 'expired', 'deployed',
        'artifact_created_at', 'artifact_updated_at',
    ];


    /**
     * Store the artifact in the DB.
     *
     * @param  array $artifact
     * @return @void
     */
    public function store(array $artifact) {

        $this->create([
            'artifact_id' => $artifact['id'],
            'node_id' => $artifact['node_id'],
            'name' => $artifact['name'],
            'size_in_bytes' => $artifact['size_in_bytes'],
            'url' => $artifact['url'],
            'archive_download_url' => $artifact['archive_download_url'],
            'expired' => $artifact['expired'],
            'artifact_created_at' => $artifact['created_at'],
            'artifact_updated_at' => $artifact['updated_at'],
        ]);
    }
}
