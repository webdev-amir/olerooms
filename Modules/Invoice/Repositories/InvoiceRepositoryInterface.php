<?php

namespace Modules\Invoice\Repositories;


interface InvoiceRepositoryInterface
{
    public function createOrderInvoicesAndUploadOnS3Bucket();
}