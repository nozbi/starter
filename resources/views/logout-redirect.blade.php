<form id="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
</form>

<script>
    document.getElementById('logout-form').submit();
</script>