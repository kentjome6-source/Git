@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showSuccess('{{ session('success') }}');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showError('{{ session('error') }}');
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showWarning('{{ session('warning') }}');
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showInfo('{{ session('info') }}');
    });
</script>
@endif

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let errorHtml = '<ul class="text-start">';
        @foreach ($errors->all() as $error)
            errorHtml += '<li>{{ $error }}</li>';
        @endforeach
        errorHtml += '</ul>';
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: errorHtml,
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
