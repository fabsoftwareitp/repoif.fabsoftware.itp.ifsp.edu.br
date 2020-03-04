<?php

namespace App\Http\Controllers;

use App\User;
use App\Likes;
use Exception;
use Validator;
use App\Comment;
use App\Project;

use ZanySoft\Zip\Zip;

use Illuminate\Http\Request;
use App\Policies\ProjectPolicy;
use Dawson\Youtube\Facades\Youtube;
use Illuminate\Support\Facades\Auth;

/*

criar controlador com as acoes padrao
php artisan make:controller --resource NOMEDOCONTROLADORController

Criar modelo junto com a migration
php artisan make:model Project -m

*/
class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //listar todos
    {

        $projects = Project::all();
        return view('project.list')->with('projects', $projects);
    }

    //PESQUISAR PROJETOS
    public function search(Request $request)
    {
        $projects = Project::search($request->search);

        return view('project.list', [
            'projects' => $projects,
            'search' => $request->search
        ]);
    }

    //NOVOS
    public function newProjects()
    {
    
        $projects = Project::all()->sortByDesc("id");
        return view('project.list')->with('projects', $projects);
    }

    //POR TIPO

    //FOTOS
    public function photosProjects()
    {
        $projects = Project::where(
            'type', 1)->get();
        return view('project.list')->with('projects', $projects);
    }

    //VIDEOS
    public function videosProjects()
    {
        $projects = Project::where(
            'type', 2)->get();
        return view('project.list')->with('projects', $projects);
    }

    //SCRIPTS
    public function codesProjects()
    {
        $projects = Project::where(
            'type', 3)->get();
        return view('project.list')->with('projects', $projects);
    }



    //USUARIOS
    public function userProject($id)
    {
        $projects = Project::where(
            'user_id', $id)->get();
        return view('project.list')->with('projects', $projects);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() //form de criacao de novo projeto
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) //salvar o projeto
    {

        $nameFile = null;
        $download = null;
        $thumbnailURL = null;
        $tipo = request('type');
        $actDate = date('Y-m-d');


        if($tipo == '2') {

                try {

                    $video = Youtube::upload($request->file('file'), [
                        'title'       => request('title'),
                        'description' => request('description'),
                        //'tags'        => request('tags'),
                        'category_id' => request('type')
                    ]);

                    $snippet = $video->getSnippet();
                    $thumbnailURL = $snippet->thumbnails->high->url;
                    $enviado = 1;

                    $nameFile = $video->getVideoId();

                    $project = Project::create([
                    'user_id' => auth()->id(),
                    'title' => request('title'),
                    'description' => request('description'),
                    'type' => request('type'),
                    'download' => $download,
                    'date' => $actDate,
                    'project' => $nameFile,
                    'sent' => $enviado,
                    'views' => 0,
                    'thumbnailURL' => $thumbnailURL

                    ])->save();

                    } catch(Exception $e) {

                        $enviado = 0;

                        if ($request->hasFile('file') && $request->file('file')->isValid()) {
                        // Define um aleatório para o arquivo baseado no timestamps atual
                        $name = uniqid(date('HisYmd'));
                 
                        // Recupera a extensão do arquivo
                        $extension = $request->file->extension();
                        // Define finalmente o nome
                        $nameFile = "{$name}.{$extension}";
                 
                        // Faz o upload:
                        $upload = $request->file->storeAs('files', $nameFile);
                        // Se tiver funcionado o arquivo foi armazenado em storage/app/public/files/nomedinamicoarquivo.extensao
                 
                        // Verifica se NÃO deu certo o upload (Redireciona de volta)
                        if ( !$upload )
                            return redirect()
                                        ->back()
                                        ->with('error', 'Falha ao fazer upload')
                                        ->withInput();
                        $download = $nameFile;
                        }

                        $project = Project::create([
                        'user_id' => auth()->id(),
                        'title' => request('title'),
                        'description' => request('description'),
                        'type' => request('type'),
                        'download' => $download,
                        'date' => $actDate,
                        'project' => $nameFile,
                        'sent' => $enviado,
                        'views' => 0,
                        'thumbnailURL' => $thumbnailURL

                    ])->save();
            } 

            } else {

                if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = uniqid(date('HisYmd'));
         
                // Recupera a extensão do arquivo
                $extension = $request->file->extension();
                // Define finalmente o nome
                $nameFile = "{$name}.{$extension}";
         
                // Faz o upload:
                $upload = $request->file->storeAs('files', $nameFile);
                // Se tiver funcionado o arquivo foi armazenado em storage/app/public/files/nomedinamicoarquivo.extensao
         
                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if ( !$upload )
                    return redirect()
                                ->back()
                                ->with('error', 'Falha ao fazer upload')
                                ->withInput();
                $download = $nameFile;
                                 
                               
                $project = Project::create([
                    'user_id' => auth()->id(),
                    'title' => request('title'),
                    'description' => request('description'),
                    'type' => request('type'),
                    'download' => $download,
                    'date' => $actDate,
                    'project' => $nameFile,
                    'views' => 0,
                    'thumbnailURL' => $thumbnailURL
                    
                ])->save();


            }

                      
              
}
    
    return response()->json(['success'=>'Projeto enviado com sucesso.']);
    
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $id, Request $request) //mostrar o projeto
    {

        $nomeSessao = 'viewProject-' . $id->id;
        $project= $id;

        if (!$request->session()->exists($nomeSessao)) {
            $project->views += 1;
            $project->save();

            $request->session()->put($nomeSessao, true);     
        }

        $like = Likes::where([
            ['user_id', Auth::id()],
            ['project_id', $project->id],
        ])->get();

        

        if($like->isEmpty()) {
            $temLike = 'Curtir';
        } else {
            $temLike = 'Descurtir';
        }

       return view('project.show')->with('project', $id)->with('temLike', $temLike); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $id) //mostrar o formulario preenchido com os dados
    {
        $project = Project::find($id);
        return view('project.edit')->with('project', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //salvar o formulario apos edicao
    {
        $project = $request->all();
        Project::find($id)->update($project);

        return redirect('projects');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //deletar o projeto
    {


        try{

            $comment = new Comment();
            $likes = new Likes();

            foreach($comment::where("project_id" , $id)->get() as $com){
              $com->delete();
            }
            foreach($likes::where("project_id" , $id)->get() as $like){
                $like->delete();
            }


            Project::find($id)->delete();



            return redirect('projects');

        }catch(Exception $ex){
            return redirect()->back();
        }


    }


    public function pesquisaSent()
    {

    	$naoEnviados = Project::where(
            'sent', 0)->get();

    	$contador = $naoEnviados->count();

    	while ($contador > 0) {

    		$upload = $naoEnviados->where('sent', '0')->first->id;

            $arquivo = "storage/files/$upload->project";

       try {

            $video = Youtube::upload($arquivo, [
                'title'       => $upload->title,
                'description' => $upload->description,
                //'tags'        => $upload->title,
                'category_id' => $upload->type
            ]);
            $snippet = $video->getSnippet();
            $thumbnailURL = $snippet->thumbnails->high->url;
            $nameFile = $video->getVideoId();

            $upload->sent = '1';
            $upload->thumbnailURL = $thumbnailURL;
            $upload->project = $nameFile;
            $upload->save();

            unlink($arquivo);  
            
        } 
        catch(Exception $e)
        {
            dd($e);
        }
        }
        
    }

}
