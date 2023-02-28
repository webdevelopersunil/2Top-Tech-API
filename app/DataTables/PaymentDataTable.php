<?php

namespace App\DataTables;

use App\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Traits\DataTableTrait;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Http\Service\PlatformFeesService;

class PaymentDataTable extends DataTable
{
    use DataTableTrait;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('company_id', function($transcation) {

                if( isset($transcation->company) ){
                    return isset($transcation->company->location->restaurant_name) ? $transcation->company->location->restaurant_name : ucfirst($transcation->company->business_name);
                }else{
                    return "-";
                }
            })
            ->editColumn('provider_id', function($transcation) {
                return $transcation->provider->user->first_name. " ". $transcation->provider->user->last_name;
            })
            ->editColumn('total_amount', function($transcation) {
                return  '$'.round($transcation->total_amount, 2);
            })
            ->editColumn('tax', function($transcation) {
                return  '$'.round($transcation->tax, 2);
            })
            ->editColumn('technician_payout', function($transcation) {
                return '$'.round((new PlatformFeesService)->calculatePlatformFees($transcation->id), 2);
            })
            ->editColumn('view_invoice', function($transcation) {
                return '<div class="d-flex justify-content-end align-items-center"><a class="mr-2" href="'.route('payment.show',$transcation->id) . '"><i class="far fa-eye text-secondary"></i></a></div>';
            })

            ->editColumn('updated_at', function($transcation) {
                $invoice_date = $transcation->updated_at;
                return date("d-m-Y", strtotime($invoice_date));
            })

            ->addIndexColumn()
            ->rawColumns([ 'restaurant_name', 'technician_name','view_invoice']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProviderPayout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Invoice $model, Request $request)
    {
        if(isset($request->start_date) && isset($request->end_date) ){

            if( $request->start_date != '' ){
                $model = $model->where('status', 'Paid')->with("company.location", "provider")->orderBy('id','DESC');
            }
        }else{
            $model =    $model->where('status', 'Paid')
                        ->where('created_at','<=',$request->start_date)
                        ->where('created_at','>=',$request->end_date)
                        ->with("company.location", "provider")->orderBy('id','DESC');
        }
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(){
        return $this->builder()
                    ->setTableId('em-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    // ->parameters([
                    //     'dom'          => 'Bfrtip',
                    //     'buttons'      => ['excel', 'csv'],
                    // ])
                    // ->buttons(
                    //     Button::make('excel'),
                    //     Button::make('csv')
                    // )
                    ;
    }


    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('DT_RowIndex')
                ->searchable(false)
                ->title(__('messages.no'))
                ->orderable(false),
            Column::make('invoice_number'),
            Column::make('updated_at')->title(__('messages.invoive_date')),
            Column::make('company_id')->searchable(true)->title(__('Company Name')),
            Column::make('provider_id')->searchable(true)->title(__('Technician Name')),
            Column::make('total_amount')->title(__('messages.invoice_amount')),
            Column::make('tax'),
            // technician_payout
            Column::make('sub_total')->title(__('messages.technician_payout')),
            Column::computed('view_invoice')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Category_' . date('YmdHis');
    }
}
