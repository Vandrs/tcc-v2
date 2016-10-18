<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\DB\User;
use App\Models\DB\Project;
use App\Models\DB\Work;
use App\Models\DB\Graduation;
use App\Models\Business\MailBusiness;
use App\Models\Business\ProjectEmailBusiness;
use App\Models\Business\ElasticUserBusiness;
use App\Models\Business\ElasticExportBusiness;
use App\Models\Business\UserProjectBusiness;
use App\Models\Business\CrudUserBusiness;
use App\Models\Enums\EnumProject;
use App\Models\Elastic\Models\ElasticUser;
use App\Jobs\SendEmailJob;
use Auth;

class TesteController extends Controller
{
    public function index()
    {
        
    }
}
