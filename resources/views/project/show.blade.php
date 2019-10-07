@extends('layouts.app')

@section('content')

	<div class="row"> 
		@if($project->type == '2')         
		
			<div class="embed-responsive embed-responsive-16by9 fl">
	      		<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$project->project}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>         
	      	</div>
	    
		@else
	      <img src="/storage/files/{{$project->project}}" class="img-resposive">
		@endif

		<div class="comentArea fl">
			@forelse ($project->comments as $comment)
				<table class="table table-bordered table-dark">
				  <thead>
				    <tr>
				      <th scope="col" colspan="2">
				      	<span class="userComentLink"> 
				      		{{ $comment->user->name }} {{$comment->created_at}}
				      	</span>
				      </th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr>
				      <td>
				      	<span class="comentArea">
				      		{{ $comment->body }}
				      	</span>
				      </td>
				  	</tr>
				  	<tr>
				  		<td>
				  			<span class="comentDate">
				  				{{ date('d/m/Y', strtotime($comment->date))}}
				  			</span>
				  		</td>
				  	</tr>
				  </tbody>
				</table>

				@empty
				<table class="table table-bordered table-dark">
				  <thead>
				    <tr>
				      <th scope="col" colspan="2">
				  		Ainda não houve nenhum comentário! Seja o primeiro.
					  </th>
					</tr>
				  </thead>
				</table>
			
			@endforelse
		</div>
		
	</div>

<div>

	<div class="fl mgl firstDiv">

		<h4>{{ $project->title }}</h4>
		
		<div id="description">
			{{ $project->description }}
		</div>	

		<span class="creator"> Criado por:
			<a href="/projects/userProject/{{$project->user->id}}">
				{{$project->user->name}}
			</a>
		</span>	

		<p>
			<span class="creator">
				Data: {{ date('d/m/Y', strtotime($project->date))}}
			</span>
		</p>

			@if($project->user->id == auth()->id() || auth()->id() == '1')
				<a href="/projects/edit/{{ $project->id }}">
					<button class="btn btn-outline-success btn-sm dvButtons" type="submit">
						<strong class="fl">EDITAR</strong>
					</button> 
				</a>

				<a onclick="return myFunction();" href="/projects/destroy/{{ $project->id }}">
					<button class="btn btn-danger btn-sm" type="submit">
						DELETAR
					</button> 
				</a>
			@endif
			
	</div>

	<div class="fl mgl dvShow dvDados">
		<h4> Dados </h4>

		<div id="description">
			<i class="fa fa-eye"> {{$project->views}} pessoas visualizaram isso</i>
			<i class="fa fa-heart"> {{$project->likes}} pessoas gostaram disso</i>
			<form action="/projects/like/{{$project->id}}" method="POST">
				@method('POST')
				@csrf
				<br>
				<button class="btn btn-outline-success btn-sm" name="idProjeto">
					<i class="fa fa-thumbs-up"> Like</i>
				</button>
			</form>
		</div>
	</div>

	<div class="fl mgl dvShow">

		<form action="/coments/{{$project->id}}" method="POST">
			@method('POST')
			@csrf
			<textarea name="body">
				
			</textarea>
			<button type="submit"> Comentar </button>
		</form>

		<a href="/download/{{$project->download}}" class="fr">
			<button type="button" class="btn btn-outline-success">
				<i class="fa fa-cloud-download"> Download</i>
			</button>
		</a>
	</div>

	<div class="clb"></div>

</div>




<script>
  function myFunction() {
      if(!confirm("DESEJA MESMO DELETAR ISSO?"))
      event.preventDefault();
  }
 </script>
@endsection
