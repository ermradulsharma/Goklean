<?php

namespace App\Transformers\Admin;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Venturecraft\Revisionable\Revision;

class RevisionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Revision $revision
     * @return array
     */
    public function transform(Revision $revision)
    {
        return [
            'message' => $revision->userResponsible()->full_name.' changed '.$revision->fieldName().' from '.$revision->oldValue().' to '.$revision->newValue(),
            'time' => Carbon::parse($revision->created_at)->shortRelativeToNowDiffForHumans()
        ];
    }
}
