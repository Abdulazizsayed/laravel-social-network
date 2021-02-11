@extends('layouts.app')

@section('content')
<div class="content-holder container" style="width: 60%">
    @forelse ($notifications as $notification)
    <div class="notification">
        <div class="scrolling-pagination">
            <a href="{{ $notification->link }}">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-1">
                                <img class="rounded-circle" src="{{ $notification->from->image ? asset('storage/' . $notification->from->image->file_name) : 'storage/images/users/profile/default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                            </div>
                            <div class="col-md-11">{{ $notification->content }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @empty
    <div class="alert alert-danger">No notifications for now</div>
    @endforelse
</div>
@endsection
