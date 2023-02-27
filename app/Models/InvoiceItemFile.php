<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItemFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "invoice_item_files";

    protected $fillable = [
        'invoice_item_id',
        'file_id'
    ];

    public function file(){

        return $this->hasOne(File::class,'id','file_id');
    }

    public function createInvoiceItemFile($files,$invoiceItem_id){

        if(isset($files) || $files != ''){
            $filesArr   =   explode(',',trim($files));

            foreach($filesArr as $index => $file_id){
                $invoiceItemFile    =   new InvoiceItemFile;
                $invoiceItemFile->invoice_item_id   =   $invoiceItem_id;
                $invoiceItemFile->file_id           =   $file_id;
                $invoiceItemFile->save();
            }
        }
    }
}
