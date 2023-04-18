<?php

namespace Modules\NewsUpdates\Repositories;

interface NewsUpdatesRepositoryInterface
{
    public function getRecord($id);

    public function getRecordBySlug($slug);

    public function getAjaxData($request);

    public function store($request);

    public function update($request, $id);

    public function destroy($id);

    public function changeStatus($request, $slug);

    public function saveNewsUpdatesPictureMedia($request);
}
