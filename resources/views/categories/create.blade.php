@extends('layouts.global')

@section('title') Create Category @endsection

@section('content')
	<div class="col-md-8">

		@if(session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
		@endif

		<form
			enctype="multipart/form-data"
			class="bg-white shadow-sm p-3"
			action="{{ route('categories.store') }}"
			method="post"
		>
			@csrf
			<label for="category-name">Category Name</label>

			<input
				type="text"
				name="name"
				id="category-name"
				class="form-control {{$errors->first('name') ? "is-invalid" : ""}}"
			/>

			<div class="invalid-feedback">
				{{$errors->first('name')}}
			</div>

			<br />

			<label for="category-image">Category Image</label>

			<input
				type="file"
				class="form-control {{$errors->first('image') ? "is-invalid" : ""}}"
				name="image"
			/>

			<div class="invalid-feedback">
				{{$errors->first('image')}}
			</div>

			<br />

			<input
				type="submit"
				class="btn btn-primary"
				value="Save"
			/>
		</form>
	</div>
@endsection