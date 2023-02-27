<?php

namespace App\DataTables;

use App\Models\RestaurantJob;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RestaurantJobDataTable extends DataTable
{
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
            ->addColumn('action', 'restaurantjob.action')

        ->editColumn('service_id', function($job) {
            return $job->service->name;
        })->escapeColumns(['service_id'])

        ->editColumn('company_id', function($job) {
            if( isset($job->company->business_name) && !empty($job->company->business_name) ){
                return ucfirst($job->company->business_name);
            }else{
                return '-';
            }
        })->escapeColumns(['company_id'])

        ->addColumn('action', function($job){
            // return view('restaurantjob.action',compact('job'))->render();
            return '<div class="d-flex justify-content-end align-items-center"> <a class="mr-2" href="'.route('jobs.show',$job->id).'"><i class="far fa-eye text-secondary"></i></a> </div>';
        })

        ->editColumn('description', function($job) {
            return substr($job->description,0,20).'...';
        })->escapeColumns(['description']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\RestaurantJob $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RestaurantJob $model)
    {
        // return $model->newQuery();
        $model = $model->with('service','company.location');
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
                    ->setTableId('restaurantjob-table')
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
            // Column::make('DT_RowIndex')->searchable(false)->title(__('messages.srno'))->orderable(false),
            Column::make('company_id')->title(__('Company Name'))->searchable(true)->orderable(true),
            Column::make('description')->searchable(true)->orderable(true),
            Column::make('restaurant_location'),
            Column::make('start_at'),
            Column::make('end_at'),
            Column::make('schedule_type'),
            Column::make('service_id')->title(__('Skill'))->searchable(true)->orderable(true),
            Column::make('status'),
            // Column::make('is_active'),
            // Column::make('created_at'),
            // Column::make('updated_at'),
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
        return 'RestaurantJob_' . date('YmdHis');
    }
}
