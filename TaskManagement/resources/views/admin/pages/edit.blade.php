<div class="modal-content">
    
    <form method="post" action="{{ route('admin.task.update', ['id' => $detail->id]) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="put"/>
        <div class="mb-3">
                <label class="form-label">Name <em class="text-danger">*</em></label>
                <input type="text" name="title" class="form-control" placeholder="Enter title" value="{{ $detail->title }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Due date<em class="text-danger">*</em></label>
                    <input type="date" name="due_date" class="form-control" value="{{ $detail->due_date }}" required>
                </div>
                <div class="col-md-6 col-12 mb-3">
                    <label class="form-label">Priority<em class="text-danger">*</em></label>
                    <select class="form-select" name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="low" {{ $detail->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $detail->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{  $detail->priority == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status<em class="text-danger">*</em></label>
                        <select class="form-select" name="status" required>
                            <option value="">Select Status</option>
                            <option value="pending" {{  $detail->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{  $detail->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{  $detail->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Assigned to<em class="text-danger">*</em></label>
                        <select class="form-select" name="assigned_to">
                            <option value="">Select</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{$detail->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description<em class="text-danger">*</em></label>
                <textarea name="description" class="form-control no-resize" rows="4" placeholder="Enter description" required>{{ $detail->description ?? '' }}</textarea>
            </div>

            <div class="justify-content-end d-flex">
                <button type="submit" class="btn btn-blue">Update</button>
        </div>
    </form>
</div>