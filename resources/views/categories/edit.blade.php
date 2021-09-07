@extends('layouts.global')
@section('title') Edit Category @endsection

@section('content')
	<div class="col-md-8">
		@if(session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
		@endif

		<form
			action="{{ route('categories.update', [$category->id]) }}"
			enctype="multipart/form-data"
			method="post"
			class="bg-white shadow-sm p-3"
		>
			@csrf
			<input type="hidden" name="_method" value="put" />

			<label for="category-name">Category Name</label>
			
			<input
				type="text"
				class="form-control {{$errors->first('name') ? "is-invalid" : ""}}"
				value="{{ $category->name }}"
				name="name"
			/>

			<div class="invalid-feedback">
				{{$errors->first('name')}}
			</div>

			<br /><br />

			<label for="category-slug">Category Slug</label>

			<input
				type="text"
				class="form-control {{$errors->first('slug') ? "is-invalid" : ""}}"
				value="{{ $category->slug }}"
				name="slug"
			/>

			<div class="invalid-feedback">
				{{$errors->first('slug')}}
			</div>

			<br /><br />

			<label for="category-image">Category Image</label>
			<br />

			@if ($category->image)
				<span>Current Image</span><br />
				<img src="{{ asset('storage/' . $category->image) }}" width="120px" />
				<br /><br />
			@endif

			<input type="file" name="image" class="form-control {{$errors->first('image') ? "is-invalid" : ""}}" />
			<small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
			
			<div class="invalid-feedback">
				{{$errors->first('image')}}
			</div>

			<br /><br />
			<input type="submit" class="btn btn-primary" value="Update" />
		</form>
	</div
@endsection