<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\UserRole;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserManagementDataTable extends DataTable
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
            ->addColumn('role', function($query){
                $roleDetail = UserRole::where('user_id',$query->id)->with('roleDetail')->first();
                return 'Staff';
            })

            ->addColumn('action', function($query){
                return '<div class="d-flex justify-content-end align-items-center"> <a class="mr-2" href="'.route('user_management.show',$query->id).'"><i class="far fa-edit text-secondary"></i></a> </div>';
            })
            ->addColumn('status', function($query){
                if($query->status == 1){
                    return 'Active';
                }elseif($query->status == 0){
                    return 'Un-Active';
                }
            })

            // ->addColumn('action', '<div class="d-flex justify-content-end align-items-center"> <a class="mr-2" href="'.route('user_management.show',$query->id).'"><i class="far fa-edit text-secondary"></i></a> </div>')
            ;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\UserManagement $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {

        // return $model->newQuery();
        // return User::join('user_roles', 'users.id', '=', 'user_roles.user_id')->where('user_roles.role_id', 7)->get();
        $model = $model->with('UserRole');
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('usermanagement-table')
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
            Column::make('display_name'),
            Column::make('role'),
            Column::make('status'),
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
        return 'UserManagement_' . date('YmdHis');
    }
}
