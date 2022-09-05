<?php

namespace App\Http\Controllers\FamilyFarm\Matrix;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\Amount;
use App\Models\FamilyFarm\CadastralParcel;
use App\Models\FamilyFarm\Matrix;
use App\Models\FamilyFarm\Matrix\Operation;
use App\Models\FamilyFarm\Matrix\Process;
use App\Models\FamilyFarm\Matrix\ProcessAmount;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessController extends Controller
{
    public function getAvailableSupplies($id): JsonResponse
    {
        if(!Matrix::find(session('matrix')['id'])->operations->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        $operation = Operation::find($id);
        $matrix = Matrix::find(session('matrix')['id']);
        return response()->json(FamilyFarm::find(session('familyFarm')['id'])
            ->familyFarmSupplies()
            ->with('amounts')
            ->has('amounts')
            ->whereHas('supply', function ($query) use ($matrix, $operation) {
                return $query->where('operation_type', '=', $operation->operation_type)->whereHas('cultures', function ($query) use ($matrix) {
                    return $query->where('culture_id', '=', $matrix->culture_id);
                });
            })
            ->get()->toArray());
    }

    public function createProcess($id, Request $request)
    {
        $matrix = Matrix::find(session('matrix')['id']);
        $operation = Operation::find($id);
        if(!$matrix->operations->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        $request->validate([
            'date'                  => 'required|date_format:d.m.Y.',
            'duration'              => 'required|numeric|gt:0',
            'tracking_value'        => 'required|numeric|gt:0',
            'weather'               => 'nullable',
            'note'                  => 'nullable',
            'amounts'              => 'required|array',
            'amounts.*.id'          => 'required',
            'amounts.*.used_amount' => 'nullable|numeric|gt:0',
        ]);
        if(strtotime($request->date) > strtotime("today")){
            return response()->json([
                'errors' => [
                    'date' => [null],
                ],
                'message' => 'Datum procesa mora biti manji ili jednak današnjem datumu.'
            ], 422);
        }
        /*
         * Trenutno nije potrebno jer se smije premašiti zadana količina/površina
         * if(($operation->tracking - $operation->total_tracking) < $request->tracking_value){
            return response()->json([
                'errors' => [
                    'tracking_value' => [null],
                ],
                'message' => "Obrađena ".($matrix->tracking_type === 'p' ? 'površina' : 'količina')." ne može biti veća od ".($matrix->tracking_type === 'p' ? 'površine' : 'količine')." koja je preostala za odabranu operaciju."
            ], 422);
        }*/
        try {
            $process = new Process();
            $process->date = date_format(date_create($request->date), "Y-m-d");
            $process->duration = $request->duration;
            $process->tracking_value = $request->tracking_value;
            $process->weather = $request->weather;
            $process->note = $request->note;
            $process->operation_id = $id;
            $process->user_id = Auth::id();
            $process->save();
            foreach ((array)($request->amounts) as $amount){
                if($amount['used_amount']){
                    if(Amount::find($amount['id'])->available_amounts >= $amount['used_amount']){
                        try {
                            ProcessAmount::create([
                                'used_amount' => $amount['used_amount'],
                                'amount_id' => $amount['id'],
                                'process_id' => $process->id,
                            ]);
                        }catch (QueryException $e){
                            return response()->json([
                                'message' => 'Dogodila se greška.'
                            ], 422);
                        }
                    }
                }
            }
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Proces je uspješno spremljen.'
        ], 200);
    }

    public function deleteProcess($id): JsonResponse
    {
        try {
            Process::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Proces je uspješno obrisan!'
        ], 200);
    }
}
