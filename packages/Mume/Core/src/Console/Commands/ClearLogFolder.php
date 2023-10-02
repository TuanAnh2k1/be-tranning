<?php

namespace Mume\Core\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearLogFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Log Laravel';

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
     * @return void
     */

    public function handle()
    {
        if (env("APP_DEBUG") == true) {
            $path       = base_path()."/storage/logs";
            $arrFolders = array_filter(scandir($path), function ($value) {
                return preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value);
            });
            $now        = Carbon::now(config('app.timezone'));
            foreach ($arrFolders as $obj) {
                if (($now->diffInDays($obj)) > (((int) config("logging.channels.my_custom.days") < 1) ? 1 : (int) config("logging.channels.my_custom.days"))) {
                    $dir   = $path."/$obj";
                    // Get all file names
                    $files = glob("$dir/*");
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            //delete file
                            unlink($file);
                        }
                    }

                    rmdir($dir);
                }
            }
        }
    }
}
