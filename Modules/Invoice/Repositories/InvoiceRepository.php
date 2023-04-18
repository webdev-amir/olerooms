<?php

namespace Modules\Invoice\Repositories;

use Exception;
use Validator;
use Carbon\Carbon;
use DB,Mail,Session,Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\Builder;
use Modules\Invoice\Entities\Invoice;
//use App\Models\api\DbOrder;
use PDF;

class InvoiceRepository implements InvoiceRepositoryInterface {

    public $Invoice;

    protected $statusCode = 200;

    function __construct(Invoice $Invoice) {
        $this->Invoice = $Invoice;
    }

    /**
     * @param update all types user communications details schedule run on daily at 01 AM
     *
     * @return mixed
     */
    public function createOrderInvoicesAndUploadOnS3Bucket()
    { 
        //$order = DbOrder::orderBy('id','desc')->first();
        $pdf = PDF::loadView('invoice::pdf.order_invoice', []);
        $file_path = '/invoice/';
        $orderid = 1;
        $invoicename = 'order-invoice'.$orderid.'.pdf';
        $uploded = \Storage::disk('s3')->put($file_path.$invoicename, $pdf->output(), [
             'ContentDisposition' => 'attachment'
        ]);
        if($uploded == 1){
            //pr($uploded);
            //return $pdf->download('invoice.pdf');
            $invoice['order_id'] = $orderid;
            $invoice['path'] = $invoicename;
            if($order->invoice){
                $order->invoice->path = $invoicename;
                $order->invoice->save();
            }else{
               $this->Invoice::create($invoice); 
            }
            return $this->respondWithSuccessMessage("Invoices Created Successfully");
        }
    } 

    /**
     * @param sendInvoiceAttechedWithEMailWahtsappAndMobile schedule run on every 30 Minutes
     *
     * @return mixed
     */
    public function sendInvoiceAttechedWithEMailWahtsappAndMobile()
    { 
        return $this->respondWithSuccessMessage("Invoices Send Successfully");
    }    

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function noRequestFound()
    {
        return $this->respond([
            'status' => false,
            'message' => 'No request found'
        ]);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondWithSuccessMessage($message)
    {
        return $this->respond([
            'status' => true,
            'message' => $message
        ]);
    }

    public function respondWithSuccess($message,$data)
    {
        return $this->respond([
            'status' => true,
            'status_code' => $this->getStatusCode(),
            'message' => $message,
            'data' => $data
        ]);
    }

    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

     /**
     * @param string $validator
     *
     * @return mixed
     */
    public function respondWithValidationError($validator)
    {
        return $this->setStatusCode(422)->respondWithError($validator->errors()->first());
    }

       /**
     * @param mixed $statusCode
     *
     * @return self
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

     /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'status' => false,
            'message' => $message
        ]);
    }

}