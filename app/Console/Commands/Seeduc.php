<?php

namespace App\Console\Commands;

use DB;
use Carbon\Carbon;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Console\Command;
use App\Data\Entities\Seeduc as Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Seeduc extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pj:seeduc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed seeduc table';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set("auto_detect_line_endings", "1");

        //load the CSV document
        $reader = Reader::createFromPath(database_path('alunos-parlamento-juvenil-2017.csv'));

        DB::statement('delete from seeduc');

        //query your records from the document
        foreach ($reader as $index => $row) {
            if ($index == 0) {
                continue;
            }

            $row = explode(';', $line = $row[0]);

            $model = Model::create([
                'escola' => $row[0],
                'municipio' => $row[1],
                'regional' => $row[2],
                'nome' => $row[3],
                'matricula' => $row[4],
                'nascimento' => $this->toDate($row[5]),
            ]);

            $this->info($model->id.' -> '.$line);
        }
    }

    private function toDate($date)
    {
        return Carbon::createFromFormat('d/m/y', $date);
    }
}
