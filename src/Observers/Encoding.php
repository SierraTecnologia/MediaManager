<?php

namespace MediaManager\Observers;

/**
 * Trigger encoding or delete the encodings rows
 */
class Encoding
{
    /**
     * Start a new encode if a new encodable file was uploaded
     *
     * @param  string $event
     * @param  array  $payload Contains:
     *                         -
     *                         Facilitador\Models\Base
     *                         $model
     * @return void
     */
    public function onSaving($event, $payload)
    {
        $model = is_array($payload) ? (reset($payload) ?: null) : $payload;

        if (!$this->isEncodable($model)) {
            return;
        }

        foreach ($model->getDirtyEncodableAttributes() as $attribute) {

            // If the attribute has a value, encode the attribute
            if ($model->getAttribute($attribute)) {
                $model->encodeOnSave($attribute);
            }

            // Otherwise delete encoding references
            elseif ($encoding = $model->encoding($attribute)) {
                $encoding->delete();
            }
        }
    }

    /**
     * Delete all encodes on the model
     *
     * @param  string $event
     * @param  mixed  $payload
     * @return void
     */
    public function onDeleted($event, $payload)
    {
        $model = is_array($payload) ? (reset($payload) ?: null) : $payload;

        if (!$this->isEncodable($model)) {
            return;
        }

        $model->deleteEncodings();
    }

    /**
     * Check if a model should be encoded
     *
     * @param  mixed $model
     * @return boolean
     */
    public function isEncodable($model)
    {
        if (is_array($model)) {
            $model = reset($model);
        }

        if (!is_object($model)) {
            return false;
        }

        if (!method_exists($model, 'getDirtyEncodableAttributes')) {
            return false;
        }

        if (is_a($model, 'Facilitador\Models\Element') && $model->getAttribute('type') != 'video-encoder') {
            return false;
        }

        return true;
    }
}
