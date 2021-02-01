@extends('layouts.app')

@section('content')
<div class="content-holder">
    <div class="container-fluid content pt-3 pb-3">
        <div class="row justify-content-center">
        <div class="col-md-3"></div>
        <div class="col-md-3 following-sugestions">
            <h3 class="title">Last messages</h3>
            {{-- <form id="search-unfollowed-form" autocomplete="off">
                @csrf
                <input id="search-unfollowed" type="text" name="search_input" class="form-control" placeholder="Search a person you want follow">
            </form> --}}
            <div class="last-messages" style="direction: ltr">
                @foreach ($lastPeopleMessagedMe as $person)
                <a href="{{route('users.messages', $person->id)}}">
                    <div class="messager{{$person->id == $user->id ? ' active' : ''}}">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-2">
                                    <img class="rounded-circle" src="{{ $person->image ? asset('storage/' . $person->image->file_name) : asset('storage/images/users/profile/default_user_photo.jpg') }}" width="60px" height="60px" alt="User photo">
                                </div>
                                <div class="col-md-10">
                                    <h5 class="text-light">{{$person->name}}</h5>
                                    <p class="text-warning">
                                        {{\Str::limit($person->lastMessage(auth()->user())->content, 30, '...')}} . <span class="text-light">{{$person->lastMessage(auth()->user())->created_at->diffForHumans()}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <hr>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            <div id="create-post-msg" class="alert alert-dismissible fade show" hidden>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- New post --}}
            <div class="card post messages text-white bg-primary mb-3">
                {{-- Post form --}}
                <div class="card-body pb-0 pb-2">
                    <form class="create-message" method="post" action="" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="form-group input-holder">
                            <input type="text" class="form-control select-image-input" name="content" placeholder="Send a message">
                            <input type="number" name="receiver_id" value="{{ $user->id }}" hidden>
                            <input class="comment-image" type="file" name="image" hidden>
                            <i class="fa fa-camera select-image"></i>
                        </div>
                        <input type="number" name="receiver_id" value="{{$user->id}}" hidden>
                        <button type="submit" class="btn btn-outline-light form-control">Send</button>
                    </form>
                    <hr>
                    <div class="scrolling-pagination messages-holder">
                        @forelse ($messages as $message)
                        @if ($message->sender->id == auth()->user()->id)
                        <div class="message from-me float-right">
                            <div>
                                {{$message->content}}
                            </div>
                            <img src="{{ $message->image ? asset('storage/' . $message->image->file_name) : '' }}" width="100%" alt="Message photo"{{$message->image ? '' : ' hidden'}}>
                            <span class="text-info date">At {{$message->created_at}}</span>
                        </div>
                        @else
                        <div class="message from-user">
                            <div class="row">
                                <div class="col-md-1">
                                    <img class="rounded-circle" src="{{ $message->sender->image ? asset('storage/' . $message->sender->image->file_name) : asset('storage/images/users/profile/default_user_photo.jpg') }}" width="40px" height="40px" alt="User photo">
                                </div>
                                <div class="col-md-11">
                                    {{$message->content}}
                                    <img src="{{ $message->image ? asset('storage/' . $message->image->file_name) : '' }}" width="100%" alt="Message photo"{{$message->image ? '' : ' hidden'}}>
                                </div>
                            </div>
                            <span class="text-info date">At {{$message->created_at}}</span>
                        </div>
                        @endif
                        @empty
                        <div class="alert alert-danger mt-5">No messages between you and {{$user->name}}!</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3 following-people text-center">
            <h3 class="title pt-2">{{ $user->name }}</h3>
            <img class="rounded-circle border border-primary" src="{{ $user->image ? asset('storage/' . $user->image->file_name) : asset('storage/images/users/profile/default_user_photo.jpg') }}" width="250px" height="250px" alt="User photo">
            <h3 class="pt-4">{{$user->name}} <span class="text-warning">( {{$user->nickname}} )</span></h3>
            <form class="follow-form d-inline">
                @csrf
                <input type="number" name="followed_id" value="{{$user->id}}" hidden>
                @if (auth()->user()->following()->get()->contains($user))
                <button class="btn btn-outline-light" name="submit" type="submit">Unfollow</button>
                @else
                <button class="btn btn-outline-light" name="submit" type="submit">Follow</button>
                @endif
            </form>
            <a href="{{asset('users/profile/' . $user->id)}}" class="btn btn-outline-light">View profile</a>
        </div>
    </div>
</div>
</div>
@endsection
