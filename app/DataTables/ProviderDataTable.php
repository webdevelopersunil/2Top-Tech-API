<?php

namespace App\DataTables;
use App\Traits\DataTableTrait;

use App\Models\User;
use App\Models\Provider;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProviderDataTable extends DataTable
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
            ->editColumn('display_name', function($provider) {

                return '<span style="font-weight:bold;" >'. substr(ucfirst($provider->user->first_name), 0, 12). " ".substr(ucfirst($provider->user->last_name), 0, 12).'</span>';
            })
            ->editColumn('profile_pic', function($provider) {
                $providerdata = Provider::with('documents')->where('id',$provider->id)->first();
                $profile_picture_data  = $providerdata->documents->where('document_type', 'provider_profile_picture')->first();
                $profile = '-';
                if($profile_picture_data && isset($profile_picture_data->document->name) ){
                    $profile = '<img src="'.asset("storage/". $profile_picture_data->document->name ). '" alt="profile-bg" class="listing-img" />';
                }else{
                    $profile = '<img src="'.asset('images/user/user.png'). '" alt="profile-bg" class="listing-img" />';
                }

                return $profile;
            })->escapeColumns(['profile_pic'])
            ->editColumn('state', function($provider) {

                return $provider->states->name;
            })
            ->escapeColumns(['created_at'])
            ->editColumn('joined_at', function($provider) {

                return '<span style="font-weight:bold;" >'. date_format($provider->created_at, 'M-d-Y') .'</span>';
            })
            ->editColumn('status', function($provider) {
                $status = '<span style="font-weight:bold; color:green;" >'. ucfirst($provider->status) .'</span>';
                return $status;
            })
            ->addColumn('action', function($provider){
                return view('provider.action',compact('provider'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action','status', 'profile_pic']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Provider $model)
    {
        $model = $model->with('user');
        if($this->list_status != null){

            $model = $model->where('status',"pending");
        } else {
            $model = $model->where('status',"approved");
        }

        return $model->newQuery()->orderBy('id','DESC');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */

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
                ->orderable(false),
            Column::make('profile_pic')
                ->title(__('messages.profile_pic'))->searchable(false)->orderable(false),
            Column::make('display_name')
                ->title(__('messages.name'))->searchable(false)->orderable(false),
            Column::make('contact_number'),
            Column::make('state')->title(__('messages.state'))->searchable(false)->orderable(false),
            Column::make('city'),
            Column::make('joined_at'),
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
