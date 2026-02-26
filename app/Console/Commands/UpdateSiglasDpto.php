<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Departamento;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UpdateSiglasDpto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-siglas-dpto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar Siglas de todos los Departamentos';

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
     * @return int
     */
    public function handle()
    {
       $this->line('Iniciando actualizacion...');
        try {
           $departamentos = Departamento::select('id', 'nombre')->get();

            foreach ($departamentos as $item) {
                $separate_name = collect(explode(' ', $item->nombre));
                $exclude = collect(['de', 'e', 'y', 'del', 'la', '-']);

                if($separate_name->count() > 0){
                    $siglas = $separate_name->map(function ($i) use($exclude) {
                        $some = $exclude->contains(function($letter) use($i) {
                            return $letter === strtolower($i);
                        });
                        return $some ? '' : Str::upper(Str::substr($i, 0, 1));
                    })->join('');
                    $save = DB::table('departamentos')->where('id', $item->id)
                        ->update(["siglas" => $siglas]);
                    $this->line($item->nombre . ' - ' .$siglas. ' - '.$save);
                } else {
                    $this->line($item->id . ' S/N');
                }
            }

            $this->info('Actualización realizada exitosamente!');

        } catch (\Throwable $th) {
            $this->error('Error: '.$th->getMessage());
        }
    }
}
