<?php

namespace App\DataTables;

use App\Models\PartRequest;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PartRequestDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()->eloquent($query)

            ->editColumn('name', function($part_request) {
                return '<span style="font-weight:bold;" >'.ucfirst($part_request->name).'</span>';
            })

            ->editColumn('file_id', function($part_request) {
                if($part_request->file->name && isset($part_request->file->name) ){
                    $file = '<img src="'.asset("storage/". $part_request->file->name ). '" alt="profile-bg" class="listing-img" />';
                }else{
                    $file = '<img src="'.asset('images/user/user.png'). '" alt="profile-bg" class="listing-img" />';
                }
                return $file;
            })->escapeColumns(['file_id'])

            ->editColumn('provider_id', function($part_request) {
                return $part_request->provider->user->first_name.' '.$part_request->provider->user->last_name;
            })->escapeColumns(['provider_id'])

            ->editColumn('description', function($part_request) {
                return ucfirst($part_request->description);
            })->escapeColumns(['description'])

            ->addColumn('status', function($part_request){
                if( $part_request->status == 'Pending' || $part_request->status == 'NotAvailable' ){
                    return $part_request->status;
                    // return "<span style='font-weight:bold;' >". ucfirst($part_request->status) ."</span>";
                }else if( $part_request->status == 'Fullfiled' ){
                    return ucfirst($part_request->status);
                    // return '<span style="font-weight:bold; color:green;" >'.ucfirst($part_request->status).'</span>';
                }
            })->escapeColumns(['status'])

            ->addColumn('action', function($part_request){
                // return view('partrequest.action',compact('part_request'))->render();
                return '<div class="d-flex justify-content-end align-items-center"> <a class="mr-2" href="'.route('part-request.show',$part_request->id).'"><i class="far fa-eye text-secondary"></i></a> </div>';
            })

            ->addColumn('model_no', function($part_request){
                if( isset($part_request->model_no) && !empty($part_request->model_no) ){
                    return '<span style="font-weight:bold;" >'.$part_request->model_no.'</span>';
                }else{
                    return '<span style="font-weight:bold;" > - </span>';
                }
            });

            // ->addColumn('action', 'partrequest.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PartRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PartRequest $model)
    {
        // return $model->newQuery();
        $model = $model->with('file','provider.user');
        return $model->newQuery()->orderBy('id','DESC');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('partrequest-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('file_id')->title(__('File')),
            Column::make('name')->title(__('Name'))->searchable(true)->orderable(true),
            Column::make('description'),
            Column::make('provider_id')->title(__('Provider Name'))->searchable(true)->orderable(true),
            Column::make('equipment_id'),
            // Column::make('sku'),
            // Column::make('make'),
            Column::make('model_no'),
            Column::make('status')->title(__('Status')),
            Column::computed('action')
                  ->exportable(true)
                  ->printable(true)
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
        return 'PartRequest_' . date('YmdHis');
    }
}
