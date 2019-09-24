@extends('layouts.app')

@section('content')

	<div class="row"> 
		@if($project->type == '2')         
		
			<div class="embed-responsive embed-responsive-16by9">
	      		<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$project->project}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>         
	      	</div>
	    
		@else
	      <img src="/storage/files/{{$project->project}}" class="img-resposive">
		@endif

	</div>

	<div class="fl mgl">

		<h4>{{ $project->title }}</h4>
		<ul>
			<li>{{ $project->id }}</li>
			<li>{{ $project->description }}</li>
			<li>{{ $project->type }}</li>
			<li>{{$project->user->name}}</li>
			<li>{{ date('d/m/Y', strtotime($project->date))}}</li>
		</ul>
	</div>


	<div class="fl mgl">
		<h4> Dados </h4>
		<ul>
			<li>X pessoas visualizaram isso</li>
			<li>{{$project->likes}} pessoas gostaram disso</li>
			<li>X pessoas baixaram isso</li>
		</ul>
	</div>

	<div class="fl mgl">
		<a href="/download/{{$project->download}}">
			<button class="btns">
				<strong class="fl" >BAIXAR</strong>
			</button>
		</a>

		<form action="/projects/like/{{$project->id}}" method="POST">
			@method('PUT')
    		@csrf
    		<button class="btns">
				<strong class="fl" >GOSTEI</strong>
			</button>
		</form>

		@if($project->user->id == auth()->id())
			<a href="/projects/edit/{{ $project->id }}">
				<button name="likes" class="btns" type="submit">
					<strong class="fl">EDITAR</strong>
				</button> 
			</a>
			<br>
			<a href="/projects/destroy/{{ $project->id }}">
				<button name="likes" class="btns" type="submit">
					<strong class="fl">DELETAR</strong>
				</button> 
			</a>
		@endif
		
	</div>

	



@endsection
