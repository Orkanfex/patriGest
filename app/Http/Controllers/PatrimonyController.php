<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use App\Models\Patrimony;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image;

class PatrimonyController extends Controller
{
    //
    public function index(Request $request, Environment $environment) {

        $patrimonies = $environment->patrimonies();

        // ========================  FILTRO DE PESQUISA ========================
        $patrimonies->when($request->keyword, function($query, $keyword){ 
            $query->where(function ($q) use ($keyword){ 
                $q->where('description','like','%' .$keyword. '%')
                    ->orWhere('code','like','%' .$keyword. '%');
            });
        });
        // =======================================================================
        
        $patrimonies = $patrimonies->paginate(); 

        // ======================== OUTROS AMBIENTES ========================
        // Se for Admin, traz todos os outros ambientes.
        // Se for Técnico, traz apenas os outros ambientes que ele está vinculado (environment_user).
        $user = auth()->user();

        $otherEnvironments = $user->isAdmin()
            ? Environment::where('id', '!=', $environment->id)->orderBy('name')->get()
            : $user->environments()->where('environments.id', '!=', $environment->id)->orderBy('name')->get();
        // =======================================================================

        return view('patrimonies.index', [
            'patrimonies' => $patrimonies,
            'environment' => $environment,
            'otherEnvironments' => $otherEnvironments
        ]);
    }

    public function create(Environment $environment)
    {
        return view('patrimonies.create', [
            'environment' => $environment,
            'states'      => State::all(), // Apenas o estado precisa de dropdown aqui
        ]);
    }

    public function store(Request $request, Environment $environment){

        Gate::authorize('create', Patrimony::class);
        $input = $request->validate([
            'code' => ['required'],
            'state_id' => ['required', 'exists:states,id'],
            'description' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if(!empty($input['image']) && $input['image']->isValid()){
            
            // Image::fromUpload() converte $input['image] de dados brutos em uma instancia
            // de imagem para manipulação
            $path = Image::fromUpload($input['image'])
                ->scale(1080)
                ->toWebp(70)
                ->store('patrimonies', 'public');

            $input['image'] = $path;

        }
        $environment->patrimonies()->create($input);

        return redirect()
            ->route('patrimonies.index', $environment)
            ->with('status','patrimonio adicionado com sucesso!');
    }

    public function edit(Environment $environment, Patrimony $patrimony){

        $states = State::all();
        return view('patrimonies.edit', compact('environment', 'patrimony', 'states'));
    }

    public function update(Request $request, Environment $environment, Patrimony $patrimony){
        Gate::authorize('update', $patrimony);
        
        $input = $request->validate([
            'code' => ['required'],
            'state_id' => ['required'],
            'description' => ['required'],
        ]);

        $patrimony->fill($input);
        $patrimony->save();

        return redirect()
            ->route('patrimony.edit', compact('patrimony','environment'))
            ->with('status','Patrimônio editado com sucesso!');
    }

    public function updateImage(Request $request, Environment $environment, Patrimony $patrimony){
        Gate::authorize('update', $patrimony);
        
        $input = $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        if(!empty($input['image']) && $input['image']->isValid()){
            // Image::fromUpload() converte $input['image] de dados brutos em uma instancia
            // de imagem para manipulação
            if($patrimony->image && Storage::disk('public')->exists($patrimony->image)) {
                Storage::disk('public')->delete($patrimony->image);
            }   

            $path = Image::fromUpload($input['image'])
                ->scale(1080)
                ->toWebp(70)
                ->store('patrimonies', 'public');

            $input['image'] = $path;
        }
        $patrimony->fill($input);
        $patrimony->save();

        return redirect()
            ->route('patrimony.edit', compact('patrimony','environment'))
            ->with('status', 'Patrimonio atualizado com sucesso!');
    }

    public function bulkMove(Request $request, Environment $environment)
    {
        // Validação padronizada
        $input = $request->validate([
            'patrimony_ids' => ['required', 'array', 'min:1'],
            'patrimony_ids.*' => ['required', 'integer', 'exists:patrimonies,id'],
            'destination_environment_id' => [
                'required', 
                'integer', 
                'exists:environments,id', 
                'different:' . $environment->id
            ],
        ]);

        $destination = Environment::findOrFail($input['destination_environment_id']);

        // Atualização performática em lote dentro de uma transação
        DB::transaction(function () use ($input, $environment, $destination) {
            Patrimony::whereIn('id', $input['patrimony_ids'])
                ->where('environment_id', $environment->id)
                ->update(['environment_id' => $destination->id]);
        });

        $count = count($input['patrimony_ids']);

        return redirect()
            ->route('patrimonies.index', $environment->id)
            ->with('status', "{$count} patrimônio(s) transferido(s) com sucesso para {$destination->name}!");
    }

    public function destroy(Environment $environment, Patrimony $patrimony){

        Gate::authorize('delete', Patrimony::class);

        // Remove o arquivo da imagem do storage
        if ($patrimony->image) {
            Storage::disk('public')->delete($patrimony->image);
        }

        // Deleta o registro do banco de dados
        $patrimony->delete();

        // Usa a variável $environment para voltar para a lista do ambiente correto
        return redirect()
            ->route('patrimonies.index', $environment)
            ->with('status', 'Patrimônio removido com sucesso!');
    }
}

