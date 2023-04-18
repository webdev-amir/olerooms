<?php

namespace Modules\Invoice\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Invoice\Repositories\InvoiceRepositoryInterface as InvoiceRepo;

class InvoiceController extends Controller
{
    /**
     * Create a new InvoiceRepo instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepo $InvoiceRepo) {
        $this->InvoiceRepo = $InvoiceRepo;
    }

    /**
     * create Invoices and upload on aws in pdf and save in invoice table
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrderInvoicesAndUploadOnS3Bucket()
    { 
        return $this->InvoiceRepo->createOrderInvoicesAndUploadOnS3Bucket();
    }
}
