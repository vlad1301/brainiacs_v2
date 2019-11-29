<?php

namespace App\Console\Commands;



//use App\Location;
use App\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpMyAdmin\Dbi\DbiDummy;
use RestClient;
use RestClientException;

class TaskerCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasker:cron';

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
        require('/var/www/brainiacs/resources/files/RestClient.php');
        //SCOTI DIN BD

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


        $key_array=DB::table('keywords')->get();
        $post_array=array();

        foreach ($key_array as $key){

            $key_id=$key->id;
            $keyword=$key->value;
            $project_id=$key->project_id;
            $user_id=$key->user_id;

            $project_query=Project::where('id', 'LIKE', $project_id)->first();

            $se_id=$project_query->engine_id;
            $se_language=$project_query->language;
            $location_id=$project_query->location_id;

/*            $post_array[$post_id] = array(*/
            $post_array[] = array(


                "se_id" => $se_id,
                "se_language"=>$se_language,
                "loc_id" => $location_id,
                "key" => mb_convert_encoding($keyword, "UTF-8")
            );

            if (count($post_array) > 0) {
                try {
                    // POST /v2/srp_tasks_post/$tasks_data
                    // $tasks_data must by array with key 'data'
                    $task_post_result = $client->post('/v2/srp_tasks_post', array('data' => $post_array));

                    /* echo "<pre>";
                    print_r($task_post_result);
                    echo "</pre>";*/

                    $task_results=$task_post_result['results'];
                    foreach ($task_results as $task)
                    {

                        if($task['task_id'])
                        {
                            DB::table('tasks')->insert(['keyword_id' => $key_id,'project_id' => $project_id, 'user_id'=>$user_id,'task_API_id'=>$task['task_id'], 'string_time'=>time()]);
                        }

                    }



                    //INSEREZI IN BD

                    //do something with post results

                    $post_array = array();
                } catch (RestClientException $e) {
                    echo "\n";
                    print "HTTP code: {$e->getHttpCode()}\n";
                    print "Error code: {$e->getCode()}\n";
                    print "Message: {$e->getMessage()}\n";
                    print  $e->getTraceAsString();
                    echo "\n";
                }
            }
        }





    }



    /*$post_array = array();

    foreach($key_array as $keyword):

        $post_array[] = array(
            "se_id" => $se_id,
            "loc_id" => $loc_id,
            "key" => mb_convert_encoding($keyword, "UTF-8")
        );

    endforeach;*/


}
