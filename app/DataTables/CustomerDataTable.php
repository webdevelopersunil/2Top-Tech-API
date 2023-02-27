<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomerDataTable extends DataTable
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
            ->editColumn('status', function($user) {
                if($user->status == '0'){
                    $status = '<span class="badge badge-danger">'.__('messages.inactive').'</span>';
                }else{
                    $status = '<span class="badge badge-success">'.__('messages.active').'</span>';
                }
                return $status;
            })
            ->editColumn('equipments', function($user) {
                if($user->company){
                    $equipments = '<span class="badge btn-primary equipment"><a href="'.route("equipment.list", ["id" => $user->company->id]).'">'.__('messages.view').'</a></span>';
                }else{
                    $equipments = '-';
                }
                return $equipments;
            })
            ->editColumn('email', function($user) {
                return (isset($user->email)) ? ucfirst($user->email) : '-';
            })
            ->editColumn('city', function($user) {
                return (isset($user->company->location->city)) ? ucfirst($user->company->location->city) : '-';
            })
            ->addColumn('action', function($user){
                return view('customer.action',compact('user'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action','status', 'equipments']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        if(auth()->user()->hasAnyRole(['admin'])){
            $model = $model->withTrashed();
        }
        $role_id = 6; // restaurant id

        $query =  $model->whereHas('UserRole', function($query) use ($role_id) {
                    return $query->where('role_id', $role_id);
                })->with('company.file','company.location')->orderBy('id','DESC');

        return $query;
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
            Column::make('display_name')
                ->searchable(true)
                ->title(__('messages.name')),
            Column::make('email'),
            Column::make('contact_number')->searchable(true),
            Column::make('city'),
            Column::make('equipments')->title(__('messages.equipments'))->searchable(false)->orderable(false),
            Column::make('status'),
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
        return 'Provider_' . date('YmdHis');
    }
}
