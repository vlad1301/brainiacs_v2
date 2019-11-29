<?php

namespace App\Console\Commands;

use App\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RestClient;
use RestClientException;

class RetrieverCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retriever:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //SCOTI DIN BD
        $jobs=DB::table('tasks')->get();
        $task_ids=array();
        foreach ($jobs as $job) {
            $job_tasks = $job->taskId;
            $project_id = $job->project_id;
            $task_ids[] = $job_tasks;
        }

        require('/var/www/brainiacs/resources/files/RestClient.php');
        $api_url = 'https://api.dataforseo.com/';
        try {
            //Instead of 'login' and 'password' use your credentials from https://my.dataforseo.com/
            $client = new RestClient($api_url, null, 'revenco_andrei@yahoo.com', 'FlMtt4RWJK7697VU');
        } catch (RestClientException $e) {
            echo "\n";
            print "HTTP code: {$e->getHttpCode()}\n";
            print "Error code: {$e->getCode()}\n";
            print  $e->getTraceAsString();
            echo "\n";
            exit();
        }

        foreach ($task_ids as $task_id):
            $serp_result = $client->get('v2/srp_tasks_get/' . $task_id);

            $results = $serp_result['results']['organic']/*['0']['result_url']*/
            ;

            foreach ($results as $result) {

                SerpResult::create(['project_id' => $project_id, 'resultPostId' => $result['post_id'], 'resultTaskId' => $result['task_id'], 'resultSeId' => $result['se_id'], 'resultLocationId' => $result['loc_id'],
                    'resultPostKey' => $result['post_key'], 'resultDatetime' => $result['result_datetime'], 'resultPosition' => $result['result_position'], 'resultUrl' => $result['result_url']]);
            }

        endforeach;
    }

}
