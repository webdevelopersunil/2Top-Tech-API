<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'title',
        'quantity',
        'unit',
        'price',
        'sub_total'
    ];

    protected $hidden = [
        'id'
    ];

    public function files(){

        return $this->hasMany(InvoiceItemFile::class,'invoice_item_id','id');
    }

    public function createInvoiceItems($line_items, $invoice_id){

        if( count($line_items) >= 1 ){

            foreach($line_items as $index => $item){

                $invoiceItem    =   new InvoiceItem;
                $invoiceItem->invoice_id    =   $invoice_id;
                $invoiceItem->title         =   $item['title'];
                $invoiceItem->quantity      =   $item['quantity'];
                $invoiceItem->unit          =   1;
                $invoiceItem->price         =   $item['price'];
                $invoiceItem->sub_total     =   $item['sub_total'];
                $invoiceItem->save();

                if(!empty($item['files'])){
                    (new InvoiceItemFile)->createInvoiceItemFile($item['files'],$invoiceItem->id);
                }
            }
        }
    }
}
