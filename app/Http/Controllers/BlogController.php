<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Asset\AssetLoader;
use App\Models\DB\Project;
use App\Models\DB\Post;
use App\Models\Business\PostBusiness;
use App\Models\Business\CrudProjectBusiness;
use App\Utils\Utils;
use DB;
use Log;
use Auth;
use Yajra\Datatables\Datatables;

class BlogController extends Controller
{


	public function __construct(){
		$this->middleware('auth');
		$this->middleware('postManager');
	}


	public function posts(Request $request, $projectId){
		try{
			$project = Project::findOrFail($projectId);
			$page_title = $project->title.": Lista de Posts";
			AssetLoader::register(['projectPosts.js'],['admin.css'],['DataTables']);
			return view('project.blog-posts',['project' => $project, 'page_title' => $page_title]);
		} catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
	}

	public function getPosts(Request $request, $projectId){
		try{
			$datasource = Post::select([
							DB::raw('posts.id'),
							DB::raw('posts.title'),
							DB::raw('posts.created_at'),
							DB::raw('users.id AS user_id'),
							DB::raw('users.name AS user_name')
						  ])
						  ->join('users','posts.created_by','=','users.id')
						  ->where('project_id', '=', $projectId);
									  
			return Datatables::of($datasource)
							 ->editColumn('title',function($post) use ($projectId){
							 	$route = route('admin.project.post.edit',['project_id' => $projectId,'id' => $post->id]);
							 	return "<a href='".$route."' title='Editar post' data-toggle='tooltip'>".$post->title."</a>";
							 })
							 ->editColumn('user_name',function($post){
							 	return "<a href='".route('user.view',['id' => $post->user_id])."' title='Ver perfil' data-toggle='tooltip'>".$post->user_name."</a>";
							 })
							 ->editColumn('created_at',function($post){
							 	return $post->created_at->format('d/m/Y H:i');
							 })
							 ->addColumn('actions',function($post) use ($projectId){
							 	$route = route('admin.project.post.edit',['project_id' => $projectId,'id' => $post->id]);
							 	$deleteRoute = route('admin.project.post.delete',['project_id' => $projectId,'id' => $post->id]);
							 	
							 	$actions = "<a href='".$route."' class='btn btn-default btn-fab btn-fab-mini margin-right-5' data-toggle='tooltip' title='Editar Post'><i class='material-icons'>edit</i></a>";
							 	$actions .= "&nbsp;&nbsp;<a href='".$deleteRoute."' class='btn btn-danger btn-fab btn-fab-mini' title='Excluir post' data-toggle='tooltip'><i class='material-icons'>delete</i></a>";

							 	return $actions;
							 })
							 ->make(true);

		} catch(\Exception $e){
			$msg = Utils::getExceptionFullMessage($e);
			Log::error($msg);
			return $this->ajaxUnexpectedError(null, $msg);
		}
    }

    public function createPost(Request $request, $projectId){
    	try{
			$project = Project::findOrFail($projectId);
			$page_title = $project->title.": Novo Post";
			AssetLoader::register(['createPost.js'],['admin.css'],['TinyMce']);
			return view('project.blog-create-post',['project' => $project, 'page_title' => $page_title]);
		} catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
    }

    public function editPost(Request $request, $projectId, $id){
    	try{
	    	$project = Project::findOrFail($projectId);
	    	$post = Post::findOrFail($id);
			$page_title = $project->title.": Editar Post";
			AssetLoader::register(['createPost.js'],['admin.css'],['TinyMce']);
			return view('project.blog-update-post',['project' => $project, 'post' => $post, 'page_title' => $page_title]);
		} catch(\Exception $e){
			Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
		}
    }

    public function savePost(Request $request, $projectId){
    	try{
    		$postBusiness = new PostBusiness();
    		$data = $request->except('_token');
    		$data['project_id'] = $projectId;
    		if($post = $postBusiness->createPost($data, Auth::user())){
    			$request->session()->flash('msg','Post criado com sucesso!');	
    			$request->session()->flash('class_msg','alert-success');
    			$project = Project::find($projectId);
    			CrudProjectBusiness::dispathElasticJob($project);
    			CrudProjectBusiness::dispatchNotificationJob($project);
    			return redirect()->route('admin.project.posts',['projectId' => $projectId]);
    		} else {
    			return back()->withErrors($postBusiness->getValidator())->withInput();
    		}
    	} catch(\Exception $e){
    		Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
    	}
    }

    public function updatePost(Request $request, $projectId, $id){
    	try{
    		$postBusiness = new PostBusiness();
    		$data = $request->except('_token');
    		$post = Post::findOrFail($id);
    		if($result = $postBusiness->updatePost($data, $post, Auth::user())){
    			$request->session()->flash('msg','Post alterado com sucesso!');	
    			$request->session()->flash('class_msg','alert-success');
    			$project = Project::find($projectId);
    			CrudProjectBusiness::dispathElasticJob($project);
    			CrudProjectBusiness::dispatchNotificationJob($project);
    			return redirect()->route('admin.project.posts',['projectId' => $projectId]);
    		} else {
    			return back()->withErrors($postBusiness->getValidator())->withInput();
    		}
    	} catch(\Exception $e){
    		Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
    	}
    }

    public function deletePost(Request $request, $projectId, $id){
    	try{
    		$postBusiness = new PostBusiness();
    		$post = Post::findOrFail($id);
    		if($result = $postBusiness->deletePost($post, Auth::user())){ 
    			$request->session()->flash('msg','Post excluído com sucesso!');	
    			$request->session()->flash('class_msg','alert-success');
    			CrudProjectBusiness::dispathElasticJob(Project::find($projectId));
    			return redirect()->route('admin.project.posts',['projectId' => $projectId]);
    		} else {
    			return $this->unexpectedError();
    		}
    	} catch(\Exception $e){
    		Log::error(Utils::getExceptionFullMessage($e));
			return $this->unexpectedError();
    	}
    }
    
}
