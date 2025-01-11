@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <!--begin::Card body-->
    <div class="card-body">

        <form id="kt_form" class="form" method="POST" action="{{ route('settings.templates.store') }}">
            @csrf

            <input type="hidden" name="id" value="{{ $template->id ?? 0 }}">

            <!--begin::Row-->
            <div class="row">
                <div class="col-md-6">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Template Name</label>
                        <input class="form-control form-control-lg {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                            name="name" placeholder="Template Name" value="{{ old('name', $template->name ?? '') }}" disabled />

                        @if ($errors->has('name'))
                        <span class=" invalid-feedback">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Template Variables</label>
                        <textarea name="variables" class="form-control form-control-lg {{ $errors->has('variables') ? 'is-invalid' : '' }}" disabled>{{ old('variables', $template->variables ?? '') }}</textarea>
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12">

                    <!--begin::Group-->
                    <div class="form-group">
                        <label class="form-label">Template HTML</label>

                        <textarea id="htmlEditor" name="html">{!! old('html', $template->html ?? '') !!}</textarea>

                        @if ($errors->has('html'))
                        <span class=" invalid-feedback">{{ $errors->first('html') }}</span>
                        @endif
                    </div>
                    <!--end::Group-->

                </div>
                <div class="col-md-12 my-2">

                    <!--begin::Group-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                        {{-- <button type="reset" class="btn btn-secondary">Reset</button> --}}
                    </div>

                </div>
            </div>
            <!--end::Row-->

        </form>

    </div>
    <!--begin::Card body-->
</div>

@endsection


@section('styles')

@endsection


@section('scripts')
{{-- <script src="{{ url('/') }}/assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js"></script> --}}
<script src="https://cdn.tiny.cloud/1/2me38emy24f0gwihie60kmho6as74v8zdoq7vkabuw1egag1/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    $(document).ready(function () {
        // var editor = ClassicEditor
		// 	.create(document.querySelector('#htmlEditor'))
		// 	.then(editor => {
		// 		console.log(editor);
        //         $(document).on('submit', '#kt_form', function (e) {
        //             $('[name="html"]').val(editor.getData());
        //         });
		// 	})
		// 	.catch(error => {
		// 		console.error(error);
		// 	});

        tinymce.init({
            selector: '#htmlEditor',
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
        });

        // $(document).on('click', 'input[type="checkbox"]', function () {
        //     alert('df');
        //     $(this).attr('checked', true);
        // });
    });
</script>
@endsection
