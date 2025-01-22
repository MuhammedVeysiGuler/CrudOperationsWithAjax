<div class="btn-group" role="group">
    <button onclick="delete{{ $modelName }}({{ $row->id }})" class="btn btn-sm btn-danger">
        <i class="fas fa-trash"></i> Delete
    </button>
    <button onclick="update{{ $modelName }}({{ $row->id }})" class="btn btn-sm btn-warning">
        <i class="fas fa-edit"></i> Update
    </button>
    <a href="{{ route(strtolower($modelName) . '.update_view', $row->id) }}" class="btn btn-sm btn-info">
        <i class="fas fa-edit"></i> Update Page
    </a>
</div>
