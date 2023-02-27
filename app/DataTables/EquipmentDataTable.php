<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\Equipment;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class EquipmentDataTable extends DataTable
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
            ->addColumn('action', function($equipment){
                 $restaurant_owner_id = $this->restaurant_owner_id;
                return view('equipment.action',compact('equipment','restaurant_owner_id'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action','status', 'equipments']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Equipment $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Equipment $model)
    {
        if($this->restaurant_owner_id != null){
            $query =  $model->where('company_id', $this->restaurant_owner_id);
            return $query;
        }
        return $model->newQuery();
        // return $model->newQuery()->orderBy('id','DESC');
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
                ->title(__('messages.srno'))
                ->orderable(false)
                ->width(60),
            Column::make('name'),
            Column::make('location'),
            Column::make('refrigerant_type'),
            Column::make('warranty_info'),
            Column::make('voltage_amps'),
            Column::computed('action')
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
        return 'Equipments_' . date('YmdHis');
    }
}
