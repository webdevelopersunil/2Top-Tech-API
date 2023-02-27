<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\EmailTemplateDataTable;
use App\Http\Requests\CreateEmailTemplateRequest;
use App\Http\Requests\UpdateEmailTemplateRequest;
use App\Repositories\EmailTemplateRepository;
use Flash;
// use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\EmailTemplate;

class EmailTemplateController extends Controller
{
    /** @var EmailTemplateRepository $emailTemplateRepository*/
    private $emailTemplateRepository;

    public function __construct(EmailTemplateRepository $emailTemplateRepo)
    {
        $this->emailTemplateRepository = $emailTemplateRepo;
    }

    /**
     * Display a listing of the EmailTemplate.
     *
     * @param EmailTemplateDataTable $emailTemplateDataTable
     *
     * @return Response
     */
    public function index(EmailTemplateDataTable $dataTable)
    {
        $pageTitle = __('messages.list_form_title',['form' => __('messages.email_templates')] );
        $assets = ['datatable'];
        return $dataTable->render('emailtemplate.index', compact('pageTitle','assets') );
    }

    /**
     * Show the form for creating a new EmailTemplate.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $id = $request->id;
        $auth_user = authSession();
        $emailtemplatedata = EmailTemplate::find($id);
        $pageTitle = __('messages.update_form_title',['form'=> __('messages.email_templates')]);
        
        if($emailtemplatedata == null){
            $pageTitle = __('messages.add_button_form',['form' => __('messages.email_templates')]);
            $emailtemplatedata = new EmailTemplate;
        }
        
        return view('emailtemplate.create', compact('pageTitle' ,'emailtemplatedata' ,'auth_user' ));
    }

    /**
     * Store a newly created EmailTemplate in storage.
     *
     * @param CreateEmailTemplateRequest $request
     *
     * @return Response
     */
    public function store(CreateEmailTemplateRequest $request)
    {

        $emailTemplate = $this->emailTemplateRepository->find($request->id);

        if (empty($emailTemplate)) {
            $input = $request->all();

            $emailTemplate = $this->emailTemplateRepository->create($input);

            Flash::success('Email Template saved successfully.');
        }else{
            $emailTemplate = $this->emailTemplateRepository->update($request->all(), $request->id);

        Flash::success('Email Template updated successfully.');
        }

        return redirect(route('emailtemplates.index'));
    }

    /**
     * Display the specified EmailTemplate.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $emailTemplate = $this->emailTemplateRepository->find($id);

        if (empty($emailTemplate)) {
            Flash::error('Email Template not found');

            return redirect(route('emailtemplate.index'));
        }

        return view('email_templates.show')->with('emailTemplate', $emailTemplate);
    }

    /**
     * Show the form for editing the specified EmailTemplate.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $emailTemplate = $this->emailTemplateRepository->find($id);

        if (empty($emailTemplate)) {
            Flash::error('Email Template not found');

            return redirect(route('emailTemplates.index'));
        }

        return view('email_templates.edit')->with('emailtemplate', $emailTemplate);
    }

    /**
     * Update the specified EmailTemplate in storage.
     *
     * @param int $id
     * @param UpdateEmailTemplateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmailTemplateRequest $request)
    {
        $emailTemplate = $this->emailTemplateRepository->find($id);

        if (empty($emailTemplate)) {
            Flash::error('Email Template not found');

            return redirect(route('emailtemplate.index'));
        }

        $emailTemplate = $this->emailTemplateRepository->update($request->all(), $id);

        Flash::success('Email Template updated successfully.');

        return redirect(route('emailtemplate.index'));
    }

    /**
     * Remove the specified EmailTemplate from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $emailTemplate = $this->emailTemplateRepository->find($id);

        if (empty($emailTemplate)) {
            Flash::error('Email Template not found');

            return redirect(route('emailtemplate.index'));
        }

        $this->emailTemplateRepository->delete($id);

        Flash::success('Email Template deleted successfully.');

        return redirect(route('emailtemplates.index'));
    }
}
