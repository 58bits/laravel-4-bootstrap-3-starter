<?php

class AuditableObserver {

    public function creating($model)
    {
        //We de-normalize username.
        $model->created_by = Auth::user()->username;
        $model->updated_by = Auth::user()->username;
    }

    public function updating($model)
    {
        $model->updated_by = Auth::user()->username;
    }
}