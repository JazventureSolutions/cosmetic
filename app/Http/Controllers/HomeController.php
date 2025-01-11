<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Helpers\ReminderHelper;
use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SoapClient;
use stdClass;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('dashboard', [
            'branches' => $branches
        ]);
    }

    public function testSendSMS()
    {
        // added
        $filename = "backup-CPM.gz";
        $filename2 = "backup-CPM-" . time() . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " | gzip > " . storage_path() . "/app/backups/" . $filename;
        $returnVar = NULL;
        $output = NULL;
        exec($command, $output, $returnVar);

        // Storage::disk('dropbox')->put($filename2, Storage::disk('local')->get('backups/' . $filename));

        return Storage::disk('dropbox')->allFiles();

        // return ReminderHelper::sendReminder();

        // $sc = new SoapClient('http://www.textapp.net/webservice/service.asmx?wsdl');

        // $params = new stdClass();
        // $params->returnCSVString = false;
        // $params->externalLogin = env('TEXTANYWHERE_USERNAME');
        // $params->password = env('TEXTANYWHERE_PASSWORD');
        // $params->clientBillingReference = Carbon::now()->toDateString();
        // $params->clientMessageReference = 'test';
        // $params->originator = env('TEXTANYWHERE_ORIGINATOR');
        // $params->destinations = '+447886577734';
        // $params->body = utf8_encode('Circumcision appointment: Sunday 11.01.21 at 10 AM at Transcend Consulting Rooms 98 Wentloog Road Cardiff CF3 3XE Both parents are required to attend & to bring photo ID documents for both parents & child. Please check your emails for full details of appointment. PPE (mask, facial shield and gloves). Please bring paracetamol and Ibuprofen liquid Thanks. Dr Khan');
        // $params->validity = 72;
        // $params->characterSetID = 2;
        // $params->replyMethodID = 4;
        // $params->replyData = '';
        // $params->statusNotificationUrl = '';

        // $result = $sc->__call('SendSMS', array($params));

        // return $result->SendSMSResult;
    }
}
